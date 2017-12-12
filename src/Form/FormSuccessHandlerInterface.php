<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\Form;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
interface FormSuccessHandlerInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function onSuccess(Request $request);
}
