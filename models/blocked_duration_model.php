<?php

namespace KlintDev\WPBooking;

class BlockedDurationModel
{
    public ?int $id;
    public bool $active;
    public string $startDate;
    public ?string $endDate;
    public string $description;
    public bool $monday;
    public bool $tuesday;
    public bool $wednesday;
    public bool $thursday;
    public bool $friday;
    public bool $saturday;
    public bool $sunday;
}