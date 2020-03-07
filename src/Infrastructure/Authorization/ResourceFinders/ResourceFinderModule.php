<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Infrastructure\Authorization\ResourceFinders;

use App\Infrastructure\Modules\LaDanseService;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ResourceFinderModule extends LaDanseService
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    abstract function findResourceById($resourceId);
}