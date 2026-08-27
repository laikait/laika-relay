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
 * CLASS: Laika\Core\Helper\Init
 *
 * @method static void db(?string $name = null)
 * @method static void file(array $params = [])
 * @method static void model(?string $name = null, bool $install = false)
 * @method static void mysql(?string $name = null, array $params = [])
 * @method static void redis(array $params = [])
 * @method static void memcached(array $params = [])
 */
class Init extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'init';
    }
}
