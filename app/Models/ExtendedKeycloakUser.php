<?php

namespace App\Models;

use Vizir\KeycloakWebGuard\Models\KeycloakUser;

class ExtendedKeycloakUser extends KeycloakUser {

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'locale',
        'preferred_username',
        'picture'
    ];

    public function __construct(array $profile) {
        parent::__construct($profile);
    }

}
