<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

/**
 * @api
 *
 * TODO Convert the enum into a pregexp to give more flexibility.
 *      We can even opt in just for a-z and 0-9. Maybe including dashes and or dots, underscores.
 */
enum ItemGroup: string
{
    case REACTION = 'reaction';
    case BOOKMARK = 'bookmark';

    public static function fromValue(string|ItemGroup $value): ItemGroup
    {
        if ($value instanceof self) {
            return $value;
        }

        foreach (self::cases() as $status) {
            if ($value === $status->value) {
                return $status;
            }
        }

        throw new \ValueError(
            sprintf(
                '%s is not a valid backing value for enum %s',
                esc_html($value),
                self::class
            )
        );
    }
}
