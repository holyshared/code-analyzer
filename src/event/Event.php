<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak\event;

use Zend\EventManager\Event as BaseEvent;
use DateTime;

/**
 * Class Event
 * @package cloak\event
 */
abstract class Event extends BaseEvent
{

    /**
     * @var \DateTime
     */
    private $sendAt;

    /**
     * @param  string|object $target
     * @param  array|\ArrayAccess $params
     */
    public function __construct($target = null, $params = null)
    {
        $this->sendAt = new DateTime();
        parent::__construct($this->name, $target, $params);
    }

    /**
     * @return \DateTime
     */
    public function getSendAt()
    {
        return $this->sendAt;
    }

}