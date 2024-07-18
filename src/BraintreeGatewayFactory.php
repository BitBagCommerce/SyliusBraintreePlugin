<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin;

use BitBag\SyliusBraintreePlugin\Action\Api\DoSaleAction;
use BitBag\SyliusBraintreePlugin\Action\Api\FindPaymentMethodNonceAction;
use BitBag\SyliusBraintreePlugin\Action\Api\GenerateClientTokenAction;
use BitBag\SyliusBraintreePlugin\Action\CaptureAction;
use BitBag\SyliusBraintreePlugin\Action\ConvertPaymentAction;
use BitBag\SyliusBraintreePlugin\Action\ObtainCardholderAuthenticationAction;
use BitBag\SyliusBraintreePlugin\Action\ObtainPaymentMethodNonceAction;
use BitBag\SyliusBraintreePlugin\Action\PurchaseAction;
use BitBag\SyliusBraintreePlugin\Action\StatusAction;
use BitBag\SyliusBraintreePlugin\ApiClient\BraintreeApiClientInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class BraintreeGatewayFactory extends GatewayFactory
{
    public const FACTORY_NAME = 'braintree';

    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => self::FACTORY_NAME,
            'payum.factory_title' => 'braintree',

            'payum.http_client' => '@bitbag_sylius_braintree_plugin.api_client.braintree',

            'payum.template.obtain_payment_method_nonce' => '@BitBagSyliusBraintreePlugin/Action/obtain_payment_method_nonce.html.twig',
            'payum.template.obtain_cardholder_authentication' => '@BitBagSyliusBraintreePlugin/Action/obtain_cardholder_authentication.html.twig',

            'payum.action.capture' => new CaptureAction(),

            'payum.action.purchase' => function (ArrayObject $config) {
                $action = new PurchaseAction();
                $action->setCardholderAuthenticationRequired($config['cardholderAuthenticationRequired']);

                return $action;
            },

            'payum.action.convert_payment' => new ConvertPaymentAction(),

            'payum.action.obtain_payment_method_nonce' => function (ArrayObject $config) {
                $action = new ObtainPaymentMethodNonceAction($config['payum.template.obtain_payment_method_nonce']);
                $action->setCardholderAuthenticationRequired($config['cardholderAuthenticationRequired']);

                return $action;
            },

            'payum.action.obtain_cardholder_authentication' => function (ArrayObject $config) {
                return new ObtainCardholderAuthenticationAction($config['payum.template.obtain_cardholder_authentication']);
            },

            'payum.action.status' => new StatusAction(),

            'payum.action.api.generate_client_token' => new GenerateClientTokenAction(),
            'payum.action.api.find_payment_method_nonce' => new FindPaymentMethodNonceAction(),
            'payum.action.api.do_sale' => new DoSaleAction(),

            'cardholderAuthenticationRequired' => true,
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = [
                'sandbox' => true,
                'storeInVault' => null,
                'storeInVaultOnSuccess' => null,
                'storeShippingAddressInVault' => null,
                'addBillingAddressToPaymentMethod' => null,
            ];
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [];

            $config['payum.api'] = function (ArrayObject $config) {
//                $config->validateNotEmpty($config['payum.required_options']);
//
//                return new Api((array) $config, $config['payum.http_client'], $config['httplug.message_factory']);

                /** @var BraintreeApiClientInterface $apiClient */
                $apiClient = $config['payum.http_client'];

                $apiClient->initialise((array) $config);

                return $apiClient;
            };
        }

        $config['payum.paths'] = [
            'PayumBraintree' => __DIR__ . '/Resources/views',
        ];
    }
}
