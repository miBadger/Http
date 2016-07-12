# ServerRequest

The server request class.

## Example(s)

```php
<?php

use miBadger\Http\ServerRequest;

/**
 * Retrieve server parameters.
 */
$serverRequest->getServerParams();

/**
 * Retrieve cookies.
 */
$serverRequest->getCookieParams();

/**
 * Return an instance with the specified cookies.
 */
$serverRequest->withCookieParams(array $cookies);

/**
 * Retrieve query string arguments.
 */
$serverRequest->getQueryParams();

/**
 * Return an instance with the specified query string arguments.
 */
$serverRequest->withQueryParams(array $query);

/**
 * Retrieve normalized file upload data.
 */
$serverRequest->getUploadedFiles();

/**
 * Create a new instance with the specified uploaded files.
 */
$serverRequest->withUploadedFiles(array $uploadedFiles);

/**
 * Retrieve any parameters provided in the request body.
 */
$serverRequest->getParsedBody();

/**
 * Return an instance with the specified body parameters.
 */
$serverRequest->withParsedBody($data);

/**
 * Retrieve attributes derived from the request.
 */
$serverRequest->getAttributes();

/**
 * Retrieve a single derived request attribute.
 */
$serverRequest->getAttribute($name, $default = null);

/**
 * Return an instance with the specified derived request attribute.
 */
$serverRequest->withAttribute($name, $value);

/**
 * Return an instance that removes the specified derived request attribute.
 */
$serverRequest->withoutAttribute($name);
```
