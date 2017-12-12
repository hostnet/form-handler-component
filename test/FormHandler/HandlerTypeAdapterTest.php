<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\FormHandler;

use Hostnet\Component\FormHandler\Fixtures\Legacy\LegacyFormHandler;
use Hostnet\Component\FormHandler\Fixtures\Legacy\LegacyNamedFormHandler;
use Hostnet\Component\FormHandler\Fixtures\TestData;
use Hostnet\Component\FormHandler\Fixtures\TestType;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group legacy
 * @covers \Hostnet\Component\FormHandler\HandlerTypeAdapter
 */
class HandlerTypeAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testRegular()
    {
        $adapter = new HandlerTypeAdapter(new LegacyFormHandler());
        $config  = $this->prophesize(HandlerConfigInterface::class);

        $config->setType(TestType::class)->shouldBeCalled();
        $config->setName()->shouldNotBeCalled();
        $config->setOptions([])->shouldBeCalled();
        $config->onSuccess(Argument::type('callable'))->shouldBeCalled();
        $config->onFailure(Argument::type('callable'))->shouldBeCalled();

        self::assertEquals(LegacyFormHandler::class, $adapter->getHandlerClass());

        $adapter->configure($config->reveal());
    }

    public function testNamed()
    {
        $adapter = new HandlerTypeAdapter(new LegacyNamedFormHandler());
        $config  = $this->prophesize(HandlerConfigInterface::class);

        $config->setType(TestType::class)->shouldBeCalled();
        $config->setName('foobar')->shouldBeCalled();
        $config->setOptions([])->shouldBeCalled();
        $config->onSuccess(Argument::type('callable'))->shouldBeCalled();
        $config->onFailure(Argument::type('callable'))->shouldBeCalled();

        self::assertEquals(LegacyNamedFormHandler::class, $adapter->getHandlerClass());

        $adapter->configure($config->reveal());
    }

    public function testDelegateCall()
    {
        $request        = Request::create('/', 'POST');
        $legacy_handler = new LegacyNamedFormHandler();
        $adapter        = new HandlerTypeAdapter($legacy_handler);

        self::assertSame($legacy_handler->getData(), $adapter->delegateCall('getData', []));
        self::assertSame('http://success.nl/', $adapter->delegateCall('onSuccess', [$request])->getTargetUrl());
    }

    public function testSyncData()
    {
        $controller_data = new TestData();
        $adapter         = new HandlerTypeAdapter(new LegacyNamedFormHandler());

        $controller_data->test = 'foobar';

        $new_data = $adapter->syncData($controller_data);

        self::assertSame($controller_data->test, $new_data->test);
    }
}
