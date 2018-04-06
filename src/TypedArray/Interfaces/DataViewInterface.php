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
use ArrayIterator\TypedArray\ArrayBufferAbstract;

/**
 * Interface DataView
 * @package ArrayIterator\TypedArray\Interfaces
 *
 * @property-read ArrayBuffer $buffer
 * @property-read int $byteLength
 * @property-read int $byteOffset
 */
interface DataViewInterface extends ArrayBufferViewInterface
{
    /**
     * DataViewInterface constructor.
     * @param ArrayBufferAbstract $buffer
     * @param int|null $byteOffset
     * @param int|null $byteLength
     */
    public function __construct(
        ArrayBufferAbstract $buffer,
        int $byteOffset = null,
        int $byteLength = null
    );

    /**
     * Gets the Float32 value at the specified byte offset from the start of the view. There is
     * no alignment constraint; multi-byte values may be fetched from any offset.
     * @param int $byteOffset The place in the buffer at which the value should be retrieved.
     * @param bool $littleEndian
     * @return int
     */
    public function getFloat32(int $byteOffset, bool $littleEndian = false) : int;

    /**
     * Gets the Float64 value at the specified byte offset from the start of the view. There is
     * no alignment constraint; multi-byte values may be fetched from any offset.
     * @param int $byteOffset The place in the buffer at which the value should be retrieved.
     * @param bool $littleEndian
     * @return int
     */
    public function getFloat64(int $byteOffset, bool $littleEndian = false) : int;

    /**
     * Gets the Int8 value at the specified byte offset from the start of the view. There is
     * no alignment constraint; multi-byte values may be fetched from any offset.
     * @param int $byteOffset The place in the buffer at which the value should be retrieved.
     * @return int
     */
    public function getInt8(int $byteOffset): int;

    /**
     * Gets the Int16 value at the specified byte offset from the start of the view. There is
     * no alignment constraint; multi-byte values may be fetched from any offset.
     * @param int $byteOffset The place in the buffer at which the value should be retrieved.
     * @param bool $littleEndian
     * @return int
     */
    public function getInt16(int $byteOffset, bool $littleEndian = false) : int;
    /**
     * Gets the Int32 value at the specified byte offset from the start of the view. There is
     * no alignment constraint; multi-byte values may be fetched from any offset.
     * @param int $byteOffset The place in the buffer at which the value should be retrieved.
     * @param bool $littleEndian
     * @return int
     */
    public function getInt32(int $byteOffset, bool $littleEndian = false) : int;

    /**
     * Gets the UInt8 value at the specified byte offset from the start of the view. There is
     * no alignment constraint; multi-byte values may be fetched from any offset.
     * @param int $byteOffset The place in the buffer at which the value should be retrieved.
     * @return int
     */
    public function getUInt8(int $byteOffset) : int;

    /**
     * Gets the UInt16 value at the specified byte offset from the start of the view. There is
     * no alignment constraint; multi-byte values may be fetched from any offset.
     * @param bool $littleEndian
     * @return int
     */
    public function getUInt16(int $byteOffset, bool $littleEndian = false) : int;

    /**
     * Gets the UInt32 value at the specified byte offset from the start of the view. There is
     * no alignment constraint; multi-byte values may be fetched from any offset.
     * @param int $byteOffset The place in the buffer at which the value should be retrieved.
     * @param bool $littleEndian
     * @return int
     */
    public function getUInt32(int $byteOffset, bool $littleEndian = false) : int;

    /**
     * Stores an Float32 value at the specified byte offset from the start of the view.
     * @param int $byteOffset The place in the buffer at which the value should be set.
     * @param int $value The value to set.
     * @param bool $littleEndian If false or undefined, a big-endian value should be written,
     * otherwise a little-endian value should be written.
     * @return void
     */
    public function setFloat32(int $byteOffset, $value, bool $littleEndian = false);

    /**
     * Stores an Float64 value at the specified byte offset from the start of the view.
     * @param int $byteOffset The place in the buffer at which the value should be set.
     * @param int $value The value to set.
     * @param bool $littleEndian If false or undefined, a big-endian value should be written,
     * otherwise a little-endian value should be written.
     * @return void
     */
    public function setFloat64(int $byteOffset, $value, bool $littleEndian = false);

    /**
     * Stores an Int8 value at the specified byte offset from the start of the view.
     * @param int $byteOffset The place in the buffer at which the value should be set.
     * @param int $value The value to set.
     * @return void
     */
    public function setInt8(int $byteOffset, $value);

    /**
     * Stores an Int16 value at the specified byte offset from the start of the view.
     * @param int $byteOffset The place in the buffer at which the value should be set.
     * @param int $value The value to set.
     * @param bool $littleEndian If false or undefined, a big-endian value should be written,
     * otherwise a little-endian value should be written.
     * @return void
     */
    public function setInt16(int $byteOffset, $value, bool $littleEndian = false);

    /**
     * Stores an Int32 value at the specified byte offset from the start of the view.
     * @param int $byteOffset The place in the buffer at which the value should be set.
     * @param int $value The value to set.
     * @param bool $littleEndian If false or undefined, a big-endian value should be written,
     * otherwise a little-endian value should be written.
     * @return void
     */
    public function setInt32(int $byteOffset, $value, bool $littleEndian = false);

    /**
     * Stores an Uint8 value at the specified byte offset from the start of the view.
     * @param int $byteOffset The place in the buffer at which the value should be set.
     * @param int $value The value to set.
     * @return void
     */
    public function setUInt8(int $byteOffset, $value);

    /**
     * Stores an Uint16 value at the specified byte offset from the start of the view.
     * @param int $byteOffset The place in the buffer at which the value should be set.
     * @param int $value The value to set.
     * @param bool $littleEndian If false or undefined, a big-endian value should be written,
     * otherwise a little-endian value should be written.
     * @return void
     */
    public function setUInt16(int $byteOffset, $value, bool $littleEndian = false);

    /**
     * Stores an Uint32 value at the specified byte offset from the start of the view.
     * @param int $byteOffset The place in the buffer at which the value should be set.
     * @param int $value The value to set.
     * @param bool $littleEndian If false or undefined, a big-endian value should be written,
     * otherwise a little-endian value should be written.
     * @return void
     */
    public function setUInt32(int $byteOffset, $value, bool $littleEndian = false);
}
