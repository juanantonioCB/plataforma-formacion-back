<div id="modal-{{ $ventana }}" class="modal modalDialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f97518" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle block mx-auto w-16 h-16 text-theme-12 mt-3"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>

                    <div class="text-3xl mt-5">{{ $titulo }}</div>
                    <div class="text-gray-600 mt-2">{{ $mensaje }}</div>
                </div>
                <div class="px-5 pb-8 text-center">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-primary w-28 dark:border-dark-5 dark:text-gray-300 ">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
</div>