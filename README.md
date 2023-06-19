# BoxNow bundle

BoxNow integration for Symfony.  
Documentation of the API can be found here: https://boxnow.gr/en/partner-api

## Installation

* install with Composer

```
composer require answear/boxnow-bundle
```

`Answear\BoxNowBundle\AnswearBoxNowBundle::class => ['all' => true],`  
should be added automatically to your `config/bundles.php` file by Symfony Flex.

## Setup

* provide required config data: `environment` and `apiKey`

```yaml
# config/packages/answear_boxnow.yaml
answear_box_now:
    clientId: yourClientId
    clientSecret: yourClientSecret
    apiUrl: apiUrl #default: 'https://api-stage.boxnow.gr'
    logger: customLogger #default: null
```
Logger service must implement Psr\Log\LoggerInterface interface.


## Usage

### Authorization
```php
/** @var \Answear\BoxNowBundle\Service\AuthorizationService $authorizationService **/
$auth = $authorizationService->authorize();

$auth->getAccessToken();
$auth->getExpiresIn();
$auth->getTokenType();
```
will return `\Answear\BoxNowBundle\Response\AuthorizationResponse`.


### Pickup points
```php
/** @var \Answear\BoxNowBundle\Service\PickupPointService $pickupPoints **/
$pickupPoints->getAll(token: 'accessToken');
```
will return `\Answear\BoxNowBundle\DTO\PickupPointDTO[]`.

Final notes
------------

Feel free to open pull requests with new features, improvements or bug fixes. The Answear team will be grateful for any comments.

