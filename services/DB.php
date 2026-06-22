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

use PDO;
use Laika\Relay\Relay;

/**
 * CLASS: Laika\Core\Helper\DB
 * 
 * @method static void  run(?string $name = null)
 * @method static PDO   getConnection(?string $name = null)
 * @method static void  session(?string $name = null)
 */
class DB extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'db';
    }
}