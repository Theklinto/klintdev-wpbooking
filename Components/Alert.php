<?php

namespace KlintDev\WPBooking\Components;

class Alert
{
    public static function minimal(
        string $id,
        string $initialMessage = ""): string|bool
    {
        ob_start();
        ?>
        <div class="alert hidden mt-2 mb-2" id="<?= $id ?>" role="alert"><?= $initialMessage ?></div>
        <?php
        return ob_get_clean();
    }
}