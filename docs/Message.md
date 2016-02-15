# Message

The message class is used to simplify http message operations.

## Example(s)

```php
// Create a new message. (Will always be a Request or Response in practise)
$message = new Message('', ['Host' => ['www.example.org']]);

// Get the protocol version.
$message->getProtocolVersion();

// Get the headers.
$message->getHeaders();

// Get the body.
$message->getBody();
```
