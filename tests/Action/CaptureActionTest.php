<?php

namespace Marem\PayumPaybox\Tests\Action;

use Marem\PayumPaybox\Action\CaptureAction;
use Marem\PayumPaybox\Api;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\Capture;
use Payum\Core\Request\GetHttpRequest;
use PHPUnit\Framework\TestCase;

class CaptureActionTest extends TestCase
{
    private const RETURN_QUERY = [
        'amount' => '22000',
        'reference' => 'CLI_5193_CDEW_5f918e4a5f1e2_394',
        'authorization_number' => 'XXXXXX',
        'error_code' => '00000',
        'card_type' => 'Maestro',
        'signature' => 'signature',
    ];

    public function testSignedReturnUpdatesThePayment()
    {
        $model = new \ArrayObject(['amount' => '22000']);

        $this->createAction(true)->execute(new Capture($model));

        self::assertSame(self::RETURN_QUERY, (array) $model);
    }

    public function testUnsignedReturnLeavesThePaymentPending()
    {
        $model = new \ArrayObject(['amount' => '22000']);

        $this->createAction(false)->execute(new Capture($model));

        self::assertSame(['amount' => '22000'], (array) $model);
    }

    private function createAction(bool $validSignature): CaptureAction
    {
        $gateway = $this->createMock(GatewayInterface::class);
        $gateway
            ->expects(self::once())
            ->method('execute')
            ->with(self::isInstanceOf(GetHttpRequest::class))
            ->willReturnCallback(static function (GetHttpRequest $request) {
                $request->query = self::RETURN_QUERY;
            })
        ;

        $api = $this->createMock(Api::class);
        $api->method('getOptions')->willReturn(['type_paiement' => 'CARTE']);
        $api->expects(self::once())->method('hasValidSignature')->willReturn($validSignature);
        $api->expects(self::never())->method('doPayment');

        $action = new CaptureAction();
        $action->setGateway($gateway);
        $action->setApi($api);

        return $action;
    }
}
