<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\FormHandler;

/**
 * Actions the Handler supports. These can be registered using the
 * ActionSubscriberInterface.
 *
 * @see ActionSubscriberInterface
 */
final class HandlerActions
{
    /**
     * The success action is triggered when the form is submitted and is
     * validated correctly.
     */
    public const SUCCESS = 'success';

    /**
     * The failure case is triggered when the form is submitted and is not
     * validated correctly.
     */
    public const FAILURE = 'failure';

    /**
     * @codeCoverageIgnore private by design because this is an ENUM class
     */
    private function __construct()
    {
    }
}
