<?php
namespace App\Http\Helpers;

class MessageHelper
{
    static function getStatus ()
    {
        return Array(
            Array(
                'key' => 'pending',
                'color' => '#00a65a'
            ),
            Array(
                'key' => 'read',
                'color' => '#f39c12'
            ),
            Array(
                'key' => 'answered',
                'color' => '#dd4b39'
            )
        );
    }

    static function getTags ()
    {
        return Array(
            Array(
                'key' => 'important',
                'color' => '#00a65a'
            ),
            Array(
                'key' => 'error',
                'color' => '#f39c12'
            ),
            Array(
                'key' => 'contact',
                'color' => '#dd4b39'
            )
        );
    }
}
