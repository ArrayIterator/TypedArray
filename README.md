# Typed Arrays

PHP Implementation of [JavaScript Typed Arrays](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Typed_arrays)

Use return value like a javascript object.

## OBJECT

### CLASS

- [ArrayBuffer](src/TypedArray/ArrayBuffer.php)
- [Uint8Array](src/TypedArray/Uint8Array.php)
- [Uint16Array](src/TypedArray/Uint16Array.php)
- [Uint32Array](src/TypedArray/Uint32Array.php)
- [Float32Array](src/TypedArray/Float32Array.php)
- [Float64Array](src/TypedArray/Float64Array.php)
- [Int8Array](src/TypedArray/Int8Array.php)
- [Int16Array](src/TypedArray/Int16Array.php)
- [Int32Array](src/TypedArray/Int32Array.php)


### MOCK ABSTRACT

- [ArrayLike](src/TypedArray/ArrayLike.php)
- [ArrayBufferAbstract](src/TypedArray/ArrayBufferAbstract.php)
- [ArrayBufferLike](src/TypedArray/ArrayBufferLike.php)
- [ArrayBufferView](src/TypedArray/ArrayBufferView.php)


### INTERFACE

- [ArrayBufferConstructorInterface](src/TypedArray/Interfaces/ArrayBufferConstructorInterface.php)
- [ArrayBufferInterface](src/TypedArray/Interfaces/ArrayBufferInterface.php)
- [ArrayBufferLikeInterface](src/TypedArray/Interfaces/ArrayBufferLikeInterface.php)
- [ArrayBufferViewInterface](src/TypedArray/Interfaces/ArrayBufferViewInterface.php)
- [BufferInterface](src/TypedArray/Interfaces/BufferInterface.php)
- [DataViewInterface](src/TypedArray/Interfaces/DataViewInterface.php)



## TODO

### NEXT

- to add `UInt8ClampedArray`
- to add `DataView` object class implements of [DataViewInterface](src/TypedArray/Interfaces/DataViewInterface.php)


### PROGRESS

- [Buffer](src/TypedArray/Buffer.php) `add more methods`
- [ArrayBufferViewInterface](src/TypedArray/Interfaces/ArrayBufferViewInterface.php) `add more methods`


### INTERFACE

- [DataViewInterface](src/TypedArray/Interfaces/Uint8ClampedArrayInterface.php)




## NOTE

Float Precision decimal point set by `ini_set('precision', (number))` or set default on `php.ini`



## LICENSE

[MIT LICENSE](LICENSE)

