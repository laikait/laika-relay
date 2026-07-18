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
 * @method static void  db(?string $name = null)
 * @method static void  dbSession(?string $name = null)
 * @method static void  fileSession(array $params = [])
 * @method static void  default(?string $connection = null)
 */
class Init extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'init';
    }
}