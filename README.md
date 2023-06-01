# php-gorse

PHP SDK for Gorse recommender system.

[![CI](https://github.com/gorse-io/php-gorse/actions/workflows/ci.yml/badge.svg)](https://github.com/gorse-io/php-gorse/actions/workflows/ci.yml)
[![Packagist Version](https://img.shields.io/packagist/v/gorse/php-gorse)](https://packagist.org/packages/gorse/php-gorse)
[![Packagist Downloads](https://img.shields.io/packagist/dt/gorse/php-gorse)](https://packagist.org/packages/gorse/php-gorse)

## Install

Install via composer:

```bash
composer require gorse/php-gorse
```

## Usage

Insert feedback and get recommendations:

```php
$client = new Gorse("http://127.0.0.1:8088/", "api_key");

$rowsAffected = $client->insertFeedback([
    new Feedback("read", "10", "3", "2022-11-20T13:55:27Z"),
    new Feedback("read", "10", "4", "2022-11-20T13:55:27Z"),
]);

$client->getRecommend('10');
```
