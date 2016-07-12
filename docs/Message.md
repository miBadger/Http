# Message

The message class.

## Example(s)

```php
<?php

use miBadger\Http\Message;

/**
 * Retrieves the HTTP protocol version as a string.
 */
$message->getProtocolVersion();

/**
 * Return an instance with the specified HTTP protocol version.
 */
$message->withProtocolVersion($version);

/**
 * Retrieves all message header values.
 */
$message->getHeaders();

/**
 * Checks if a header exists by the given case-insensitive name.
 */
$message->hasHeader($name);

/**
 * Retrieves a message header value by the given case-insensitive name.
 */
$message->getHeader($name);

/**
 * Retrieves a comma-separated string of the values for a single header.
 */
$message->getHeaderLine($name);

/**
 * Return an instance with the provided value replacing the specified header.
 */
$message->withHeader($name, $value);

/**
 * Return an instance with the specified header appended with the given value.
 */
$message->withAddedHeader($name, $value);

/**
 * Return an instance without the specified header.
 */
$message->withoutHeader($name);

/**
 * Gets the body of the message.
 */
$message->getBody();

/**
 * Return an instance with the specified message body.
 */
$message->withBody(StreamInterface $body);
```
