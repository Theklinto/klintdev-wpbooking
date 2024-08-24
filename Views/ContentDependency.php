<?php

namespace KlintDev\WPBooking\Views;

use KlintDev\WPBooking\GlobalSettings;

class ContentDependency
{
    public const INLINE_SCRIPT_HANDLE = GlobalSettings::PLUGIN_PREFIX . "-inline-script";
    public const INLINE_STYLE_HANDLE = GlobalSettings::PLUGIN_PREFIX . "-inline-style";

    public string $Handle;
    /**
     * @var string|callable
     */
    public mixed $Content;
    public ContentDependencyLoadingStyle $LoadingStyle;
    public ContentDependencyType $type;
    /** @var string[] */
    public array $RequiredDependencies = [];

    public function __construct(string $handle, string|callable $content, ContentDependencyType $type, ContentDependencyLoadingStyle $loadingStyle, array $requiredDependencies = [])
    {
        $this->Handle = $handle;
        $this->Content = $content;
        $this->LoadingStyle = $loadingStyle;
        $this->type = $type;
        $this->RequiredDependencies = $requiredDependencies;
    }
}

