<?php

namespace KlintDev\WPBooking\Components;

class Form
{
    public static function beginForm(
        string  $formId,
        string  $fieldsetId,
        bool    $includeAlert,
        ?string $alertContainerId,
        bool    $disabled
    ): string|bool
    {
        ob_start();
        ?>
        <div>
        <form id="<?= $formId ?>">
        <?php if ($includeAlert)
        echo Alert::minimal($alertContainerId) ?>
        <fieldset id="<?= $fieldsetId ?>"  <?= $disabled ? "disabled" : "" ?>>
        <?php
        return ob_get_clean();
    }

    public static function endForm(): string|bool
    {
        ob_start();
        ?>
        </fieldset>
        </form>
        </div>
        <?php
        return ob_get_clean();
    }

    public static function formControls(
        ?string $cancelButtonId,
        ?string $submitButtonId,
        string  $submitButtonLabel = "Opdater",
        string  $cancelButtonLabel = "Annuller",
        bool    $hidden = true,
        string  $deleteButtonId = null,
        string  $deleteButtonLabel = "Slet",
    ): string|bool
    {
        ob_start(); ?>
        <div class="d-flex justify-content-end">
            <?php if ($deleteButtonId !== null) { ?>
                <button type="button" id="<?= $deleteButtonId ?>" class="btn btn-danger me-4 <?= $hidden ? "hidden" : "" ?>">
                    <?= $deleteButtonLabel ?>
                </button>
            <?php } ?>
            <?php if ($cancelButtonId !== null) { ?>
                <button id="<?= $cancelButtonId ?>" class="btn btn-secondary me-2 <?= $hidden ? "hidden" : "" ?>"
                        type="button">
                    <?= $cancelButtonLabel ?>
                </button>
            <?php } ?>
            <button id="<?= $submitButtonId ?>" class="btn btn-primary <?= $hidden ? "hidden" : "" ?>" type="submit">
                <?= $submitButtonLabel ?>
            </button>
        </div>
        <?php return ob_get_clean();
    }
}