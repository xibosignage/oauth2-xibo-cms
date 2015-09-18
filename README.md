# Xibo CMS Provider for league/oauth2-client
This is a package to integrate Xibo CMS authentication with the OAuth2 client library by 
[The League of Extraordinary Packages](https://github.com/thephpleague/oauth2-client).

To install, use composer:

```bash
composer require xibosignage/oauth2-xibo-cms
```

Usage is the same as the league's OAuth client, using `\Xibo\OAuth2\Client\Provider\XiboCms` as the provider.
For example:

```php
$provider = new \Xibo\OAuth2\Client\Provider\Xibo([
    'sandbox' => "TRUE_OR_FALSE",
    'clientId' => "YOUR_CLIENT_ID",
    'clientSecret' => "YOUR_CLIENT_SECRET",
    'responseType' => "JSON_OR_STRING"
    'redirectUri' => "http://your-redirect-uri"
]);

$token = $provider->getAccessToken('refresh_token', [
    'grant_type' => 'refresh_token',
    'refresh_token' => "REFRESH_TOKEN"
]);

// OR (to get the token)

$token = $this->provider->getAccessToken("authorizaton_code", [
    'code' => $_GET['code']
]);

// pass the token to the headers
$provider->headers = ['Authorization' => 'Bearer ' . $token];

```

## License

This provider is under the MIT license. See the complete license in the provider:

[LICENSE](https://github.com/xibosignage/oauth2-xibo-cms/blob/master/LICENSE)

## Reporting an issue or a feature request

Issues and feature requests are tracked in the [GitHub issue tracker](https://github.com/xibosignage/xibo).