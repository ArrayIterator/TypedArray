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
use ArrayIterator\TypedArray\Interfaces\BufferInterface;
use ArrayIterator\TypedArray\Util\Conversion;

/**
 * Class Buffer
 * @package ArrayIterator\TypedArray
 *
 * @property-read string $encoding
 * @property-read ArrayBuffer $buffer
 */
class Buffer implements BufferInterface
{
    /**
     * @var string[] List Valid buffer encoding
     */
    protected static $listEncoding = [
        'ascii'     => self::ENCODING_ASCII,
        'ucs-2'     => self::ENCODING_UCS2,
        'ucs2'      => self::ENCODING_UCS2, // alias ucs-2
        'utf16le'   => self::ENCODING_UCS2, // alias ucs-2
        'utf-16le'  => self::ENCODING_UCS2, // alias ucs-2
        'utf-8'     => self::ENCODING_UTF8,
        'utf8'      => self::ENCODING_UTF8, // alias utf-8
        'latin1'    => self::ENCODING_LATIN1,
        'binary'    => self::ENCODING_LATIN1, // alias latin1
        'bin'       => self::ENCODING_LATIN1, // alias latin1
        'base64'    => self::ENCODING_BASE64,
        'hex'       => self::ENCODING_HEX,
    ];

    /**
     * @var string $encoding
     */
    protected $encoding = self::DEFAULT_ENCODING;

    /**
     * @var \SplFixedArray
     */
    protected $data;

    /**
     * Buffer constructor.
     * @param string|iterable|int|float $obj
     * @param mixed|string $encoding default is UTF-8 encoding
     */
    public function __construct($obj, $encoding = null)
    {
        $this->encoding = $this->resolveEncoding($encoding);
        if (is_int($obj) || is_float($obj)) {
            $obj = intval($obj);
            if ($obj < 0) {
                throw new \RangeException(
                    'Buffer size must not be negative',
                    E_USER_WARNING
                );
            }
            $this->data = \SplFixedArray::fromArray(
                $obj === 0 ? [] : array_fill(0, $obj, '00')
            );

            return;
        }

        if (is_string($obj)) {
            $this->data = \SplFixedArray::fromArray(
                Util\Conversion::stringToHexArray($obj)
            );
            return;
        }

        if ($obj instanceof Buffer) {
            $this->data = \SplFixedArray::fromArray(
                $obj->toArray()
            );
            return;
        }

        /**
         * @var ArrayBufferView|BufferInterface $obj
         */
        $this->data = \SplFixedArray::fromArray(
            array_map(
                [Conversion::class, 'ordinalToHex'],
                (ArrayBuffer::isView($obj) ? $obj : new Uint8Array($obj))->toArray()
            )
        );
    }

    /**
     * Check Encoding and fallback to default
     *
     * @param mixed $encoding
     * @param string $defaultEncoding
     * @return string encoding if not on match from list encoding, fallback to utf8
     */
    public static function resolveEncoding(
        $encoding,
        string $defaultEncoding = self::DEFAULT_ENCODING
    ) : string {
        if (!is_string($encoding)) {
            return $defaultEncoding;
        }

        $encoding = strtolower(trim($encoding));
        return $encoding === '' || !isset(static::$listEncoding[$encoding])
            ? $defaultEncoding
            : static::$listEncoding[$encoding];
    }

    /**
     * {@inheritdoc}
     */
    public static function isEncoding(string $encoding): bool
    {
        return isset(static::$listEncoding[strtolower(trim($encoding))]);
    }

    /**
     * {@inheritdoc}
     */
    public static function from(
        $arrayBuffer,
        $byteOffsetOrEncoding = null,
        int $length = null
    ) : BufferInterface {
        if (is_int($arrayBuffer) || is_float($arrayBuffer)) {
            throw new \InvalidArgumentException(
                'Buffer value must not be a number',
                E_USER_WARNING
            );
        }
        $byteOffset = is_int($byteOffsetOrEncoding)
            ? $byteOffsetOrEncoding
            : 0;
        $encoding = !is_int($byteOffsetOrEncoding)
            ? $byteOffsetOrEncoding
            : self::DEFAULT_ENCODING;
        if (is_string($arrayBuffer)) {
            if ($length > -1) {
                $arrayBuffer = substr($arrayBuffer, $byteOffset);
            } else {
                $arrayBuffer = substr($arrayBuffer, $byteOffset, $length);
            }
            return new Buffer($arrayBuffer, $encoding);
        }

        $arrayBuffer = new Uint8Array($arrayBuffer);
        if ($byteOffset > 0) {
            $arrayBuffer = $arrayBuffer->sliceLength($byteOffset, $length);
        }

        return new Buffer($arrayBuffer, $encoding);
    }

    /**
     * {@inheritdoc}
     */
    public static function alloc(int $size, $fill = null, $encoding = self::DEFAULT_ENCODING): BufferInterface
    {
        if ($size < 0) {
            throw new \RangeException(
                'Buffer size must not be negative',
                E_USER_WARNING
            );
        }

        return new static(array_fill(0, $size, (!$fill ? 0x0 : $fill)), $encoding);
    }

    /**
     * {@inheritdoc}
     */
    public static function allocUnsafe(int $size) : BufferInterface
    {
        return new Buffer($size);
    }

    /**
     * {@inheritdoc}
     */
    public function getEncoding(): string
    {
        return $this->encoding;
    }

    /**
     * @return ArrayBufferInterface
     */
    public function getBuffer() : ArrayBufferInterface
    {
        return new ArrayBuffer($this->data->count());
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return $this->data->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function toString($encoding = null, int $start = null, int $end = null) : string
    {
        $max     = $this->data->count();
        $start = $start === null || $start < 0 ? 0 : $start;
        if ($start > $max) {
            return '';
        }
        $end    = $end === null || $end > $max ? $max : $end;
        $length = $end - $start;
        if ($max < 1 || $length < 1) {
            return '';
        }

        $encoding = $this->resolveEncoding($encoding, $this->encoding);
        $data = $start > 0
            ? array_slice($this->toArray(), $start, $length)
            : $this->toArray();

        if (count($data) === 0) {
            return '';
        }
        $data = implode($data);
        if ($encoding === self::ENCODING_HEX) {
            return $data;
        }
        $data = pack('H*', $data);
        if ($this->encoding === $encoding) {
            return $data;
        }

        switch ($encoding) {
            case self::ENCODING_LATIN1:
                return $data;
            case self::ENCODING_BASE64:
                return base64_encode($data);
            case self::ENCODING_ASCII:
            case self::ENCODING_UTF8:
            case self::ENCODING_UCS2:
                return iconv($this->encoding, $encoding, $data);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function toJSON() : string
    {
        return json_encode($this, JSON_UNESCAPED_SLASHES);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : array
    {
        $className = explode('\\', get_class($this));
        return [
            'type' => end($className),
            'data' => $this->toArray()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function count() : int
    {
        return $this->data->count();
    }

    /**
     * @return \Traversable|\ArrayIterator
     */
    public function getIterator() : \Traversable
    {
        return clone $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset) : bool
    {
        return $this->data->offsetExists($offset);
    }

    /**
     * {@inheritdoc}
     * @return int ordinal value
     */
    public function offsetGet($offset)
    {
        $data = isset($this->data[$offset])
            ? $this->data[$offset]
            : null;
        return $data !== null ? hexdec($data) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if (!is_numeric($offset)
            || $offset < 0
            || !is_int(abs($offset))
            || abs($offset) >= count($this->data)
        ) {
            return;
        }

        $offset = abs($offset);
        $value = is_numeric($value)
            ? abs(intval($value) % pow(0x100, Uint8Array::BYTES_PER_ELEMENT))
            : 0x0;
        $this->data->offsetSet($offset, Util\Conversion::ordinalToHex($value));
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        // pass can not delete buffer keys
    }

    /**
     * Magic method get
     *
     * @param string $name
     * @return null|array|string|ArrayBuffer|int|mixed
     */
    public function __get(string $name)
    {
        switch ($name) {
            case 'length':
                return count($this);
            case 'buffer':
                return $this->getBuffer();
            case 'encoding':
                return $this->getEncoding();
            case 'listEncoding':
                return static::$listEncoding;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString() : string
    {
        return $this->toString($this->getEncoding());
    }

    /**
     * Magic method destruct
     */
    public function __destruct()
    {
        // reset
        $this->data = new \SplFixedArray();
    }
}
