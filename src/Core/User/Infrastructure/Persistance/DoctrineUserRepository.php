<?php

namespace App\Core\User\Infrastructure\Persistance;

use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Psr\EventDispatcher\EventDispatcherInterface;

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface   $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {}

    /**
     * @throws NonUniqueResultException
     */
    public function getByEmail(string $email): User
    {
        $user = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.is = :user_email')
            ->where('u.email = :user_email')
            ->setParameter(':user_email', $email)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            throw new UserNotFoundException('Użytkownik nie istnieje');
        }

        return $user;
    }

    /**
     * @return User[]|null
     */
    public function getInactive(): ?array
    {
        return $this->entityManager->getRepository(User::class)
            ->findBy(['isActive' => false]);
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);

        $events = $user->pullEvents();
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
