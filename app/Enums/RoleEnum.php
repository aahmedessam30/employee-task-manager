<?php

namespace App\Enums;

enum RoleEnum : string
{
    case ADMIN = 'admin';
    case EMPLOYEE = 'employee';
    case MANAGER = 'manager';
}
