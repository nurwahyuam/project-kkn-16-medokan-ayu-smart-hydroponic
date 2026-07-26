<?php

function sensorCard($icon,$title,$value,$color){

?>

<div class="sensor-card">

    <div
        class="sensor-icon"
        style="background:<?= $color ?>">

        <i class="<?= $icon ?>"></i>

    </div>

    <div class="sensor-value">

        <?= $value ?>

    </div>

    <div class="sensor-title">

        <?= $title ?>

    </div>

</div>

<?php

}

?>