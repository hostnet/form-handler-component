<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
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
    const SUCCESS = 'success';

    /**
     * The failure case is triggered when the form is submitted and is not
     * validated correctly.
     */
    const FAILURE = 'failure';

    /**
     * @codeCoverageIgnore private by design because this is an ENUM class
     */
    private function __construct()
    {
    }
}
