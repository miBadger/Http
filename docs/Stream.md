# Stream

The stream class is used to simplify http stream operations.

## Example(s)

```php
// Create a new server request.
$serverRequest = new ServerRequest();

// Get stream
$stream = $serverRequest->getBody();

// Get size
$stream->getSize();

// Get contents
$stream->getContents();
```
