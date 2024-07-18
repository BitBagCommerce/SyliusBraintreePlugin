<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Action;

use BitBag\SyliusBraintreePlugin\ApiClient\BraintreeApiClientInterface;
use BitBag\SyliusBraintreePlugin\Reply\Api\PaymentMethodNonceArray;
use BitBag\SyliusBraintreePlugin\Reply\Api\TransactionResultArray;
use BitBag\SyliusBraintreePlugin\Request\Api\DoSale;
use BitBag\SyliusBraintreePlugin\Request\Api\FindPaymentMethodNonce;
use BitBag\SyliusBraintreePlugin\Request\ObtainCardholderAuthentication;
use BitBag\SyliusBraintreePlugin\Request\ObtainPaymentMethodNonce;
use BitBag\SyliusBraintreePlugin\Request\Purchase;
use Braintree\Transaction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\RuntimeException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;

final class PurchaseAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    protected $cardholderAuthenticationRequired;

    public function __construct()
    {
        $this->cardholderAuthenticationRequired = true;
    }

    public function setCardholderAuthenticationRequired(bool $value): void
    {
        $this->cardholderAuthenticationRequired = $value;
    }

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        if ($details->offsetExists('status')) {
            return;
        }

        try {
            $this->obtainPaymentMethodNonce($details);

            $this->obtainCardholderAuthentication($details);

            $this->doSaleTransaction($details);

            $this->resolveStatus($details);

            $details->validateNotEmpty([
                'paymentMethodNonce',
                'paymentMethodNonceInfo',
                'sale',
                'status',
            ]);
        } catch (RuntimeException $exception) {
            $details['status'] = 'failed';
            $details['status_reason'] = $exception->getMessage();
        }
    }

    protected function obtainPaymentMethodNonce(ArrayObject $details): void
    {
        if ($details->offsetExists('paymentMethodNonce')) {
            return;
        }

        $this->gateway->execute($request = new ObtainPaymentMethodNonce($details));

        $paymentMethodNonce = $request->getResponse();

        $details['paymentMethodNonce'] = $paymentMethodNonce;

        $this->findPaymentMethodNonceInfo($details);
    }

    protected function obtainCardholderAuthentication(ArrayObject $details): void
    {
        $paymentMethodNonceInfo = $details['paymentMethodNonceInfo'];

        $isNotRequired = true !== $this->cardholderAuthenticationRequired;
        $isNotCreditCardType = 'CreditCard' !== $paymentMethodNonceInfo['type'];
        $has3DSecureInfo = !empty($paymentMethodNonceInfo['threeDSecureInfo']);

        if ($isNotRequired || $isNotCreditCardType || $has3DSecureInfo) {
            return;
        }

        $this->gateway->execute($request = new ObtainCardholderAuthentication($details));

        $paymentMethodNonce = $request->getResponse();

        $details['paymentMethodNonce'] = $paymentMethodNonce;

        $this->findPaymentMethodNonceInfo($details);
    }

    protected function findPaymentMethodNonceInfo(ArrayObject $details): void
    {
        $this->gateway->execute($request = new FindPaymentMethodNonce($details['paymentMethodNonce']));

        $paymentMethodInfo = $request->getResponse();

        if (null === $paymentMethodInfo) {
            throw new RuntimeException('payment_method_nonce not found');
        }

        $details['paymentMethodNonceInfo'] = PaymentMethodNonceArray::toArray($paymentMethodInfo);
    }

    protected function doSaleTransaction(ArrayObject $details): void
    {
        if ($details->offsetExists('sale')) {
            return;
        }

        $saleOptions = [
            'submitForSettlement' => true,
        ];

        if ($details->offsetExists('paymentMethodNonce')) {
            $saleOptions['threeDSecure'] = [
                'required' => $this->cardholderAuthenticationRequired,
            ];
        }

        $details['saleOptions'] = $saleOptions;

        $this->gateway->execute($request = new DoSale($details));

        $transaction = $request->getResponse();

        $details['sale'] = TransactionResultArray::toArray($transaction);
    }

    protected function resolveStatus(ArrayObject $details): void
    {
        $details->validateNotEmpty(['sale']);

        $sale = $details['sale'];

        if (true === $sale['success']) {
            switch ($sale['transaction']['status']) {
                case Transaction::AUTHORIZED:
                case Transaction::AUTHORIZING:
                    $details['status'] = BraintreeApiClientInterface::AUTHORIZED;

                    break;
                case Transaction::SUBMITTED_FOR_SETTLEMENT:
                case Transaction::SETTLING:
                case Transaction::SETTLED:
                case Transaction::SETTLEMENT_PENDING:
                case Transaction::SETTLEMENT_CONFIRMED:
                    $details['status'] = BraintreeApiClientInterface::CAPTURED;

                    break;
            }
        } else {
            $details['status'] = BraintreeApiClientInterface::FAILED;
        }
    }

    public function supports($request): bool
    {
        return
            $request instanceof Purchase &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
