# URI

The URI class is used to simplify URI operations.

## Components

```PHP
URI::SCHEME;
URI::AUTHORITY;
URI::USERNAME;
URI::PASSWORD;
URI::HOST;
URI::PORT;
URI::PATH;
URI::DIRECTORY;
URI::FILE;
URI::QUERY;
URI::FRAGMENT;
```

## Example(s)

```php
// Create an URI.
$uri = new URI('http://www.example.org/directory/file?key=value#fragment');

// Get host.
$uri->getHost(); // www.example.org

// Get path.
$uri->getPath(); // /directory/file

// Get query value.
$uri->getQueryValue('key'); // value
```
