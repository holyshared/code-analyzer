<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak\collection;

use PhpOption\Option;
use PhpCollection\Map;


/**
 * Trait PairStackable
 * @package cloak\collection
 */
trait PairStackable
{

    use Stackable;


    /**
     * @return mixed|null
     */
    public function first()
    {
        $first = $this->collection->first();
        return $this->returnValue($first);
    }

    /**
     * @return mixed|null
     */
    public function last()
    {
        $last = $this->collection->last();
        return $this->returnValue($last);
    }

    /**
     * @param Option $value
     * @return mixed|null
     */
    private function returnValue(Option $value)
    {
        if ($value->isEmpty()) {
            return null;
        }
        $kv = $value->get();

        return array_pop($kv);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->createArray($this->collection);
    }

    /**
     * @param Map $files
     * @return array
     */
    private function createArray(Map $collection)
    {
        $keys = $collection->keys();
        $values = $collection->values();

        return array_combine($keys, $values);
    }

}
