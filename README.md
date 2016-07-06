[![Build Status](https://travis-ci.org/pavlakis/php-server-interface-middleware.svg?branch=master)](https://travis-ci.org/pavlakis/php-server-interface-middleware)

# Server Interface (SAPI) Middleware

Middleware to filter based on server type interface e.g. PHP SAPI

### Add it with composer

```
composer require pavlakis/php-server-interface-middleware
```

### Pass it to a route

Use by adding it to a route. e.g. in Slim 3:

```
$app->get('/status', 'PHPMinds\Action\EventStatusAction:dispatch')
    ->add(new Pavlakis\Middleware\Server\Sapi(["cli"]))
```

We can pass an array of accepted interfaces. If those are not matched, a default response with a `403` status code will be returned.

### Pass a custom response

For a custom response pass a `Response` object. e.g.

```
        $whiteList = ["cli"];
        $sapiRes = new Response();
        $sapiRes = $sapiRes->withStatus(500);

        $sapi = new Sapi($whiteList, $sapiRes);
```




