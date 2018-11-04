<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusBraintreePlugin\PHPUnit\Action;

use BitBag\SyliusBraintreePlugin\Action\StatusAction;
use Payum\Core\Request\GetHumanStatus;

class StatusActionTest extends GenericActionTest
{
    protected $actionClass = StatusAction::class;

    protected $requestClass = GetHumanStatus::class;

    /**
     * @test
     */
    public function shouldMarkNewIfDetailsEmpty()
    {
        $action = new StatusAction();

        $action->execute($status = new GetHumanStatus([]));

        $this->assertTrue($status->isNew());
    }

    /**
     * @test
     */
    public function shouldMarkPendingIfOnlyHasPaymentMethodNonce()
    {
        $action = new StatusAction();

        $action->execute($status = new GetHumanStatus([
            'paymentMethodNonce' => '1234',
        ]));

        $this->assertTrue($status->isPending());
    }

    /**
     * @test
     */
    public function shouldMarkFailedIfHasFailedStatus()
    {
        $action = new StatusAction();

        $action->execute($status = new GetHumanStatus([
            'status' => 'failed',
        ]));

        $this->assertTrue($status->isFailed());
    }

    /**
     * @test
     */
    public function shouldMarkAuthorized()
    {
        $action = new StatusAction();

        $action->execute($status = new GetHumanStatus([
            'status' => 'authorized',
            'sale' => [
                'success' => true,
            ],
        ]));

        $this->assertTrue($status->isAuthorized());
    }

    /**
     * @test
     */
    public function shouldMarkCaptured()
    {
        $action = new StatusAction();

        $action->execute($status = new GetHumanStatus([
            'status' => 'captured',
            'sale' => [
                'success' => true,
            ],
        ]));

        $this->assertTrue($status->isCaptured());
    }

    /**
     * @test
     */
    public function shouldMarkUnknownIfMissingTransactionSuccess()
    {
        $action = new StatusAction();

        $action->execute($status = new GetHumanStatus([
            'status' => 'captured',
        ]));

        $this->assertTrue($status->isUnknown());
    }

    /**
     * @test
     */
    public function shouldMarkUnknownIfTransactionFalseSuccess()
    {
        $action = new StatusAction();

        $action->execute($status = new GetHumanStatus([
            'status' => 'captured',
            'sale' => [
                'success' => false,
            ],
        ]));

        $this->assertTrue($status->isUnknown());
    }
}
