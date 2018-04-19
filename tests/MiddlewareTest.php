<?php

namespace Tests;

class MiddlewareTest extends TestCase
{
    /** @test */
    public function using_an_invalid_token_returns_unauthorised()
    {
        $token = \App\ApiToken::createNew('test');
        \Route::middleware('apitoken:test')->any('/_test/', function () {
            return 'OK';
        });

        $response = $this->call('GET', '_test', ['api_token' => 'invalidtoken']);

        $response->assertStatus(401);
    }

    /** @test */
    public function using_no_token_returns_unauthorised()
    {
        $token = \App\ApiToken::createNew('test');
        \Route::middleware('apitoken:test')->any('/_test/', function () {
            return 'OK';
        });

        $response = $this->call('GET', '_test');

        $response->assertStatus(401);
    }

    /** @test */
    public function using_a_valid_token_as_a_url_param_returns_ok()
    {
        $token = \App\ApiToken::createNew('test');
        \Route::middleware('apitoken:test')->any('/_test/', function () {
            return 'OK';
        });

        $response = $this->call('GET', '_test', ['api_token' => $token]);

        $response->assertStatus(200);
    }

    /** @test */
    public function using_a_valid_token_as_a_json_field_returns_ok()
    {
        $token = \App\ApiToken::createNew('test');
        \Route::middleware('apitoken:test')->any('/_test/', function () {
            return 'OK';
        });
        $response = $this->json('GET', '_test', ['api_token' => $token]);

        $response->assertStatus(200);
    }

    /** @test */
    public function using_a_valid_token_as_a_form_field_returns_ok()
    {
        $token = \App\ApiToken::createNew('test');
        \Route::middleware('apitoken:test')->any('/_test/', function () {
            return 'OK';
        });
        $response = $this->call('POST', '_test', ['api_token' => $token]);

        $response->assertStatus(200);
    }

    /** @test */
    public function using_a_valid_token_as_a_bearer_token_returns_ok()
    {
        $token = \App\ApiToken::createNew('test');
        \Route::middleware('apitoken:test')->any('/_test/', function () {
            return 'OK';
        });

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])->get('_test');

        $response->assertStatus(200);
    }

    /** @test */
    public function we_can_use_multiple_api_service_tokens()
    {
        $token1 = \App\ApiToken::createNew('test1');
        $token2 = \App\ApiToken::createNew('test2');
        $token3 = \App\ApiToken::createNew('test3');
        \Route::middleware('apitoken:test1,test2')->any('/_test/', function () {
            return 'OK';
        });

        $response = $this->call('GET', '_test', ['api_token' => $token1]);
        $response->assertStatus(200);

        $response = $this->call('GET', '_test', ['api_token' => $token2]);
        $response->assertStatus(200);

        $response = $this->call('GET', '_test', ['api_token' => $token3]);
        $response->assertStatus(401);
    }

    /** @test */
    public function using_a_non_existant_service_name_always_returns_unauthorised()
    {
        $token = \App\ApiToken::createNew('test');
        \Route::middleware('apitoken:nottest')->any('/_test/', function () {
            return 'OK';
        });

        $response = $this->call('GET', '_test', ['api_token' => $token]);

        $response->assertStatus(401);
    }

    /** @test */
    public function using_no_service_name_always_returns_unauthorised()
    {
        $token = \App\ApiToken::createNew('test');
        \Route::middleware('apitoken')->any('/_test/', function () {
            return 'OK';
        });

        $response = $this->call('GET', '_test', ['api_token' => $token]);

        $response->assertStatus(401);
    }
}
