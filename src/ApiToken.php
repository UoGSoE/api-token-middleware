<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    protected $guarded = [];

    public static function createNew($service)
    {
        $newToken = str_random(32);
        static::create([
            'service' => $service,
            'token' => bcrypt($newToken)
        ]);
        return $newToken;
    }

    public static function regenerate($service)
    {
        $token = static::where('service', '=', $service)->first();
        if (! $token) {
            throw new \InvalidArgumentException('No such service');
        }
        $newToken = str_random(32);
        $token->token = bcrypt($newToken);
        $token->save();
        return $newToken;
    }
}
