# Example Implementation of a Hexagonal Architecture

This is a translated code from Java to PHP to understand Hexagonal Architecture. The code is originated from [Get Your Hands Dirty on Clean Architecture](https://leanpub.com/get-your-hands-dirty-on-clean-architecture). You can find the original code from [GitHub](https://github.com/thombergs/buckpal).

The code implements a domain-centric hexagonal approach with PHP, Symfony, and Doctrine.

## Getting started

```bash
$ composer install
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate
$ php bin/console doctrine:fixtures:load
$ php bin/console list buckpal
```

For testing:

```bash
$ php bin/console doctrine:database:create --env=test
$ php bin/console doctrine:migrations:migrate -n --env=test
$ php bin/console doctrine:fixtures:load -n --env=test
$ php bin/phpunit
```
