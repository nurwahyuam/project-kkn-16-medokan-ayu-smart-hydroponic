/**
 * Light/Dark theme handling.
 *
 * Default theme is LIGHT (matches the original design). The chosen
 * theme is persisted in localStorage and applied via a `data-theme`
 * attribute on <html>, which variables.css reads to swap color tokens.
 */

const STORAGE_KEY = "hydroponic-theme";
const DARK = "dark";
const LIGHT = "light";

/** Reads the stored theme preference, defaulting to "light". */
export function getTheme() {
    return localStorage.getItem(STORAGE_KEY) === DARK ? DARK : LIGHT;
}

/** Applies and persists a theme ("light" or "dark"). */
export function setTheme(theme) {
    const normalized = theme === DARK ? DARK : LIGHT;

    if (normalized === DARK) {
        document.documentElement.setAttribute("data-theme", DARK);
    } else {
        document.documentElement.removeAttribute("data-theme");
    }

    localStorage.setItem(STORAGE_KEY, normalized);
}

/**
 * Applies whatever theme is currently stored. Safe to call on every
 * page load (also called synchronously in layouts/app.php's <head>
 * to avoid a flash of the wrong theme before this module loads).
 */
export function applyStoredTheme() {
    setTheme(getTheme());
}

/**
 * Wires up the Settings page's toggle switch (a checkbox with
 * id="theme-toggle-input") to reflect and control the current theme.
 * No-ops safely on pages that don't have that element.
 */
export function initThemeToggle() {
    const input = document.getElementById("theme-toggle-input");
    if (!input) return;

    input.checked = getTheme() === DARK;

    input.addEventListener("change", () => {
        setTheme(input.checked ? DARK : LIGHT);
    });
}
