<?php

namespace KlintDev\WPBooking\Views;

enum ContentDependencyType: string
{
    case Script = 'script';
    case Style = 'style';
}