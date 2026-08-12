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
 * @method static void      generate(int $byte = 32)
 * @method static string    get()
 * @method static true      validate(int $byte = 32)
 * @method static void      fix(int $byte = 32)
 */
class AppKey extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'app.key';
    }
}
