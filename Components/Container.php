<?php

namespace KlintDev\WPBooking\Components;

class Container
{
    public static function beginDashboardContainer(string $containerId = ""): string|bool
    {
        ob_start(); ?>
        <div class="bg-light rounded p-4 mt-4" id="<?= $containerId ?>">
        <?php return ob_get_clean();
    }

    public static function endDashboardContainer(): string|bool
    {
        ob_start(); ?>
        </div>
        <?php return ob_get_clean();
    }

    public static function header(
        string  $headerLabel,
        bool    $includeButton,
        ?string $buttonId = null,
        ?string $buttonLabel = null,
    ): string|bool
    {
        ob_start();
        ?>
        <div class="d-flex justify-content-between mb-2">
            <h3><?= $headerLabel ?></h3>
            <?php if ($includeButton) { ?>
                <button id="<?= $buttonId ?>" type="button" class="btn btn-primary"><?= $buttonLabel ?></button>
            <?php } ?>
        </div>
        <?php
        return ob_get_clean();
    }
}