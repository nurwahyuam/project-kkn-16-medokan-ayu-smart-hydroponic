/**
 * Dashboard entry point.
 *
 * The only script loaded by the page (see layouts/app.php). Its sole
 * job is to wire the single-responsibility modules together — it
 * contains no business logic of its own.
 */

import { WEATHER_REFRESH_INTERVAL_MS } from "./config.js";
import { subscribeToMonitoring } from "./api.js";
import { initTempChart, initNutrientChart } from "./chart.js";
import { handleMonitoringSnapshot, initActivityToggle, refreshDynamicText } from "./sensor.js";
import { refreshExternalWeather } from "./weather.js";
import { applyStoredTheme, initThemeToggle } from "./theme.js";
import { applyTranslations, initLanguageToggle } from "./i18n.js";

// Theme (redundant with the inline <head> script, kept for consistency
// if this module ever runs before that script for any reason).
applyStoredTheme();
initThemeToggle(); // no-ops on pages without the Settings toggle

// Language: translate static (data-i18n) text, wire the Settings
// language selector, and keep dynamic (JS-generated) text in sync
// whenever the language changes.
applyTranslations();
initLanguageToggle(); // no-ops on pages without the Settings selector
window.addEventListener("app:languagechange", refreshDynamicText);

// Recent Activity "View All" / "Show Less" toggle (no-ops on pages
// without #activity-toggle, e.g. Home/Device).
initActivityToggle();

// Charts must exist before the first Firebase snapshot arrives.
initTempChart();
initNutrientChart();

// Realtime sensor readings + ESP32 connection status.
subscribeToMonitoring(handleMonitoringSnapshot);

// External weather widget: fetch immediately, then refresh periodically.
refreshExternalWeather();
setInterval(refreshExternalWeather, WEATHER_REFRESH_INTERVAL_MS);
