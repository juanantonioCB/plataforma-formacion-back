<?php

namespace App\Http\Livewire\Cursos;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Curso;
use App\Models\Edicion;
use App\Models\Teacher;
use App\Models\Enroll;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;

class Cursos extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';
    use WithFileUploads;

    public $view = 'lista';
    public $perPage='10';
    public $sortField='name';
    public $sortDirection='asc';
    public $search='';

    public $curso_id;
    public $nombreCurso, $name, $short_desc, $description, $video_url, $estimated_time;

    public $image;
    public $image_content;
    public $iteration;

    public $confirmingNameDelete='';
    public $confirmingIdDelete='';

    public $name_version, $init_date, $end_date, $is_open, $visible, $init_date_mat, $end_date_mat, $teacher_id, $badge_id, $badge_img_url;
    public $posibles_tipos;
    public $teachers = [];
    public $ediciones = [];
    public $confirmingNameDelete2='';
    public $confirmingIdDelete2='';

    public $nombreEdicion, $edicion_id, $name_version2, $init_date2, $end_date2, $is_open2, $visible2, $init_date_mat2, $end_date_mat2, $teacher_id2, $badge_id2, $badge_img_url2;
    public $course_type, $course_id;
    
    public $mensaje;



    protected $queryString=[
        'search' => ['except' => ''],
        'perPage' => ['except' => '10'],
        'sortField',
        'sortDirection'
    ];

    public function render()
    {
        $this->posibles_tipos = Curso::getPossibleEnumValues('course_type');
        $this->teachers = Teacher::get();
        $cursos = Curso::where('name','LIKE','%'.$this->search.'%');
        $cursos = $cursos->orderBy($this->sortField,$this->sortDirection)->paginate($this->perPage);
        return view('livewire.cursos.cursos',compact('cursos'));
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
        $this->sortField='name';
        $this->sortDirection='asc';
        $this->page=1;
        $this->perPage='10';
    }

    public function add()
    {
        $this->curso_id = null;
        $this->name = '';
        $this->short_desc = '';
        $this->description = '';
        $this->video_url = '';
        $this->image = '';
        $this->image_content='';
        $this->ediciones = [];
        $this->course_type = '';
        $this->course_id = '';
        $this->estimated_time = '';

        $this->view = 'editar';
    }

    public function edit($id)
    {
        $curso = Curso::find($id);
        $this->curso_id = $curso->id;
        $this->nombreCurso = $curso->name;
        $this->name = $curso->name;
        $this->short_desc = $curso->short_desc;
        $this->description = $curso->description;
        $this->video_url = $curso->video_url;
        $this->image = $curso->imagen;
        $this->image_content='';
        $this->ediciones = $curso->ediciones;
        $this->course_type = $curso->course_type;
        $this->course_id = $curso->course_id;
        $this->estimated_time = $curso->estimated_time;
        $this->inicializarEdition();
        $this->view = 'editar';
    }

    public function listado()
    {
        $this->view = 'lista';
    }

    public function save()
    {
        //dd($this->description);
        $request = request();
        $rules = [
            'name' => 'required',
            'course_type' => 'required'
        ];
        $messages = [
            'name.required' => 'El nombre del curso no puede estar vacío.',
            'course_type.required' => 'El tipo del curso debe de elejirse.',
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

        $datos = ['name' => $this->name,'short_desc' => $this->short_desc,'description' => $this->description, 'video_url' => $this->video_url, 'course_type' => $this->course_type, 'course_id' => $this->course_id, 'estimated_time' => $this->estimated_time];
        if ($this->image_content) {
            $path = Storage::disk('s3')->put('images/coursers', $this->image_content);
            $datos['image'] = substr($path, strripos($path, '/')+1);

            if ($this->image) {
                try {
                    Storage::disk('s3')->delete('images/coursers/'.$this->image);//code...
                } catch (\Throwable $th) {
                }
            }
        } 
        if ($this->curso_id == null) { 
            $uuid = Uuid::generate()->string;
            $datos['id'] = $uuid;
        }
        $curso = Curso::updateOrCreate(['id' => $this->curso_id], $datos);
        if ($this->image_content) {
            $this->image = $curso->imagen;
            $this->image_content='';
        }

        if ($this->curso_id==null) { 
            $this->curso_id = $uuid;
            $this->nombreCurso = $curso->name;
            $this->ediciones = [];
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Curso insertado correctamente.']); 
        } else {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Curso actualizado correctamente.']); 
        }
 
    }


    public function delete($id)
    {
        $curso = Curso::find($id);
        $ediciones = Edicion::where('course_id',$curso->id)->get();
        if ($ediciones->count()>0) {
            $this->mensaje='Imposible eliminación, curso con ediciones creadas.';
            $this->dispatchBrowserEvent('abrirModal',['ventana' => 'imposible']);
            return;
        } 
        /* $canal=Canal::find($id); */
        $this->confirmingNameDelete='El curso "'.$curso->name.'" será eliminado.';
        $this->confirmingIdDelete= $id;
        $this->dispatchBrowserEvent('abrirModalDialog',['ventana' => 'borrado']);
    }

    public function deleteCurso()
    {
        $curso = Curso::find($this->confirmingIdDelete);
        if ($curso->image !== null & $curso->image !== '') {
            try {
                Storage::disk('s3')->delete('images/coursers/'.$curso->image);//code...
            } catch (\Throwable $th) {
            }
            //Storage::delete('public/imgs/coursers/'.$curso->image);
        }
        $curso->delete();
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'error',  'message' => 'Curso eliminado.']); 
    }

    public function saveEdition() {
        $request = request();
        $rules = [
            'name_version' => 'required',
        ];
        $messages = [
            'name_version.required' => 'El nombre de la edición no puede estar vacío.',
        ];
        $validator = Validator::make($request->serverMemo['data'], $rules,$messages);
        
        $validator->after(function ($validator) {
            if (!$this->is_open) { $is_open = false; } else { $is_open = true; }
            $curso = Curso::find($this->curso_id);
            if ($is_open) {
                if ($curso->cursosEdicionesAbiertas->count() !== 0) {
                    $validator->errors()->add('field', 'Ya existe una edición abierta, imposible insertar otra edición abierta.');
                }
            }
        });

        if ($validator->fails()) {
            $errores='';
            foreach ($validator->errors()->all() as $message) {
                $errores=$errores.$message.'<br>';
            }
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => $errores]);
            return;
        }
      
        if (!$this->is_open) { $is_open = false; } else { $is_open = true; }
        if (!$this->visible) { $visible = false; } else { $visible = true; }
        $uuid = Uuid::generate()->string;
        $datos = ['id' => $uuid, 'name' => $this->name_version,'course_id' => $this->curso_id, 'is_open' => $is_open, 'visible' => $visible, 'teacher_id' => $this->teacher_id, 'badge_id' => intval($this->badge_id), 'badge_img_url' => $this->badge_img_url];
        if ( $this->init_date ) {
            $datos['init_date'] = $this->init_date;
        }
        if ( $this->end_date ) {
            $datos['end_date'] = $this->end_date;
        }
        if ( $this->init_date_mat ) {
            $datos['init_date_mat'] = $this->init_date_mat;
        }
        if ( $this->end_date_mat ) {
            $datos['end_date_mat'] = $this->end_date_mat;
        }
        $edicion = Edicion::create($datos);
        $curso = Curso::find($this->curso_id);
        $this->ediciones = $curso->ediciones;
        $this->inicializarEdition();
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Edición insertada correctamente.']); 
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            if (true) {
                $validator->errors()->add('field', 'Something is wrong with this field!');
            }
        });
    }

    public function inicializarEdition() {
        $this->name_version = '';
        $this->is_open = false;
        $this->visible = false;
        $this->teacher_id = '';
        $this->dispatchBrowserEvent('borrarFecha',['id' => 'init_date']);
        $this->dispatchBrowserEvent('borrarFecha',['id' => 'end_date']);
        $this->dispatchBrowserEvent('borrarFecha',['id' => 'init_date_mat']);
        $this->dispatchBrowserEvent('borrarFecha',['id' => 'end_date_mat']);
        $this->badge_id = '';
        $this->badge_img_url = '';
    }

    public function deleteEdition($id)
    {
        $edicion = Edicion::find($id);
        $enrolls = Enroll::where('edition_id',$edicion->id)->get();
        if ($enrolls->count()>0) {
            $this->mensaje = 'Imposible eliminación, edicion con matrículas creadas.';
            $this->dispatchBrowserEvent('abrirModal',['ventana' => 'imposible']);
            return;
        } 
        $this->confirmingNameDelete2='La edición "'.$edicion->name.'" será eliminada.';
        $this->confirmingIdDelete2= $id;
        $this->dispatchBrowserEvent('abrirModalDialog',['ventana' => 'borradoEdicion']);
    }

    public function deleteEdicion()
    {
        $edicion = Edicion::find($this->confirmingIdDelete2);
        $edicion->delete();
        $curso = Curso::find($this->curso_id);
        $this->ediciones = $curso->ediciones;
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'error',  'message' => 'Edición curso eliminada.']); 
    }

    public function editEdition($id)
    {
        $edicion = Edicion::find($id);
        $this->nombreEdicion = $edicion->name;
        $this->name_version2 = $edicion->name;
        $this->edicion_id = $edicion->id;
        $this->init_date2 = $edicion->init_date;
        $this->end_date2 = $edicion->end_date;
        $this->is_open2 = $edicion->is_open;
        $this->visible2 = $edicion->visible;
        $this->init_date_mat2 = $edicion->init_date_mat;
        $this->end_date_mat2 = $edicion->end_date_mat;
        $this->teacher_id2 = $edicion->teacher_id;
        $this->badge_id2 = $edicion->badge_id;
        $this->badge_img_url2 = $edicion->badge_img_url;
        
        $this->view = 'editarEdicion';
        /* $this->dispatchBrowserEvent('abrirModal',['ventana' => 'EditEdition']); */
    }

    public function curso()
    {
        $this->view = 'editar';
    }

    public function updateEdition()
    {
        $request = request();
        $rules = [
            'name_version2' => 'required',
        ];
        $messages = [
            'name_version2.required' => 'El nombre de la edición no puede estar vacío.',
        ];
        $validator = Validator::make($request->serverMemo['data'], $rules,$messages);
        $validator->after(function ($validator) {
            if (!$this->is_open2) { $is_open = false; } else { $is_open = true; }
            $curso = Curso::find($this->curso_id);
            if ($is_open) {
                if ($curso->cursosEdicionesAbiertas->where('id','!==',$this->edicion_id)->count() >= 1) {
                    $validator->errors()->add('field', 'Ya existe una edición abierta, imposible insertar otra edición abierta.');
                }
            }
        });
        if ($validator->fails()) {
            $errores='';
            foreach ($validator->errors()->all() as $message) {
                $errores=$errores.$message.'<br>';
            }
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => $errores]);
            return;
        }
        if (!$this->is_open2) { $is_open = false; } else { $is_open = true; }
        if (!$this->visible2) { $visible = false; } else { $visible = true; }
        $datos = ['name' => $this->name_version2,'is_open' => $is_open, 'visible' => $visible, 'teacher_id' => $this->teacher_id2, 'badge_id' => intval($this->badge_id2), 'badge_img_url' => $this->badge_img_url2];
        if ( $this->init_date2 ) {
            $datos['init_date'] = $this->init_date2;
        }
        if ( $this->end_date2 ) {
            $datos['end_date'] = $this->end_date2;
        }
        if ( $this->init_date_mat2 ) {
            $datos['init_date_mat'] = $this->init_date_mat2;
        }
        if ( $this->end_date2 ) {
            $datos['end_date_mat'] = $this->end_date_mat2;
        }
       //$edicion = Edicion::update($datos);
        $edicion = Edicion::find($this->edicion_id);
        $edicion->fill($datos);
        $edicion->save();

        $curso = Curso::find($this->curso_id);
        $this->ediciones = $curso->ediciones;
        
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Edición actualizada correctamente.']); 
        $this->view = 'editar';
    }





}
