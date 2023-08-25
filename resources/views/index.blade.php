@extends('../layout/login2')

@section('head')
    <title>Administración Incibe Formación</title>
@endsection

@section('content')

<div class="container sm:px-10">
    <div class="grid gap-4">
        <!-- BEGIN: Login Form -->
        <div class="h-screen2 flex ">
            <div class="my-auto mx-auto bg-white  px-5 sm:px-8 py-8  rounded-md shadow-md  w-full ">
                <img src="{{ asset('imgs/incibe_logo_es.svg') }}" alt="" class="max-h-16 w-full">
                <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center mt-10 text-gris2">Acceso al Panel de Administración de Incibe Formación</h2>
                <div class="intro-x mt-2 text-slate-400 text-center flex flex-col ">
                    @if (!Auth()->user())
                        Para acceder al panel pulse en el botón Login
                    @else
                        <div class="flex justify-center mt-2">
                            @if (config('app.picture'))
                                <img alt="" class="rounded-full h-12" src="{{ config('app.picture')  }}">
                                {{-- <img alt="" class="rounded-full h-12" src="{{ asset('storage/imgs/persons/'.config('app.picture'))  }}"> --}}
                            @else
                                <img alt="" class="rounded-full h-12" src="{{ asset('imgs/perfil.png') }}">
                            @endif
                        </div>
                        <div class="mt-2 mb-5">
                            <b>{{ 'Bienvenido '.ucfirst(rol(Auth()->user()->email))/* Auth()->user()->name */ }}</b>
                        </div>
 
                        Para acceder al panel pulse en el botón Entrar
                    @endif
                </div>
                
                <div class="intro-x mt-5 xl:mt-8 text-center ">
                    @if (!Auth()->user())
                        <a href="{{ route('keycloak.login') }}" id="btn-login" class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Login</a>
                    @else
                        <a href="{{ route('dashboard') }}" id="btn-login" class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Entrar</a>
                        <a href="{{ route('keycloak.logout') }}" class="btn btn-outline-secondary py-3 px-4 w-full xl:w-32 mt-3 xl:mt-0 align-top">Logout</a>
                    @endif
                    {{-- <button class="btn btn-outline-secondary py-3 px-4 w-full xl:w-32 mt-3 xl:mt-0 align-top">Register</button> --}}
                </div>
                
            </div>
        </div>
        <!-- END: Login Form -->
    </div>
</div>

@endsection