<?php
/**
 * Laika Framework Relay Service
 * Author: Showket Ahmed
 * Email: riyadhtayf@gmail.com
 * License: MIT
 * This file is part of the Laika PHP MVC Framework.
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Laika\Service;

use Laika\Relay\Relay;

/**
 * @method static void add(string $name, string $content, string $type = 'name')
 * @method static void print()
 */
class Meta extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'template.meta';
    }
}