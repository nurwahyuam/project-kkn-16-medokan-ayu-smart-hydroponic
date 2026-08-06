<?php

/**
 * Renders a single quick-action card (e.g. Pump, ESP32 status).
 *
 * @param string $iconClass Bootstrap Icons class, e.g. "bi bi-fan"
 * @param string $typeClass Color/type modifier class, e.g. "pump", "wifi", "lamp"
 * @param string $title     Card title, e.g. "Pump" (fallback if $i18nKey has no JS translation yet)
 * @param string $value     Initial display value, e.g. "Running"
 * @param string $id        Optional element id so JS can update the value live
 * @param string $i18nKey   Optional i18n.js dictionary key for the title
 */
function quickActionCard($iconClass, $typeClass, $title, $value, $id = "", $i18nKey = "")
{
?>

    <div class="action-card">

        <div class="action-icon <?= $typeClass ?>">
            <i class="<?= $iconClass ?>"></i>
        </div>

        <div class="action-info">
            <h6 <?= $i18nKey ? 'data-i18n="' . $i18nKey . '"' : '' ?>><?= $title ?></h6>
            <span <?= $id ? 'id="' . $id . '"' : '' ?>><?= $value ?></span>
        </div>

    </div>

<?php
}

/**
 * Renders the full quick-actions grid from a list of action definitions.
 *
 * @param array $actions List of ['icon' => ..., 'type' => ..., 'title' => ..., 'value' => ..., 'id' => ..., 'i18n' => ...]
 */
function quickActionsGrid(array $actions)
{
?>
<div class="quick-actions">

    <?php foreach ($actions as $action): ?>
        <?php
        quickActionCard(
            $action['icon'],
            $action['type'],
            $action['title'],
            $action['value'],
            $action['id'] ?? "",
            $action['i18n'] ?? ""
        );
        ?>
    <?php endforeach; ?>

</div>
<?php
}
