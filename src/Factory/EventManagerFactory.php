<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zend-eventmanager for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Zend\EventManager\Factory;

use Zend\EventManager\EventManager;
use Zend\EventManager\ListenerPluginManager;
use Zend\ServiceManager\FactoryInterface;

class EventManagerFactory implements FactoryInterface
{
    /**
     * Override config key in an extending class to point to more specific key if needed
     *
     * @var string
     */
    protected $configKey = "event_manager";

    /**
     * {@inheritDoc}
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator, $className, array $options = [])
    {
        // @TODO: ZF3 should have a Config accessible through FQCN
        $config = $serviceLocator->get('Config');

        $evm = new EventManager($serviceLocator->get(ListenerPluginManager::class));

        if (isset($config[$this->configKey])) {
            if (isset($config[$this->configKey]['listener_aggregates'])) {
                foreach($config[$this->configKey]['listener_aggregates'] as $aggregateClass) {
                    // @TODO: perform some checks before like class_exists and maybe implements interface as well?
                    $aggregateClass::attachAggregate($evm);
                }
            }

            /*
             * @example
             *
             * [
             *   'event_listener_map' => [
             *     'my.event.name' => [
             *        [[My\Listener1::class, 'onMyEventName'], 100],
             *        [[My\Listener2::class, 'onMyEventName'], 100],
             *     ]
             *   ]
             * ]
             *
             *
             */
            if (isset($config[$this->configKey]['event_listener_map'])) {
                foreach($config[$this->configKey]['event_listener_map'] as $eventName => $listeners) {
                    foreach($listeners as $listenerSpecPriorityArr) {
                        $evm->attach($eventName, $listenerSpecPriorityArr[0], $listenerSpecPriorityArr[1]);
                    }
                }
            }

            return $evm;
        }
    }
} 