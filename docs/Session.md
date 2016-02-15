# Session

The session class is used to simplify http session operations.

## Example(s)

```php
// Start session.
Session::start();

// Save value.
Session::set('key', 'value');

// Get value.
Session::get('key');

// Destroy session.
Session::destroy();
```
