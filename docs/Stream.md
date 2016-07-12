# Stream

The stream class.

## Example(s)

```php
<?php

use miBadger\Http\Stream;

/**
 * Reads all data from the stream into a string, from the beginning to end.
 */
$stream->__toString();

/**
 * Closes the stream and any underlying resources.
 */
$stream->close();

/**
 * Separates any underlying resources from the stream.
 */
$stream->detach();

/**
 * Get the size of the stream if known.
 */
$stream->getSize();

/**
 * Returns the current position of the file read/write pointer
 */
$stream->tell();

/**
 * Returns true if the stream is at the end of the stream.
 */
$stream->eof();

/**
 * Returns whether or not the stream is seekable.
 */
$stream->isSeekable();

/**
 * Seek to a position in the stream.
 */
$stream->seek($offset, $whence = SEEK_SET);

/**
 * Seek to the beginning of the stream.
 */
$stream->rewind();

/**
 * Returns whether or not the stream is writable.
 */
$stream->isWritable();

/**
 * Write data to the stream.
 */
$stream->write($string);

/**
 * Returns whether or not the stream is readable.
 */
$stream->isReadable();

/**
 * Read data from the stream.
 */
$stream->read($length);

/**
 * Returns the remaining contents in a string
 */
$stream->getContents();

/**
 * Get stream metadata as an associative array or retrieve a specific key.
 */
$stream->getMetadata($key = null);
```
