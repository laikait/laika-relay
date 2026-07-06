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
 * @method static array getModelClasses()
 * @method static array getSchemaClasses()
 * @method static array getControllerClasses()
 * @method static array getMiddlewareClasses()
 * @method static array getAfterwareClasses()
 * @method static array getTemplateNames()
 * @method static array getRelayClasses()
 * @method static array getFunctionFiles()
 * @method static array getHookFiles()
 */
class Infra extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'infra';
    }
}