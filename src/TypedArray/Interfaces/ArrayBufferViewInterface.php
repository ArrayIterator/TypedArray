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

namespace ArrayIterator\TypedArray\Interfaces;

use ArrayIterator\TypedArray\ArrayBuffer;
use ArrayIterator\TypedArray\ArrayLike;

/**
 * Interface ArrayBufferViewInterface
 * @package ArrayIterator\TypedArray\Interfaces
 *
 * @property-read ArrayBuffer $buffer
 * @property-read int $byteLength
 * @property-read int $length
 * @property-read int $byteOffset
 *
 * @todo add more methods
 */
interface ArrayBufferViewInterface extends ArrayBufferLikeInterface
{
    /**
     * Get Byte offset
     *
     * @return int
     */
    public function getByteOffset() : int;

    /**
     * Get ArrayBuffer Length
     *
     * @return int
     */
    public function getLength() : int;

    /**
     * Get Buffer
     *
     * @return ArrayBufferLikeInterface
     */
    public function getBuffer() : ArrayBufferLikeInterface;

    /**
     * Returns the this object after copying a section of the array identified by start and end
     * to the same array starting at position target
     * @param int $target If target is negative, it is treated as length+target where length is the
     * length of the array.
     * @param int $start If start is negative, it is treated as length+start. If end is negative, it
     * is treated as length+end.
     * @param int $end If not specified, length of the this object is used as its default value.
     * @return ArrayBufferViewInterface
     */
    // public function copyWithin(int $target, int $start = 0, int $end = null) : ArrayBufferViewInterface;

    /**
     * Sort Order Buffer
     *
     * @param callable|null $callbackFn
     * @return ArrayBufferViewInterface
     */
    public function sort(callable $callbackFn = null) : ArrayBufferViewInterface;

    /**
     * Returns a new array from a set of elements.
     *
     * @param mixed|int ...$items
     * @return ArrayBufferViewInterface
     */
    public static function of(...$items) : ArrayBufferViewInterface;

    /**
     * Creates an array from an array-like or iterable object.
     *
     * @param iterable|ArrayLike $arrayLike
     * @param callable|null $mapFn
     * @param mixed $thisArg
     * @return ArrayBufferViewInterface
     */
    public static function from(
        iterable $arrayLike,
        callable $mapFn = null,
        $thisArg = null
    ) : ArrayBufferViewInterface;

    /**
     * Return new ArrayBufferViewInterface
     * Just like @uses ArrayBufferViewInterface::slice()
     *
     * @param int $begin The beginning of the specified portion of the array.
     * @param int $end The end of the specified portion of the array.
     * @return static|mixed
     */
    public function subArray(int $begin, int $end = null) : ArrayBufferViewInterface;

    /**
     * Returns the this object after filling the section identified by start and end with value
     * @param int $value value to fill array section with
     * @param int $start index to start filling the array at. If start is negative, it is treated as
     * length+start where length is the length of the array.
     * @param int $end index to stop filling the array at. If end is negative, it is treated as
     * length+end.
     *
     * @return ArrayBufferViewInterface
     */
    public function fill(int $value, int $start = null, int $end = null) : ArrayBufferViewInterface;

    /**
     * Get Data conversion Array
     *
     * @return array
     */
    public function toArray() : array;
}
