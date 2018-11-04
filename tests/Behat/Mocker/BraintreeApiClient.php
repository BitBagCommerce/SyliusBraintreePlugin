<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusBraintreePlugin\Behat\Mocker;

use BitBag\SyliusBraintreePlugin\ApiClient\BraintreeApiClientInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Braintree\PaymentMethodNonce;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BraintreeApiClient implements BraintreeApiClientInterface
{
    /** @var BraintreeApiClientInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function initialise(array $options): void
    {
        $this->container->get('bitbag_sylius_braintree_plugin.api_client.braintree')->initialise($options);
    }

    public function findPaymentMethodNonce(string $nonceString): PaymentMethodNonce
    {
        return $this->container->get('bitbag_sylius_braintree_plugin.api_client.braintree')->findPaymentMethodNonce($nonceString);
    }

    public function sale(ArrayObject $params)
    {
        return $this->container->get('bitbag_sylius_braintree_plugin.api_client.braintree')->sale($params);
    }

    public function refund(string $transactionId)
    {
        return $this->container->get('bitbag_sylius_braintree_plugin.api_client.braintree')->refund($transactionId);
    }

    public function generateClientToken($params): string
    {
        return $this->container->get('bitbag_sylius_braintree_plugin.api_client.braintree')->generateClientToken($params);
    }
}
