<?php
declare(strict_types=1);
namespace NAC\Infrastructure;

class IdGenerator
{
    public static function generate(): string
    {
        return uniqid();
    }
}