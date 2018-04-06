<?php
/**
 * MIT License
 *
 * Copyright (c) 2018 ArrayIterator
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

declare(strict_types=1);

namespace ArrayIterator\TypedArray;

use ArrayIterator\TypedArray\Interfaces\ArrayBufferInterface;
use ArrayIterator\TypedArray\Interfaces\ArrayBufferViewInterface;
use ArrayIterator\TypedArray\Interfaces\BufferInterface;

/**
 * Class ArrayBuffer
 * @package ArrayIterator\TypedArray
 */
class ArrayBuffer extends ArrayBufferAbstract
{
    /**
     * ArrayBuffer constructor.
     * @param int $byteLength
     */
    public function __construct(int $byteLength = 0)
    {
        parent::__construct($byteLength);
    }

    /**
     * {@inheritdoc}
     */
    public static function isView($any) : bool
    {
        if (!is_object($any)) {
            return false;
        }

        return $any instanceof ArrayBufferViewInterface || $any instanceof BufferInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function slice(int $begin = null, int $end = null) : ArrayBufferInterface
    {
        $max    = $this->getByteLength();
        $begin  = $begin?: 0;
        $begin = $begin < -$max ? -$max : $begin;
        $end    = $end === null || $end > $max ? $max : $end;
        if ($max < $begin || $begin >= $end || $end <= -$max) {
            return new static();
        }

        return new static($end - $begin);
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator() : \Traversable
    {
        return new \ArrayIterator(get_object_vars($this));
    }
}
