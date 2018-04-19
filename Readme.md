# Basic API key middleware for Laravel

This is a simple key-based middleware for Laravel.  It suited our common use-case of internal apps which need access to other internal apps (machine-to-machine) without the hassles of oauth etc.

## Installation

You should be able to pull it in using composer :

```
composer require uogsoe/basic-api-token-middleware
```

Then you have to publish the database migration and ApiKey model :
```
php artisan vendor:publish
```
And pick `UoGSoE\ApiTokenMiddleware\ApiTokenServiceProvider` from the list.  Then run the migration :
```
php artisan migrate
```

## Usage

First of all you create a token for the consuming 'service' (eg, the remote client) :
```
php artisan apitoken:create testservice
```
That will create the token and show it to you.  You need to take note of the token as your client will have to use it to access the routes.

Now in your `routes/api.php` file you can use the middleware to wrap endpoints :
```
Route::group(['middleware' => 'apitoken:testservice'], function () {
    Route::get('/hello', function () {
        return 'hello';
    });
});
```

If you try and access that route without passing the token you will get a 401 response :
```
curl -kv https://my-project.test/api/hello
...
HTTP/2 401
{"message":"Unauthorized"}
```
So pass the token you created above and it should let you through :
```
curl -kv https://my-project.test/api/hello?api_token=jT7ryt28gi3YCvgE4WvluO1uVcb0ndVx
...
HTTP/2 200
hello
```

You can pass the token in various ways, like a GET param as above, a bearer token header or as part of the JSON body.  Eg:
```
$this->withHeaders([
    'Authorization' => 'Bearer '.$tokenString,
])->get('https://my-project.test/api/hello');

$this->json('POST', 'https://my-project.test/api/hello', ['api_token' => $token]);

$this->call('POST', 'https://my-project.test/api/hello', ['api_token' => $token]);
```

There are a few other artisan commands available to help manage the tokens :
```
php artisan apitoken:list -- lists all current tokens
php artisan apitoken:regenerate -- create a new token for a given service
php artisan apitoken:delete -- deletes a given service token
```
