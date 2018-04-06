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

use ArrayIterator\TypedArray\Util\TypeArg;

/**
 * Class ArrayLike
 * @package ArrayIterator\TypedArray
 *
 * @property-read int $length
 */
abstract class ArrayLike extends \ArrayObject
{
    /**
     * ArrayLike constructor.
     * @param array $input
     */
    public function __construct(array $input = [])
    {
        parent::__construct($input);
    }

    /**
     * {@inheritdoc}
     */
    public function setFlags($flags)
    {
        // pass
    }

    /**
     * @param mixed $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($name === 'length') {
            return count($this);
        }

        return $this->$name;
    }

    /**
     * @return int
     */
    public function getLength() : int
    {
        return count($this);
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return $this->getArrayCopy();
    }

    /**
     * @param callable $callback
     * @param null $thisArgs
     */
    public function forEach(callable $callback, $thisArgs = null)
    {
        $this->map($callback, $thisArgs);
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
            $object = is_object($thisArgs) && ! $this instanceof \Closure
                ? $thisArgs
                : new TypeArg($thisArgs);
            $callback = $callback->bindTo($object);
        }

        foreach ($this as $key => $value) {
            $callback($value, $key, $object);
        }

        return $object;
    }

    /**
     * @param callable $callback
     * @param null $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->getArrayCopy(), $callback, $initial);
    }

    /**
     * @return ArrayLike
     */
    public function reverse() : ArrayLike
    {
        $obj = clone $this;
        $obj->exchangeArray(array_reverse($this->getArrayCopy()));
        return $obj;
    }

    /**
     * Returns a section of an array.
     *
     * @param int $begin The beginning of the specified portion of the array.
     * @param int $length The end of the specified portion of the array.
     * @return static|mixed
     */
    public function slice(int $begin = null, int $length = null)
    {
        $obj  = clone $this;
        if ($begin < 1 || $begin === null) {
            if ($length === null) {
                return $obj->exchangeArray($obj->getArrayCopy());
            }

            $begin = 0;
        }

        $max    = count($this) - 1;
        if ($max < $begin) {
            return new static();
        }

        if ($length !== null) {
            $max = ($max - $begin);
            $length = ($max - $length) < 0 ? $max : $length;
        }

        if ($length === 0) {
            return new static();
        }

        $copy = array_slice($this->getArrayCopy(), $begin, $length);
        $obj->exchangeArray($copy);
        return $obj;
    }

    /**
     * @param mixed $value
     * @return int|string returning -1 if not exists
     */
    public function indexOf($value)
    {
        $search = array_search($this->toArray(), $value, true);
        return $search === false ? -1 : $search;
    }

    /**
     * @param mixed $v
     * @return string
     */
    protected function joinCommas($v) : string
    {
        if (!is_iterable($v)) {
            $value = ',';
            if (is_object($v)) {
                $value .= method_exists($v, '__toString')
                    ? (string) $v
                    : (
                        $v instanceof \JsonSerializable
                        ? json_encode($v)
                        : '{}'
                    );
            } elseif ($v === true) {
                $value .= 'true';
            } else {
                $value .= $value;
            }

            return $value;
        }

        $value = '';
        foreach ($v as $val) {
            $value .= $this->joinCommas($val);
        }

        return $value;
    }

    /**
     * @param string $separator
     */
    public function join(string $separator = '')
    {
        $data = '';
        foreach ($this as $value) {
            if (is_iterable($value)) {
                $counted = 0;
                foreach ($value as $key => $v) {
                    if ($counted++ > 0) {
                        if (is_iterable($v)) {
                            $data .= $this->joinCommas($v);
                            continue;
                        }
                        $data .= $separator . (string) $v;
                    }
                }
                continue;
            }

            $data .= $separator . $value;
        }
    }
}
