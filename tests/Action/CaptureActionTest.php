<?php

namespace Marem\PayumPaybox\Tests\Action;

use Marem\PayumPaybox\Action\CaptureAction;
use Marem\PayumPaybox\Api;
use Marem\PayumPaybox\PayBoxRequestParams;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\Capture;
use Payum\Core\Request\GetHttpRequest;
use PHPUnit\Framework\TestCase;

class CaptureActionTest extends TestCase
{
    private const DETAILS = [
        PayBoxRequestParams::PBX_CMD => 'CLI_5193_CDEW_5f918e4a5f1e2_394',
        PayBoxRequestParams::PBX_TOTAL => 22000,
    ];

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
        $model = new \ArrayObject(self::DETAILS);

        $this->createAction(self::RETURN_QUERY, true)->execute(new Capture($model));

        self::assertSame(self::DETAILS + self::RETURN_QUERY, (array) $model);
    }

    public function testUnsignedReturnLeavesThePaymentPending()
    {
        $model = new \ArrayObject(self::DETAILS);

        $this->createAction(self::RETURN_QUERY, false)->execute(new Capture($model));

        self::assertSame(self::DETAILS, (array) $model);
    }

    /**
     * @dataProvider provideReturnOfAnotherPayment
     */
    public function testSignedReturnOfAnotherPaymentLeavesThePaymentPending(array $query)
    {
        $model = new \ArrayObject(self::DETAILS);

        $this->createAction($query, true)->execute(new Capture($model));

        self::assertSame(self::DETAILS, (array) $model);
    }

    public function provideReturnOfAnotherPayment(): iterable
    {
        yield 'another reference' => [['reference' => 'CLI_5193_CDEW_5f918e4a5f1e2_393'] + self::RETURN_QUERY];
        yield 'another amount' => [['amount' => '100'] + self::RETURN_QUERY];
    }

    private function createAction(array $query, bool $validSignature): CaptureAction
    {
        $gateway = $this->createMock(GatewayInterface::class);
        $gateway
            ->expects(self::once())
            ->method('execute')
            ->with(self::isInstanceOf(GetHttpRequest::class))
            ->willReturnCallback(static function (GetHttpRequest $request) use ($query) {
                $request->query = $query;
            })
        ;

        $api = $this->createMock(Api::class);
        $api->method('getOptions')->willReturn(['type_paiement' => 'CARTE']);
        $api->method('hasValidSignature')->willReturn($validSignature);
        $api->expects(self::never())->method('doPayment');

        $action = new CaptureAction();
        $action->setGateway($gateway);
        $action->setApi($api);

        return $action;
    }
}
