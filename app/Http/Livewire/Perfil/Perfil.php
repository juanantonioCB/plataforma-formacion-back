<?php

namespace App\Http\Livewire\Perfil;

use Livewire\Component;
use App\Models\Administrador;
use App\Models\Teacher;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\ForumUser;

class Perfil extends Component
{
    use WithFileUploads;

    public $email, $nick, $name, $surname;
    public $picture;
    public $picture_content;
    public $iteration;

    public $person;


    public function mount()
    {
        if (config('app.rol') == config('app.keycloak_role_superadmin_name') || config('app.rol') == config('app.keycloak_role_administrator_name')) {
            $this->person = Administrador::where('email',Auth()->user()->email)->first();
         }
        if (config('app.rol') == config('app.keycloak_role_teacher_name')) {
            $this->person = Teacher::where('email',Auth()->user()->email)->first();
        }
        
        $this->email = $this->person->email;
        $this->nick = $this->person->nick;
        $this->name = $this->person->name;
        $this->surname = $this->person->surname;
        $this->picture = $this->person->avatar;
        $this->picture_content='';
        
    }

    public function render()
    {
        return view('livewire.perfil.perfil');
    }

    public function guardarPerfil() {
        $datos = ['nick' => $this->nick, 'name' => $this->name, 'surname' => $this->surname];
        if ($this->picture_content) {
            $path = Storage::disk('s3')->put('images/persons', $this->picture_content);
            $datos['picture'] = substr($path, strripos($path, '/')+1);

            if ($this->picture) {
                try {
                    Storage::disk('s3')->delete('images/persons/'.$this->picture);
                } catch (\Throwable $th) {
                }
            }
        }
        $this->person->fill($datos);
        $this->person->save();
        
        $forumuser = ForumUser::where('user_id',$this->person->id)->first();
        $forumuser->name = $this->nick;
        if ($this->picture_content) {
            $forumuser->avatar = substr($path, strripos($path, '/')+1);
        }
        $forumuser->save();
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Usuario actualizado correctamente.']);  
        return redirect()->route('perfil');
    }
}
