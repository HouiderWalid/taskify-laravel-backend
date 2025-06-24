<?php

namespace App\Models\helpers;

trait Validation
{
    public static function existIn($attribute = null): string
    {
        $attribute = $attribute ?? static::getIdAttributeName();
        return join(':', ['exists', join(',', [static::TABLE_NAME, $attribute])]);
    }

    public static function uniqueAt($attribute = null): string
    {
        $attribute = $attribute ?? static::getIdAttributeName();
        return join(':', ['unique', join(',', [static::TABLE_NAME, $attribute])]);
    }
}
