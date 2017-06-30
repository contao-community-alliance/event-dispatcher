<?php

/**
 * This file is part of contao-community-alliance/event-dispatcher.
 *
 * (c) 2013-2016 Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/event-dispatcher
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2013-2016 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/event-dispatcher/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/event-dispatcher
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Test;

use ContaoCommunityAlliance\Contao\EventDispatcher\EventDispatcherPopulator;

class EventDispatcherPopulatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        unset($GLOBALS['TL_EVENTS']);
        unset($GLOBALS['TL_EVENT_SUBSCRIBERS']);
    }

    /**
     * Test the population.
     *
     * @return void
     */
    public function testInstantiation()
    {
        $dispatcher = $this->getMockForAbstractClass('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $populator  = new EventDispatcherPopulator($dispatcher, [], []);

        $this->assertInstanceOf('ContaoCommunityAlliance\Contao\EventDispatcher\EventDispatcherPopulator', $populator);
    }

    /**
     * Test the population.
     *
     * @return void
     */
    public function testPopulateCallsOriginalConfigurator()
    {
        $dispatcher = $this->getMockForAbstractClass('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $populator  = new EventDispatcherPopulator($dispatcher, [], []);

        $mockConfigurator = $this->getMock('stdClass', ['configure']);
        $mockConfigurator->expects($this->once())->method('configure');

        $populator->setConfigurator([$mockConfigurator, 'configure']);
        $populator->populate();
    }

    /**
     * Test the population.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function testPopulateAddsListenerFromGlobalsArray()
    {
        $GLOBALS['TL_EVENTS'] = [
            'event-name' => [$listener = [new \DateTime(), 'testMethod']]
        ];

        $dispatcher = $this->getMockForAbstractClass('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $populator  = new EventDispatcherPopulator($dispatcher, [], []);

        $dispatcher->expects($this->once())->method('addListener')->with('event-name', $listener, 0);

        $populator->populate();
    }

    /**
     * Test the population.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function testPopulateAddsSubscriberFromGlobalsArray()
    {
        $dispatcher = $this->getMockForAbstractClass('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $subscriber = $this->getMockForAbstractClass('Symfony\Component\EventDispatcher\EventSubscriberInterface');

        $GLOBALS['TL_EVENT_SUBSCRIBERS'] = [$subscriber];

        $dispatcher->expects($this->once())->method('addSubscriber')->with($subscriber);

        $populator  = new EventDispatcherPopulator($dispatcher, [], []);
        $populator->populate();
    }
}
