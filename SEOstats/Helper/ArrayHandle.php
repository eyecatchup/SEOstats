<?php
namespace SEOstats\Helper;

/**
 * Array - Handle
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/02/03
 */

class ArrayHandle
{
    protected $array;

    public function push($element)
    {
        $this->array[] = $element;
    }

    public function setElement($key, $element)
    {
        $this->array[$key] = $element;
    }

    public function count()
    {
        return count($this->array);
    }

    public function toArray()
    {
        return $this->array;
    }
}
