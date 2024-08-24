<?php

namespace KlintDev\WPBooking\Views;

enum ContentDependencyLoadingStyle: string
{
    case InlineContent = "InlineContent";
    case RegisteredContent = "RegisteredContent";
    case EnqeueFunctinon = "EnqeueFunctinon";
}