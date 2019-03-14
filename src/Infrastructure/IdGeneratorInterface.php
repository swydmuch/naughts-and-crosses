<?php
declare(strict_types=1);
namespace NAC\Infrastructure;

interface IdGeneratorInterface
{
    public static function generate(): string;
}
