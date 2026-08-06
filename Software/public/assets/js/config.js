/**
 * Application configuration & constants.
 *
 * Centralizes every "magic string/number" that was previously scattered
 * across app.js (Firebase config, DOM element ids, colors, timeouts).
 */

export const FIREBASE_CONFIG = {
    databaseURL: "https://smart-urban-farming-kkn16-default-rtdb.asia-southeast1.firebasedatabase.app/",
};

export const MONITORING_NODE = "monitoring";
export const MONITORING_HISTORY_LIMIT = 10;

export const ESP_CONNECTION_TIMEOUT_MS = 5 * 60 * 1000; // 5 minutes

export const WEATHER_API_URL =
    "https://api.open-meteo.com/v1/forecast?latitude=-7.3235&longitude=112.7951&current=temperature_2m,relative_humidity_2m,weather_code&timezone=Asia%2FJakarta";
export const WEATHER_REFRESH_INTERVAL_MS = 15 * 60 * 1000; // 15 minutes

export const DOM_IDS = {
    sensorTemp: "val-suhu",
    sensorTds: "val-tds",
    pumpStatus: "val-pump",
    espStatusQuickAction: "val-esp-status",
    espStatusWeather: "ext-weather-esp-status",
    weatherTemp: "ext-weather-temp",
    weatherDesc: "ext-weather-desc",
    weatherIcon: "ext-weather-icon",
    tempChartCanvas: "tempChart",
    nutrientChartCanvas: "nutrientChart",
    lastUpdate: "val-last-update",
    activityList: "activity-list",
    nutrientStatusDot: "nutrient-status-dot",
    nutrientStatusLabel: "nutrient-status-label",
    activityToggle: "activity-toggle",
};

export const COLORS = {
    primary: "#58C472",
    primaryChartFill: "rgba(88,196,114,.15)",
    nutrient: "#4D96FF",
    nutrientChartFill: "rgba(77,150,255,.15)",
    danger: "#FF6B6B",
    muted: "#777",
};
