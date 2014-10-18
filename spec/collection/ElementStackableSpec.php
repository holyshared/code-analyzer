<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use cloak\spec\collection\FixtureElementStack;

describe('ElementStackable', function() {
    before(function() {
        $this->stack = new FixtureElementStack([
            'a', 'b'
        ]);
    });
    describe('first', function() {
        it('return first element', function() {
            expect($this->stack->first())->toBe('a');
        });
    });
    describe('last', function() {
        it('return last element', function() {
            expect($this->stack->last())->toBe('b');
        });
    });
    describe('count', function() {
        it('return stacked count', function() {
            expect($this->stack->count())->toBe(2);
        });
    });
    describe('isEmpty', function() {
        context('when not empty', function() {
            before(function() {
                $this->stack = new FixtureElementStack(['a']);
            });
            it('return false', function() {
                expect($this->stack->isEmpty())->toBeFalse();
            });
        });
        context('when empty', function() {
            before(function() {
                $this->stack = new FixtureElementStack([]);
            });
            it('return true', function() {
                expect($this->stack->isEmpty())->toBeTrue();
            });
        });
    });
    describe('toArray', function() {
        before(function() {
            $this->elements = $this->stack->toArray();
        });
        it('return array', function() {
            expect($this->elements)->toBeAn('array');
        });
        it('contains all the elements', function() {
            expect(count($this->elements))->toBe(2);
        });
    });
    describe('getIterator', function() {
        it('return ArrayIterator', function() {
            expect($this->stack->getIterator())->toBeAnInstanceOf('ArrayIterator');
        });
    });
});
