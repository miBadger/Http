# ServerRequest

The server request class is used to simplify http server request operations.

## Example(s)

```php
// Create a new server request.
$serverRequest = new ServerRequest();

// Get the server parameters.
$serverRequest->getServerParams();

// Get the cookie parameters.
$serverRequest->getCookieParams();

// Get the query parameters.
$serverRequest->getQueryParams();

// Get the uploaded files.
$serverRequest->getUploadedFiles();

// Get the parsed body.
$serverRequest->getParsedBody();
```
