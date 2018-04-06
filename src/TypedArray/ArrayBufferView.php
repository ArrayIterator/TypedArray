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
use ArrayIterator\TypedArray\Interfaces\ArrayBufferViewInterface;
use ArrayIterator\TypedArray\Traits\FloatArrayTrait;
use ArrayIterator\TypedArray\Traits\UintArrayTrait;
use ArrayIterator\TypedArray\Util\TypeArg;

/**
 * Class ArrayBufferView
 * @package ArrayIterator\TypedArray
 *
 * @property-read int $length
 * @property-read int $byteLength
 * @property-read int $byteOffset
 * @property-read ArrayBufferLikeInterface $buffer
 */
abstract class ArrayBufferView extends ArrayBufferLike implements ArrayBufferViewInterface
{
    /**
     * @var int
     */
    const BYTES_PER_ELEMENT = 0x1;

    /**
     * Bits unit
     * @var int
     */
    const BITS_UNIT = 0x100;

    /**
     * @var int The length in of the array count
     */
    protected $length = 0;

    /**
     * @var int The length in bytes of the array.
     */
    protected $byteLength = 0;

    /**
     * The ArrayBuffer instance referenced by the array.
     * @var ArrayBufferLikeInterface
     */
    protected $buffer;

    /**
     * @var int The offset in bytes of the array.
     */
    protected $byteOffset = 0;

    /**
     * @var int
     */
    protected $bytesElement = 0x100;

    /** @noinspection PhpMissingParentConstructorInspection */

    /**
     * ArrayBufferLike constructor.
     * @param null $arrayOrBuffer
     */
    public function __construct($arrayOrBuffer = null)
    {
        // reset byte length
        $this->byteLength = 0x0;
        $this->length = 0x0;
        // Calculate Total Bytes Element
        // pow(static::BITS_UNIT, static::BYTES_PER_ELEMENT);
        $this->bytesElement = static::BITS_UNIT ** static::BYTES_PER_ELEMENT;
        // if array buffer is null create empty ArrayBufer
        if ($arrayOrBuffer === null) {
            $this->buffer = new ArrayBuffer();
            return;
        }

        if (is_string($arrayOrBuffer) && is_numeric(trim($arrayOrBuffer))) {
            $arrayOrBuffer = intval(trim($arrayOrBuffer));
        }

        if (is_int($arrayOrBuffer)) {
            if ($arrayOrBuffer < 0) {
                throw new \RangeException(
                    sprintf(
                        'Byte length must not to be negative : %d',
                        $arrayOrBuffer
                    )
                );
            }

            $this->byteLength = $arrayOrBuffer;
            $this->length = $arrayOrBuffer;
            $this->buffer = new ArrayBuffer($arrayOrBuffer);
            parent::exchangeArray($this->createArrayFill($arrayOrBuffer));
            return;
        }

        if (is_iterable($arrayOrBuffer) && count($arrayOrBuffer) > 0) {
            $c = 0;
            $this->byteLength = count($arrayOrBuffer);
            foreach ($arrayOrBuffer as $key => $value) {
                if (!is_int($key) || $key < 0) {
                    continue;
                }

                $this[$c++] = $value;
            }

            unset($arrayOrBuffer);
            $this->byteLength = $c;
        }

        $this->length = $this->byteLength;
        $this->buffer = new ArrayBuffer($this->byteLength);
    }

    /**
     * {@inheritdoc}
     *
    public function copyWithin(int $target, int $start = 0, int $end = null) : ArrayBufferViewInterface
    {
        $length = $this->getLength();

        if ($target < 0) {
            $target = $target + $length;
        }
        if ($start < 0) {
            $start = $target + $length;
        }

        return $this->slice($start, $end);
    }*/

    /**
     * @param mixed $value if not as numeric return 0x0 = 0
     *  or NAN if it was float / double array
     *
     * @see UintArrayTrait  for override Uint(8|16|32)Array
     * @see FloatArrayTrait for override Float(32|64)Array
     *
     * @return int|float
     */
    abstract protected function dataBufferFor($value);

    /**
     * Create Array Fill
     *
     * @param int $count
     * @return array|int[]|float[]
     */
    protected function createArrayFill(int $count) : array
    {
        return array_fill(0, $count, 0x0);
    }

    /**
     * @return int
     */
    public function getByteOffset() : int
    {
        return $this->byteOffset;
    }

    /**
     * @return ArrayBufferLikeInterface
     */
    public function getBuffer() : ArrayBufferLikeInterface
    {
        return $this->buffer;
    }

    /**
     * {@inheritdoc}
     */
    public function asort()
    {
        parent::asort();
        $this->exchangeArray($this->getArrayCopy());
    }

    /**
     * {@inheritdoc}
     */
    public function ksort()
    {
        parent::ksort();
        $this->exchangeArray($this->getArrayCopy());
    }

    /**
     * {@inheritdoc}
     */
    public function natsort()
    {
        parent::natsort();
        $this->exchangeArray($this->getArrayCopy());
    }

    /**
     * {@inheritdoc}
     */
    public function natcasesort()
    {
        parent::natcasesort();
        $this->exchangeArray($this->getArrayCopy());
    }

    /**
     * {@inheritdoc}
     */
    public function uksort($cmp_function)
    {
        parent::uksort($cmp_function);
        $this->exchangeArray($this->getArrayCopy());
    }

    /**
     * {@inheritdoc}
     */
    public function uasort($cmp_function)
    {
        parent::uasort($cmp_function);
        $this->exchangeArray($this->getArrayCopy());
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if (is_numeric($offset)) {
            if ($offset < 0x0
                || ! is_int(($offset = abs($offset)))
                || abs($offset) >= $this->byteLength
            ) {
                return;
            }

            $value = $this->dataBufferFor($value);
        }

        parent::offsetSet($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        if (is_numeric($offset)
            && is_int(abs($offset))
            && $offset > -1 && abs($offset) < $this->byteLength
        ) {
            return;
        }

        parent::offsetUnset($offset);
    }

    /**
     * @return int get count of byte length
     */
    public function count() : int
    {
        return $this->length;
    }

    /**
     * {@inheritdoc}
     */
    public function exchangeArray($input)
    {
        $copy = $this->getArrayCopy();
        if (is_iterable($input)) {
            parent::exchangeArray($this->getBytesLengthArray());
            foreach ($input as $key => $value) {
                $this[$key] = $value;
            }
            unset($input);
        }

        return $copy;
    }

    /**
     * {@inheritdoc}
     * pass append
     */
    public function append($value)
    {
        // pass
    }

    /**
     * {@inheritdoc}
     */
    public static function of(...$items) : ArrayBufferViewInterface
    {
        return new static($items);
    }

    /**
     * {@inheritdoc}
     */
    public static function from(
        iterable $arrayLike,
        callable $mapFn = null,
        $thisArg = null
    ) : ArrayBufferViewInterface {
        $obj = new static($arrayLike);
        if ($mapFn) {
            $obj->map($mapFn, $thisArg);
        }
        return $obj;
    }

    /**
     * @param callable $callback
     * @param mixed $thisArgs
     * @return ArrayLike
     */
    public function map(callable $callback, $thisArgs = null) : ArrayLike
    {
        $object = clone $this;
        if ($callback instanceof \Closure) {
            $thisArgs = is_object($thisArgs) && ! $this instanceof \Closure
                ? $thisArgs
                : new TypeArg($thisArgs);
        } else {
            $thisArgs = $object;
        }

        foreach ($this->getBytesLengthArray() as $key => $value) {
            $callback($value, $key, $thisArgs);
        }

        return $object;
    }

    /**
     * Slice array like an array_slice uses
     *
     * @param int|null $start
     * @param int|null $length
     * @return ArrayBufferViewInterface
     */
    public function sliceLength(int $start = null, int $length = null) : ArrayBufferViewInterface
    {
        return parent::slice($start, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function subArray(int $begin, int $end = null): ArrayBufferViewInterface
    {
        return clone $this->slice($begin, $end);
    }

    /**
     * @param callable $callbackFn
     * @return ArrayBufferViewInterface
     */
    public function sort(callable $callbackFn = null): ArrayBufferViewInterface
    {
        $arr = $this->getArrayCopy();
        if ($callbackFn) {
            uasort($arr, $callbackFn);
        } else {
            sort($arr);
        }

        $this->exchangeArray($arr);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function fill(int $value, int $start = null, int $end = null) : ArrayBufferViewInterface
    {
        $max = $this->getByteLength();
        if ($max === 0 || $end < $start) {
            return $this;
        }

        $start = $start < 0 ? $max + $start : $start;
        $end   = $end < 0 ? $max + $end : $end;

        return array_fill($start, $end, $value);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return implode(',', $this->getBytesLengthArray());
    }
}
