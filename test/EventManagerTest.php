<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\EventManager;

use Zend\EventManager\Event;
use Zend\EventManager\EventManager;
use Zend\EventManager\Exception\RuntimeException;

class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testExpectedAttachAndTriggerBehaviour()
    {
        $eventManager = new EventManager();

        $listenersTriggered = [];

        $eventManager->attach('MyEvent', function (Event $event) use (&$listenersTriggered) {
            $listenersTriggered[] = 'listenerOne';
        }, 1);

        $eventManager->attach('MyEvent', function (Event $event) use (&$listenersTriggered) {
            $listenersTriggered[] = 'listenerTwo';
            $event->stopPropagation();
        }, 2);

        $eventManager->attach('MyEvent', function (Event $event) use (&$listenersTriggered) {
            $listenersTriggered[] = 'listenerThree';
        }, 3);

        $event = new Event();

        $eventManager->trigger('MyEvent', $event);

        // We expect two and three to have been triggered (because two stops propagation)
        $this->assertContains('listenerTwo', $listenersTriggered);
        $this->assertContains('listenerThree', $listenersTriggered);

        // Ensure events have been triggered according to highest priority first
        $this->assertSame([
            'listenerThree',
            'listenerTwo',
        ], $listenersTriggered);
    }

    public function testCreatingEventManagerWithoutListenerInstantiatorCausesExceptionWhenEventsFired()
    {
        $eventManager = new EventManager();

        $spec = [
            'SomeEventListener'
        ];
        $eventManager->attach('MyEvent', $spec);

        $this->setExpectedException(RuntimeException::class);

        $event = new Event();
        $eventManager->trigger('MyEvent', $event);
    }

    public function testLazyInstantiator()
    {
        $this->markTestIncomplete('Not written yet');
    }

    public function testDetachReturnsFalseIfEventDoesNotExist()
    {
        $this->markTestIncomplete('Not written yet');
    }

    public function testDetachAllListenersForEvent()
    {
        $this->markTestIncomplete('Not written yet');
    }

    public function testDetachSingleListener()
    {
        $this->markTestIncomplete('Not written yet');
    }

    public function testTriggerUntil()
    {
        $this->markTestIncomplete('Not written yet');
    }
}
