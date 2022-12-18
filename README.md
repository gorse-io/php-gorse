# php-gorse

PHP SDK for Gorse recommender system.

[![CI](https://github.com/gorse-io/php-gorse/actions/workflows/ci.yml/badge.svg)](https://github.com/gorse-io/php-gorse/actions/workflows/ci.yml)

```php
$client = new Gorse("http://127.0.0.1:8088/", "api_key");

$rowsAffected = $client->insertFeedback([
    new Feedback("read", "10", "3", "2022-11-20T13:55:27Z"),
    new Feedback("read", "10", "4", "2022-11-20T13:55:27Z"),
]);

$client->getRecommend('10');
```
