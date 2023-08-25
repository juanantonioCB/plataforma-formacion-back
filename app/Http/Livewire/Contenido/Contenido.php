<?php

namespace App\Http\Livewire\Contenido;

use Livewire\Component;
use App\Models\Contenido as Content;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Contenido extends Component
{
    use WithFileUploads;
    public $title, $short_desc, $text_button, $link_button;

    public $picture;
    public $picture_content;
    public $iteration;

    public function mount()
    {
        $contenido = Content::first();
        $this->title = $contenido->title;
        $this->short_desc = $contenido->short_desc;
        $this->text_button = $contenido->text_button;
        $this->link_button = $contenido->link_button;
        $this->picture = $contenido->imagen;
        $this->picture_content='';
    }

    public function render()
    {
        return view('livewire.contenido.contenido');
    }

    public function guardarContenido() {
        $datosContenido=[
            'title' => $this->title, 'short_desc' => $this->short_desc, 'text_button' => $this->text_button, 'link_button' => $this->link_button 
        ];

        if ($this->picture_content) {
            $path = Storage::disk('s3')->put('images/content', $this->picture_content);
            $datosContenido['picture'] = substr($path, strripos($path, '/')+1);

            if ($this->picture) {
                try {
                    Storage::disk('s3')->delete('images/content/'.$this->picture);//code...
                } catch (\Throwable $th) {
                }
            }
        } 
        $content = Content::first();
        $content->fill($datosContenido);
        $content->save();

        if ($this->picture_content) {
            $this->picture = $content->imagen;
            $this->picture_content='';
        }

        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Información actualizada correctamente.']); 
    }


}
