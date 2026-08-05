/**
 * External weather widget.
 *
 * Maps Open-Meteo's numeric weather codes to a description/icon (same
 * ranges as the original app.js) and updates the weather card's DOM
 * elements. The original code fetched `relative_humidity_2m` but never
 * used it — that dead variable is dropped here (Clean Code), with no
 * change to what's rendered.
 */

import { DOM_IDS } from "./config.js";
import { $ } from "./utils.js";
import { fetchExternalWeather } from "./api.js";

const WEATHER_CODE_RANGES = [
    { min: 1, max: 3, desc: "Partly Cloudy", icon: "bi bi-cloud-sun-fill" },
    { min: 45, max: 48, desc: "Foggy", icon: "bi bi-cloud-haze-fill" },
    { min: 51, max: 67, desc: "Rainy", icon: "bi bi-cloud-rain-fill" },
    { min: 71, max: 77, desc: "Snowy", icon: "bi bi-cloud-snow-fill" },
    { min: 80, max: 99, desc: "Stormy", icon: "bi bi-cloud-lightning-rain-fill" },
];

const DEFAULT_WEATHER = { desc: "Clear", icon: "bi bi-brightness-high-fill" };

/** Maps an Open-Meteo weather_code to { desc, icon } (falls back to "Clear"). */
function describeWeatherCode(code) {
    const match = WEATHER_CODE_RANGES.find(({ min, max }) => code >= min && code <= max);
    return match ? { desc: match.desc, icon: match.icon } : DEFAULT_WEATHER;
}

/** Fetches the latest external weather and updates the weather card. */
export async function refreshExternalWeather() {
    const current = await fetchExternalWeather();
    if (!current) return;

    const temp = current.temperature_2m;
    const weatherCode = current.weather_code;
    const { desc, icon } = describeWeatherCode(weatherCode);

    const tempEl = $(DOM_IDS.weatherTemp);
    const descEl = $(DOM_IDS.weatherDesc);
    const iconEl = $(DOM_IDS.weatherIcon);

    if (tempEl) tempEl.textContent = `${temp}°C`;
    if (descEl) descEl.textContent = desc;
    if (iconEl) iconEl.innerHTML = `<i class="${icon}"></i>`;
}
