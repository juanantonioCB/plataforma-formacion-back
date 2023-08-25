<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /* public function __construct()
    {
    } */

    public function index()
    {
        if (Auth()->user()) {
            $rol = rol(Auth()->user()->email);
            if ( $rol !== config('app.keycloak_role_superadmin_name') && $rol !== config('app.keycloak_role_administrator_name') && $rol !== config('app.keycloak_role_teacher_name')) {
                return redirect()->route('keycloak.logout');
            }
        }
        return view('index');
    }

    public function dashboard()
    {
        if (Auth()->user()) {
            $rol = rol(Auth()->user()->email);
            if ( $rol !== config('app.keycloak_role_superadmin_name') && $rol !== config('app.keycloak_role_administrator_name') && $rol !== config('app.keycloak_role_teacher_name')) {
                return redirect()->route('keycloak.logout');
            }
        }
        $ruta1Texto="Dashboard";
        $ruta1Link="dashboard";
        return view('admin.dashboard', compact('ruta1Texto','ruta1Link'));
    }

    public function cursos()
    {
        if (Auth()->user()) {
           
            $rol = rol(Auth()->user()->email);
            if ( $rol !== config('app.keycloak_role_superadmin_name') && $rol !== config('app.keycloak_role_administrator_name') ) {
                return redirect()->route('keycloak.logout');
            }
        }
        $ruta1Texto="Cursos";
        $ruta1Link="cursos";
        return view('admin.cursos', compact('ruta1Texto','ruta1Link'));
    }

    public function contenido()
    {
        if (Auth()->user()) {
            
            $rol = rol(Auth()->user()->email);
            if ( $rol !== config('app.keycloak_role_superadmin_name') && $rol !== config('app.keycloak_role_administrator_name') ) {
                return redirect()->route('keycloak.logout');
            }
        }
        $ruta1Texto="Contenido";
        $ruta1Link="contenido";
        return view('admin.contenido', compact('ruta1Texto','ruta1Link'));
    }

    public function administradores()
    {
        if (Auth()->user()) {
            
            $rol = rol(Auth()->user()->email);
            if ( $rol !== config('app.keycloak_role_superadmin_name') ) {
                return redirect()->route('keycloak.logout');
            }
        }
        $ruta1Texto="Administradores";
        $ruta1Link="administradores";
        return view('admin.administradores', compact('ruta1Texto','ruta1Link'));
    }

    public function teachers()
    {
        if (Auth()->user()) {
            
            $rol = rol(Auth()->user()->email);
            if ( $rol !== config('app.keycloak_role_superadmin_name') && $rol !== config('app.keycloak_role_administrator_name') ) {
                return redirect()->route('keycloak.logout');
            }
        }
        $ruta1Texto="Teachers";
        $ruta1Link="teachers";
        return view('admin.teachers', compact('ruta1Texto','ruta1Link'));
    }

    public function perfil()
    {
        if (Auth()->user()) {
            $rol = rol(Auth()->user()->email);
        }
        $ruta1Texto="Perfil";
        $ruta1Link="perfil";
        return view('admin.perfil', compact('ruta1Texto','ruta1Link'));
    }

}
