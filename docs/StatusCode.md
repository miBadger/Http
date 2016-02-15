# StatusCode

The status code class can be used to validate that http status codes are RFC 7230 compatible.

## Example(s)

```php
$statusCode = new StatusCode(StatusCode::SUCCESFULL_OK);

try {
	$statusCode = StatusCode::valueOf(404);
} catch (UnexpectedValueException $e) {
	// Invalid status code
}
```
