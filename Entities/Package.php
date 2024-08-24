<?php
//
//namespace KlintDev\WPBooking\Entities;
//
//use KlintDev\WPBooking\Attributes\ColumnAttribute;
//
//class Package
//{
//    #[ColumnAttribute("id", ColumnAttribute::INTEGER, 0)]
//    public ?int $id;
//    #[ColumnAttribute("name", ColumnAttribute::STRING, "")]
//    public string $name;
//    #[ColumnAttribute("price", ColumnAttribute::INTEGER, 0)]
//    public int $price;
//    #[ColumnAttribute("room_id", ColumnAttribute::INTEGER, 0)]
//    public int $room_id;
//    #[ColumnAttribute("deposit", ColumnAttribute::INTEGER, 0)]
//    public int $deposit;
//    #[ColumnAttribute("rental_duration_in_hours", ColumnAttribute::INTEGER, 0)]
//    public int $rental_duration_in_hours;
//    #[ColumnAttribute("active", ColumnAttribute::INTEGER, 0)]
//    public bool $active;
//    #[ColumnAttribute("monday", ColumnAttribute::INTEGER, 0)]
//    public bool $monday;
//    #[ColumnAttribute("tuesday", ColumnAttribute::INTEGER, 0)]
//    public bool $tuesday;
//    #[ColumnAttribute("wednesday", ColumnAttribute::INTEGER, 0)]
//    public bool $wednesday;
//    #[ColumnAttribute("thursday", ColumnAttribute::INTEGER, 0)]
//    public bool $thursday;
//    #[ColumnAttribute("friday", ColumnAttribute::INTEGER, 0)]
//    public bool $friday;
//    #[ColumnAttribute("saturday", ColumnAttribute::INTEGER, 0)]
//    public bool $saturday;
//    #[ColumnAttribute("sunday", ColumnAttribute::INTEGER, 0)]
//    public bool $sunday;
//
//    public static function ToPackageModel($package)
//    {
//        $model = new PackageModel();
//        $model->name = $package->name;
//        $model->price = $package->price;
//        $model->roomId = $package->room_id;
//        $model->deposit = $package->deposit;
//        $model->rentalDurationInHours = $package->rental_duration_in_hours;
//        $model->active = $package->active;
//        $model->monday = $package->monday;
//        $model->tuesday = $package->tuesday;
//        $model->wednesday = $package->wednesday;
//        $model->thursday = $package->thursday;
//        $model->friday = $package->friday;
//        $model->saturday = $package->saturday;
//        $model->sunday = $package->sunday;
//        return $model;
//    }
//
//    public static function FromPackageModel(PackageModel $model): Package
//    {
//        $package = new Package();
//        $package->name = $model->name;
//        $package->price = $model->price;
//        $package->room_id = $model->roomId;
//        $package->deposit = $model->deposit;
//        $package->rental_duration_in_hours = $model->rentalDurationInHours;
//        $package->active = $model->active;
//        $package->monday = $model->monday;
//        $package->tuesday = $model->tuesday;
//        $package->wednesday = $model->wednesday;
//        $package->thursday = $model->thursday;
//        $package->friday = $model->friday;
//        $package->saturday = $model->saturday;
//        $package->sunday = $model->sunday;
//        return $package;
//    }
//}