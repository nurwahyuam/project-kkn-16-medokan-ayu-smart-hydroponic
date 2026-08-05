/**
 * Charts (Chart.js wrapper).
 *
 * Owns both Chart.js instances used by the dashboard: temperature and
 * nutrient (TDS). A shared factory avoids duplicating the same
 * line-chart setup twice — other modules never touch Chart.js
 * directly, they call updateTempChart()/updateNutrientChart().
 */

import { DOM_IDS, COLORS } from "./config.js";
import { $ } from "./utils.js";

let tempChart = null;
let nutrientChart = null;

function createLineChart(canvasId, label, borderColor, backgroundColor) {
    const canvas = $(canvasId);
    if (!canvas) return null;

    const ctx = canvas.getContext("2d");
    return new Chart(ctx, {
        type: "line",
        data: {
            labels: [],
            datasets: [{
                label,
                data: [],
                borderColor,
                backgroundColor,
                fill: true,
                tension: 0.4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: { beginAtZero: false },
            },
        },
    });
}

/** Creates the temperature line chart if #tempChart exists on the page. */
export function initTempChart() {
    tempChart = createLineChart(
        DOM_IDS.tempChartCanvas,
        "Temperature",
        COLORS.primary,
        COLORS.primaryChartFill
    );
    return tempChart;
}

/** Creates the nutrient (TDS) line chart if #nutrientChart exists on the page. */
export function initNutrientChart() {
    nutrientChart = createLineChart(
        DOM_IDS.nutrientChartCanvas,
        "TDS",
        COLORS.nutrient,
        COLORS.nutrientChartFill
    );
    return nutrientChart;
}

/** Replaces the temperature chart's labels/data and re-renders it. */
export function updateTempChart(labels, data) {
    if (!tempChart) return;
    tempChart.data.labels = labels;
    tempChart.data.datasets[0].data = data;
    tempChart.update();
}

/** Replaces the nutrient chart's labels/data and re-renders it. */
export function updateNutrientChart(labels, data) {
    if (!nutrientChart) return;
    nutrientChart.data.labels = labels;
    nutrientChart.data.datasets[0].data = data;
    nutrientChart.update();
}
