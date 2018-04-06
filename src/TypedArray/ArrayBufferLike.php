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

use ArrayIterator\TypedArray\Interfaces\ArrayBufferLikeInterface;

/**
 * Class ArrayBufferLike
 * @package ArrayIterator\TypedArray
 *
 * @property-read int $length
 * @property-read int $byteLength
 */
abstract class ArrayBufferLike extends ArrayLike implements ArrayBufferLikeInterface
{
    /**
     * @var int The length in bytes of the array.
     */
    protected $byteLength = 0;

    /**
     * {@inheritdoc}
     */
    public function getByteLength() : int
    {
        return $this->byteLength;
    }

    /**
     * @return int
     */
    public function getLength() : int
    {
        return $this->length;
    }

    /**
     * Get Array by length sliced
     *
     * @return array
     */
    public function getBytesLengthArray() : array
    {
        return array_slice(
            $this->getArrayCopy(),
            0,
            $this->getByteLength()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function slice(int $begin = null, int $end = null)
    {
        $max    = $this->getByteLength();
        $begin = $begin < -$max ? -$max : $begin;
        $end    = $end === null || $end > $max ? $max : $end;
        if ($max < $begin || $begin >= $end || $end <= -$max) {
            return new static();
        }
        $length = $end - $begin;
        if ($begin < 0) {
            $begin = $max + $begin;
        }

        return new static(array_slice(
            $this->getBytesLengthArray(),
            $begin,
            $length
        ));
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name): bool
    {
        return $this->offsetExists($name);
    }

    /**
     * @param string|float|int $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     * @param string|float|int $name
     */
    public function __unset($name)
    {
        $this->offsetUnset($name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($name === 'length' && !isset($this->$name)) {
            return $this->count();
        }

        return property_exists($this, $name)
            ? $this->$name
            : $this->offsetGet($name);
    }
}
