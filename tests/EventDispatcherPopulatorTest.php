<?php

/**
 * This file is part of contao-community-alliance/event-dispatcher.
 *
 * (c) 2013-2018 Contao Community Alliance <https://c-c-a.org>
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/event-dispatcher
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2013-2018 Contao Community Alliance <https://c-c-a.org>
 * @license    https://github.com/contao-community-alliance/event-dispatcher/LICENSE LGPL-3.0+
 * @link       https://github.com/contao-community-alliance/event-dispatcher
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\EventDispatcher\Test;

use ContaoCommunityAlliance\Contao\EventDispatcher\EventDispatcherPopulator;

class EventDispatcherPopulatorTest extends \PHPUnit\Framework\TestCase
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

        $mockConfigurator = $this->getMockBuilder('stdClass')->setMethods(['configure'])->getMock();
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
            'event-name1' => [$listener1 = [new \DateTime(), 'testMethod']],
            'event-name2' => [
                [$listener2 = [new \DateTime(), 'testMethod'], 2],
                [$listener3 = [new \DateTime(), 'testMethod'], 3]
            ]
        ];

        $dispatcher = $this->getMockForAbstractClass('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $populator  = new EventDispatcherPopulator($dispatcher, [], []);

        $dispatcher->expects($this->exactly(3))
            ->method('addListener')
            ->withConsecutive(
                ['event-name1', $listener1, 0],
                ['event-name2', $listener2, 2],
                ['event-name2', $listener3, 3]
            );

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
        $dispatcher  = $this->getMockForAbstractClass('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $subscriber1 = $this->getMockForAbstractClass('Symfony\Component\EventDispatcher\EventSubscriberInterface');
        $subscriber2 = $this->getMockForAbstractClass('Symfony\Component\EventDispatcher\EventSubscriberInterface');
        $subscriber3 = $this->getMockForAbstractClass('Symfony\Component\EventDispatcher\EventSubscriberInterface');

        $subscriberCallable3 = function () use ($subscriber3) {
            return $subscriber3;
        };

        $GLOBALS['TL_EVENT_SUBSCRIBERS'] = [
            $subscriber1,
            get_class($subscriber2),
            $subscriberCallable3
        ];

        $dispatcher->expects($this->exactly(3))
            ->method('addSubscriber')
            ->withConsecutive(
                [$subscriber1],
                [$subscriber2],
                [$subscriber3]
            );

        $populator = new EventDispatcherPopulator($dispatcher, [], []);
        $populator->populate();
    }
}
