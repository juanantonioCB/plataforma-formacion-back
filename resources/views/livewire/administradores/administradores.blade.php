<div>
    @if($view=='lista')

        <!--   TÍTULO   ------------------------------------------------>
        <div class="intro-y flex flex-col sm:flex-row items-center mt-4">
            <h2 class="text-lg font-medium mr-auto">Lista de Administradores</h2>
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
                @if ($search !=='' || ($perPage!=='10') || ($sortField!=='email'))
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
            @if ($administradores->count())
                <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-3">
                    <table class="table table-report -mt-2">
                        <thead>
                            <tr>
                                <th class="text-center whitespace-nowrap"></th>
                                <th class="text-center whitespace-nowrap"></th>
                                <th class="whitespace-nowrap cursor-pointer" wire:click="sortBy('email')" :direction="$sortField === 'email' ? $sortDirection : null">
                                    EMAIL
                                    <div class="float-right mr-3 mt-1"> @if ($sortField=='email') @include('layout.sort') @endif </div>
                                </th>
                                <th class="whitespace-nowrap cursor-pointer" wire:click="sortBy('superadmin')" :direction="$sortField === 'superadmin' ? $sortDirection : null">
                                    SUPER
                                    <div class="float-right mr-3 mt-1"> @if ($sortField=='superadmin') @include('layout.sort') @endif </div>
                                </th>
                                <th class="whitespace-nowrap cursor-pointer" wire:click="sortBy('nick')" :direction="$sortField === 'nick' ? $sortDirection : null">
                                    NICK
                                    <div class="float-right mr-3 mt-1"> @if ($sortField=='nick') @include('layout.sort') @endif </div>
                                </th>
                                <th class="whitespace-nowrap cursor-pointer" wire:click="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">
                                    NOMBRE
                                    <div class="float-right mr-3 mt-1"> @if ($sortField=='name') @include('layout.sort') @endif </div>
                                </th>
                                <th class="whitespace-nowrap cursor-pointer" wire:click="sortBy('surname')" :direction="$sortField === 'surname' ? $sortDirection : null">
                                    APELLIDO/S
                                    <div class="float-right mr-3 mt-1"> @if ($sortField=='surname') @include('layout.sort') @endif </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($administradores as $administrador)
                                <tr class=" ">
                                    <td class=" w-1/6">
                                        <div class="flex justify-center items-center">
                                            <button class="flex items-center mr-3 text-p_s_azul" wire:click="edit('{{ $administrador->id }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit w-4 h-4 mr-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Editar
                                            </button>
                                            @if (Auth()->user()->email !== $administrador->email)
                                                <button class="flex items-center text-p_p_rojo" wire:click="delete('{{ $administrador->id }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Borrar
                                                </button> 
                                            @endif
                                        </div>
                                    </td>
                                    <td class="  ">
                                        <div class="flex justify-center">
                                            @if ($administrador->avatar)
                                                <img alt="" class="rounded-full h-8" src="{{ $administrador->avatar }}">
                                            @else
                                                <img alt="" class="rounded-full h-8" src="{{ asset('imgs/perfil.png') }}">
                                            @endif
                                        </div>
                                    </td>
                                    <td class="">
                                        <button class="text-left w-full" {{-- wire:click="edit('{{ $curso->id }}')" --}}>
                                            {{ $administrador->email }}
                                        </button>
                                    </td>
                                    <td class="">
                                        <div class="form-switch mt-2">
                                            <input type="checkbox" class="form-check-input" {{ ($administrador->superadmin) ? 'checked' : '' }} disabled>
                                        </div>
                                    </td>
                                    <td class="">
                                        <button class="text-left w-full" {{-- wire:click="edit('{{ $curso->id }}')" --}}>
                                            {{ $administrador->nick }}
                                        </button>
                                    </td>
                                    <td class="">
                                        <button class="text-left w-full" {{-- wire:click="edit('{{ $curso->id }}')" --}}>
                                            {{ $administrador->name }}
                                        </button>
                                    </td>
                                    <td class="">
                                        <button class="text-left w-full" {{-- wire:click="edit('{{ $curso->id }}')" --}}>
                                            {{ $administrador->surname }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>  
                <div class="intro-y col-span-12 ">
                    {{ $administradores->links() }}
                </div>
            @else
                <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                    No hay resultados
                </div>
            @endif
        </div>

    @endif

    @if($view=='editar')
        <!--------------------- EDITAR CURSO -------------------------------------------->
        <div class="intro-y flex flex-col sm:flex-row items-center mt-4">
            @if ($administrador_id)
                <h2 class="text-lg font-medium mr-auto">Editar administrador: <span class="">{{ $nombreAdministrador }}</span></h2>
            @else
                <h2 class="text-lg font-medium mr-auto">Insertar administrador</h2>
            @endif
        </div>

        <div class="grid grid-cols-12 gap-6 mt-5 box p-5">
            <div class="col-span-12 xl:col-span-6">
                <div class="mt-4">
                    <label class="form-label">Email *</label>
                    <input type="text" class="form-control w-full" placeholder="Email..." :value="old('email')" wire:model="email" autofocus maxlength="255" {{ ($this->administrador_id) ? 'disabled' : '' }}>
                </div>
            </div>
            <div class="col-span-12 xl:col-span-6">
                <div class="mt-4">
                    <label>Superadmin</label>
                    <div class="form-switch mt-2">
                        <input type="checkbox" class="form-check-input" wire:model="superadmin" {{ ($this->administrador_id) ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>
            <div class="col-span-12 xl:col-span-6">
                <div class="mt-4">
                    <label class="form-label">Nick</label>
                    <input type="text" class="form-control w-full" placeholder="Nick..." :value="old('nick')" wire:model="nick" maxlength="255">
                </div>
            </div>
            <div class="col-span-12 xl:col-span-6">
                <div class="mt-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control w-full" placeholder="Nombre..." :value="old('name')" wire:model="name" maxlength="255">
                </div>
            </div>
            <div class="col-span-12 xl:col-span-6">
                <div class="mt-4">
                    <label class="form-label">Apellido/s</label>
                    <input type="text" class="form-control w-full" placeholder="Apellido/s..." :value="old('surname')" wire:model="surname" maxlength="255">
                </div>
            </div>
            <div class="col-span-12 mt-4">
                <div class="grid grid-cols-12 gap-4 mb-5">
                    <div class="col-span-12 sm:col-span-4 ">
                        <div class="text-right pr-2"><label>Imagen perfil actual</label></div>
                        <div class="flex justify-items-end">
                            <div class="relative ml-auto mt-2 pr-2">
                                @if (!empty($picture))
                                    <img alt="" class="rounded-md max-h-44" src="{{ $picture }}" title="imagen">
                                @else
                                    <img alt="" class="tooltip rounded-md max-h-44" src="{{ asset('imgs/perfil.png') }}" title="imagen">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4 flex items-center justify-items-center">
                        <div class="w-full mx-auto cursor-pointer relative mt-5 text-center">
                            <button class="btn btn-outline-primary border-dashed w-40">
                                Cambiar imagen
                                <div wire:loading class="ml-3" wire:target="picture_content">
                                    <x-loading/>
                                </div>
                            </button>
                            <input type="file" wire:model="picture_content" class="w-full h-full top-0 left-0 absolute opacity-0 cursor-pointer" id="picture_content{{$iteration}}">
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-4 pl-3">
                        @if ($picture_content)
                            <div class="mb-1"><label>Preview imagen perfil</label></div>
                            <img class="max h-44 rounded-md" class="mt-2" src="{{ $picture_content->temporaryUrl() }}">
                        @endif
                    </div>
                    <div class="col-span-12 text-p_s_azul">
                        Características idóneas para la imagen de perfil:<br/>
                        Formato cuadrado, máximo de anchura 800 px, tipo jpg o png optimizado en tamaño para no exceder de 300 Kbytes.
                    </div>
                </div>
            </div>
        </div>

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


    <!-- VENTANA CONFIRMACION DE BORRADO -->   
    <x-modaldialog tipo="error" titulo="Eliminación de Administrador" :mensajeconfirmacion="$confirmingNameDelete" nombreaccion="deleteAdministrador" :idaccion="$confirmingIdDelete" textoaccion="Borrar" ventana="borrado"></x-modaldialog>
    <!-- FIN VENTANA CONFIRMACION DE BORRADO -->   

</div>
