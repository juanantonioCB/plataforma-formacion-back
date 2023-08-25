<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Models\Administrador;
use App\Library\AWS\SignWithCloudFront;

if (! function_exists('has_rol')) {
    function has_rol($role)
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
            $email = Auth()->user()->email;
            $response2 = Http::withToken($token)->get(config('keycloak-web.base_url').'/admin/realms/'.config('keycloak-web.realm').'/users?email='.$email);
                if (!$response2->successful()) {
                    return false;
                } else {
                    $id_cliente = json_decode($response2)[0]->id;
                    $response3 = Http::withToken($token)->get(config('keycloak-web.base_url').'/admin/realms/'.config('keycloak-web.realm').'/users/'.$id_cliente.'/role-mappings');
                    if (!$response3->successful()) {
                        return false;
                    } else {
                        $esRol = false;
                        foreach(json_decode($response3)->realmMappings as $rol) {
                            if ($rol->name == $role) { 
                                $esRol = true;
                            } 
                        }
                        
                        if ($esRol) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
        }
    }
}

if (! function_exists('rol')) {
    function rol($email)
    {
        $response = Http::asForm()->post(config('keycloak-web.base_url').'/realms/'.config('keycloak-web.realm').'/protocol/openid-connect/token', [
            'client_id' => config('app.keycloak_client_id_api'),
            'client_secret' => config('app.keycloak_client_secret_api'),
            'grant_type' => 'client_credentials',
        ]);

        if (!$response->successful()) {
            return '';
        } else {
            $token = json_decode($response)->access_token;
            $response2 = Http::withToken($token)->get(config('keycloak-web.base_url').'/admin/realms/'.config('keycloak-web.realm').'/users?email='.$email);
                if (!$response2->successful()) {
                    return '';
                } else {
                    $id_cliente = json_decode($response2)[0]->id;
                    $response3 = Http::withToken($token)->get(config('keycloak-web.base_url').'/admin/realms/'.config('keycloak-web.realm').'/users/'.$id_cliente.'/role-mappings');
                    if (!$response3->successful()) {
                        return '';
                    } else {
                        $Rol = '';
                        foreach(json_decode($response3)->realmMappings as $rol) {
                            switch ($rol->name) {
                                case config('app.keycloak_role_subscriptor_name'):
                                    $Rol = config('app.keycloak_role_subscriptor_name');
                                    break;
                                case config('app.keycloak_role_superadmin_name'):
                                    $Rol = config('app.keycloak_role_superadmin_name');
                                    if (Administrador::where('email',$email)->first()->picture) {
                                        $result = SignWithCloudFront::sign(Administrador::where('email',$email)->first()->picture, 'person', 5);
                                        if ($result->Success == true) {
                                            config(['app.picture' => $result->Link]);
                                         } else {
                                            config(['app.picture' => '']);
                                        }
                                    }
                                    //config(['app.picture' => Administrador::where('email',$email)->first()->picture]);
                                    break;
                                case config('app.keycloak_role_administrator_name'):
                                    $Rol = config('app.keycloak_role_administrator_name');
                                    if (Administrador::where('email',$email)->first()->picture) {
                                        $result = SignWithCloudFront::sign(Administrador::where('email',$email)->first()->picture, 'person', 5);
                                        if ($result->Success == true) {
                                            config(['app.picture' => $result->Link]);
                                         } else {
                                            config(['app.picture' => '']);
                                        }
                                    }
                                    //config(['app.picture' => Administrador::where('email',$email)->first()->picture]);
                                    break;
                                case config('app.keycloak_role_teacher_name'):
                                    $Rol = config('app.keycloak_role_teacher_name');
                                    if (Teacher::where('email',$email)->first()->picture) {
                                        $result = SignWithCloudFront::sign(Teacher::where('email',$email)->first()->picture, 'person', 5);
                                        if ($result->Success == true) {
                                            config(['app.picture' => $result->Link]);
                                         } else {
                                            config(['app.picture' => '']);
                                        }
                                    }
                                    //config(['app.picture' => Teacher::where('email',$email)->first()->picture]);
                                    break;
                            }
                        }
                        config(['app.rol' => $Rol]);
                        return $Rol;
                    }
                }
        }
    }
}

if (! function_exists('altaKC')) {
    function altaKC($email,$rol)
    {
        $response = Http::asForm()->post(config('keycloak-web.base_url').'/realms/'.config('keycloak-web.realm').'/protocol/openid-connect/token', [
            'client_id' => config('app.keycloak_client_id_api'),
            'client_secret' => config('app.keycloak_client_secret_api'),
            'grant_type' => 'client_credentials',
        ]);

        if (!$response->successful()) {
            return json_encode(['ok' => false, 'id_cliente' => '']);
        } else {
            $token = json_decode($response)->access_token;
            $response2 = Http::withToken($token)->post(config('keycloak-web.base_url').'/admin/realms/'.config('keycloak-web.realm').'/users', [
                "emailVerified" => true,
                "enabled" => true,
                "username" => $email,
                "email" => $email
            ]);
            if (!$response2->successful()) {
                return json_encode(['ok' => false, 'id_cliente' => '']);
            } else {
                $response3 = Http::withToken($token)->get(config('keycloak-web.base_url').'/admin/realms/'.config('keycloak-web.realm').'/users?email='.$email);
                if (!$response3->successful()) {
                    return json_encode(['ok' => false, 'id_cliente' => '']);
                } else {
                    $id_cliente = json_decode($response3)[0]->id;
                    $body35 = '[{"id": "'.config('app.keycloak_role_'.$rol.'_id').'","name": "'.config('app.keycloak_role_'.$rol.'_name').'"}]';
                    $response35 = Http::withHeaders(['Content-Type' => 'application/json'])->withBody($body35,'json')->withToken($token)->post(config('keycloak-web.base_url').'/admin/realms/'.config('keycloak-web.realm').'/users/'.$id_cliente.'/role-mappings/realm'
                    );
                    if (!$response35->successful()) {
                        return json_encode(['ok' => false, 'id_cliente' => '']);
                    } else {
                        /* $response4 = Http::withHeaders(['Content-Type' => 'application/json'])->withBody('["UPDATE_PASSWORD"]','json')->withToken($token)->put(config('keycloak-web.base_url').'/admin/realms/'.config('keycloak-web.realm').'/users/'.$id_cliente.'/execute-actions-email?lifespan=43200&cliente_id='.config('app.keycloak_client_id_api')
                        );
                        if (!$response4->successful()) {
                            return false;
                        } */

                        return json_encode(['ok' => true, 'id_cliente' => $id_cliente]);
                    }
                }
            }
        }
    }
}

if (! function_exists('bajaKC')) {
    function bajaKC($uuid)
    {
        $response = Http::asForm()->post(config('keycloak-web.base_url').'/realms/'.config('keycloak-web.realm').'/protocol/openid-connect/token', [
            'client_id' => config('app.keycloak_client_id_api'),
            'client_secret' => config('app.keycloak_client_secret_api'),
            'grant_type' => 'client_credentials',
        ]);

        if (!$response->successful()) {
            return json_encode(['ok' => false]);
        } else {
            $token = json_decode($response)->access_token;
            $response2 = Http::withToken($token)->delete(config('keycloak-web.base_url').'/admin/realms/'.config('keycloak-web.realm').'/users/'.$uuid);
            if (!$response2->successful()) {
                return json_encode(['ok' => false]);
            } else {
                return json_encode(['ok' => true]);
            }
        }
    }
}

if (! function_exists('disabledKC')) {
    function disabledKC($uuid)
    {
        $response = Http::asForm()->post(config('keycloak-web.base_url').'/realms/'.config('keycloak-web.realm').'/protocol/openid-connect/token', [
            'client_id' => config('app.keycloak_client_id_api'),
            'client_secret' => config('app.keycloak_client_secret_api'),
            'grant_type' => 'client_credentials',
        ]);

        if (!$response->successful()) {
            return json_encode(['ok' => false]);
        } else {
            $token = json_decode($response)->access_token;
            $response2 = Http::withToken($token)->put(config('keycloak-web.base_url').'/admin/realms/'.config('keycloak-web.realm').'/users/'.$uuid, [
                "enabled" => false
            ]);
            if (!$response2->successful()) {
                return json_encode(['ok' => false]);
            } else {
                return json_encode(['ok' => true]);
            }
        }
    }
}


   
        
        