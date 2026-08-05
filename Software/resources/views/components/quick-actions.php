<?php

/**
 * Renders a single quick-action card (e.g. Pump, ESP32 status).
 *
 * @param string $iconClass Bootstrap Icons class, e.g. "bi bi-fan"
 * @param string $typeClass Color/type modifier class, e.g. "pump", "wifi", "lamp"
 * @param string $title     Card title, e.g. "Pump"
 * @param string $value     Initial display value, e.g. "Running"
 * @param string $id        Optional element id so JS can update the value live
 */
function quickActionCard($iconClass, $typeClass, $title, $value, $id = "")
{
?>

    <div class="action-card">

        <div class="action-icon <?= $typeClass ?>">
            <i class="<?= $iconClass ?>"></i>
        </div>

        <div class="action-info">
            <h6><?= $title ?></h6>
            <span <?= $id ? 'id="' . $id . '"' : '' ?>><?= $value ?></span>
        </div>

    </div>

<?php
}

/**
 * Renders the full quick-actions grid from a list of action definitions.
 *
 * @param array $actions List of ['icon' => ..., 'type' => ..., 'title' => ..., 'value' => ..., 'id' => ...]
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
            $action['id'] ?? ""
        );
        ?>
    <?php endforeach; ?>

</div>
<?php
}
