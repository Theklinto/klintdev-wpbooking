<?php

namespace KlintDev\WPBooking\Views\Settings;

use KlintDev\WPBooking\Views\PartialPage;

class SettingsOverviewView extends PartialPage
{
    protected static SettingsOverviewView $instance;

    public static function render(): string|false
    {
        ob_start();
        ?>
        <div class="container-fluid" style="height: 100%; margin: 2em 0 0 0;">
            <h1>Indstillinger</h1>
            <div class="row">
                <div class="col-6">
                    <?= BlockedDurationListView::render() ?>
                </div>
                <div class="col-3">
                    <?= StripeSettingsView::render() ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public static function getRequiredContent(): array
    {
        return [
            ...StripeSettingsView::getRequiredContent(),
            ...BlockedDurationListView::getRequiredContent(),
        ];
    }

    public static function getInstance(): object
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}