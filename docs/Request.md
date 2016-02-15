# Request

The request class is used to simplify http request operations.

## Example(s)

```php
// Create a new request.
$request = new Request('GET', new URI('http://www.example.org/'));

// Get the request target.
$request->getRequestTarget();

// Get the method.
$request->getMethod();

// Get the URI.
$request->getUri();
```
