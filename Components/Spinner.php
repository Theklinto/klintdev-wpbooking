<?php

namespace KlintDev\WPBooking\Components;

class Spinner
{
    public static function defaultSpinner(?string $id): false|string
    {
        ob_clean();
        ob_start();
        ?>

        <div class="spinner-border" id="<?= $id ?>" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>

        <?php

        return ob_get_clean();
    }
}