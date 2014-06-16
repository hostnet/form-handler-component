<?php
namespace Hostnet\Component\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
interface FormProviderInterface
{
    /**
     * @param Request                  $request
     * @param FormInformationInterface $handler
     * @param FormInterface            $form
     */
    public function handle(Request $request, FormInformationInterface $handler, FormInterface $form = null);
}
