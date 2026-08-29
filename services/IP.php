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

use Generator;
use Laika\Relay\Relay;
use Laika\Core\IP\Version\IPv4;
use Laika\Core\IP\Version\IPv6;

/**
 * @method static IPv4|IPv6 parse(string $cidr)
 * @method static IPv4|IPv6 fromRange(string $startIp, string $endIp)
 * @method static IPv4      fromMask(string $networkIp, string $subnetMask)
 * @method static bool      ipInCidr(string $ip, string $cidr)
 * @method static array     summarise(array $cidrs)
 */
class IP extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'ip';
    }
}