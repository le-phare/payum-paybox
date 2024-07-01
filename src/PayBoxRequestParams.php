<?php

namespace Marem\PayumPaybox;

/**
 * Interface PayBoxRequestParams.
 */
interface PayBoxRequestParams
{
    /**
     * Paybox Site param.
     */
    public const PBX_SITE = 'PBX_SITE';

    /**
     * Paybox rang param.
     */
    public const PBX_RANG = 'PBX_RANG';

    /**
     * Paybox identifiant param.
     */
    public const PBX_IDENTIFIANT = 'PBX_IDENTIFIANT';

    /**
     * Paybox hash param.
     */
    public const PBX_HASH = 'PBX_HASH';

    /**
     * Paybox code retour param.
     */
    public const PBX_RETOUR = 'PBX_RETOUR';

    /**
     * Paybox hmac param.
     */
    public const PBX_HMAC = 'PBX_HMAC';

    /**
     * Paybox payment type param.
     */
    public const PBX_TYPEPAIEMENT = 'PBX_TYPEPAIEMENT';

    /**
     * Paybox card type param.
     */
    public const PBX_TYPECARTE = 'PBX_TYPECARTE';

    /**
     * Paybox total param.
     */
    public const PBX_TOTAL = 'PBX_TOTAL';

    /**
     * Paybox devise param.
     */
    public const PBX_DEVISE = 'PBX_DEVISE';

    /**
     * Paybox order number param.
     */
    public const PBX_CMD = 'PBX_CMD';

    /**
     * Paybox porteur param.
     */
    public const PBX_PORTEUR = 'PBX_PORTEUR';

    /**
     * Paybox redirect url when payment done.
     */
    public const PBX_EFFECTUE = 'PBX_EFFECTUE';

    /**
     * Paybox redirect url when payment canceled.
     */
    public const PBX_ANNULE = 'PBX_ANNULE';

    /**
     * Paybox redirect url when payment refused.
     */
    public const PBX_REFUSE = 'PBX_REFUSE';

    /**
     * Paybox time param.
     */
    public const PBX_TIME = 'PBX_TIME';

    /**
     * Server to server callback URL, also called IPN (Instant Payment Notification), called
     * immediately after each payment attempt, whatever the result is. It allows the merchant to
     * manage safely the validation of the orders.
     * Fields defined in PBX_RETOUR will be sent to this page.
     */
    public const PBX_REPONDRE_A = 'PBX_REPONDRE_A';
}
