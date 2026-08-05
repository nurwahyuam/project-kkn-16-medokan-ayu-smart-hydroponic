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
import { handleMonitoringSnapshot } from "./sensor.js";
import { refreshExternalWeather } from "./weather.js";
import { applyStoredTheme, initThemeToggle } from "./theme.js";

// Theme (redundant with the inline <head> script, kept for consistency
// if this module ever runs before that script for any reason).
applyStoredTheme();
initThemeToggle(); // no-ops on pages without the Settings toggle

// Charts must exist before the first Firebase snapshot arrives.
initTempChart();
initNutrientChart();

// Realtime sensor readings + ESP32 connection status.
subscribeToMonitoring(handleMonitoringSnapshot);

// External weather widget: fetch immediately, then refresh periodically.
refreshExternalWeather();
setInterval(refreshExternalWeather, WEATHER_REFRESH_INTERVAL_MS);
