<?php

namespace App\Core\Invoice\Application\Command\CreateInvoice;

use App\Core\User\Domain\Exception\InactiveUserException;
use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\Invoice\Domain\Invoice;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateInvoiceHandler
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly UserRepositoryInterface    $userRepository
    ) {}

    /**
     * @throws InactiveUserException | UserNotFoundException
     */
    public function __invoke(CreateInvoiceCommand $command): void
    {
        $user = $this->userRepository->getByEmail($command->email);
        if (!$user->isActive()) {
            throw new InactiveUserException('Nie można wygenerować faktury dla nieaktywnego użytkownika');
        }

        $this->invoiceRepository->save(new Invoice(
            $user,
            $command->amount
        ));

        $this->invoiceRepository->flush();
    }
}
