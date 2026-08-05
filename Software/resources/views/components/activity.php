<?php

/**
 * Renders a single activity item.
 *
 * @param string $typeClass Color modifier class, e.g. "success", "primary", "warning"
 * @param string $iconClass Bootstrap Icons class, e.g. "bi bi-droplet-fill"
 * @param string $title     Activity title, e.g. "Pump Turned ON"
 * @param string $time      Relative time text, e.g. "2 minutes ago"
 */
function activityItem($typeClass, $iconClass, $title, $time)
{
?>

    <div class="activity-item">

        <div class="activity-icon <?= $typeClass ?>">
            <i class="<?= $iconClass ?>"></i>
        </div>

        <div class="activity-content">
            <h6><?= $title ?></h6>
            <span><?= $time ?></span>
        </div>

    </div>

<?php
}

/**
 * Renders the full "Recent Activity" card from a list of activity items.
 *
 * @param array $items List of ['type' => ..., 'icon' => ..., 'title' => ..., 'time' => ...]
 */
function activityCard(array $items)
{
?>
<div class="activity-card">

    <div class="activity-header">
        <h5>Recent Activity</h5>
        <a href="#">View All</a>
    </div>

    <div id="activity-list">
        <?php foreach ($items as $item): ?>
            <?php
            activityItem(
                $item['type'],
                $item['icon'],
                $item['title'],
                $item['time']
            );
            ?>
        <?php endforeach; ?>
    </div>

</div>
<?php
}
