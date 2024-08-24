<?php

namespace KlintDev\WPBooking;

class PackageModel
{
    public ?int $id;
    public string $name;
    public int $price;
    public int $roomId;
    public int $deposit;
    public int $rentalDurationInHours;
    public bool $active;
    public bool $monday;
    public bool $tuesday;
    public bool $wednesday;
    public bool $thursday;
    public bool $friday;
    public bool $saturday;
    public bool $sunday;
}