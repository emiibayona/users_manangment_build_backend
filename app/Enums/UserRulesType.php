<?php

namespace App\Enums;

enum UserRuleType: string
{
    case Create = 'create';
    case Update = 'update';
    case Delete = 'delete';
}