<?php

namespace App\Core\User\Application\Command\GetInactiveUser;

use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetInactiveUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}
    public function __invoke(GetInactiveUserQuery $command): ?array
    {
        return $this->userRepository->getInactive();
    }
}
