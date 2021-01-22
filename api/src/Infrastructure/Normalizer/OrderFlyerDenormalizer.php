<?php

declare(strict_types=1);

namespace App\Infrastructure\Normalizer;

use App\Infrastructure\Request\Address;
use App\Infrastructure\Request\FlyerOrder;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class OrderFlyerDenormalizer implements DenormalizerInterface
{
    /**
     * @param array<mixed> $context
     * @return FlyerOrder
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $flyerOrder = new FlyerOrder();

        if ($type !== FlyerOrder::class) {
            return $flyerOrder;
        }

        $flyerOrder->quantity = $data['quantity'] ?? null;
        $flyerOrder->partnerPrice = $data['partnerPrice'] ?? null;
        $flyerOrder->addToNewsletter = $data['addToNewsletter'] ?? null;

        $invoiceAddress = new Address();
        $invoiceAddress->companyName = $data['invoiceAddress']['companyName'] ?? null;
        $invoiceAddress->streetName = $data['invoiceAddress']['streetName'] ?? null;
        $invoiceAddress->zipCode = $data['invoiceAddress']['zipCode'] ?? null;
        $invoiceAddress->city = $data['invoiceAddress']['city'] ?? null;
        $invoiceAddress->contactPerson = $data['invoiceAddress']['contactPerson'] ?? null;
        $invoiceAddress->email = $data['invoiceAddress']['email'] ?? null;
        $invoiceAddress->emailRepeat = $data['invoiceAddress']['emailRepeat'] ?? null;
        $invoiceAddress->telephone = $data['invoiceAddress']['telephone'] ?? null;
        $flyerOrder->invoiceAddress = $invoiceAddress;

        if (array_key_exists('deliveryAddress', $data)) {
            $deliveryAddress = new Address();
            $deliveryAddress->companyName = $data['deliveryAddress']['companyName'] ?? null;
            $deliveryAddress->streetName = $data['deliveryAddress']['streetName'] ?? null;
            $deliveryAddress->zipCode = $data['deliveryAddress']['zipCode'] ?? null;
            $deliveryAddress->city = $data['deliveryAddress']['city'] ?? null;
            $deliveryAddress->contactPerson = $data['deliveryAddress']['contactPerson'] ?? null;
            $deliveryAddress->email = $data['deliveryAddress']['email'] ?? null;
            $deliveryAddress->emailRepeat = $data['deliveryAddress']['emailRepeat'] ?? null;
            $deliveryAddress->telephone = $data['deliveryAddress']['telephone'] ?? null;
            $flyerOrder->deliveryAddress = $deliveryAddress;
        }

        return $flyerOrder;
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return $type === FlyerOrder::class;
    }
}
