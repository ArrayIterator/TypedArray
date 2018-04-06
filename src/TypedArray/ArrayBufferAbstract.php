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

/**
 * Class ArrayBufferAbstract
 * @package ArrayIterator\TypedArray
 *
 * @property-read int $byteLength
 */
abstract class ArrayBufferAbstract implements ArrayBufferInterface
{
    /**
     * @var int The length in bytes of the array.
     */
    private $byteLength;

    /**
     * ArrayBufferAbstract constructor.
     * @param int $byteLength
     */
    public function __construct(int $byteLength = 0)
    {
        if ($byteLength < 0) {
            throw new \RangeException(
                sprintf(
                    'Invalid array buffer length. Byte length must be positive, given size : %d',
                    $byteLength
                )
            );
        }

        $this->byteLength = $byteLength;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset) : bool
    {
        return isset($this->$offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === 'byteLength') {
            return;
        }

        // fix offset increment
        if ($offset === null || $offset === false) {
            $arrayInstance  =  get_object_vars($this);
            $arrayInstance[] = true;
            end($arrayInstance);
            $offset = key($arrayInstance);
        }

        $this->$offset = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        if ($offset === 'byteLength') {
            return;
        }

        unset($this->$offset);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * {@inheritdoc}
     */
    public function __isset($name) : bool
    {
        return property_exists($this, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function __unset($name)
    {
        if ($name === 'byteLength') {
            return;
        }
        unset($this->$name);
    }

    /**
     * {@inheritdoc}
     */
    public function __set($name, $value)
    {
        if ($name === 'byteLength') {
            return;
        }
        $this->$name = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize() : string
    {
        return serialize(get_object_vars($this));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        set_error_handler(function ($errNo, $errString) {
            throw new \RuntimeException(
                $errString,
                $errNo
            );
        });
        $serialized = unserialize($serialized);
        restore_error_handler();
        if (!is_array($serialized)
            || !isset($serialized['byteLength'])
            || !is_int($serialized['byteLength'])
        ) {
            throw new \InvalidArgumentException(
                'Serialized string is not a valid data',
                E_WARNING
            );
        }

        foreach ($serialized as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getByteLength(): int
    {
        return $this->byteLength;
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return $this->getByteLength();
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return get_class($this) . ':' . spl_object_hash($this);
    }
}
