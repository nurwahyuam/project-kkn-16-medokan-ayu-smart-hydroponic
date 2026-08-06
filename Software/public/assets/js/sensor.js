/**
 * Sensor data handling.
 *
 * Parses each Firebase "monitoring" snapshot. Field names match the
 * actual database structure:
 *   { status_pompa: "OFF", suhu: 29.1875, tds: 251.9166, timestamp: 12730381 }
 *
 * Note on `timestamp`: real sample values (e.g. 12730381) are far too
 * small to be a Unix epoch (that would land in April 1970), so this is
 * almost certainly the ESP32's `millis()` — milliseconds since the
 * device booted, NOT a wall-clock date. toDeviceDate() below detects
 * this automatically: if the value IS a plausible epoch (seconds or
 * milliseconds), it's used as a real date; otherwise it's shown as
 * device uptime instead of a bogus 1970 date. If your firmware is
 * later updated to send a true Unix timestamp, no code change is
 * needed — the detection just starts using it as a real date.
 */

import { DOM_IDS, ESP_CONNECTION_TIMEOUT_MS } from "./config.js";
import { $, setText, setColor, formatRelativeTime } from "./utils.js";
import { updateTempChart, updateNutrientChart } from "./chart.js";
import { t } from "./i18n.js";

const ACTIVITY_PREVIEW_COUNT = 5;

let espConnectionTimer = null;
let isEspConnected = true;

// Kept so Recent Activity / Last Update / nutrient status can be
// re-rendered instantly on a language change, without waiting for a
// new Firebase snapshot.
let latestSnapshotData = null;
let latestEntriesNewestFirst = [];
let showAllActivity = false;

/**
 * Converts a device `timestamp` value to a real Date, or null if the
 * value is too small to be a plausible calendar date (i.e. it's
 * actually millis()-since-boot, not a Unix epoch).
 */
function toDeviceDate(timestamp) {
    const ts = Number(timestamp);
    if (!ts || Number.isNaN(ts)) return null;

    if (ts > 1e12) return new Date(ts);       // milliseconds epoch
    if (ts > 1e9) return new Date(ts * 1000);  // seconds epoch

    return null; // too small — treat as device uptime, not a date
}

/** Formats milliseconds of device uptime as "1j 32m" / "45m". */
function formatUptime(ms) {
    const totalMinutes = Math.floor(ms / 60000);
    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;
    return hours > 0 ? `${hours}j ${minutes}m` : `${minutes}m`;
}

/** Short label for chart X-axes: real clock time if possible, else raw uptime. */
function chartLabelFor(timestamp) {
    const date = toDeviceDate(timestamp);
    if (date) return date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
    return formatUptime(Number(timestamp) || 0);
}

/** Relative/human label for Last Update & Recent Activity: real relative time if possible, else uptime. */
function relativeLabelFor(timestamp) {
    const date = toDeviceDate(timestamp);
    if (date) return formatRelativeTime(date);
    return `${t("device_uptime_prefix")} ${formatUptime(Number(timestamp) || 0)}`;
}

function setEspDisconnected() {
    isEspConnected = false;

    setText($(DOM_IDS.espStatusQuickAction), t("esp_status_short_disconnected"));
    setColor($(DOM_IDS.espStatusQuickAction), "#FF6B6B");

    setText($(DOM_IDS.espStatusWeather), t("esp_status_full_disconnected"));
    setColor($(DOM_IDS.espStatusWeather), "#FF6B6B");
}

function resetEspConnectionTimer() {
    isEspConnected = true;

    if (espConnectionTimer) {
        clearTimeout(espConnectionTimer);
    }

    setText($(DOM_IDS.espStatusQuickAction), t("esp_status_short_connected"));
    setColor($(DOM_IDS.espStatusQuickAction), "#777");

    setText($(DOM_IDS.espStatusWeather), t("esp_status_full_connected"));
    setColor($(DOM_IDS.espStatusWeather), "inherit");

    espConnectionTimer = setTimeout(setEspDisconnected, ESP_CONNECTION_TIMEOUT_MS);
}

/** Maps a pump state string to the activity icon/color/label used in Recent Activity. */
function describePumpState(statusPompa) {
    if (statusPompa === "ON") {
        return { type: "success", icon: "bi bi-droplet-fill", label: t("pump_on") };
    }
    if (statusPompa === "OFF") {
        return { type: "warning", icon: "bi bi-droplet", label: t("pump_off") };
    }
    return { type: "primary", icon: "bi bi-question-circle", label: `${t("pump_status_prefix")}: ${statusPompa}` };
}

/**
 * Maps a TDS (ppm) reading to its status per the project's 5-tier table:
 *   < 400       Baik / Good
 *   400–800     Bagus / Great
 *   801–1200    Cukup / Fair
 *   1201–1600   Berlebih / Excess
 *   > 1600      Buruk / Poor
 */
function describeTdsStatus(tds) {
    if (tds < 400) return { emoji: "🔵", label: t("nutrient_status_baik"), color: "#3B82F6" };
    if (tds <= 800) return { emoji: "🟢", label: t("nutrient_status_bagus"), color: "#58C472" };
    if (tds <= 1200) return { emoji: "🟡", label: t("nutrient_status_cukup"), color: "#FDBA2D" };
    if (tds <= 1600) return { emoji: "🟠", label: t("nutrient_status_berlebih"), color: "#FF8A34" };
    return { emoji: "🔴", label: t("nutrient_status_buruk"), color: "#FF6B6B" };
}

/** Updates the nutrient-status dot/label indicator. No-ops on pages without it (e.g. Device, History). */
function updateNutrientStatus(tds) {
    const dot = $(DOM_IDS.nutrientStatusDot);
    const label = $(DOM_IDS.nutrientStatusLabel);
    if (!dot && !label) return;

    const status = describeTdsStatus(Number(tds));

    if (dot) dot.style.backgroundColor = status.color;
    setText(label, `${status.emoji} TDS ${Number(tds).toFixed(0)} ppm — ${status.label}`);
}

/** Builds the HTML for one Recent Activity entry, matching the PHP activityItem() component's markup exactly. */
function activityItemHtml(entry) {
    const pump = describePumpState(entry.status_pompa);
    const suhu = Number(entry.suhu).toFixed(1);
    const tds = Number(entry.tds).toFixed(0);
    const time = relativeLabelFor(entry.timestamp);

    return `
        <div class="activity-item">
            <div class="activity-icon ${pump.type}">
                <i class="${pump.icon}"></i>
            </div>
            <div class="activity-content">
                <h6>${pump.label} &bull; Suhu ${suhu}&deg;C &bull; TDS ${tds}</h6>
                <span>${time}</span>
            </div>
        </div>
    `;
}

/** Shows/hides and (re)labels the "View All" / "Show Less" toggle depending on how many entries exist. */
function updateActivityToggleLabel() {
    const toggle = $(DOM_IDS.activityToggle);
    if (!toggle) return;

    if (latestEntriesNewestFirst.length <= ACTIVITY_PREVIEW_COUNT) {
        toggle.style.display = "none";
        return;
    }

    toggle.style.display = "";
    toggle.textContent = showAllActivity ? t("activity_show_less") : t("activity_view_all");
}

/** Renders either the first 5 entries or all of them, depending on showAllActivity. */
function renderActivityList() {
    const container = $(DOM_IDS.activityList);
    if (!container) return;

    const visible = showAllActivity
        ? latestEntriesNewestFirst
        : latestEntriesNewestFirst.slice(0, ACTIVITY_PREVIEW_COUNT);

    container.innerHTML = visible.map(activityItemHtml).join("");
    updateActivityToggleLabel();
}

/**
 * Wires up the "View All" / "Show Less" click handler. Safe to call
 * once on every page — no-ops on pages without #activity-toggle.
 */
export function initActivityToggle() {
    const toggle = $(DOM_IDS.activityToggle);
    if (!toggle) return;

    toggle.addEventListener("click", (event) => {
        event.preventDefault();
        showAllActivity = !showAllActivity;
        renderActivityList();
    });
}

/**
 * Re-renders every dynamic (JS-generated) piece of text using the last
 * known data, without needing a new Firebase snapshot. Called after a
 * language change (see dashboard.js's "app:languagechange" listener).
 */
export function refreshDynamicText() {
    if (isEspConnected) {
        setText($(DOM_IDS.espStatusQuickAction), t("esp_status_short_connected"));
        setText($(DOM_IDS.espStatusWeather), t("esp_status_full_connected"));
    } else {
        setText($(DOM_IDS.espStatusQuickAction), t("esp_status_short_disconnected"));
        setText($(DOM_IDS.espStatusWeather), t("esp_status_full_disconnected"));
    }

    if (latestSnapshotData) {
        setText($(DOM_IDS.lastUpdate), relativeLabelFor(latestSnapshotData.timestamp));
        updateNutrientStatus(latestSnapshotData.tds);
    }

    renderActivityList();
}

/**
 * Handles one Firebase "monitoring" snapshot: resets the ESP32 connection
 * timer, updates the sensor readouts, pushes fresh data into both charts
 * (labeled using each entry's own timestamp), refreshes "Last Update",
 * updates the nutrient status indicator, and rebuilds Recent Activity.
 *
 * @param {object|null} data Raw snapshot value (object keyed by push() ids).
 */
export function handleMonitoringSnapshot(data) {
    if (!data) return;

    resetEspConnectionTimer();

    let latestData = null;
    const chartLabels = [];
    const tempData = [];
    const tdsData = [];
    const entries = [];

    Object.keys(data).forEach((key) => {
        const entry = data[key];
        latestData = entry; // By the end of the loop, this is the most recent entry.

        chartLabels.push(chartLabelFor(entry.timestamp));
        tempData.push(entry.suhu);
        tdsData.push(entry.tds);
        entries.push(entry);
    });

    if (!latestData) return;

    latestSnapshotData = latestData;
    latestEntriesNewestFirst = entries.slice().reverse();

    setText($(DOM_IDS.sensorTemp), `${Number(latestData.suhu).toFixed(1)}°C`);
    setText($(DOM_IDS.sensorTds), `${Number(latestData.tds).toFixed(0)}`);
    setText($(DOM_IDS.pumpStatus), latestData.status_pompa);
    setText($(DOM_IDS.lastUpdate), relativeLabelFor(latestData.timestamp));
    updateNutrientStatus(latestData.tds);

    updateTempChart(chartLabels, tempData);
    updateNutrientChart(chartLabels, tdsData);

    renderActivityList();
}
