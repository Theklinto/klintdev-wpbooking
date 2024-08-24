<?php

namespace KlintDev\WPBooking\Components;

class Input
{
    public static function text(
        string  $fieldId,
        string  $label,
        ?string $value,
        ?string $hint = null,
        ?int    $minLength = null,
        ?int    $maxLength = null,
        ?string $placeholder = null,
        ?string $type = "text",
        ?bool   $required = true
    ): false|string
    {
        ob_start();
        ?>

        <div class="mb-3">
            <label for="<?= $fieldId ?>" class="form-label">
                <?= $label ?>
                <?php if ($required) { ?>
                    <span class="text-danger">
                        *
                    </span>
                <?php } ?>
            </label>
            <div class="input-group">
                <input
                        type="<?= $type ?>"
                        class="form-control"
                        id="<?= $fieldId ?>"
                    <?php if ($minLength !== null) { ?>
                        minlength="<?= $minLength ?>"
                    <?php } ?>
                    <?php if ($maxLength !== null) { ?>
                        maxlength="<?= $maxLength ?>"
                    <?php } ?>
                    <?php if ($placeholder !== null) { ?>
                        placeholder="<?= $placeholder ?>"
                    <?php } ?>
                        value="<?= $value ?>"
                    <?php if ($required) { ?>
                        required
                    <?php } ?>
                >
            </div>
            <?php if ($hint !== null) { ?>
                <div class="form-text"><?= $hint ?></div>
            <?php } ?>
        </div>

        <?php
        return ob_get_clean();
    }

    public static function textarea(
        string  $fieldId,
        string  $label,
        ?string $value,
        ?string $hint = null,
        ?int    $minLength = null,
        ?int    $maxLength = null,
        ?string $placeholder = null,
        int     $rows = 3,
        ?bool   $required = true
    ): false|string
    {
        ob_start();
        ?>
        <div class="mb-3">
            <label for="<?= $fieldId ?>" class="form-label">
                <?= $label ?>
                <?php if ($required) { ?>
                    <span class="text-danger">
                        *
                    </span>
                <?php } ?>
            </label>
            <div class="input-group">
                <textarea
                        rows="<?= $rows ?>"
                        class="form-control"
                        id="<?= $fieldId ?>"
                        minlength="<?= $minLength ?>"
                        maxlength="<?= $maxLength ?>"
                        placeholder="<?= $placeholder ?>"
                        <?php if ($required) { ?>
                            required
                        <?php } ?>
                ><?= $value ?></textarea>
            </div>
            <?php if ($hint !== null) { ?>
                <div class="form-text"><?= $hint ?></div>
            <?php } ?>
        </div>

        <?php
        return ob_get_clean();
    }

    public static function checkbox(
        string $fieldId,
        string $label,
        bool   $value = false
    ): false|string
    {
        ob_start(); ?>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="<?= $fieldId ?>"
                <?php if ($value) { ?>
                    checked
                <?php } ?>>
            <label class="form-check-label" for="<?= $fieldId ?>">
                <?= $label ?>
            </label>
        </div>
        <?php return ob_get_clean();
    }

    public static function hiddenInput(
        string $fieldId,
        mixed  $value = null,
    )
    {
        ob_start(); ?>

        <input type="hidden" id="<?= $fieldId ?>"
            <?php if ($value) { ?>
                value="<?= $value ?>"
            <?php } ?>
        >
        <?php return ob_get_clean();
    }
}

