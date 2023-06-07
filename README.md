# BoxNow bundle

BoxNow integration for Symfony.  
Documentation of the API can be found here: https://boxnow.gr/en/partner-api

## Installation

* install with Composer

```
composer require git@github.com:answear/boxnow-bundle.git
```

`Answear\BoxNowBundle\AnswearBoxNowBundle::class => ['all' => true],`  
should be added automatically to your `config/bundles.php` file by Symfony Flex.

## Setup

* provide required config data: `environment` and `apiKey`

```yaml
# config/packages/answear_boxnow.yaml
answear_boxnow:
    environment: test|prod
    apiKey: yourApiKey
    logger: yourCustomLoggerService #default: null
```

## Usage

### TODO

Final notes
------------

Feel free to open pull requests with new features, improvements or bug fixes. The Answear team will be grateful for any comments.

