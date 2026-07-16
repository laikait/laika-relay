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
 * @method static void      register(string $extension, string $mimeType)
 * @method static string    fromExtension(string $extension)
 * @method static string    fromFile(string $filename)
 * @method static string    fromContent(string $content)
 * @method static array     all()
 */
class MimeType extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'mime';
    }
}
