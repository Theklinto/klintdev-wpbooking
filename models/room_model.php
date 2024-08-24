<?php

namespace KlintDev\WPBooking;

require_once KDWPB_PATH . "models/room_image_model.php";

class RoomModel{
    public int|null $id;
    public string $name;
    public string $description;
    /**
     * @var RoomImageModel[]
     */
    public array $images;
}