<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

use App\Infrastructure\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

final class JsonExceptionListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $response = $this->handleException($event->getThrowable());

        $event->setResponse($response);
    }

    private function handleException(Throwable $exception): JsonResponse
    {
        if ($exception instanceof UnexpectedValueException) {
            $data = [
                'code' => 451,
                'message' => 'Wrong request: json expected.',
                'data' => null,
            ];
            $this->logger->notice('An UnexpectedValueException reached the user', $data);

            return new JsonResponse($data, 400);
        }

        if ($exception instanceof AccessDeniedException) {
            $data = [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'data' => null,
            ];
            $this->logger->notice('A Symfony AccessDeniedException reached the user', $data);

            return new JsonResponse($data, 403);
        }

        if ($exception instanceof HttpException) {
            $data = [
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage(),
                'data' => null,
            ];
            $this->logger->notice('A HttpException reached the user', $data);

            return new JsonResponse($data, $exception->getStatusCode());
        }

        if ($exception instanceof ValidationException) {
            $data = [
                'code' => 450,
                'message' => 'Validation errors.',
                'data' => $this->transformViolationList($exception->getViolationList()),
            ];
            $this->logger->notice('A ValidationException reached the user', $data);

            return new JsonResponse($data, 422);
        }

        return $this->handleHardException($exception);
    }

    private function handleHardException(Throwable $exception): JsonResponse
    {
        $this->logger->critical('exception', ['message' => $exception->getMessage()]);

        return new JsonResponse(
            [
                'code' => 500,
                'message' => 'Internal Server Error.',
                'data' => $this->transformException($exception),
            ],
            500
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function transformException(Throwable $exception): array
    {
        $previousException = $exception->getPrevious();

        return [
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
            'previous' => $previousException ? $this->transformException($previousException) : null,
        ];
    }

    /**
     * @return array<int, array>
     */
    private function transformViolationList(ConstraintViolationListInterface $violationList): array
    {
        $errors = [];
        /** @var ConstraintViolation $violation */
        foreach ($violationList as $violation) {
            $errors[] = [
                'property' => $violation->getPropertyPath(),
                'message' => (string)$violation->getMessage(),
            ];
        }

        return $errors;
    }
}
