/**
 * External data access layer (API).
 *
 * Every call that talks to something outside this app — Firebase
 * Realtime Database, or the Open-Meteo HTTP API — lives here. Other
 * modules (sensor.js, weather.js) consume these functions and never
 * touch `fetch`/Firebase directly.
 */

import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
import { getDatabase, ref, onValue, limitToLast, query } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-database.js";
import { FIREBASE_CONFIG, MONITORING_NODE, MONITORING_HISTORY_LIMIT, WEATHER_API_URL } from "./config.js";

const firebaseApp = initializeApp(FIREBASE_CONFIG);
const database = getDatabase(firebaseApp);

/**
 * Subscribes to the last N entries of the "monitoring" node in Firebase.
 *
 * @param {(data: object|null) => void} callback Called with the raw snapshot value on every update.
 * @returns {() => void} Unsubscribe function (as returned by Firebase's onValue).
 */
export function subscribeToMonitoring(callback) {
    const monitoringRef = query(ref(database, MONITORING_NODE), limitToLast(MONITORING_HISTORY_LIMIT));
    return onValue(monitoringRef, (snapshot) => callback(snapshot.val()));
}

/**
 * Fetches current external weather conditions from Open-Meteo.
 *
 * @returns {Promise<object|null>} The `current` weather object, or null on failure.
 */
export async function fetchExternalWeather() {
    try {
        const response = await fetch(WEATHER_API_URL);
        const data = await response.json();
        return data && data.current ? data.current : null;
    } catch (error) {
        console.error("Failed to fetch weather data:", error);
        return null;
    }
}
