<?php

namespace App\Core\User\Application\Command\CreateUser;

use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\User\Domain\Exception\UserAlreadyExistsException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * @throws
     */
    public function __invoke(CreateUserCommand $command): void
    {
        try {
            $this->userRepository->getByEmail($command->email);

            throw new UserAlreadyExistsException('Użytkownik o podanym mailu już istnieje.');
        } catch (UserNotFoundException $e) {
            $this->userRepository->save(new User($command->email));
            $this->userRepository->flush();
        }
    }
}
