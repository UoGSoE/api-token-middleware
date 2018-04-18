<?php

namespace Tests;

use App\ApiToken;

class ArtisanTest extends TestCase
{
    /** @test */
    public function creating_a_new_token_stores_a_hashed_token_in_the_db()
    {
        $token = ApiToken::createNew('test');

        $dbToken = ApiToken::first();

        $this->assertNotEquals($token, $dbToken->token);
        $this->assertTrue(\Hash::check($token, $dbToken->token));
    }

    /** @test */
    public function we_can_generate_a_new_hashed_token_for_an_existing_token()
    {
        $token = ApiToken::createNew('test');

        $newToken = ApiToken::regenerate('test');

        $dbToken = ApiToken::first();
        $this->assertNotEquals($token, $newToken);
        $this->assertTrue(\Hash::check($newToken, $dbToken->token));
    }

    /** @test */
    public function we_can_call_artisan_to_create_a_new_token()
    {
        $this->assertCount(0, ApiToken::all());

        $this->artisan('apitoken:create', ['service' => 'test']);

        $this->assertCount(1, ApiToken::all());
        $this->assertDatabaseHas('api_tokens', ['service' => 'test']);
    }

    /** @test */
    public function we_can_call_artisan_to_delete_a_token()
    {
        $token1 = ApiToken::createNew('test1');
        $token2 = ApiToken::createNew('test2');

        $this->artisan('apitoken:delete', ['service' => 'test1']);

        $this->assertCount(1, ApiToken::all());
        $this->assertDatabaseHas('api_tokens', ['service' => 'test2']);
    }

    /** @test */
    public function we_can_call_artisan_to_list_all_tokens()
    {
        $token1 = ApiToken::createNew('test1');
        $token2 = ApiToken::createNew('test2');

        $this->artisan('apitoken:list');

        $output = \Artisan::output();
        $this->assertContains('test1', $output);
        $this->assertContains('test2', $output);
    }

    /** @test */
    public function we_can_call_artisan_to_regenerate_a_token()
    {
        $token = ApiToken::createNew('test');
        $dbToken = ApiToken::first();
        $this->assertTrue(\Hash::check($token, $dbToken->token));

        $this->artisan('apitoken:regenerate', ['service' => 'test']);

        $dbToken = ApiToken::first();
        $this->assertFalse(\Hash::check($token, $dbToken->token));
    }
}
