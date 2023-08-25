<?php

namespace App\Http\Livewire\Teachers;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Teacher;
use App\Models\Edicion;
use App\Models\ForumUser;
use App\Rules\Email;
use App\Rules\EmailKC;
use Webpatser\Uuid\Uuid;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Teachers extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';
    use WithFileUploads;

    public $view = 'lista';
    public $perPage='10';
    public $sortField='email';
    public $sortDirection='asc';
    public $search='';

    public $teacher_id;
    public $nombreTeacher, $email, $nick, $name, $surname;

    public $confirmingNameDelete='';
    public $confirmingIdDelete='';

    public $picture;
    public $picture_content;
    public $iteration;

    public $mensaje;

    protected $queryString=[
        'search' => ['except' => ''],
        'perPage' => ['except' => '10'],
        'sortField',
        'sortDirection'
    ];

    public function render()
    {
        $search=$this->search;
        $teachers= Teacher::
            where(
                function ($query) use ($search) { 
                    $query->where('email','LIKE','%'.$search.'%')->orWhere('nick','LIKE','%'.$search.'%')->orWhere('name','LIKE','%'.$search.'%')->orWhere('surname','LIKE','%'.$search.'%');
                });
        //$teachers = Teacher::where('email','LIKE','%'.$this->search.'%');
        $teachers = $teachers->orderBy($this->sortField,$this->sortDirection)->paginate($this->perPage);
        return view('livewire.teachers.teachers',compact('teachers'));
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
        $this->teacher_id = null;
        $this->email = '';
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

        if ($this->teacher_id == null) { 
            $rules = [
                'email' => ['required', new Email, new EmailKC],
            ];
            $messages = [
                'email.required' => 'El email del teacher no puede estar vacío.',
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
        
        if ($this->teacher_id == null) { 
            $newKC = json_decode(altaKC( $this->email,config('app.keycloak_role_teacher_name')));
            if ($newKC->ok)
            {
                $datos = ['email' => $this->email, 'nick' => $this->nick, 'name' => $this->name, 'surname' => $this->surname];
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
                if ($this->teacher_id == null) { 
                    $uuid = $newKC->id_cliente;//Uuid::generate()->string;
                    $datos['id'] = $uuid;
                }
                $teacher = Teacher::updateOrCreate(['id' => $this->teacher_id], $datos);
                $forumuser = new ForumUser;
                $uuid2 = Uuid::generate()->string;
                $forumuser->id = $uuid2;
                $forumuser->user_id = $newKC->id_cliente;
                $forumuser->name = $this->nick;
                if ($this->picture_content) {
                    $forumuser->avatar = substr($path, strripos($path, '/')+1);
                }
                $forumuser->is_teacher = true;
                $forumuser->save();
                $this->dispatchBrowserEvent(
                    'alert', ['type' => 'success',  'message' => 'Teacher insertado correctamente.']); 
            } else {
                $this->dispatchBrowserEvent(
                    'alert', ['type' => 'error',  'message' => 'Error Teacher no insertado.']); 
            }
        } else {
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
            $teacher = Teacher::updateOrCreate(['id' => $this->teacher_id], $datos);
            $forumuser = ForumUser::where('user_id',$this->teacher_id)->first();
            $forumuser->name = $this->nick;
            if ($this->picture_content) {
                $forumuser->avatar = substr($path, strripos($path, '/')+1);;
            }
            $forumuser->save();
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Teacher actualizado correctamente.']); 
        }

        $this->view = 'lista';
    }

    public function delete($id)
    {
        $teacher = Teacher::find($id);
        $ediciones = Edicion::where('teacher_id',$teacher->id)->get();
        if ($ediciones->count()>0) {
            $this->mensaje='Imposible eliminación, teacher con ediciones asignadas.';
            $this->dispatchBrowserEvent('abrirModal',['ventana' => 'imposible']);
            return;
        } 
        $this->confirmingNameDelete='El teacher "'.$teacher->email.'" será eliminado.';
        $this->confirmingIdDelete = $id;
        $this->dispatchBrowserEvent('abrirModalDialog',['ventana' => 'borrado']);
    }

    public function deleteTeacher()
    {
        $disabledKC = json_decode(disabledKC( $this->confirmingIdDelete ));
        if ($disabledKC->ok) {
            $teacher = Teacher::find($this->confirmingIdDelete);
            if ($teacher->picture) {
                try {
                    Storage::disk('s3')->delete('images/persons/'.$teacher->picture);//code...
                } catch (\Throwable $th) {
                }
                //Storage::delete('public/imgs/persons/'.$teacher->picture);
            }
            $teacher->delete();
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Teacher eliminado correctamente.']); 
        } else {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Error Teacher no eliminado.']); 
        }
        /* $deleteKC = json_decode(bajaKC( $this->confirmingIdDelete ));
        if ($deleteKC->ok) {
            $teacher = Teacher::find($this->confirmingIdDelete);
            if ($teacher->picture) {
                Storage::delete('public/imgs/persons/'.$teacher->picture);
            }
            $teacher->delete();
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Teacher eliminado correctamente.']); 
        } else {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Error Teacher no eliminado.']); 
        } */
    }

    public function edit($id)
    {
        $teacher = Teacher::find($id);
        $this->teacher_id = $teacher->id;
        $this->email = $teacher->email;
        $this->nombreTeacher = $teacher->email;
        $this->nick = $teacher->nick;
        $this->name = $teacher->name;
        $this->surname = $teacher->surname;
        $this->picture = $teacher->avatar;
        $this->picture_content='';
        $this->view = 'editar';
    }

}
