<?php

namespace App\Core\Contracts;

interface JsonResourceInterface
{
    public function toArray(): array;
    public static function collection($resources): array;
    public static function make($resource): array;
}