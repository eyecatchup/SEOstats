<?php
namespace SEOstats\Helper;

/**
 * Event
 *
 * @package    SEOstats
 * @author     Clemens Sahs <clemens.sahs@slides-worker.org>
 * @license    http://eyecatchup.mit-license.org/  MIT License
 */
class Event
{
    const STATUS_UNTRIGGERED = 'untriggered';
    const STATUS_CANCELLED = 'cancel';
    const STATUS_DONE = 'done';
    const STATUS_PROGRESS = 'progress';

    protected $data = array();
    protected $status = self::STATUS_UNTRIGGERED;
    protected $exception = null;

    public function cancel()
    {
        $this->status = self::STATUS_CANCELLED;
    }

    public function isInProgress()
    {
        return ($this->status === self::STATUS_PROGRESS);
    }

    public function startProgress()
    {
        $this->status = self::STATUS_PROGRESS;
    }

    public function setException($exception)
    {
        $this->exception = $exception;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function setProp($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function getProp($key)
    {
        return $this->data[$key];
    }

    public function stopProgress()
    {
        $this->status = self::STATUS_DONE;
    }
}
