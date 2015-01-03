<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use cloak\reflection\MethodSelector;
use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Reflection\MethodReflection;


describe('MethodSelector', function() {
    beforeEach(function() {
        $this->parentClass = 'cloak\spec\reflection\FixtureTargetClass';
        $this->subClass = 'cloak\spec\reflection\FixtureTargetSubClass';

        $reflection = new ClassReflection($this->subClass);
        $classMethods = $reflection->getMethods(MethodReflection::IS_PUBLIC);

        $this->selector = new MethodSelector($classMethods);
    });
    describe('#excludeNative', function() {
        it('return filter result', function() {
            $selector = $this->selector->excludeNative();
            expect($selector->count())->toBe(4);
        });
    });
    describe('#excludeInherited', function() {
        it('return filter result', function() {
            $selector = $this->selector->excludeInherited($this->parentClass);
            expect($selector->count())->toBe(1);
        });
    });
});