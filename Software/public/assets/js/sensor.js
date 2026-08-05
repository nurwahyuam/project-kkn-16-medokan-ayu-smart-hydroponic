/**
 * Sensor data handling.
 *
 * Parses each Firebase "monitoring" snapshot. Field names match the
 * actual database structure:
 *   { status_pompa: "OFF", suhu: 29.1875, tds: 251.9166, timestamp: 12730381 }
 *
 * Note on `timestamp`: its value (e.g. 12730381) is far too small to be
 * a real Unix epoch (that would land in April 1970), so it's almost
 * certainly the ESP32's `millis()` — milliseconds since the device
 * booted, NOT a wall-clock date. We therefore do NOT convert it to a
 * calendar date; chart labels and "Last Update"/Recent Activity times
 * use the moment the browser actually received the update instead,
 * which is the only value we can trust as real time. If your firmware
 * is later updated to send a true Unix timestamp, swap the two
 * `new Date()` calls below for `new Date(entry.timestamp * 1000)`.
 */

import { DOM_IDS, COLORS, ESP_CONNECTION_TIMEOUT_MS } from "./config.js";
import { $, setText, setColor, formatRelativeTime } from "./utils.js";
import { updateTempChart, updateNutrientChart } from "./chart.js";

let espConnectionTimer = null;

function setEspDisconnected() {
    setText($(DOM_IDS.espStatusQuickAction), "Disconnected");
    setColor($(DOM_IDS.espStatusQuickAction), COLORS.danger);

    setText($(DOM_IDS.espStatusWeather), "ESP32 Disconnected");
    setColor($(DOM_IDS.espStatusWeather), COLORS.danger);
}

function resetEspConnectionTimer() {
    if (espConnectionTimer) {
        clearTimeout(espConnectionTimer);
    }

    setText($(DOM_IDS.espStatusQuickAction), "Connected");
    setColor($(DOM_IDS.espStatusQuickAction), COLORS.muted);

    setText($(DOM_IDS.espStatusWeather), "ESP32 Connected");
    setColor($(DOM_IDS.espStatusWeather), "inherit");

    espConnectionTimer = setTimeout(setEspDisconnected, ESP_CONNECTION_TIMEOUT_MS);
}

/** Maps a pump state string to the activity icon/color/label used in Recent Activity. */
function describePumpState(statusPompa) {
    if (statusPompa === "ON") {
        return { type: "success", icon: "bi bi-droplet-fill", label: "Pompa Menyala" };
    }
    if (statusPompa === "OFF") {
        return { type: "warning", icon: "bi bi-droplet", label: "Pompa Mati" };
    }
    return { type: "primary", icon: "bi bi-question-circle", label: `Status Pompa: ${statusPompa}` };
}

/**
 * Maps a TDS (ppm) reading to its status per the project's 5-tier table:
 *   < 400       Baik      (blue)
 *   400–800     Bagus     (green)
 *   801–1200    Cukup     (yellow)
 *   1201–1600   Berlebih  (orange)
 *   > 1600      Buruk     (red)
 */
function describeTdsStatus(tds) {
    if (tds < 400) return { emoji: "🔵", label: "Baik", color: "#3B82F6" };
    if (tds <= 800) return { emoji: "🟢", label: "Bagus", color: "#58C472" };
    if (tds <= 1200) return { emoji: "🟡", label: "Cukup", color: "#FDBA2D" };
    if (tds <= 1600) return { emoji: "🟠", label: "Berlebih", color: "#FF8A34" };
    return { emoji: "🔴", label: "Buruk", color: "#FF6B6B" };
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

/**
 * Renders the Recent Activity list from real monitoring entries
 * (newest first), replacing the placeholder/previous content of
 * #activity-list. Uses the exact same CSS classes as the PHP
 * activityItem() component, so there is no visual difference — only
 * the data source changes (real Firebase history instead of a static
 * hardcoded array).
 */
function renderRecentActivity(entriesNewestFirst) {
    const container = $(DOM_IDS.activityList);
    if (!container) return;

    const receivedAt = formatRelativeTime(new Date());

    container.innerHTML = entriesNewestFirst.map((entry) => {
        const pump = describePumpState(entry.status_pompa);
        const suhu = Number(entry.suhu).toFixed(1);
        const tds = Number(entry.tds).toFixed(0);

        return `
            <div class="activity-item">
                <div class="activity-icon ${pump.type}">
                    <i class="${pump.icon}"></i>
                </div>
                <div class="activity-content">
                    <h6>${pump.label} &bull; Suhu ${suhu}&deg;C &bull; TDS ${tds}</h6>
                    <span>${receivedAt}</span>
                </div>
            </div>
        `;
    }).join("");
}

/**
 * Handles one Firebase "monitoring" snapshot: resets the ESP32 connection
 * timer, updates the sensor readouts, pushes fresh data into both charts,
 * refreshes "Last Update", and rebuilds Recent Activity from real data.
 *
 * @param {object|null} data Raw snapshot value (object keyed by push() ids).
 */
export function handleMonitoringSnapshot(data) {
    if (!data) return;

    resetEspConnectionTimer();

    const receivedAt = new Date();
    const timeLabel = receivedAt.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });

    let latestData = null;
    const chartLabels = [];
    const tempData = [];
    const tdsData = [];
    const entries = [];

    Object.keys(data).forEach((key) => {
        const entry = data[key];
        latestData = entry; // By the end of the loop, this is the most recent entry.

        chartLabels.push(timeLabel);
        tempData.push(entry.suhu);
        tdsData.push(entry.tds);
        entries.push(entry);
    });

    if (!latestData) return;

    setText($(DOM_IDS.sensorTemp), `${Number(latestData.suhu).toFixed(1)}°C`);
    setText($(DOM_IDS.sensorTds), `${Number(latestData.tds).toFixed(0)}`);
    setText($(DOM_IDS.pumpStatus), latestData.status_pompa);
    setText($(DOM_IDS.lastUpdate), formatRelativeTime(receivedAt));
    updateNutrientStatus(latestData.tds);

    updateTempChart(chartLabels, tempData);
    updateNutrientChart(chartLabels, tdsData);

    // Newest first, capped to the last 10 so the activity list stays short.
    renderRecentActivity(entries.reverse().slice(0, 10));
}
