<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Curso;
use App\Models\Edicion;
use App\Models\Teacher;

class Dashboard extends Component
{
    public function render()
    {
        $rol = config('app.rol');
        if ( $rol == config('app.keycloak_role_superadmin_name') || $rol == config('app.keycloak_role_administrator_name') )
        {
            $cursos = Curso::orderBy('created_at','desc')->get();
            return view('livewire.dashboard.dashboard',compact('cursos'));
        }
        if ( $rol == config('app.keycloak_role_teacher_name') )
        {
            $teacher = Teacher::where('email',Auth()->user()->email)->first();
            $cursos = Curso::with('cursosEdiciones')->orderBy('created_at','desc')->get();
            return view('livewire.dashboard.dashboard',compact('cursos','teacher'));
        }
    }
}
