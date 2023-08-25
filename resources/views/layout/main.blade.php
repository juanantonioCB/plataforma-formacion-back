@extends('../layout/base')

@section('body')
    <body class="py-5">
        @yield('content')
        {{-- @include('../layout/components/dark-mode-switcher')
        @include('../layout/components/main-color-switcher') --}}

        <!-- BEGIN: JS Assets-->
        {{-- <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script> --}}
        @vite('resources/js/app.js')
        
        <!-- END: JS Assets-->
        @livewireScripts
        @yield('script')

        <script src="{{ asset('js/jquery.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        
        <script>
            window.addEventListener('alert', event => { 
                toastr[event.detail.type](event.detail.message, event.detail.title ?? '') 
                toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        //"positionClass": "toast-bottom-right",
                    }
                });

            window.addEventListener('abrirModalDialog', event => {
                const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#modalDialog-" + event.detail.ventana));
                myModal.show(); 
            });

            window.addEventListener('abrirModal', event => {
                const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#modal-" + event.detail.ventana));
                myModal.show();
            });

            window.addEventListener('borrarFecha', event => {
                $("#" + event.detail.id).flatpickr().clear();
            });

            window.addEventListener('ponerFecha', event => {
                //$("#" + event.detail.id).flatpickr().localize('es'); 
                $("#" + event.detail.id).flatpickr().setDate(event.detail.valor);
            });

            window.addEventListener('cerrarModalEdition', event => {
                $("#modal-EditEdition").removeClass("show");
            });
        </script>
    </body>
@endsection
