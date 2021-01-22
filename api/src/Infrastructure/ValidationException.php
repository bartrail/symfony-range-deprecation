<?php

declare(strict_types=1);

namespace App\Infrastructure;

use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use function sprintf;

final class ValidationException extends RuntimeException
{
    private ConstraintViolationListInterface $violationList;

    public function __construct(ConstraintViolationListInterface $violationList)
    {
        $this->violationList = $violationList;
        $message = $violationList->count() . " violations\n";
        /** @var ConstraintViolationInterface $violation */
        foreach ($violationList as $key => $violation) {
            /** @var string $message */
            $message = $violation->getMessage();
            $message .= sprintf(
                "%s. %s: %s\n",
                $key + 1,
                $violation->getPropertyPath(),
                $message
            );
        }
        parent::__construct($message);
    }

    public function getViolationList(): ConstraintViolationListInterface
    {
        return $this->violationList;
    }
}
