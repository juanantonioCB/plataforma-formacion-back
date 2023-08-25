<?php

namespace App\Http\Livewire\Administradores;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Administrador;
use App\Models\ForumUser;
use App\Rules\Email;
use App\Rules\EmailKC;
use Webpatser\Uuid\Uuid;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Administradores extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';
    use WithFileUploads;

    public $view = 'lista';
    public $perPage='10';
    public $sortField='email';
    public $sortDirection='asc';
    public $search='';

    public $administrador_id;
    public $nombreAdministrador, $email, $superadmin, $nick, $name, $surname;

    public $confirmingNameDelete='';
    public $confirmingIdDelete='';

    public $picture;
    public $picture_content;
    public $iteration;

    protected $queryString=[
        'search' => ['except' => ''],
        'perPage' => ['except' => '10'],
        'sortField',
        'sortDirection'
    ];

    public function render()
    {
        $search=$this->search;
        $administradores= Administrador::
            where(
                function ($query) use ($search) { 
                    $query->where('email','LIKE','%'.$search.'%')->orWhere('nick','LIKE','%'.$search.'%')->orWhere('name','LIKE','%'.$search.'%')->orWhere('surname','LIKE','%'.$search.'%');
                });
        $administradores = $administradores->orderBy($this->sortField,$this->sortDirection)->paginate($this->perPage);
        return view('livewire.administradores.administradores',compact('administradores'));
    }

    public function sortBy($field) {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function clear()
    {
        $this->search='';
        $this->sortField='email';
        $this->sortDirection='asc';
        $this->page=1;
        $this->perPage='10';
    }

    public function add()
    {
        $this->administrador_id = null;
        $this->email = '';
        $this->superadmin = false;
        $this->nick = '';
        $this->name = '';
        $this->surname = '';
        $this->picture = '';
        $this->picture_content='';
        $this->view = 'editar';
    }

    public function listado()
    {
        $this->view = 'lista';
    }

    public function save()
    {
        $request = request();

        if ($this->administrador_id == null) { 
            $rules = [
                'email' => ['required', new Email, new EmailKC],
            ];
            $messages = [
                'email.required' => 'El email del administrador no puede estar vacío.',
            ];
            $validator = Validator::make($request->serverMemo['data'], $rules,$messages);
            if ($validator->fails()) {
                $errores='';
                foreach ($validator->errors()->all() as $message) {
                    $errores=$errores.$message.'<br>';
                }
                $this->dispatchBrowserEvent(
                    'alert', ['type' => 'error',  'message' => $errores]);
                return;
            }
        }
        if ($this->administrador_id == null) { 
            if (!$this->superadmin) { 
                $superadmin = false; 
                $newKC = json_decode(altaKC( $this->email,config('app.keycloak_role_administrator_name')));
            } else { 
                $superadmin = true; 
                $newKC = json_decode(altaKC( $this->email,config('app.keycloak_role_superadmin_name')));
            }
            if ($newKC->ok)
            {
                $datos = ['email' => $this->email, 'superadmin' => $superadmin, 'nick' => $this->nick, 'name' => $this->name, 'surname' => $this->surname];
                if ($this->picture_content) {
                    $path = Storage::disk('s3')->put('images/persons', $this->picture_content);
                    $datos['picture'] = substr($path, strripos($path, '/')+1);
        
                    if ($this->picture) {
                        try {
                            Storage::disk('s3')->delete('images/persons/'.$this->picture);//code...
                        } catch (\Throwable $th) {
                        }
                    }
                } 
                if ($this->administrador_id == null) { 
                    $uuid = $newKC->id_cliente;//Uuid::generate()->string;
                    $datos['id'] = $uuid;
                }
                $administrador = Administrador::updateOrCreate(['id' => $this->administrador_id], $datos);
                $forumuser = new ForumUser;
                $uuid2 = Uuid::generate()->string;
                $forumuser->id = $uuid2;
                $forumuser->user_id = $newKC->id_cliente;
                $forumuser->name = $this->nick;
                if ($this->picture_content) {
                    $forumuser->avatar = substr($path, strripos($path, '/')+1);
                }
                $forumuser->is_admin = true;
                $forumuser->save();
                $this->dispatchBrowserEvent(
                    'alert', ['type' => 'success',  'message' => 'Administrador insertado correctamente.']); 
            } else {
                $this->dispatchBrowserEvent(
                    'alert', ['type' => 'error',  'message' => 'Error Administrador no insertado.']); 
            }
        } else {
            $datos = ['nick' => $this->nick, 'name' => $this->name, 'surname' => $this->surname];
            if ($this->picture_content) {
                //$path = $this->picture_content->hashName();
                //$this->picture_content->store('public/imgs/persons');
                $path = Storage::disk('s3')->put('images/persons', $this->picture_content);
                $datos['picture'] = substr($path, strripos($path, '/')+1);
    
                if ($this->picture) {
                    try {
                        Storage::disk('s3')->delete('images/persons/'.$this->picture);
                    } catch (\Throwable $th) {
                    }
                }
            } 
            $administrador = Administrador::updateOrCreate(['id' => $this->administrador_id], $datos);
            $forumuser = ForumUser::where('user_id',$this->administrador_id)->first();
            $forumuser->name = $this->nick;
            if ($this->picture_content) {
                $forumuser->avatar = substr($path, strripos($path, '/')+1);
            }
            $forumuser->save();
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Administrador actualizado correctamente.']); 
        }

        $this->view = 'lista';
    }

    public function delete($id)
    {
        $administrador = Administrador::find($id);
        
        $this->confirmingNameDelete='El administrador "'.$administrador->email.'" será eliminado.';
        $this->confirmingIdDelete = $id;
        $this->dispatchBrowserEvent('abrirModalDialog',['ventana' => 'borrado']);
    }

    public function deleteAdministrador()
    {
        $disabledKC = json_decode(disabledKC( $this->confirmingIdDelete ));
        if ($disabledKC->ok) {
            $administrador = Administrador::find($this->confirmingIdDelete);
            if ($administrador->picture) {
                try {
                    Storage::disk('s3')->delete('images/persons/'.$administrador->picture);//code...
                } catch (\Throwable $th) {
                }
            }
            $administrador->delete();
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Administrador eliminado correctamente.']); 
        } else {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Error Administrador no eliminado.']); 
        }
    }

    public function edit($id)
    {
        $administrador = Administrador::find($id);
        $this->administrador_id = $administrador->id;
        $this->email = $administrador->email;
        $this->superadmin = $administrador->superadmin;
        $this->nombreAdministrador = $administrador->email;
        $this->nick = $administrador->nick;
        $this->name = $administrador->name;
        $this->surname = $administrador->surname;
        $this->picture = $administrador->avatar;
        $this->picture_content='';
        $this->view = 'editar';
    }

}
