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
 * @method static void   register(string $name, string $path, ?string $base_namespace = null, ?string $contract = null)
 * @method static void   define(\Laika\Core\App\ResourceDefinition $definition)
 * @method static void   package(string $composer_file)
 * @method static array  getResources(?string $name = null)
 * @method static array  getClasses(string $name, ?string $contract = null)
 * @method static array  getFiles(string $name)
 * @method static array  names()
 * @method static bool   has(string $name)
 * @method static bool   isClassMap(string $name)
 * @method static array  entries(\Laika\Core\App\ResourceDefinition $definition)
 * @method static array  definitions(?string $name = null)
 * @method static string manifestPath()
 * @method static array  compile()
 * @method static string cache(?string $file = null)
 * @method static bool   loadManifest(?string $file = null)
 * @method static void   isolate()
 * @method static void   flush(?string $name = null)
 */
class Resource extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'resource';
    }
}