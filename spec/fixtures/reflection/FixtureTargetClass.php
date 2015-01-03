<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak\spec\reflection;

/**
 * Class FixtureTargetClass
 * @package cloak\spec\reflection
 */
class FixtureTargetClass
{
    use FixtureTargetTrait
    {
        bar as public bar1;
    }

    public function bar()
    {
    }

}
