# URI

The URI class is used to simplify URI operations.

## Example(s)

```PHP
<?php

use miBadger\Http\URI;

/**
 * The URI components.
 */
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

```php
<?php

use miBadger\Http\URI;

/**
 * Return the string representation as a URI reference.
 */
$uri->__toString();

/**
 * Retrieve the scheme component of the URI.
 */
$uri->getScheme();

/**
 * Retrieve the authority component of the URI.
 */
$uri->getAuthority();

/**
 * Retrieve the user information component of the URI.
 */
$uri->getUserInfo();

/**
 * Retrieve the host component of the URI.
 */
$uri->getHost();

/**
 * Retrieve the port component of the URI.
 */
$uri->getPort();

/**
 * Retrieve the path component of the URI.
 */
$uri->getPath();

/**
 * Retrieve the query string of the URI.
 */
$uri->getQuery();

/**
 * Retrieve the fragment component of the URI.
 */
$uri->getFragment();

/**
 * Return an instance with the specified scheme.
 */
$uri->withScheme($scheme);

/**
 * Return an instance with the specified user information.
 */
$uri->withUserInfo($user, $password = null);

/**
 * Return an instance with the specified host.
 */
$uri->withHost($host);

/**
 * Return an instance with the specified port.
 */
$uri->withPort($port);

/**
 * Return an instance with the specified path.
 */
$uri->withPath($path);

/**
 * Return an instance with the specified query string.
 */
$uri->withQuery($query);

/**
 * Return an instance with the specified URI fragment.
 */
$uri->withFragment($fragment);
```
