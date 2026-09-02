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
use Laika\Core\Nav\Builder;
use Laika\Core\Nav\Helper\Item;

/**
 * @method static Item      add(string $title, string $named, array $namedParams = [], bool $display = true)
 * @method static Item|null find(string $name)
 * @method static Builder   extend(string $name, callable $callback)
 * @method static Builder   configure(array $config)
 * @method static Builder   current(?string $url)
 * @method static string    render(string $class = 'navbar')
 * @method static Item[]    items()
 * @method static Builder   flush()
 */
class Nav extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'nav';
    }
}
