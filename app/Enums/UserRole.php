<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum UserRole: string {

    use EnumTrait;

    case Admin = 'admin';
    case User = 'user';
}
