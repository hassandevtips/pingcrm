<?php
namespace App\Traits;

trait Status
{
    public static function statuses()
    {
        return ['new' => 'New', 'pending' => 'Pending', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
    }

    public static function getStatus(string $key)
    {
        return self::statuses()[$key] ?? null;
    }
}