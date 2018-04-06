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

/**
 * Interface BufferInterface
 * @package ArrayIterator\TypedArray\Interfaces
 * @todo completion
 */
interface BufferInterface extends \JsonSerializable, \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * List Encoding
     */
    const ENCODING_ASCII  = 'ascii';
    const ENCODING_BASE64 = 'base64';
    const ENCODING_HEX    = 'hex';
    const ENCODING_LATIN1 = 'latin1';
    const ENCODING_UCS2   = 'ucs-2';
    // @link http://www.ietf.org/rfc/rfc3629
    const ENCODING_UTF8   = 'utf-8';

    /**
     * Default encoding
     */
    const DEFAULT_ENCODING = self::ENCODING_UTF8;

    /**
     * BufferInterface constructor.
     * @param string|iterable|int|float $obj
     * @param string $encoding default is UTF-8 encoding
     */
    public function __construct($obj, $encoding = null);

    /**
     * Check if valid encoding
     *
     * @param string $encoding
     * @return bool
     */
    public static function isEncoding(string $encoding) : bool;

    /**
     * Create new buffer from given param
     *
     * @param iterable|string $arrayBuffer
     * @param int|string $byteOffsetOrEncoding
     * @param int|null $length
     * @return BufferInterface
     */
    public static function from($arrayBuffer, $byteOffsetOrEncoding = null, int $length = null) : BufferInterface;

    /**
     * Allocates a new Buffer of size bytes. If fill is undefined, the Buffer will be zero-filled.
     *
     * @param int $size
     * @param mixed $fill
     * @param string $encoding
     * @return BufferInterface
     */
    public static function alloc(int $size, $fill = null, $encoding = self::DEFAULT_ENCODING) : BufferInterface;

    /**
     * Allocates a new Buffer of size bytes. If the size is larger than
     * buffer.kMaxLength or smaller than 0, a RangeError will be thrown.
     * A zero-length Buffer will be created if size is 0.
     * The underlying memory for Buffer instances created in this way is not initialized.
     * The contents of the newly created Buffer are unknown and may contain sensitive data.
     * Use Buffer.alloc() instead to initialize Buffer instances to zeroes.
     *
     * @param int $size
     * @return BufferInterface
     */
    public static function allocUnsafe(int $size) : BufferInterface;


    /**
     * Get Current encoding
     *
     * @return string
     */
    public function getEncoding() : string;

    /**
     * Get ArrayBuffer
     *
     * @return ArrayBufferInterface
     */
    public function getBuffer() : ArrayBufferInterface;

    /**
     * Get Buffer into array
     *
     * @return array
     */
    public function toArray() : array;

    /**
     * Decodes and returns a string from buffer data encoded with
     * $encoding beginning at $start and ending at $end.
     *
     * @param null|string $encoding
     * @param int|null $start
     * @param int|null $end
     * @return string
     */
    public function toString($encoding = null, int $start = null, int $end = null) : string;

    /**
     * Returns a JSON-representation of the Buffer instance, which is identical to the output for JSON Arrays.
     * json_encode() implicitly calls this function when stringify a Buffer instance.
     *
     * @return string
     */
    public function toJSON() : string;

    /**
     * Returning Json of
     * {type: 'Buffer', data: [...]}
     *
     * @return array
     */
    public function jsonSerialize() : array;
}
