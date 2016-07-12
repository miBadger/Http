# UploadedFile

The uploaded file class.

## Example(s)

```php
<?php

use miBadger\Http\UploadedFile;

/**
 * Retrieve a stream representing the uploaded file.
 */
$uploadedFile->getStream();

/**
 * Move the uploaded file to a new location.
 */
$uploadedFile->moveTo($targetPath);

/**
 * Retrieve the file size.
 */
$uploadedFile->getSize();

/**
 * Retrieve the error associated with the uploaded file.
 */
$uploadedFile->getError();

/**
 * Retrieve the filename sent by the client.
 */
$uploadedFile->getClientFilename();

/**
 * Retrieve the media type sent by the client.
 */
$uploadedFile->getClientMediaType();
```
