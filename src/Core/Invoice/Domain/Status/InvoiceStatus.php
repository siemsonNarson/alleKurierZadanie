<?php

namespace App\Core\Invoice\Domain\Status;

use App\Core\Invoice\Domain\Exception\InvoiceStatusException;

enum InvoiceStatus: string
{
    case NEW = 'new';
    case PAID = 'paid';
    case CANCELED = 'canceled';

    public static function getByKey(string $statusKey): self
    {
        self::validate($statusKey);

        return constant(InvoiceStatus::class . '::' . $statusKey);
    }

    /**
     * @throws InvoiceStatusException
     */
    public static function validate(string $statusCase): void
    {
        $reflectionClass = new \ReflectionClass(self::class);
        $constants = $reflectionClass->getConstants();

        if (!in_array($statusCase, array_keys($constants), true)) {
            throw new InvoiceStatusException('Podany przypadek statusu nie istnieje. Użyj np. canceled');
        }
    }
}
