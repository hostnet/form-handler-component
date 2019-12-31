<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\FormHandler\Fixtures\Legacy;

use Hostnet\Component\Form\AbstractFormHandler;
use Hostnet\Component\Form\FormFailureHandlerInterface;
use Hostnet\Component\Form\FormSuccessHandlerInterface;
use Hostnet\Component\FormHandler\Fixtures\TestData;
use Hostnet\Component\FormHandler\Fixtures\TestType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class LegacyFormVariableOptionsHandler extends AbstractFormHandler implements
    FormSuccessHandlerInterface,
    FormFailureHandlerInterface
{
    private $data;

    public function __construct()
    {
        $this->data = new TestData();
    }

    public function getOptions(): array
    {
        return ['attr' => ['class' => $this->data->test]];
    }

    public function getType(): string
    {
        return TestType::class;
    }

    public function getData(): TestData
    {
        return $this->data;
    }

    public function onSuccess(Request $request): RedirectResponse
    {
        return new RedirectResponse('http://success.nl/' . $this->data->test);
    }

    public function onFailure(Request $request): RedirectResponse
    {
        return new RedirectResponse('http://failure.nl/' . $this->data->test);
    }
}
