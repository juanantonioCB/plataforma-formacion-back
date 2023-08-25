<!-- BEGIN: Top Bar -->
<div class="top-bar">
    <!-- BEGIN: Breadcrumb -->
    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Incibe</a></li>
            @if ($ruta1Texto??'')
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route($ruta1Link ?? '') }}" class="breadcrumb--active">{{ $ruta1Texto ?? '' }}</a></li>
            @endif
        </ol>
    </nav>
    <!-- END: Breadcrumb -->
    
    <!-- BEGIN: Account Menu -->
    <div class="intro-x dropdown w-8 h-8">
        <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in" role="button" aria-expanded="false" data-tw-toggle="dropdown">
            {{-- <img alt="" src="{{ asset('imgs/perfil.png') }}"> --}}
            {{-- {{ config('app.picture') }}
            <img alt="" class="rounded-full h-8" src="{{ asset('storage/imgs/persons/'.config('app.picture'))  }}"> --}}
            @if (config('app.picture'))
                <img alt="" class="rounded-full h-8" src="{{ config('app.picture')  }}">
            @else
                <img alt="" class="rounded-full h-8" src="{{ asset('imgs/perfil.png') }}">
            @endif
        </div>
        <div class="dropdown-menu w-56">
            <ul class="dropdown-content bg-primary text-white">
                <li class="p-2">
                    @if(auth()->check())
                        <div class="font-medium">{{ Auth::user()->email }}</div>
                    @endif
                    <div class="text-xs text-white/70 mt-0.5 dark:text-slate-500">{{ ucfirst(rol(Auth::user()->email)) }}</div>
                </li>
                <li><hr class="dropdown-divider border-white/[0.08]"></li>
               
                <li>
                    <a href="{{ route('keycloak.logout') }}" class="dropdown-item hover:bg-white/5">
                        <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: Account Menu -->
</div>
<!-- END: Top Bar -->
