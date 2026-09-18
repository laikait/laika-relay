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
 * @method static mixed   get(string $key, mixed $default = null)
 * @method static bool    set(string $key, mixed $value, ?int $ttl = null)
 * @method static bool    has(string $key)
 * @method static bool    pop(string $key)
 * @method static bool    flush()
 * @method static int|false increment(string $key, int $by = 1)
 * @method static int|false decrement(string $key, int $by = 1)
 * @method static bool    forever(string $key, mixed $value)
 * @method static mixed   pull(string $key, mixed $default = null)
 * @method static mixed   remember(string $key, ?int $ttl, callable $callback)
 * @method static \Laika\Cache\Contracts\CacheDriverInterface store(string $name)
 * @method static \Laika\Cache\Contracts\CacheDriverInterface driver(?string $name = null)
 * @method static void    extend(string $name, callable $resolver)
 * @method static void    resetProcess()
 * @method static array   config()
 */
class Cache extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'cache';
    }
}
