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

    public function add($eventKey, $callback)
    {
        $cEventKey = $this->canonicalizeName($eventKey);

        $this->listeners[$cEventKey][] = $callback;
    }

    public function canonicalizeName($name)
    {
        $replace = array('-' => '', '_' => '', ' ' => '', '\\' => '', '/' => '');

        return strtolower(strtr($name, $replace));
    }

    public function remove($eventKey, $callback = null)
    {
        $cEventKey = $this->canonicalizeName($eventKey);

        if (is_callable($callback)) {
            // remove only one callback
            $this->listeners[$cEventKey] = array_diff($this->listeners[$cEventKey], array($callback));
        } elseif (is_array($callback)) {
            // remove only more callback's
            $this->listeners[$cEventKey] = array_diff($this->listeners[$cEventKey], $callback);
        } else {
            // remove all callback's
            $this->listeners[$cEventKey] = array();
        }
    }

    public function trigger($eventKey, Event $event)
    {
        $cEventKey = $this->canonicalizeName($eventKey);

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
