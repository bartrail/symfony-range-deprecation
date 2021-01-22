<?php

declare(strict_types=1);

namespace App\Infrastructure\Request;

use Symfony\Component\Validator\Constraints as Assert;

class Address
{
    /**
     * @var mixed
     * @Assert\Sequentially(
     *     @Assert\NotBlank(
     *          message="Bitte geben Sie Ihren Firmennamen an.",
     *          groups={"invoice_address", "delivery_address"}
     *      ),
     *     @Assert\Length(
     *          min=3,
     *          max=255,
     *          minMessage="Bitte geben Sie min. {{ limit }} Zeichen an.",
     *          maxMessage="Bitte geben Sie max. {{ limit }} Zeichen an.",
     *          groups={"invoice_address", "delivery_address"}
     *      )
     * )
     */
    public $companyName;

    /**
     * @var mixed
     * @Assert\Sequentially(
     *      @Assert\NotBlank(
     *          message="Bitte geben Sie Ihre Straße + Hausnummer an.",
     *          groups={"invoice_address", "delivery_address"}
     *      ),
     *      @Assert\Length(
     *          min=3,
     *          max=255,
     *          minMessage="Bitte geben Sie min. {{ limit }} Zeichen an.",
     *          maxMessage="Bitte geben Sie max. {{ limit }} Zeichen an.",
     *          groups={"invoice_address", "delivery_address"}
     *      )
     * )
     */
    public $streetName;

    /**
     * @var mixed
     * @Assert\Sequentially(
     *      @Assert\NotBlank(
     *          message="Bitte geben Sie Ihre Postleitzahl an.",
     *          groups={"invoice_address", "delivery_address"}
     *      ),
     *      @Assert\Length(
     *          min=3,
     *          max=255,
     *          minMessage="Bitte geben Sie min. {{ limit }} Zeichen an.",
     *          maxMessage="Bitte geben Sie max. {{ limit }} Zeichen an.",
     *          groups={"invoice_address", "delivery_address"}
     *      )
     * )
     */
    public $zipCode;

    /**
     * @var mixed
     * @Assert\Sequentially(
     *      @Assert\NotBlank(
     *          message="Bitte geben Sie Ihre Stadt an.",
     *          groups={"invoice_address", "delivery_address"}
     *      ),
     *      @Assert\Length(
     *          min=3,
     *          max=255,
     *          minMessage="Bitte geben Sie min. {{ limit }} Zeichen an.",
     *          maxMessage="Bitte geben Sie max. {{ limit }} Zeichen an.",
     *          groups={"invoice_address", "delivery_address"}
     *      )
     * )
     */
    public $city;

    /**
     * @var mixed
     * @Assert\Sequentially(
     *      @Assert\NotBlank(
     *          message="Bitte tragen Sie hier den Namen der Kontaktperson ein.",
     *          groups={"invoice_address", "delivery_address"}
     *      ),
     *      @Assert\Length(
     *          min=3,
     *          max=255,
     *          minMessage="Bitte geben Sie min. {{ limit }} Zeichen an.",
     *          maxMessage="Bitte geben Sie max. {{ limit }} Zeichen an.",
     *          groups={"invoice_address", "delivery_address"}
     *      )
     * )
     */
    public $contactPerson;

    /**
     * @var mixed
     * @Assert\Sequentially(
     *      @Assert\NotBlank(
     *          message="Bitte geben Sie Ihre E-Mail Adresse an.",
     *          groups={"invoice_address"}
     *      ),
     *      @Assert\Email(
     *          message="Die angebebene E-Mail Adresse ist ungültig.",
     *          groups={"invoice_address"}
     *      )
     * )
     */
    public $email;

    /**
     * @var mixed
     * @Assert\Sequentially(
     *      @Assert\NotBlank(
     *          message="Bitte wiederholen Sie Ihre E-Mail Adresse.",
     *          groups={"invoice_address"}
     *      ),
     *      @Assert\EqualTo(
     *          propertyPath="email",
     *          message="Bitte wiederholen Sie Ihre E-Mail Adresse.",
     *          groups={"invoice_address"}
     *      )
     * )
     */
    public $emailRepeat;

    /**
     * @var mixed
     * @Assert\Sequentially(
     *      @Assert\NotBlank(
     *          message="Bitte geben Sie Ihre Telefonnummer an.",
     *          groups={"invoice_address"}
     *      ),
     *      @Assert\Length(
     *          min=3,
     *          max=255,
     *          minMessage="Bitte geben Sie min. {{ limit }} Zeichen an.",
     *          maxMessage="Bitte geben Sie max. {{ limit }} Zeichen an.",
     *          groups={"invoice_address"}
     *      )
     * )
     */
    public $telephone;
}
