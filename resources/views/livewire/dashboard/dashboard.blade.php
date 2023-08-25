<div>
    <div class="intro-y box p-5 mt-5">
        <div class="border border-slate-200/60 dark:border-darkmode-400 rounded-md p-5">
            <div class="font-medium text-base flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5">
                <i data-lucide="chevron-down" class="w-4 h-4 mr-2"></i> Cursos y ediciones
            </div>

            @if ( config('app.rol') == config('app.keycloak_role_superadmin_name') || config('app.rol') == config('app.keycloak_role_administrator_name') )
            
                @foreach ($cursos as $curso)
                    <div class="mb-3">
                        <div class="bg-p_p_rojo text-white py-3 px-3 rounded-md">
                            {{ $curso->name }}
                        </div>
                        <table class="table mt-2">
                            <thead class="table-light">
                                <tr>
                                    <th class="whitespace-nowrap">Edición</th>
                                    <th class="whitespace-nowrap">Nº Alumnos</th>
                                    <th class="whitespace-nowrap"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($curso->ediciones as $edicion)
                                    <tr>
                                        <td>{{ $edicion->name }}</td>
                                        <td>{{ $edicion->alumnos->count() }}</td>
                                        <td>
                                            @if ($edicion->is_open)
                                                <a href="{{ config('app.foro_base_url').'/'.$edicion->id.'/'.$curso->id }}" target="_blank" class="px-4 xl:px-6 py-2 xl:py-2 text-sm xl:text-md bg-p_s_azul text-white hover:bg-gris3 duration-300" >Ir al foro</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                @endforeach

            @endif

            @if ( config('app.rol') == config('app.keycloak_role_teacher_name') )
                @foreach ($cursos as $curso)
                    @if ($curso->ediciones->where('teacher_id',$teacher->id)->count()>0)
                    <div class="mb-3">
                        <div class="bg-p_p_rojo text-white py-3 px-3 rounded-md">
                            {{ $curso->name }}
                        </div>
                        <table class="table mt-2">
                            <thead class="table-light">
                                <tr>
                                    <th class="whitespace-nowrap">Edición</th>
                                    <th class="whitespace-nowrap">Nº Alumnos</th>
                                    <th class="whitespace-nowrap"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($curso->ediciones->where('teacher_id',$teacher->id) as $edicion)
                                    
                                    <tr>
                                        <td>{{ $edicion->name }}</td>
                                        <td>{{ $edicion->alumnos->count() }}</td>
                                        <td>
                                            @if ($edicion->is_open)
                                                <a href="{{ config('app.foro_base_url').'/'.$edicion->id.'/'.$curso->id }}" target="_blank" class="px-4 xl:px-6 py-2 xl:py-2 text-sm xl:text-md bg-p_s_azul text-white hover:bg-gris3 duration-300" >Ir al foro</a>
                                            @endif
                                        </td>
                                    </tr>
                                    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                @endforeach
            @endif


        </div>
    </div>
</div>
