<div>
    @if($view=='lista')

        <!--   TÍTULO   ------------------------------------------------>
        <div class="intro-y flex flex-col sm:flex-row items-center mt-4">
            <h2 class="text-lg font-medium mr-auto">Lista de Cursos</h2>
            <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                <button class="btn btn-primary text-dark-7 bg-theme-17" wire:click="add">Añadir nuevo</button>
            </div>
        </div>

        <!--   FILTROS   ------------------------------------------------>
        <div class="grid grid-cols-12 gap-3 mt-5">
            <div class="intro-y col-span-12 md:col-span-6 xl:col-span-4 mt-2 sm:mt-0 flex flex-row">
                <div class="w-full relative text-gray-700 dark:text-gray-300">
                    <input type="text" class="form-control w-full box pr-10 placeholder-theme-8" placeholder="Buscar..." wire:model="search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>
                @if ($search !=='' || ($perPage!=='10') || ($sortField!=='name'))
                <button class="button px-2 box  ml-2" wire:click="clear">
                    <span class="w-5 h-5 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x mx-auto"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </span>
                </button>
                @endif
            </div>
            <div class="intro-y col-span-12 md:col-span-3 xl:col-span-2 text-right mt-2 sm:mt-0">
                <select wire:model="perPage" class="w-full form-select box  text-theme-8" >
                    <option value="5">5 por página</option>
                    <option value="10">10 por página</option>
                    <option value="15">15 por página</option>
                    <option value="25">25 por página</option>
                    <option value="50">50 por página</option>
                    <option value="100">100 por página</option>
                </select>
            </div>
        </div>

        <!--   LISTA   ------------------------------------------------>
        <div class="grid grid-cols-12 gap-3 mt-5">
            @if ($cursos->count())
                <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-3">
                    <table class="table table-report -mt-2">
                        <thead>
                            <tr>
                                <th class="text-center whitespace-nowrap"></th>
                                <th class="whitespace-nowrap cursor-pointer" wire:click="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">
                                    NOMBRE
                                    <div class="float-right mr-3 mt-1"> @if ($sortField=='name') @include('layout.sort') @endif </div>
                                </th>
                                <th class="whitespace-nowrap cursor-pointer" wire:click="sortBy('sort_desc')" :direction="$sortField === 'sort_desc' ? $sortDirection : null">
                                    DESC. CORTA
                                    <div class="float-right mr-3 mt-1"> @if ($sortField=='sort_desc') @include('layout.sort') @endif </div>
                                </th>
                                <th class="whitespace-nowrap cursor-pointer" wire:click="sortBy('course_type')" :direction="$sortField === 'course_type' ? $sortDirection : null">
                                    TIPO
                                    <div class="float-right mr-3 mt-1"> @if ($sortField=='course_type') @include('layout.sort') @endif </div>
                                </th>
                                <th class="whitespace-nowrap">
                                    VISIBLE
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cursos as $curso)
                                <tr class=" ">
                                    <td class=" w-1/6">
                                        <div class="flex justify-center items-center">
                                            <button class="flex items-center mr-3 text-p_s_azul" wire:click="edit('{{ $curso->id }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Editar
                                            </button>
                                            <button class="flex items-center text-p_p_rojo" wire:click="delete('{{ $curso->id }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Borrar
                                            </button>
                                        </div>
                                    </td>
                                    <td class=" w-2/6">
                                        <button class="text-left w-full" wire:click="edit('{{ $curso->id }}')">
                                            {{ $curso->name }}
                                        </button>
                                    </td>
                                    <td class="w-2/6">
                                        <button class="text-left w-full" wire:click="edit('{{ $curso->id }}')">
                                            {{ $curso->short_desc }}
                                        </button>
                                    </td>
                                    <td class="w-1/6">
                                        {{ $curso->course_type }}
                                    </td>
                                    <td class="w-1/6">
                                        <div class="form-switch">
                                            <input type="checkbox" class="form-check-input" {{ ($curso->cursosEdicionesVisibles->count() !== 0) ? 'checked' : '' }} disabled>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>  
                <div class="intro-y col-span-12 ">
                    {{ $cursos->links() }}
                </div>
            @else
                <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                    No hay resultados
                </div>
            @endif

    @endif

    @if($view=='editar')
        <!--------------------- EDITAR CURSO -------------------------------------------->
        <div class="intro-y flex flex-col sm:flex-row items-center mt-4">
            @if ($curso_id)
                <h2 class="text-lg font-medium mr-auto">Editar curso: <span class="">{{ $nombreCurso }}</span></h2>
            @else
                <h2 class="text-lg font-medium mr-auto">Insertar curso</h2>
            @endif
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5 box p-5">
            <div class="col-span-12 xl:col-span-6">
                <div class="mt-4">
                    <label class="form-label">Nombre curso *</label>
                    <input type="text" class="form-control w-full" placeholder="Nombre..." :value="old('name')" wire:model="name" autofocus maxlength="255">
                </div>
                <div class="mt-4">
                    <label class="form-label">Descripción corta curso</label>
                    <textarea class="form-control" placeholder="Descripción corta..." :value="old('short_desc')" wire:model="short_desc" rows="3" maxlength="999"></textarea>
                </div>
                <div class="mt-4">
                    <label class="form-label">Tiempo estimado</label>
                    <input type="text" class="form-control w-full" placeholder="Tiempo estimado..." :value="old('estimated_time')" wire:model="estimated_time" maxlength="255">
                </div>
            </div>
            <div class="col-span-12 xl:col-span-6">
                <div class="mt-4">
                    <label class="form-label">Enlace vídeo curso</label>
                    <input type="text" class="form-control w-full" placeholder="Video Url..." :value="old('video_url')" wire:model="video_url" maxlength="255">
                </div>
                <div class="mt-4">
                    <label>Tipo</label>
                    <select wire:model="course_type" class="w-full form-select mt-2">
                        <option value="">Elija tipo</option>
                        @foreach ($posibles_tipos as $tipo)
                            <option value="{{ $tipo }}">{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-4">
                    <label class="form-label">Curso id</label>
                    <input type="text" class="form-control w-full" placeholder="Curso id..." :value="old('course_id')" wire:model="course_id" maxlength="255">
                </div>
            </div>
            <div class="col-span-12">
                <div class="" >
                    <div class="mb-2">
                        <label>Descripción curso</label>
                    </div>
                    
                    <div class="mt-2 " wire:ignore>
                        <x-quill :description="$description" campo="description" />
                    </div>
                </div>
            </div>
            <div class="col-span-12 mt-4">
                <div class="grid grid-cols-12 gap-4 mb-5">
                    <div class="col-span-12 sm:col-span-4 ">
                        <div class="text-right pr-2"><label>Imagen actual</label></div>
                        <div class="flex justify-items-end">
                            <div class="relative ml-auto mt-2 pr-2">
                                @if (!empty($image))
                                    <img alt="" class="rounded-md max-h-44" src="{{ $image }}" title="imagen">
                                @else
                                    <img alt="" class="tooltip rounded-md max-h-44" src="{{ asset('imgs/preview.jpg') }}" title="imagen">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4 flex items-center justify-items-center">
                        <div class="w-full mx-auto cursor-pointer relative mt-5 text-center">
                            <button class="btn btn-outline-primary border-dashed w-40">
                                Cambiar imagen
                                <div wire:loading class="ml-3" wire:target="image_content">
                                    <x-loading/>
                                </div>
                            </button>
                            <input type="file" wire:model="image_content" class="w-full h-full top-0 left-0 absolute opacity-0 cursor-pointer" id="image_content{{$iteration}}">
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4 pl-3">
                        @if ($image_content)
                            <div class="mb-1"><label>Preview imagen</label></div>
                            <img class="max h-44 rounded-md" class="mt-2" src="{{ $image_content->temporaryUrl() }}">
                        @endif
                    </div>
                    <div class="col-span-12 text-p_s_azul">
                        Características idóneas para la imagen:<br/>
                        Formato horizontal o apaisado, máximo de anchura 800 px, tipo jpg o png optimizado en tamaño para no exceder de 300 Kbytes.
                    </div>
                </div>
            </div>
        </div>
        
        @if ($curso_id)
            <div class="grid grid-cols-12 gap-6 box mt-5 p-5">
                @if ($ediciones)
                    @if ($ediciones->count())
                        <div class="col-span-12">
                            <div class="flex items-center ">
                                <h2 class="font-medium text-base mr-auto">Ediciones curso</h2>
                            </div>
                            <div class="col-span-12 overflow-x-auto mt-4">
                                <table class="table border">
                                    <thead>
                                        <tr>
                                            <th class="bg-slate-50 dark:bg-darkmode-800 w-24"></th>
                                            <th class="bg-slate-50 dark:bg-darkmode-800 text-slate-500 whitespace-nowrap">Edición</th>
                                            <th class="bg-slate-50 dark:bg-darkmode-800 text-slate-500 whitespace-nowrap">Fecha inicial</th>
                                            <th class="bg-slate-50 dark:bg-darkmode-800 text-slate-500 whitespace-nowrap">Fecha final</th>
                                            <th class="bg-slate-50 dark:bg-darkmode-800 text-slate-500 whitespace-nowrap">Fecha inicial mat.</th>
                                            <th class="bg-slate-50 dark:bg-darkmode-800 text-slate-500 whitespace-nowrap">Fecha final mat.</th>
                                            <th class="bg-slate-50 dark:bg-darkmode-800 text-slate-500 whitespace-nowrap">Teacher</th>
                                            <th class="bg-slate-50 dark:bg-darkmode-800 text-slate-500 whitespace-nowrap">Abierto</th>
                                            <th class="bg-slate-50 dark:bg-darkmode-800 text-slate-500 whitespace-nowrap">Visible</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ediciones as $edicion)
                                        <tr>
                                            <td class="whitespace-nowrap flex justify-center items-center w-24 px-3 ">
                                                <button class="flex items-center mr-3 text-p_s_azul mt-1 mb-1" wire:click="editEdition('{{ $edicion->id }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </button>
                                                <button class="flex items-center text-p_p_rojo mt-1 mb-1" wire:click="deleteEdition('{{ $edicion->id }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                </button>
                                            </td>
                                            <td class="whitespace-nowrap">{{ $edicion->name }}</td>
                                            <td class="whitespace-nowrap">
                                                @if ($edicion->init_date)
                                                    {{ date('d-m-Y', strtotime($edicion->init_date)) }}
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap">
                                                @if ($edicion->end_date)
                                                    {{ date('d-m-Y', strtotime($edicion->end_date)) }}
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap">
                                                @if ($edicion->init_date_mat)
                                                    {{ date('d-m-Y', strtotime($edicion->init_date_mat)) }}
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap">
                                                @if ($edicion->end_date_mat)
                                                    {{ date('d-m-Y', strtotime($edicion->end_date_mat)) }}
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap">
                                                {{ $edicion->teacher->email }}
                                            </td>
                                            <td class="whitespace-nowrap">
                                                <div class="form-switch">
                                                    <input type="checkbox" class="form-check-input" {{ ($edicion->is_open) ? 'checked' : '' }} disabled>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap">
                                                <div class="form-switch">
                                                    <input type="checkbox" class="form-check-input" {{ ($edicion->visible) ? 'checked' : '' }} disabled>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                    <div class="col-span-12">
                        <div class="flex items-center ">
                            <h2 class="font-medium text-base mr-auto">No hay ediciones</h2>
                        </div>
                    </div>
                    @endif
                @endif
                <div class="col-span-12 w-full mt-3 xl:mt-0 border border-p_p_rojo border-dashed rounded-md">
                    <div class="grid grid-cols-12 gap-6 box p-5">
                        <div class="col-span-4">
                            <label class="form-label">Nombre edición</label>
                            <input type="text" class="form-control w-full" placeholder="Nombre edición..." :value="old('name_version')" wire:model="name_version" maxlength="255">
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Fecha Inicial</label>
                            <x-date id="init_date" class="block border-gray-300 w-full fechablank" wire:model="init_date" />
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Fecha Final</label>
                            <x-date id="end_date" class="block border-gray-300 w-full fechablank" wire:model="end_date" />
                        </div>
                        <div class="col-span-1">
                            <label>Abierto</label>
                            <div class="form-switch mt-2">
                                <input type="checkbox" class="form-check-input" wire:model="is_open">
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label>Visible</label>
                            <div class="form-switch mt-2">
                                <input type="checkbox" class="form-check-input" wire:model="visible">
                            </div>
                        </div>
                        <div class="col-span-2">
                            <button class="btn btn-outline-primary border-dashed w-full mt-4 inline-flex" wire:click="saveEdition()">
                                Añadir
                                <div wire:loading class="ml-3" wire:target="saveEdition">
                                    <x-loading2/>
                                </div>
                            </button>
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Fecha Inicio Matriculación</label>
                            <x-date id="init_date_mat" class="block border-gray-300 w-full fechablank" wire:model="init_date_mat" />
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Fecha Final Matriculación</label>
                            <x-date id="end_date_mat" class="block border-gray-300 w-full fechablank" wire:model="end_date_mat" />
                        </div>
                        <div class="col-span-2">
                            <label>Teacher</label>
                            <select wire:model="teacher_id" class="w-full form-select mt-2">
                                <option value="">Elija teacher</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Id Badge</label>
                            <input type="text" class="form-control w-full" placeholder="Id Badge..." :value="old('badge_id')" wire:model="badge_id">
                        </div>
                        <div class="col-span-4">
                            <label class="form-label">Badge imagen url</label>
                            <input type="text" class="form-control w-full" placeholder="Badge imagen url..." :value="old('badge_img_url')" wire:model="badge_img_url" maxlength="255">
                        </div>
                        {{-- <div class="col-span-4">
                            <select wire:model="tipo" class="w-full form-select">
                                <option value="">Elija tipo</option>
                                @foreach ($posibles_tipos as $tipo)
                                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-6 form-inline">
                            <label class="form-label">Url edición</label>
                            <input type="text" class="form-control w-full" placeholder="Url edición..." :value="old('url_version')" wire:model="url_version" maxlength="255">
                        </div> --}}
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-12 gap-6 p-2">
            <div class=" col-span-12">
                <div class="text-right mt-5">
                    <button type="button" class="btn btn-outline-secondary w-24 mr-1" wire:click="listado()">Volver</button>
                    
                    <button type="button" class="btn btn-primary w-40 inline-flex" wire:click="save()">
                        Guardar
                        <div wire:loading class="ml-3" wire:target="save">
                            <x-loading/>
                        </div>
                    </button>
                </div>
            </div>
        </div>

    @endif

    @if($view=='editarEdicion')
        <div class="intro-y flex flex-col sm:flex-row items-center mt-4">
            <h2 class="text-lg font-medium mr-auto">Editar edición: <span class="">{{ $nombreEdicion }}</span></h2>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5 box p-5">
            <div class="col-span-12 xl:col-span-6">
                <div class="mt-4">
                    <label class="form-label">Nombre edición *</label>
                    <input type="text" class="form-control w-full" placeholder="Nombre edición..." :value="old('name_version2')" wire:model="name_version2" autofocus maxlength="255">
                </div>
            </div>
            <div class="col-span-12 xl:col-span-3 mt-4">
                <label class="form-label">Fecha Inicial</label>
                <x-date id="init_date2" class="block border-gray-300 w-full fechablank" wire:model="init_date2" />
            </div>
            <div class="col-span-12 xl:col-span-3 mt-4">
                <label class="form-label">Fecha Final</label>
                <x-date id="end_date2" class="block border-gray-300 w-full fechablank" wire:model="end_date2" />
            </div>
            <div class="col-span-12 xl:col-span-1">
                <label>Abierto</label>
                <div class="form-switch mt-2">
                    <input type="checkbox" class="form-check-input" wire:model="is_open2">
                </div>
            </div>
            <div class="col-span-12 xl:col-span-1">
                <label>Visible</label>
                <div class="form-switch mt-2">
                    <input type="checkbox" class="form-check-input" wire:model="visible2">
                </div>
            </div>
            <div class="col-span-12 xl:col-span-3 ">
                <label class="form-label">Fecha Inicial Matriculación</label>
                <x-date id="init_date_mat2" class="block border-gray-300 w-full fechablank" wire:model="init_date_mat2" />
            </div>
            <div class="col-span-12 xl:col-span-3 ">
                <label class="form-label">Fecha Final Matriculación</label>
                <x-date id="end_date_mat2" class="block border-gray-300 w-full fechablank" wire:model="end_date_mat2" />
            </div>
            <div class="col-span-12 xl:col-span-4">
                <label>Teacher</label>
                <select wire:model="teacher_id2" class="w-full form-select mt-2">
                    <option value="">Elija teacher</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->email }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-12 xl:col-span-3">
                <div class="">
                    <label class="form-label">Id Badge</label>
                    <input type="text" class="form-control w-full" placeholder="Id badge..." :value="old('badge_id2')" wire:model="badge_id2">
                </div>
            </div>
            <div class="col-span-12 xl:col-span-9">
                <div class="">
                    <label class="form-label">Badge imagen url</label>
                    <input type="text" class="form-control w-full" placeholder="Badge imagen url..." :value="old('badge_img_url2')" wire:model="badge_img_url2">
                </div>
            </div>
            {{-- <div class="col-span-4">
                <label>Tipo</label>
                <select wire:model="tipo2" class="w-full form-select mt-2">
                    @foreach ($posibles_tipos as $tipo)
                        <option value="{{ $tipo }}">{{ $tipo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-6">
                <label class="form-label">Url edición</label>
                <input type="text" class="form-control w-full" placeholder="Url edición..." :value="old('url_version2')" wire:model="url_version2" maxlength="255">
            </div> --}}
        </div>

        <div class="grid grid-cols-12 gap-6 p-2">
            <div class=" col-span-12">
                <div class="text-right mt-5">
                    <button type="button" class="btn btn-outline-secondary w-24 mr-1" wire:click="curso()">Volver</button>
                    <button type="button" class="btn btn-primary w-40 inline-flex" wire:click="updateEdition()">
                        Actualizar
                        <div wire:loading class="ml-3" wire:target="updateEdition">
                            <x-loading/>
                        </div>
                    </button>
                </div>
            </div>
        </div>

    @endif


    <!-- VENTANA CONFIRMACION DE BORRADO -->   
    <x-modaldialog tipo="error" titulo="Eliminación de Curso" :mensajeconfirmacion="$confirmingNameDelete" nombreaccion="deleteCurso" :idaccion="$confirmingIdDelete" textoaccion="Borrar" ventana="borrado"></x-modaldialog>
    <!-- FIN VENTANA CONFIRMACION DE BORRADO -->   

    <!-- VENTANA CONFIRMACION DE BORRADO EDICIÓN -->   
    <x-modaldialog tipo="error" titulo="Eliminación de Edición" :mensajeconfirmacion="$confirmingNameDelete2" nombreaccion="deleteEdicion" :idaccion="$confirmingIdDelete2" textoaccion="Borrar" ventana="borradoEdicion"></x-modaldialog>
    <!-- FIN VENTANA CONFIRMACION DE BORRADO EDICIÓN -->   

    <!-- VENTANA IMPOSIBLE ELIMINACION -->   
    <x-modal titulo="Imposible eliminación" :mensaje="$mensaje" ventana="imposible"></x-modal>
    <!-- FIN VENTANA IMPOSIBLE ELIMINACION -->  
    
    <!-- VENTANA MODAL ADD/EDIT -->   
    {{-- <x-modalEditEdition :titulo="$titulo" ventana="EditEdition"></x-modalEditEdition>  --}}
    <!-- FIN VENTANA MODAL ADD/EDIT -->  

</div>


