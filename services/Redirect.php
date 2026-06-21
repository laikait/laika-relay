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
 * @method static static with(string $message, bool $status)
 * @method static static back(int $code = 302)
 * @method static static to(string $to, array $params = [], int $code = 302)
 */
class Redirect extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'redirect';
    }
}