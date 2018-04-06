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

namespace ArrayIterator\TypedArray\Util;

use ArrayIterator\TypedArray\ArrayLike;

/**
 * Class TypeArg
 * @package ArrayIterator\TypedArray\Util
 *
 * @see ArrayLike::map()
 * To Handle Map Binding
 */
class TypeArg
{
    /**
     * @var mixed stored argument
     */
    private $arg;

    /**
     * @var string argument type
     */
    private $type;

    /**
     * @var null|string
     */
    private $class;

    /**
     * TypeArg constructor.
     * @param mixed $arg
     */
    public function __construct($arg)
    {
        $this->arg = $arg;
        $this->type = gettype($arg);
        $this->class = $this->type === 'object'
            ? get_class($arg)
            : null;
    }

    /**
     * Return argument set on Constructor
     *
     * @return mixed
     */
    public function getArg()
    {
        return $this->arg;
    }

    /**
     * Return type of argument
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Get class name if argument constructor was an object
     *
     * @return null|string
     */
    public function getClass()
    {
        return $this->class;
    }
}
