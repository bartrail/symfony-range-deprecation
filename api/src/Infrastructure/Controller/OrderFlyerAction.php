<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\Request\FlyerOrder;
use App\Infrastructure\ValidationException;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class OrderFlyerAction
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route("/order-flyer", methods={"POST"})
     */
    public function __invoke(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $flyerOrderDto = $this->serializer->deserialize($content, FlyerOrder::class, 'json');

        if (!$flyerOrderDto instanceof FlyerOrder) {
            throw new RuntimeException();
        }

        $validationGroups = ['invoice_address'];
        if ($flyerOrderDto->deliveryAddress !== null) {
            $validationGroups[] = 'delivery_address';
        }

        $errors = $this->validator->validate($flyerOrderDto, null, $validationGroups);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        return new JsonResponse($this->serializer->serialize($flyerOrderDto, 'json'), JsonResponse::HTTP_OK, [], true);
    }
}
