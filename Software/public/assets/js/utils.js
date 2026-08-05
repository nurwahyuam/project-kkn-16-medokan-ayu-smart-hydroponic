/**
 * Generic, reusable helpers. No business logic lives here — only
 * small pure/DOM-safety utilities used across multiple modules.
 */

/** Shorthand for document.getElementById. */
export function $(id) {
    return document.getElementById(id);
}

/** Sets text content only if the element exists (matches original `if (el) el.textContent = ...` guard). */
export function setText(el, text) {
    if (el) el.textContent = text;
}

/** Sets inline color only if the element exists. */
export function setColor(el, color) {
    if (el) el.style.color = color;
}

/**
 * Formats a Date as a short Indonesian relative-time string
 * (e.g. "Baru saja", "5 menit lalu", "2 jam lalu").
 */
export function formatRelativeTime(date) {
    const seconds = Math.floor((Date.now() - date.getTime()) / 1000);

    if (seconds < 10) return "Baru saja";
    if (seconds < 60) return `${seconds} detik lalu`;

    const minutes = Math.floor(seconds / 60);
    if (minutes < 60) return `${minutes} menit lalu`;

    const hours = Math.floor(minutes / 60);
    if (hours < 24) return `${hours} jam lalu`;

    const days = Math.floor(hours / 24);
    return `${days} hari lalu`;
}
