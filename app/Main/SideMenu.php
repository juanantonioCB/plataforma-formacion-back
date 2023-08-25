<?php

namespace App\Main;
use Auth;
/* use App\Models\Administrador; */

class SideMenu
{
    /**
     * List of side menu items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function menu()
    {
        /* $rol = '';
        if (Auth::check()) {
            $rol = Administrador::where('email',Auth()->user()->email)->first();

        }
 */
        //$prueba = $ruta1Texto;
        $rol = config('app.rol');

        $menu=collect([
            'dashboard' => [
                'icon' => 'home',
                'route_name' => 'dashboard',
                'params' => [],
                'title' => 'Dashboard'
            ],
        ]);

        if ( $rol == config('app.keycloak_role_superadmin_name') || $rol == config('app.keycloak_role_administrator_name') )
        {
            $menu=$menu->union([
                'cursos' => [
                    'icon' => 'book',
                    'route_name' => 'cursos',
                    'params' => [],
                    'title' => 'Cursos'
                ],
                'contenido' => [
                    'icon' => 'credit-card',
                    'route_name' => 'contenido',
                    'params' => [],
                    'title' => 'Contenido'
                ],
                'teachers' => [
                    'icon' => 'users',
                    'route_name' => 'teachers',
                    'params' => [],
                    'title' => 'Teachers'
                ],
            ]);
        }
        if ( $rol == config('app.keycloak_role_superadmin_name') )
        {
            $menu=$menu->union([
                'administradores' => [
                    'icon' => 'user-check',
                    'route_name' => 'administradores',
                    'params' => [],
                    'title' => 'Administradores'
                ]
            ]);
        }
        $menu=$menu->union([
            'perfil' => [
                'icon' => 'user',
                'route_name' => 'perfil',
                'params' => [],
                'title' => 'Editar perfil'
            ]
        ]);
        

        //$rol = rol(Auth()->user()->email);

/*         if (Auth::check())
        {
            $rol = rol(Auth()->user()->email);
            
            if ( $rol == config('app.keycloak_role_superadmin_name') || $rol == config('app.keycloak_role_administrator_name') )
            {
                $menu=$menu->union([
                    'cursos' => [
                        'icon' => 'book',
                        'route_name' => 'cursos',
                        'params' => [],
                        'title' => 'Cursos'
                    ],
                    'contenido' => [
                        'icon' => 'credit-card',
                        'route_name' => 'contenido',
                        'params' => [],
                        'title' => 'Contenido'
                    ],
                ]);
                if ( $rol == config('app.keycloak_role_superadmin_name') )
                {
                    $menu=$menu->union([
                        'administradores' => [
                            'icon' => 'user-check',
                            'route_name' => 'administradores',
                            'params' => [],
                            'title' => 'Administradores'
                        ]
                    ]);
                }
            } 
        } */

        

        /* return [
            'dashboard' => [
                'icon' => 'home',
                'route_name' => 'dashboard',
                'params' => [],
                'title' => 'Dashboard'
            ],
            'cursos' => [
                'icon' => 'book',
                'route_name' => 'cursos',
                'params' => [],
                'title' => 'Cursos'
            ],
            'contenido' => [
                'icon' => 'credit-card',
                'route_name' => 'contenido',
                'params' => [],
                'title' => 'Contenido'
            ],
        ]; */

        return $menu;
    }
}
