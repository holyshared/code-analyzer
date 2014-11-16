<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use cloak\event\StartEvent;


describe('StartEvent', function() {
    beforeEach(function() {
        $this->startEvent = new StartEvent();
    });
    describe('#getSendAt', function() {
        it('should return time send the event', function() {
            expect($this->startEvent->getSendAt())->toBeAnInstanceOf('\DateTime');
        });
    });
});
