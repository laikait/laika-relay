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
 * @method static void      setTtl(int $ttl)
 * @method static void      bindFingerprint(bool $bind = true)
 * @method static string    generate()
 * @method static string    token()
 * @method static bool      validate(?string $token)
 * @method static ?string   fromRequest(string $header = 'X-Csrf-Token')
 * @method static void      field()
 */
class CSRF extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'csrf';
    }
}