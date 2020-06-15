# Http

[![Build Status](https://scrutinizer-ci.com/g/miBadger/miBadger.Http/badges/build.png?b=master)](https://scrutinizer-ci.com/g/miBadger/miBadger.Http/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/miBadger/miBadger.Http/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/miBadger/miBadger.Http/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/miBadger/miBadger.Http/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/miBadger/miBadger.Http/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/96ddbe84-d8eb-4b6c-954a-7bd2d90d0c1c/mini.png)](https://insight.sensiolabs.com/projects/96ddbe84-d8eb-4b6c-954a-7bd2d90d0c1c)

The HTTP Component.

## PSR-7

This package implements the [PSR-7 HTTP message interfaces](http://www.php-fig.org/psr/psr-7/) so you can easily swap this package with other PSR-7 compatible package. Check the [official PHP Framework Interop Group website](http://www.php-fig.org) for information about the [PSR recommendations](http://www.php-fig.org/psr/).

## Changelog
### 4.0.0
- php requirement changed to 7.3. 
- version 3.0.x can be considered broken when using php < 7.3, and it is advised to update immediately.

### 3.0.0
- Adding utf-8 support
- getParsedBody now always returns a nested array structure
