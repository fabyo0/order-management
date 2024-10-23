<?php

namespace App\enums;

enum PasswordStrength: int
{
    case Weak = 1;
    case Fair = 2;
    case Good = 3;
    case Strong = 4;

    public static function toArray(): array
    {
        return [
            self::Weak->value => 'Weak',
            self::Fair->value => 'Fair',
            self::Good->value => 'Good',
            self::Strong->value => 'Strong'
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::Weak => 'Weak',
            self::Fair => 'Fair',
            self::Good => 'Good',
            self::Strong => 'Strong',
        };
    }
}
