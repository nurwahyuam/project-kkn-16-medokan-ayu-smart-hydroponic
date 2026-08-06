<?php

/**
 * Renders a single sensor metric card.
 *
 * @param string $icon    Bootstrap Icons class, e.g. "bi bi-thermometer-high"
 * @param string $title   Sensor label, e.g. "Nutrient Temp"
 * @param string $value   Initial display value, e.g. "27°C"
 * @param string $color   Icon background color (hex)
 * @param string $id      Optional element id so JS can update the value live
 * @param string $i18nKey Optional i18n.js dictionary key for the title
 */
function sensorCard($icon, $title, $value, $color, $id = "", $i18nKey = "")
{
?>

<div class="sensor-card">

    <div
        class="sensor-icon"
        style="background:<?= $color ?>">

        <i class="<?= $icon ?>"></i>

    </div>

    <div class="sensor-value" <?= $id ? 'id="' . $id . '"' : '' ?>>

        <?= $value ?>

    </div>

    <div class="sensor-title" <?= $i18nKey ? 'data-i18n="' . $i18nKey . '"' : '' ?>>

        <?= $title ?>

    </div>

</div>

<?php
}
