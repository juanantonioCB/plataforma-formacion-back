<div id="modal-EditEdition" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 ">
                    <div class="text-2xl mt-5 text-center">{{ $titulo }}</div>
                    <div class="grid grid-cols-12 gap-6 box p-5">
                        <div class="col-span-6">
                            <label class="form-label">Nombre edición</label>
                            <input type="text" class="form-control w-full" placeholder="Nombre edición..." :value="old('name_version2')" wire:model="name_version2" maxlength="255">
                        </div>
                        <div class="col-span-3">
                            <label class="form-label">Fecha Inicial</label>
                            <x-date id="init_date2" class="block border-gray-300 w-full fechablank" wire:model="init_date2" />
                        </div>
                        <div class="col-span-3">
                            <label class="form-label">Fecha Final</label>
                            <x-date id="end_date2" class="block border-gray-300 w-full fechablank" wire:model="end_date2" />
                        </div>
                    </div>
                </div>
                <div class="px-5 pb-8 text-center mt-8">
                    <button type="button" class="btn btn-outline-secondary w-24 mr-1" data-tw-dismiss="modal">Cancelar</button>
                    <button type="button" {{-- data-tw-dismiss="modal" --}} wire:click="guardarEditEdition()" class="btn btn-primary w-28">Guardar línea</button>
                </div>
            </div>
        </div>
    </div>
</div>