<?php

namespace Marem\PayumPaybox\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;

class StatusAction implements ActionInterface
{
    public const SUCCESS = '00000';

    public const CONTACT_CARD_OWNER = '01';

    public const INVALID_TRANSACTION = '12';

    public const INVALID_AMOUNT = '13';

    public const INVALID_HOLDER_NUMBER = '14';

    public const CUSTOM_CANCELATION = '17';

    public const RETRY_LATER = '19';

    public const EXPIRED_CARD = '33';

    /**
     * @param GetStatusInterface $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (null === $model['error_code']) {
            $request->markNew();

            return;
        }

        if (self::SUCCESS === $model['error_code']) {
            $request->markCaptured();

            return;
        }

        $request->markFailed();
    }

    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface
            && $request->getModel() instanceof \ArrayAccess
        ;
    }
}
