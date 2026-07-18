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
 * @method static void  origins(array $origins)
 * @method static void  methods(array $methods)
 * @method static void  headers(array $headers)
 * @method static void  expose(array $headers)
 * @method static void  credentials(bool $allow = true)
 * @method static void  maxAge(int $seconds)
 * @method static void  securityHeaders(array $headers)
 * @method static void  handle()
 */
class CORS extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'cors';
    }
}