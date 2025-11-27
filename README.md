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

Create a Gorse client:

```php
$client = new Gorse("http://127.0.0.1:8088/", "api_key");
```

Insert users:

```php
$user = new User("100", ["gender" => "M", "age" => "25"], "my_comment");
$rowsAffected = $client->insertUser($user);
```

Insert items:

```php
$item = new Item(
    "2000", 
    true, 
    ["embedding" => [0.1, 0.2, 0.3]], 
    ["Comedy", "Animation"], 
    "2022-11-20T13:55:27Z", 
    "Minions (2015)"
);
$rowsAffected = $client->insertItem($item);
```

Insert feedback:

```php
$feedback = [
    new Feedback("read", "100", "2000", 1.0, "2022-11-20T13:55:27Z"),
    new Feedback("read", "100", "2001", 1.0, "2022-11-20T13:55:27Z"),
];
$rowsAffected = $client->insertFeedback($feedback);
```

Get recommendations:

```php
$items = $client->getRecommend('100', 10);
```
