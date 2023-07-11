<?php
/**
 * @copyright 2012,2013 Binovo it Human Project, S.L.
 * @license http://www.opensource.org/licenses/bsd-license.php New-BSD
 */

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class CreateAdminUserCommand extends Command
{
    private string|PasswordHasherFactoryInterface|null $encoderFactory;
    private EntityManagerInterface $entityManager;

    /**
     * {@inheritDoc}
     * @see Command::__construct()
     */
    public function __construct(PasswordHasherFactoryInterface $encoder, EntityManagerInterface $manager)
    {
        $this->encoderFactory = $encoder;
        $this->entityManager = $manager;
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();
        $this->setName('elkarbackup:create_admin')
            ->addOption('reset', null, InputOption::VALUE_NONE, 'Reset the root account to the original values')
            ->setDescription('Creates initial admin user');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (0 != posix_geteuid()) {
            echo "You have to be root to run this command\n";

            return 1;
        }

        $user = $this->entityManager->getRepository(User::class)->find(User::SUPERUSER_ID);
        if (!$user) {
            $user = new User();
        } else if ($input->getOption('reset')) {
            echo "Admin user exists. Trying to reset to initial values.\n";
        } else {
            echo "Admin user exists and reset was not requested. Nothing to do.\n";
            return 0;
        }
        $factory = $this->encoderFactory;
        $encoder = $factory->getPasswordHasher($user);
        $user->setUsername('root');
        $user->setEmail('root@localhost');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setSalt(md5(uniqid(null, true)));
        $password = $encoder->hash('root');
        $user->setPassword($password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return 0;
    }
}