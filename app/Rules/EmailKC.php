<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class EmailKC implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $response = Http::asForm()->post(config('keycloak-web.base_url').'/realms/'.config('keycloak-web.realm').'/protocol/openid-connect/token', [
            'client_id' => config('app.keycloak_client_id_api'),
            'client_secret' => config('app.keycloak_client_secret_api'),
            'grant_type' => 'client_credentials',
        ]);

        if (!$response->successful()) {
            return false;
        } else {
            $token = json_decode($response)->access_token;
            $response2 = Http::withToken($token)->get(config('keycloak-web.base_url').'/admin/realms/'.config('keycloak-web.realm').'/users?email='.$value);
            if (!$response2->successful()) {
                return false;
            } else {
                if (count(json_decode($response2))>0) {
                    return false;
                } else {
                    return true;
                }
            }

        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Email existente, imposible añadir administrador.';
    }
}
