/**
 * Relay control.
 *
 * Reserved for future use. The original app.js only DISPLAYS the pump
 * status (`status_pompa`) read from Firebase — there is no toggle/
 * control action anywhere in the current codebase. Once the backend's
 * `POST /api/relay` endpoint exists (Step 8), the toggle logic will be
 * added here, e.g.:
 *
 *   export async function toggleRelay(relayId, state) {
 *       return fetch('/api/relay', {
 *           method: 'POST',
 *           headers: { 'Content-Type': 'application/json' },
 *           body: JSON.stringify({ relay: relayId, state }),
 *       });
 *   }
 *
 * Intentionally left without implementation for now — no behavior change.
 */

export {};
