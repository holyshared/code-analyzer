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
use cloak\reflection\collection\ReflectionCollection;
use cloak\spec\reflection\FixtureTargetClass;
use cloak\spec\reflection\FixtureTargetSubClass;

describe(MethodResult::class, function() {
    beforeEach(function() {
        $this->subClass = FixtureTargetSubClass::class;
        $this->selector = MethodSelector::fromClassName($this->subClass);
    });
    describe('#excludeNative', function() {
        it('return filter result', function() {
            $selector = $this->selector->excludeNative();
            expect($selector->count())->toBe(4);
        });
    });
    describe('#excludeInherited', function() {
        it('return filter result', function() {
            $selector = $this->selector->excludeInherited();
            expect($selector->count())->toBe(1);
        });
    });
    describe('#excludeTraitMethods', function() {
        beforeEach(function() {
            $this->class = FixtureTargetClass::class;
            $this->selector = MethodSelector::fromClassName($this->class);
        });
        it('return filter result', function() {
            $selector = $this->selector->excludeTraitMethods();
            expect($selector->count())->toBe(1);
        });
    });
    describe('#toCollection', function() {
        beforeEach(function() {
            $this->class = FixtureTargetClass::class;

            $this->selector = MethodSelector::fromClassName($this->class);
            $this->collection = $this->selector->toCollection();
        });
        it('return cloak\reflection\collection\ReflectionCollection instance', function() {
            expect($this->collection->count())->toBe(2);
            expect($this->collection)->toBeAnInstanceOf(ReflectionCollection::class);
        });
    });
});
