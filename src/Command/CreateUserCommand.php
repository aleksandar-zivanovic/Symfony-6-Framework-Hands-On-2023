<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new user account',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserRepository $users,
        )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('password', InputArgument::REQUIRED, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        $this->users->save($user, true);

        $io->success(sprintf('User %s account is created', $email));

        return Command::SUCCESS;
    }
}
