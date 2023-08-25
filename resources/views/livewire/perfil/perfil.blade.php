<div>
    <div class="intro-y box p-5 mt-5">
        <div class="border border-slate-200/60 dark:border-darkmode-400 rounded-md p-5">
            <div class="font-medium text-base flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5">
                <i data-lucide="chevron-down" class="w-4 h-4 mr-2"></i> Perfil
            </div>
            <div class="grid grid-cols-12 gap-6 mt-5 p-5">
                <div class="col-span-12 xl:col-span-6">
                    <div class="mt-4">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control w-full" placeholder="Email..." :value="old('email')" wire:model="email" autofocus maxlength="255" disabled >
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
            <div class="flex justify-end flex-col md:flex-row gap-2 mt-5">
                <button type="button" class="btn py-3 btn-primary w-full md:w-52 inline-flex" wire:click="guardarPerfil()">
                    Guardar
                    <div wire:loading class="ml-3" wire:target="guardarPerfil">
                        <x-loading/>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
