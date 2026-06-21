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
 * @method static Item          add(string $title, string $url, bool $display = true)
 * @method static Item          child(string $title, string $url, bool $display = true)
 * @method static Item|Builder  end()
 * @method static string        render(string $class = 'navbar')
 */
class Nav extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'nav';
    }
}