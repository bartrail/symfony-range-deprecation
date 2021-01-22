<?php

declare(strict_types=1);

namespace App\Infrastructure\Request;

use Symfony\Component\Validator\Constraints as Assert;

class FlyerOrder
{
    /**
     * @var mixed
     * @Assert\Sequentially(
     *     @Assert\NotNull(
     *          message="Bitte geben Sie die Anzahl an.",
     *          groups={"invoice_address"}
     *      ),
     *     @Assert\Type(
     *          type="integer",
     *          message="Die Anzahl muss eine ganze Zahl sein.",
     *          groups={"invoice_address"}
     *     ),
     *     @Assert\Range(
     *          min=5,
     *          max=1000,
     *          minMessage="Die Anzahl muss größer als {{ min }} sein.",
     *          maxMessage="Die Anzahl darf max. {{ max }} betragen",
     *          groups={"invoice_address"}
     *     )
     * )
     */
    public $quantity;

    /**
     * @var mixed
     * @Assert\Type(
     *     type="boolean",
     *     groups={"invoice_address"}
     * )
     */
    public $partnerPrice;

    /**
     * @var mixed
     * @Assert\Type(
     *     type="boolean",
     *     groups={"invoice_address"}
     * )
     */
    public $addToNewsletter;

    /**
     * @var mixed|Address
     * @Assert\NotBlank(groups={"invoice_address"})
     * @Assert\Valid(groups={"invoice_address"})
     */
    public $invoiceAddress;

    /**
     * @var mixed|Address
     * @Assert\Valid(groups={"delivery_address"})
     */
    public $deliveryAddress;
}
