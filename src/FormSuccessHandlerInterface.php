<?php
namespace Hostnet\Component\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
interface FormSuccessHandlerInterface
{
    /**
     * @param Request $request
     * @param FormInterface $form
     * @return mixed
     */
    public function onSuccess(Request $request, FormInterface $form);
}
