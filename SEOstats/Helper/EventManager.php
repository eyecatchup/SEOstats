<?php
namespace SEOstats\Helper;

/**
 * EventManager
 *
 * @package    SEOstats
 * @author     Clemens Sahs <clemens.sahs@slides-worker.org>
 * @license    http://eyecatchup.mit-license.org/  MIT License
 */
class EventManager
{
    protected $listeners = array();
    protected $instance = null;

    protected function __construct()
    {
    }

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public static function add($eventKey, $callback)
    {
        $this->listeners[$eventKey][] = $callback;
    }

    public static function remove($eventKey, $callback = null)
    {
        if (is_callable($callback)) {
            // remove only one callback
            $this->listeners[$eventKey] = array_diff($this->listeners[$eventKey], array($callback));
        } elseif (is_array($callback)) {
            // remove only more callback's
            $this->listeners[$eventKey] = array_diff($this->listeners[$eventKey], $callback);
        } else {
            // remove all callback's
            $this->listeners[$eventKey] = array();
        }
    }

    public static function trigger($eventKey, Event $event)
    {
        $event->startProgress();

        foreach ($this->listeners[$eventKey] as $listener) {
            if (!$event->isInProgress()) {
                return false;
            }

            try {
                $result = call_user_func_array($listener, array($event));

                if (!$result) {
                    $event->cancel();
                }
            } catch (\Exception $e) {
                $event->cancel();
            }
        }

        $event->stopProgress();
    }
}
