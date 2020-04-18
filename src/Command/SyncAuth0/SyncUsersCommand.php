<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Command\SyncAuth0;

use App\Command\SyncAuth0\DTO\CreateUser\PostUser;
use App\Command\SyncAuth0\DTO\CreateUser\PostUserFactory;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncUsersCommand extends AbstractAuth0Command
{
    /**
     * @var string|null
     */
    protected static $defaultName = 'app:auth0:sync-users';

    /**
     * @var SymfonyStyle
     */
    private SymfonyStyle $io;

    protected function configure()
    {
        $this->setDescription('Add a short description for your command');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $postUser = new PostUser();

        $postUser
            ->setEmail('john3@doe.com')
            ->setNickname('johny')
            ->setPassword('.TY*8csR[9&n#z[w')
            ->setConnection('Username-Password-Authentication');

        /** @noinspection PhpUnusedLocalVariableInspection */
        $response = $this->apiCallPost('/api/v2/users', $postUser, new PostUserFactory());

        return 0;
    }
}
