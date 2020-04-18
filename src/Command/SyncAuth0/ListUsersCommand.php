<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Command\SyncAuth0;

use App\Command\SyncAuth0\DTO\ListUsers\ListUsersFactory;
use App\Command\SyncAuth0\DTO\ListUsers\User;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListUsersCommand extends AbstractAuth0Command
{
    /**
     * @var string|null
     */
    protected static $defaultName = 'app:auth0:list-users';

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

        $users = $this->apiCallGet('/api/v2/users', new ListUsersFactory());

        $userRows = array();

        foreach ($users as $user)
        {
            /** @var User $user */

            $userRows[] = [
                $user->getEmail(),
                $user->getNickname(),
                $user->getUserId(),
                $user->getAppMetadata() === null ? "N/A" : $user->getAppMetadata()->getLadanseLegacyId(),
                $user->getLastLogin() === null ? "N/A" : $user->getLastLogin()
            ];
        }

        $this->io->title('Listing users defined in Auth0');

        $this->io->table(
            ['Email', 'Nickname', 'Auth0 User ID', 'Ladanse ID', 'Last Login'],
            $userRows
        );

        return 0;
    }
}
