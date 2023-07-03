<?php

namespace App\Http\Controllers\servicios;

use App\Http\Controllers\Controller;
use App\Models\catalogos\Departamento;
use App\Models\catalogos\Departamento2;
use App\Models\catalogos\Organo;
use App\Models\catalogos\Servicios;
use App\Models\catalogos\Unidad;
use App\Models\servicios\HistorialServicios;
use App\Models\servicios\Notificacion;
use App\Models\servicios\SolicitudServicio;
use App\Models\servicios\UrlArchivo;
use App\Models\User;
use Carbon\Carbon;
use Facade\FlareClient\View;
use Illuminate\Contracts\View\View as ViewView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View as IlluminateViewView;
use App\Models\catalogos\DepartamentoServicios;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexRecibidos()
    {
        //
        //bandeja de entrada recibidos
        $recibidos = [];
        $departamentosDirector = [];
        $director = false;


        if (Auth::user()->idRol == 3 || Auth::user()->idRol == 1) { //para jefes de departamentos
            $usuario = User::with('rol', 'departamento')->where([['estatus', 1], ['idUsuario', Auth::id()]])->first();
        } else {
            $usuario = User::with('rol', 'organo')->where([['estatus', 1], ['idUsuario', Auth::id()]])->first();
        }

        if ($usuario->rol->id == 2) { //si es director(ej: Mtro. Javier) obtiene toda las solicitudes enviadas de su organo (ej: unidad Ejecutiva)
            $director = true;
            $departamentosDirector = Departamento::select('departamento.id as idDepartamento','departamento.departamento','o.id as idOrgano','o.organo','u.idUsuario','u.name','u.email','u.idRol')
            ->join('organo as o','o.id','=','departamento.idOrgano')
            ->join('users as u','u.idOrganoDepartamento','=', 'o.id')
            ->where([['u.idRol',2], ['u.idUsuario', Auth::id()] ])
            ->get();

            //Departamento2::toBase()->where([['idparent', $unidadUsuario->idArea], ['estatus', 1]])->orWhere('id', $unidadUsuario->idArea)->get(); //obtiene las areas de la direccion

            foreach ($departamentosDirector as $departamento) {
                $recibidosAux = SolicitudServicio::select('solicitudes.id', 'solicitudes.descripcion as detallesServicio', 'ds.departamento as departamentoSolicitante', 'dr.departamento as departamentoReceptora', 'solicitudes.estatusSolicitud', 'solicitudes.visto', 'solicitudes.lector', 'solicitudes.estatus', 'solicitudes.fechaAlta')
                ->join('departamento as ds', 'ds.id', '=', 'solicitudes.idDepartamentoSolicitante')
                ->join('departamento as dr', 'dr.id', '=', 'solicitudes.idDepartamentoReceptora')
                ->join('servicios as s2', 's2.idServicio', '=', 'solicitudes.idServicio')
                ->where([['dr.id', $usuario->idOrganoDepartamento], ['solicitudes.estatus', '1']])->orderBy('solicitudes.fechaAlta', 'DESC')->get();
                // SolicitudServicio::toBase()->where([['idDepartamentoSolicitante',$departamento->idDepartamento], ['estatus',1]])->get(); //obtiene las solicitudes de cada departamento

                foreach ($recibidosAux as $recibido) { //itera cada solicitud de una area
                    if ($recibido->estatusSolicitud != 'Pendiente') {

                        $historial = HistorialServicios::toBase()->where('idSolicitud', $recibido->id)->orderBy('id', 'DESC')->first();
                        $recibido->detallesServicio = $recibido->detallesServicio . ' - ' . $historial->motivo; // obtiene detalles de cada solicitud
                    }
                }
                $departamento->recibidos = $recibidosAux; // agrega las solicitudes al array de cada area de la direccion
            }
            // dump($departamentosDirector->toArray());
        } else { //si es jefe (ej: Ing. Alejandro) obtiene solamente las solicitudes enviadas de su departamento (ej: area de informatica)
            $recibidos = SolicitudServicio::select('solicitudes.id', 'solicitudes.descripcion as detallesServicio', 'ds.departamento as departamentoSolicitante', 'dr.departamento as departamentoReceptora', 'solicitudes.estatusSolicitud', 'solicitudes.visto', 'solicitudes.lector', 'solicitudes.estatus', 'solicitudes.fechaAlta')
                ->join('departamento as ds', 'ds.id', '=', 'solicitudes.idDepartamentoSolicitante')
                ->join('departamento as dr', 'dr.id', '=', 'solicitudes.idDepartamentoReceptora')
                ->join('servicios as s2', 's2.idServicio', '=', 'solicitudes.idServicio')
                ->where([['dr.id', $usuario->idOrganoDepartamento], ['solicitudes.estatus', '1']])->orderBy('solicitudes.fechaAlta', 'DESC')->get();
            foreach ($recibidos as $recibido) {
                if ($recibido->estatusSolicitud != 'Pendiente') {
                    $historial = HistorialServicios::toBase()->where('idSolicitud', $recibido->id)->orderBy('id', 'DESC')->first();
                    // $municipios = Municipio::toBase()->where('estatus', '1')->where('idEstado', 30)->orderBy('nombreMunicipio', 'ASC')->get();
                    $recibido->detallesServicio = $recibido->detallesServicio . ' - ' . $historial->motivo;
                }
            }
        }

        /**
         * modificaciones por
         */
        $servicioByDepto = DepartamentoServicios::where('idDepartamento', $usuario->idOrganoDepartamento)
            ->join('servicios', 'servicios.idServicio', '=', 'departamentoservicios.idServicio')->get();

        return view('servicios.bandejaEntrada', compact('recibidos', 'usuario', 'director', 'departamentosDirector', 'servicioByDepto'));
    }


    public function indexEnviados()
    {
        $enviados = [];
        $departamentosDirector = [];
        $director = false;
        $usuario = User::with('rol')->where('idUsuario', Auth::id())->first();



        if ($usuario->rol->id == 2) { //si es director(ej: Mtro. Javier) obtiene toda las solicitudes enviadas de su organo (ej: unidad Ejecutiva)
            $director = true;
            $departamentosDirector = Departamento::select('departamento.id as idDepartamento','departamento.departamento','o.id as idOrgano','o.organo','u.idUsuario','u.name','u.email','u.idRol')
            ->join('organo as o','o.id','=','departamento.idOrgano')
            ->join('users as u','u.idOrganoDepartamento','=', 'o.id')
            ->where([['u.idRol',2], ['u.idUsuario', Auth::id()] ])
            ->get(); //obtiene los departamentos de la direccion

            foreach ($departamentosDirector as $departamento) {

                $enviadosAux = SolicitudServicio::select('solicitudes.id', 'solicitudes.descripcion as detallesServicio', 'ds.departamento as departamentoSolicitante', 'dr.departamento as departamentoReceptora', 'solicitudes.estatusSolicitud', 'solicitudes.visto', 'solicitudes.lector', 'solicitudes.estatus', 'solicitudes.fechaAlta')
                ->join('departamento as ds', 'ds.id', '=', 'solicitudes.idDepartamentoSolicitante')
                ->join('departamento as dr', 'dr.id', '=', 'solicitudes.idDepartamentoReceptora')
                ->join('servicios as s2', 's2.idServicio', '=', 'solicitudes.idServicio')
                ->where([['ds.id', $departamento->idDepartamento], ['solicitudes.estatus', '1']])->orderBy('solicitudes.fechaAlta', 'DESC')->get(); //obtiene las solicitudes de cada area
                $departamento->enviados = $enviadosAux; // agrega las solicitudes al array de cada area de la direccion
            }
        } else { //si es jefe (ej: Ing. Alejandro) obtiene solamente las solicitudes enviadas de su departamento (ej: area de informatica)
            $enviados = SolicitudServicio::select('solicitudes.id', 'solicitudes.descripcion as detallesServicio', 'ds.departamento as departamentoSolicitante', 'dr.departamento as departamentoReceptora', 'solicitudes.estatusSolicitud', 'solicitudes.visto', 'solicitudes.lector', 'solicitudes.estatus', 'solicitudes.fechaAlta')
                ->join('departamento as ds', 'ds.id', '=', 'solicitudes.idDepartamentoSolicitante')
                ->join('departamento as dr', 'dr.id', '=', 'solicitudes.idDepartamentoReceptora')
                ->join('servicios as s2', 's2.idServicio', '=', 'solicitudes.idServicio')
                ->where([['ds.id', $usuario->idOrganoDepartamento], ['solicitudes.estatus', '1']])->orderBy('solicitudes.fechaAlta', 'DESC')->get();
        }

        // dd($enviados->toArray());
        // dd($departamentosDirector->toArray());
        return view('servicios.enviados', compact('enviados', 'director', 'departamentosDirector'));
    }
    public function create()
    {
        $usuario = User::find(Auth::id())->first();

        $servicios = [];
        // $unidades = Unidad::toBase()->where('estatus', 1)->get();
        $organoAdm = Organo::where([['estatus', '1']])->get();
        // $servicios = [];Servicios::select('servicios.idServicio', 'servicios.descripcion', 'servicios.idDepartamento', 'departamento.departamento')
        //     ->join('departamento', 'departamento.id', '=', 'servicios.idDepartamento')
        //     ->where('departamento.id', '!=', $usuario->idOrganoDepartamento)
        //     ->get(); //obtiene todos los servicios por areas excepto la misma
        return view('servicios.crearServicio', compact('organoAdm', 'servicios', 'usuario'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $validExtensions = ['pdf', 'jpg', 'jpeg', 'xls', 'docx', 'xlsx'];
        $request->validate([
            'servicio' => 'required',
            'descripcion' => 'required',

        ]);

        DB::beginTransaction();
        try {
            $archivo = $request->file('archivo') ?? null;

            $destino = 'files/archivosservicios'; // el destino dónde se guardará el archivo

            $datosUsuario = User::toBase()->where([
                ['estatus', 1],
                ['idUsuario', Auth::id()]
            ])->first();


            $idDepartamentoSolicitante = $datosUsuario->idOrganoDepartamento;
            // dd($request->toArray(), $datosUsuario);

            $servicio = preg_split('#-#', $request->servicio)[0];
            $idDepartamentoReceptora = preg_split('#-#', $request->servicio)[1];


            $datosSolicitud =  SolicitudServicio::create([
                'idDepartamentoSolicitante' => $idDepartamentoSolicitante,
                'idServicio' => $servicio,
                'descripcion' => $request->descripcion,
                'idDepartamentoReceptora' => $idDepartamentoReceptora,
                'estatusSolicitud' => 'Pendiente', //Pendiente
                'visto' => 0,
                'lector' => 'receptor',
                'estatus' => 1,
                'idUsuarioAlta' => Auth::id(),
                'fechaUMod' => null
            ]);

            // parte de los archivos

            if ($archivo != null && !in_array($archivo->getClientOriginalExtension(), $validExtensions)) {
                return 'extension invalida';
            } else if ($archivo != null) {
                $nombre = $datosSolicitud->id.'_'.time().'.'.$archivo->extension();
            }

            if ($archivo != null) {
                $path = $archivo->storeAs($destino, $nombre, 'public');
                $archivo =  UrlArchivo::create([
                    'idSolicitud' => $datosSolicitud->id,
                    'tipoArchivo' => 'original',
                    'urlArchivo' => $path,
                    'nombreArchivo' => $nombre,
                    'estatus' => 1,
                    'idUsuarioAlta' => Auth::id(),
                    'fechaUMod' => null
                ]);
            }

            HistorialServicios::create([
                'idSolicitud' => $datosSolicitud->id,
                'idDepartamentoSolicitante' => $idDepartamentoSolicitante,
                'idServicio' => $servicio,
                'descripcion' => $request->descripcion,
                'idDepartamentoReceptora' => $idDepartamentoReceptora,
                'estatusSolicitud' => 'Pendiente', //Pendiente
                'visto' => 0,
                'estatus' => 1,
                'idUsuarioAlta' => Auth::id(),
                'fechaUMod' => null
            ]);

            $usuario = User::where('idOrganoDepartamento', $idDepartamentoReceptora)->first(); //usuario jefe de departamento al que se le envia la solicitud (Area atencion)

            if (count((array)$usuario) > 0) {
                # si hay una consulta se tiene que hacer todo la información

                $departamentoSolicitante = Departamento::with('organo')->where('id', $idDepartamentoSolicitante)->first(); //el que solicita
                $departamentoAtencion = Departamento::with('organo')->where('id', $usuario->idOrganoDepartamento)->first(); //el que atiende la solicitud

                $servicio = Servicios::where('idServicio', $servicio)->first()->descripcion;

                if ($datosUsuario->idRol == 3 || $datosUsuario->idRol == 1) { //si el usuario que esta solicitando el servicio es jefe de area (ejemplo: Ing. Alejandro, Unidad Inforrmatica)
                    $id_parentSolicitante = $departamentoSolicitante->id;
                } else {
                    $id_parentSolicitante = $departamentoSolicitante->organo->id;
                }


                if ($usuario->idRol == 3) {
                    $id_parentAtencion = $departamentoAtencion->id;
                } else {
                    $id_parentAtencion = $departamentoAtencion->organo->id;
                }

                $infoDirectorSolicitante = User::where('idOrganoDepartamento', $id_parentSolicitante)->first(); //info director organo de unidad solicitante
                $infoDirectorAtencion = User::where('idOrganoDepartamento', $id_parentAtencion)->first(); //info director organo de unidad de atencion

                // dd($infoDirectorAtencion->toArray(), $infoDirectorSolicitante->toArray());

                $contenido = [ //se envia 1 notificacion para jefe area solicitante, usuario atencion  y jere area atencion
                    [ //contenido para jefe de area atencion
                        'idusuario' => $usuario->idUsuario,
                        'usuario' => $usuario->name,
                        'areaAtencion' => $departamentoAtencion->area,
                        'titulo' => 'Tienes una nueva solicitud de ' . $departamentoSolicitante->area,
                        'contenido' =>  $servicio,
                        'idunidad' => $usuario->idDepartamento,
                        'notifiable' => true,

                    ],
                    [ //contenido para director area de atencion
                        'idusuario' => $infoDirectorAtencion->idUsuario,
                        'usuario' => $infoDirectorAtencion->name,
                        'areaAtencion' => $departamentoAtencion->area,
                        'titulo' => $departamentoAtencion->area .  ' Tiene una nueva solicitud de' . ' ' . $departamentoSolicitante->area,
                        'contenido' =>  $servicio,
                        'idunidad' => $departamentoAtencion->idparent,
                        'notifiable' => true,
                    ],
                    [ //contenido para director area solicitante
                        'idusuario' => $infoDirectorSolicitante->idUsuario,
                        'usuario' => $infoDirectorSolicitante->name,
                        'areaAtencion' => $departamentoAtencion->area,
                        'titulo' => $departamentoSolicitante->area . ' Envio una nueva solicitud a ' . $departamentoAtencion->area,
                        'contenido' =>  $servicio,
                        'idunidad' => $departamentoSolicitante->idparent,
                        'notifiable' => true,
                    ]
                ];
                // dd($contenido);
                if ($infoDirectorAtencion->idUsuario == $infoDirectorSolicitante->idUsuario) {
                    $contenido[1]['notifiable'] = false;
                }
                if (isset($usuario->idUsuario)) {
                    foreach ($contenido as $notificacion) {

                        $letter = [
                            'titulo' => $notificacion['titulo'],
                            'servicio' => $servicio,
                            'detalles' => $request->descripcion,
                            'url' => '/siata/detalles/' . $datosSolicitud->id,
                        ];
                        $letter = json_encode($letter);

                        Notificacion::create([
                            'idSolicitud' => $datosSolicitud->id,
                            'type' => 'App\Notifications\SupreNotification',
                            'notifiable_type' => 'App\Models\User',
                            'notifiable_id' => $notificacion['idusuario'],
                            'data' => $letter,
                            'created_at' => Carbon::now(),
                            'read_at' => null,
                            'updated_at' => null,
                            'read_movil' => false
                        ]);

                        if ($notificacion['notifiable']) {
                            $this->enviarNotificacion($notificacion['idusuario'], $notificacion['titulo'], $notificacion['contenido']); //se envia notificacion a jefe y/o directores  de area de atencion
                        }
                    }
                }

            } else {
                # si no se realiza una consulta completamente diferente

            }

            DB::commit();

            session(['message' => 'Se ha enviado la solicitud correctamente']);
            session(['alert' => 'alert-success']);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }
        return redirect()->route('enviados');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detalles($id)
    {


        DB::beginTransaction();
        try {
            DB::table('solicitudes')
                ->where('id', $id)
                ->update([
                    'visto' => 1,
                    'idUsuarioUMod' => Auth::id(),
                    'fechaUMod' => Carbon::now()
                ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);

            return redirect()->back();
        }

        if (Auth::user()->idRol == 3 || Auth::user()->idRol == 1) { //para jefes de departamentos
            $datosUsuario = User::with('departamento')->where([['estatus', 1], ['idUsuario', Auth::id()]])->first();
        } else {
            $datosUsuario = User::with('organo')->where([['estatus', 1], ['idUsuario', Auth::id()]])->first();
        }

        $files = UrlArchivo::toBase()->where('idSolicitud', $id)->get();
        // dd($files);
        $unidadUsuario =  $datosUsuario->departamento;
         // Departamento2::with('servicios')->where([['estatus', '1'], ['idparent', '!=', '1'], ['area', '!=', 'Direccion General']])->get();
        $detallesServicio = SolicitudServicio::select('solicitudes.id',
        'solicitudes.descripcion AS detallesServicio',
        'deptoSolicitante.departamento AS departamentoSolicitante',
        'deptoReceptor.departamento AS departamentoReceptor',
        'solicitudes.estatusSolicitud',
        'solicitudes.visto',
        'solicitudes.lector',
        'solicitudes.estatus',
        's2.descripcion as servicio',
        'solicitudes.fechaAlta as fechaAltaa')
        ->join('departamento as deptoSolicitante', 'solicitudes.idDepartamentoSolicitante', '=', 'deptoSolicitante.id')
        ->join('departamento as deptoReceptor', 'solicitudes.idDepartamentoReceptora', '=', 'deptoReceptor.id' )
        ->join('servicios as s2', 's2.idServicio', '=', 'solicitudes.idServicio')
        ->where([['solicitudes.id', $id], ['solicitudes.estatus', '1']])->first();
        /**
         * obtener el organo
         */
        // $unidades = Unidad::where('estatus', '1')->get();
        $organoAdm = Organo::where([['estatus', '1']])->get();

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = Carbon::parse($detallesServicio->fechaAltaa);
        $mes = $meses[($fecha->format('n')) - 1];
        $fechaEnviado = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

        $infoAdicionalSolicitud = HistorialServicios::toBase()->where('idSolicitud', $detallesServicio->id)->orderBy('id', 'DESC')->first();
        $infoAdicionalSolicitud->fechaAlta = date('d/M/Y ', strtotime($infoAdicionalSolicitud->fechaAlta)) . ' a las ' . date('H:i', strtotime($infoAdicionalSolicitud->fechaAlta));

        // dd($unidadUsuario,$detallesServicio);
        return view('servicios.responderServicio', compact('detallesServicio', 'infoAdicionalSolicitud', 'files', 'organoAdm', 'unidadUsuario', 'fechaEnviado'));
    }

    public function detalles2($id) //recibe id de la solicitud
    {


        if (Auth::user()->idRol == 3 || Auth::user()->idRol == 1) { //para jefes de departamentos
            $datosUsuario = User::with('departamento')->where([['estatus', 1], ['idUsuario', Auth::id()]])->first();
        } else {
            $datosUsuario = User::with('organo')->where([['estatus', 1], ['idUsuario', Auth::id()]])->first();
        }
        $unidadUsuario = $datosUsuario->departamento;
        $organoAdmin = Organo::where([['estatus', '1']])->get();
        $detallesServicio = SolicitudServicio::select(DB::raw('solicitudes.id,solicitudes.descripcion as detallesServicio,ds.departamento as departamentoSolicitante,dr.departamento as departamentoReceptor,solicitudes.estatusSolicitud,solicitudes.visto,solicitudes.lector,solicitudes.estatus,s2.descripcion as servicio,date_format(solicitudes.fechaAlta, "%D-%M-%Y") as fechaAltaa'))
            ->join('departamento as ds', 'ds.id', '=', 'solicitudes.idDepartamentoSolicitante')
            ->join('departamento as dr', 'dr.id', '=', 'solicitudes.idDepartamentoReceptora')
            ->join('servicios as s2', 's2.idServicio', '=', 'solicitudes.idServicio')
            ->where([['solicitudes.id', $id], ['solicitudes.estatus', '1']])->first();


        // dd($detallesServicio->toArray(), $unidadUsuario);
        $files = UrlArchivo::toBase()->where('idSolicitud', $id)->get();

        // obtener fecha del servicio
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = Carbon::parse($detallesServicio->fechaAltaa);
        $mes = $meses[($fecha->format('n')) - 1];
        $fechaEnvio = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');
        // agregado por Daniel Méndez 21-06-2023

        $infoAdicionalSolicitud = HistorialServicios::toBase()->where('idSolicitud', $detallesServicio->id)->orderBy('id', 'DESC')->first();
        $infoAdicionalSolicitud->fechaAlta = date('d/M/Y ', strtotime($infoAdicionalSolicitud->fechaAlta)) . ' a las ' . date('H:i', strtotime($infoAdicionalSolicitud->fechaAlta));

        if ($detallesServicio->idDepartamentoSolicitante == $datosUsuario->departamento->idOrganoDepartamento && $detallesServicio->estatusSolicitud != 'Pendiente') {
            DB::beginTransaction();
            try {
                DB::table('solicitudes')
                    ->where('id', $id)
                    ->update([
                        'visto' => 1,
                        'idUsuarioUMod' => Auth::id(),
                        'fechaUMod' => Carbon::now()
                    ]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                session(['message' => 'Algo salió mal intente nuevamente']);
                session(['alert' => 'alert-danger']);

                return redirect()->back();
            }
        }

        return view('servicios.detallesServicio', compact('detallesServicio', 'infoAdicionalSolicitud', 'files', 'organoAdmin', 'unidadUsuario', 'fechaEnvio'));
    }



    public function rechazarSolicitud(Request $request, $id)
    {

        DB::beginTransaction();

        try {

            // dd($request->toArray(), $datosUsuario);
            DB::table('solicitudes')
                ->where('id', $id)
                ->update([
                    'estatusSolicitud' => 'Rechazado',
                    'lector' => 'emisor',
                    'visto' => 0,
                    'idUsuarioUMod' => Auth::id(),
                    'fechaUMod' => Carbon::now()
                ]);
            $solicitud = SolicitudServicio::toBase()->where('id',$id)->first();
            $servicio = Servicios::toBase()->where('idServicio',$solicitud->idServicio)->first();
            $datosHistorial = HistorialServicios::create([
                'idSolicitud' => $id,
                'idServicio' => $servicio->idServicio,
                'descripcion' => '',
                'estatusSolicitud' => 'Rechazado',
                'motivo' => $request->motivoRechazo,
                'urlArchivo' => $request->oldArchivo,
                'estatus' => 1,
                'idUsuarioAlta' => Auth::id(),
                'fechaUMod' => null,
                'visto' => 0,
                'estatus' => 1,
                'idUsuarioAlta' => Auth::id(),
                'fechaUMod' => null
            ]);

            //jefe de departamento al que se le enviara la notificacion de solicitud rechazada
            $usuario = User::where('idOrganoDepartamento', $solicitud->idDepartamentoSolicitante)->first();

            $departamentoSolicitante = Departamento::with('organo')->where('id', $solicitud->idDepartamentoSolicitante)->first();
            $departamentoAtencion = Departamento::with('organo')->where('id', $solicitud->idDepartamentoReceptora)->first(); //el que atiende la soli

            // dd($departamentoSolicitante);

            $infoDirectorSolicitante = User::with('organo')->where([['idOrganoDepartamento', $departamentoSolicitante->organo->id],['idRol',2]])->first(); //info director de unidad solicitante
            $infoDirectorAtencion = User::with('organo')->where([['idOrganoDepartamento', $departamentoAtencion->organo->id],['idRol',2]])->first(); //info director de unidad de atencion


            $contenido = [
                [ //contenido para jefe de area
                    'idusuario' => $usuario->idUsuario,
                    'usuario' => $usuario->name,
                    'areaatencion' => $departamentoAtencion->area,
                    'titulo' => $departamentoAtencion->area . ' Ha rechazado tu solicitud ',
                    // 'Tienes una nueva solicitud de ' . $departamentoSolicitante->area,
                    'contenido' =>  $servicio->descripcion,
                    'idunidad' => $usuario->idDepartamento,
                    'notifiable' => true,

                ],
                [ //contenido para director area de atencion
                    'idusuario' => $infoDirectorAtencion->idUsuario,
                    'usuario' => $infoDirectorAtencion->name,
                    'areaatencion' => $departamentoAtencion->area,
                    'titulo' => $departamentoAtencion->area .  ' Ha rechazado la nueva solicitud de' . ' ' . $departamentoSolicitante->area,
                    'contenido' =>  $servicio->descripcion,
                    'idunidad' => $departamentoAtencion->idparent,
                    'notifiable' => true,
                ],
                [ //contenido para director area solicitante
                    'idusuario' => $infoDirectorSolicitante->idUsuario,
                    'usuario' => $infoDirectorSolicitante->name,
                    'areaatencion' => $departamentoAtencion->area,
                    'titulo' => $departamentoAtencion->area . ' Rechazo la solicitud de ' . $departamentoSolicitante->area,
                    'contenido' =>  $servicio->descripcion,
                    'idunidad' => $departamentoSolicitante->idparent,
                    'notifiable' => true,
                ]
            ];

            if ($infoDirectorAtencion->idUsuario == $infoDirectorSolicitante->idUsuario) {
                $contenido[1]['notifiable'] = false;
            }
            // dd($contenido);
            if (isset($usuario->idUsuario)) {
                foreach ($contenido as $notificacion) {

                    $letter = [
                        'titulo' => $notificacion['titulo'],
                        'servicio' => $servicio,
                        'detalles' => $request->motivoRechazo,
                        'url' => '/siata/detalles/' . $id,
                    ];
                    $letter = json_encode($letter);

                    Notificacion::create([
                        'idSolicitud' => $id,
                        'type' => 'App\Notifications\SupreNotification',
                        'notifiable_type' => 'App\Models\User',
                        'notifiable_id' => $notificacion['idusuario'],
                        'data' => $letter,
                        'created_at' => Carbon::now(),
                        'read_at' => null,
                        'updated_at' => null,
                        'read_movil' => false
                    ]);

                    if ($notificacion['notifiable']) {
                        $this->enviarNotificacion($notificacion['idusuario'], $notificacion['titulo'], $notificacion['contenido']); //se envia notificacion a jefe y/o directores  de area de atencion
                    }
                }
            }


            // dd($contenido);



            DB::commit();
            session(['message' => 'Se ha enviado la respuesta correctamente']);
            session(['alert' => 'alert-success']);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }

        return response()->json(['url' => url('/recibidos')]);
    }
    public function aceptarSolicitud(Request $request, $id)
    {

        DB::beginTransaction();
        try {
            // checamos las extensiones validas - 22 Junio 2023 DMC
            $extensionesValidas = ['pdf', 'jpg', 'jpeg', 'xls', 'docx'];
            /**
             * primeramente vamos a checar que haya un archivo nuevo
             */
            if ($request->hasFile('archivoValidar')) {
                #si es verdadero
                # si existe un archivo empezamos a iterar en este momento
                $archivo = $request->file('archivoValidar');
                if (in_array($archivo->extension(), $extensionesValidas)) {
                    # si no se encuentra en el arreglo pasamos la condicional...
                    $nombreArchivo = $id.'_'.time().'.'.$archivo->extension();
                    $filePath = $archivo->storeAs('atencion', $nombreArchivo, 'public'); // hacemos el movimiento de guardar el archivo
                    // cargando archivo en la base de datos
                    UrlArchivo::create([
                        'idSolicitud' => $id,
                        'tipoArchivo' => 'atendido',
                        'urlArchivo' => $filePath,
                        'nombreArchivo' => $nombreArchivo,
                        'estatus' => 1,
                        'idUsuarioAlta' => Auth::id(),
                        'fechaUMod' => null
                    ]);

                } else {
                    #no se encuentra  en la extensión.
                    $wrongArray = [
                        'success' => false,
                        'message' => 'No se pudo cargar el archivo intentalo nuevamente',
                        'data' => 'ERROR'
                    ];
                    return response()->json($wrongArray, 500);
                }
            }

            DB::table('solicitudes')
                ->where('id', $id)
                ->update([
                    'estatusSolicitud' => 'Atendido', //Atendido
                    'lector' => 'emisor',
                    'idUsuarioUMod' => Auth::id(),
                    'fechaUMod' => Carbon::now()
                ]);




            //jefe de departamento al que se le enviara la notificacion de solicitud aceptada
            $solicitud = SolicitudServicio::where('id', $id)->first();
            $servicio = Servicios::where('idServicio', $solicitud->idServicio)->first();


            $usuario = User::where('idOrganoDepartamento', $solicitud->idDepartamentoSolicitante)->first();

            $departamentoSolicitante = Departamento::with('organo')->where('id', $solicitud->idDepartamentoSolicitante)->first();
            $departamentoAtencion = Departamento::with('organo')->where('id', $solicitud->idDepartamentoReceptora)->first();


            $infoDirectorSolicitante = User::with('organo')->where([['idOrganoDepartamento', $departamentoSolicitante->organo->id],['idRol',2]])->first(); //info director de unidad solicitante
            $infoDirectorAtencion = User::with('organo')->where([['idOrganoDepartamento', $departamentoAtencion->organo->id],['idRol',2]])->first(); //info director de unidad de atencion
            // dump($infoDirectorSolicitante->toArray(), $infoDirectorAtencion->toArray());

            $datosHistorial = HistorialServicios::create([
                'idSolicitud' => $id,
                'idServicio' => $servicio->idServicio,
                'descripcion' => '',
                'estatusSolicitud' => 'Atendido', //Atendido
                'motivo' => $request->descripcion ?? null,
                'estatus' => 1,
                'idUsuarioAlta' => Auth::id(),
                'fechaUMod' => null
            ]);
            $contenido = [
                [ //contenido para jefe de area
                    'idusuario' => $usuario->idUsuario,
                    'usuario' => $usuario->name,
                    'areaatencion' => $departamentoAtencion->area,
                    'titulo' => $departamentoAtencion->area . ' Ha atendido tu solicitud ',
                    // 'Tienes una nueva solicitud de ' . $departamentoSolicitante->area,
                    'contenido' =>  $servicio,
                    'idunidad' => $usuario->idDepartamento,
                    'notifiable' => true,

                ],
                [ //contenido para director area de atencion
                    'idusuario' => $infoDirectorAtencion->idUsuario,
                    'usuario' => $infoDirectorAtencion->name,
                    'areaatencion' => $departamentoAtencion->area,
                    'titulo' => $departamentoAtencion->area .  ' Ha atendido la nueva solicitud de' . ' ' . $departamentoSolicitante->area,
                    'contenido' =>  $servicio,
                    'idunidad' => $departamentoAtencion->idparent,
                    'notifiable' => true,
                ],
                [ //contenido para director area solicitante
                    'idusuario' => $infoDirectorSolicitante->idUsuario,
                    'usuario' => $infoDirectorSolicitante->name,
                    'areaatencion' => $departamentoAtencion->area,
                    'titulo' => $departamentoAtencion->area . ' Atendio la solicitud de ' . $departamentoSolicitante->area,
                    'contenido' =>  $servicio,
                    'idunidad' => $departamentoSolicitante->idparent,
                    'notifiable' => true,
                ]
            ];
            if ($infoDirectorAtencion->idUsuario == $infoDirectorSolicitante->idUsuario) {
                $contenido[1]['notifiable'] = false;
            }
            // dd($contenido);
            if (isset($usuario->idUsuario)) {
                foreach ($contenido as $notificacion) {

                    $letter = [
                        'titulo' => $notificacion['titulo'],
                        'servicio' => $servicio,
                        'detalles' => $request->descripcion ?? '',
                        'url' => '/siata/detalles/' . $id,
                    ];
                    $letter = json_encode($letter);

                    Notificacion::create([
                        'idSolicitud' => $id,
                        'type' => 'App\Notifications\SupreNotification',
                        'notifiable_type' => 'App\Models\User',
                        'notifiable_id' => $notificacion['idusuario'],
                        'data' => $letter,
                        'created_at' => Carbon::now(),
                        'read_at' => null,
                        'updated_at' => null,
                        'read_movil' => false
                    ]);

                    if ($notificacion['notifiable']) {
                        $this->enviarNotificacion($notificacion['idusuario'], $notificacion['titulo'], $notificacion['contenido']); //se envia notificacion a jefe y/o directores  de area de atencion
                    }
                }
            }


            DB::commit();
            session(['message' => 'Se ha enviado la respuesta correctamente']);
            session(['alert' => 'alert-success']);
            // enviar respuesta al ajax desde el servidor
            $doneArray = [
                'success' => true,
                'message' => 'Se ha dado atención a la solicitud correctamente',
                'data' => 'OK',
                'url' => url('/recibidos')
            ];
            return response()->json($doneArray, 200);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }

        // return response()->json(['url' => url('/recibidos')]);
    }


    public function corregirSolicitud(Request $request, $id)
    {


        $validExtensions = ['pdf', 'jpg', 'jpeg', 'xls', 'xlsx', 'docx'];

        DB::beginTransaction();
        try {
            $archivo = $request->file('archivoNuevo');

            if ($archivo != null && !in_array($archivo->getClientOriginalExtension(), $validExtensions)) {
                return 'extension invalida';
            } else if ($archivo != null) {
                $nombreArchivo = $id.'_'.time().'.'.$archivo->extension();
            }

            $destino = 'files/archivosservicios';

            $datosUsuario = User::with('departamento')->where([
                ['estatus', 1],
                ['idUsuario', Auth::id()]
            ])->get()->toArray();

            // dd($request->toArray(), $datosUsuario);

            $idServicio = preg_split('#-#', $request->servicio)[0];
            $idDepartamentoReceptora = preg_split('#-#', $request->servicio)[1];

            $datosSolicitud = DB::table('solicitudes')
                ->where('id', $id)
                ->update([
                    'idServicio' => $idServicio,
                    'descripcion' => $request->descripcion,
                    'idDepartamentoReceptora' => $idDepartamentoReceptora,
                    'estatusSolicitud' => 'Pendiente', //Pendiente
                    'lector' => 'receptor',
                    'visto' => 0,
                    'idUsuarioUMod' => Auth::id(),
                    'fechaUMod' => Carbon::now()
                ]);

            if ($archivo != null) {

                $archivoTemp = UrlArchivo::where('idSolicitud', $id)->first();
                if (isset($archivoTemp->id)) {
                    DB::table('archivos')
                        ->where([['idSolicitud', $id], ['tipoArchivo', 'original']])
                        ->update([
                            'urlArchivo' => $archivo->storeAs($destino, $nombreArchivo, 'public'),
                            'nombreArchivo' => $nombreArchivo,
                            'idUsuarioUMod' => Auth::id(),
                            'fechaUMod' => Carbon::now()
                        ]);
                } else {
                    $archivo =  UrlArchivo::create([
                        'idSolicitud' => $id,
                        'tipoArchivo' => 'original',
                        'urlArchivo' => $archivo->storeAs($destino, $nombreArchivo, 'public'),
                        'nombreArchivo' => $nombreArchivo,
                        'estatus' => 1,
                        'idUsuarioAlta' => Auth::id(),
                        'fechaUMod' => null
                    ]);
                    // DB::table('assign_batches')->insert($data);
                }
            }


            $datosHistorial = HistorialServicios::create([
                'idSolicitud' => $id,
                'idServicio' => $idServicio,
                'descripcion' => $request->descripcion,
                'estatusSolicitud' => 'Pendiente', //Pendiente
                'urlAntiguoArchivo' => $request->oldArchivo,
                'estatus' => 1,
                'idUsuarioAlta' => Auth::id(),
                'fechaUMod' => null
            ]);


            $solicitud = SolicitudServicio::where('id', $id)->first();
            // dd($solicitud);
            $servicio = Servicios::where('idServicio', $solicitud->idServicio)->first()->descripcion;

            $usuario = User::where('idOrganoDepartamento', $solicitud->idDepartamentoReceptora)->first();

            $departamentoSolicitante = Departamento::with('organo')->where('id', $solicitud->idDepartamentoSolicitante)->first();
            $departamentoAtencion = Departamento::with('organo')->where('id', $solicitud->idDepartamentoReceptora)->first();
            // dd($departamentoSolicitante->toArray(), $departamentoAtencion->toArray());

            // checar si hay o no un usuario si hay trabajamos lo siguiente dentro
            if (count((array)$usuario) > 0) {
                # se ejecuta si hay un usuario seleccionado
                $infoDirectorSolicitante = User::with('organo')->where([['idOrganoDepartamento', $departamentoSolicitante->organo->id],['idRol',2]])->first(); //info director de unidad solicitante
                $infoDirectorAtencion = User::with('organo')->where([['idOrganoDepartamento', $departamentoAtencion->organo->id],['idRol',2]])->first(); //info director de unidad de atencion

                $contenido = [
                    [ //contenido para jefe de area atencion
                        'idusuario' => $usuario->idUsuario,
                        'usuario' => $usuario->name,
                        'areaatencion' => $departamentoAtencion->area,
                        'titulo' => $departamentoSolicitante->area . ' Le ha enviado una version corregida de la solicitud ',
                        // 'Tienes una nueva solicitud de ' . $departamentoSolicitante->area,
                        'contenido' =>  $servicio,
                        'idunidad' => $usuario->idDepartamento,
                        'notifiable' => true,

                    ],
                    [ //contenido para director area de atencion
                        'idusuario' => $infoDirectorAtencion->idUsuario,
                        'usuario' => $infoDirectorAtencion->name,
                        'areaatencion' => $departamentoAtencion->area,
                        'titulo' => $departamentoSolicitante->area .  ' Ha enviado una correccion de la solicitud a' . ' ' . $departamentoAtencion->area,
                        'contenido' =>  $servicio,
                        'idunidad' => $departamentoAtencion->idparent,
                        'notifiable' => true,
                    ],
                    [ //contenido para director area solicitante
                        'idusuario' => $infoDirectorSolicitante->idUsuario,
                        'usuario' => $infoDirectorSolicitante->name,
                        'areaatencion' => $departamentoAtencion->area,
                        'titulo' => $departamentoSolicitante->area . ' Envio una correccion de la solicitud a ' . $departamentoAtencion->area,
                        'contenido' =>  $servicio,
                        'idunidad' => $departamentoSolicitante->idparent,
                        'notifiable' => true,
                    ]
                ];

                if ($infoDirectorAtencion->idUsuario == $infoDirectorSolicitante->idUsuario) {
                    $contenido[1]['notifiable'] = false;
                }
                // dd($contenido);
                if (isset($usuario->idUsuario)) {
                    foreach ($contenido as $notificacion) {

                        $letter = [
                            'titulo' => $notificacion['titulo'],
                            'servicio' => $servicio,
                            'detalles' =>  $request->descripcion,
                            'url' => '/siata/detalles/' . $id,
                        ];
                        $letter = json_encode($letter);

                        Notificacion::create([
                            'idSolicitud' => $id,
                            'type' => 'App\Notifications\SupreNotification',
                            'notifiable_type' => 'App\Models\User',
                            'notifiable_id' => $notificacion['idusuario'],
                            'data' => $letter,
                            'created_at' => Carbon::now(),
                            'read_at' => null,
                            'updated_at' => null,
                            'read_movil' => false
                        ]);

                        if ($notificacion['notifiable']) {
                            $this->enviarNotificacion($notificacion['idusuario'], $notificacion['titulo'], $notificacion['contenido']); //se envia notificacion a jefe y/o directores  de area de atencion
                        }
                    }
                }
            }

            DB::commit();
            session(['message' => 'Se ha enviado la correccion correctamente']);
            session(['alert' => 'alert-success']);

            return redirect()->route('enviados');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }
    }


    public function transferirSolicitud(Request $request, $id)
    {

        $request->validate([
            'descripcionTransferencia' => 'required'
        ]);


        $idServicio = $request->servicios;
        $newIdDepartamentoReceptora = $request->deptos;
        // $idDepartamentoReceptora = preg_split('#-#', $request->servicio)[1];
        DB::beginTransaction();

        try {
            $validExtensions = ['pdf', 'jpg', 'jpeg', 'xls', 'xlsx', 'docx'];
            // $destino = 'files/archivosservicios';
            $idAntiguoDepartamentoAtencion = DB::table('solicitudes')->where('id', $id)->first()->idDepartamentoReceptora;
            DB::table('solicitudes')
                ->where('id', $id)
                ->update([
                    'idServicio' => $idServicio,
                    'idDepartamentoReceptora' => $newIdDepartamentoReceptora,
                    'estatusSolicitud' => 'Turnado',
                    'lector' => 'receptor',
                    'visto' => 0,
                    'idUsuarioUMod' => Auth::id(),
                    'fechaUMod' => Carbon::now()
                ]);

            /**
             * primeramente vamos a checar que haya un archivo nuevo
             */
            if ($request->hasFile('archivoReturnar')) {
                #si es verdadero
                # si existe un archivo empezamos a iterar en este momento
                $archivoReturnar = $request->file('archivoReturnar');
                if (in_array($archivoReturnar->extension(), $validExtensions)) {
                    # si no se encuentra en el arreglo pasamos la condicional...
                    $fileName = $id.'_'.time().'.'.$archivoReturnar->extension();
                    $filePath = $archivoReturnar->storeAs('transferir', $fileName, 'public'); // hacemos el movimiento de guardar el archivo
                    // cargando archivo en la base de datos
                    UrlArchivo::create([
                        'idSolicitud' => $id,
                        'tipoArchivo' => 'atendido',
                        'urlArchivo' => $filePath,
                        'nombreArchivo' => $fileName,
                        'estatus' => 1,
                        'idUsuarioAlta' => Auth::id(),
                        'fechaUMod' => null
                    ]);

                } else {
                    #no se encuentra  en la extensión.
                    $wrongArray = [
                        'success' => false,
                        'message' => 'No se pudo cargar el archivo intentalo nuevamente',
                        'data' => 'ERROR'
                    ];
                    return response()->json($wrongArray, 500);
                }
            }


            HistorialServicios::create([
                'idSolicitud' => $id,
                'idServicio' => $idServicio,
                'idDepartamentoReceptora' => $newIdDepartamentoReceptora,
                'estatusSolicitud' => 'Turnado',
                'estatus' => 1,
                'idUsuarioAlta' => Auth::id(),
                'motivo' => $request->descripcionTransferencia,
                'descripcion' => ''
            ]);
            // $his->motivo = $request->descripcionTransferencia . '' ?? 'N/A'; //no guarda el campo motivo en el create :( AIUDA NO SE POR QUE, AQUI SI FUNCIONA
            // $his->save();


            $solicitud = SolicitudServicio::where('id', $id)->first();
            $servicio = Servicios::where('idServicio', $solicitud->idServicio)->first()->descripcion;

            //jefe de departamento al que se le enviara la notificacion de solicitud transferida
            $usuario = User::where([['idOrganoDepartamento', $newIdDepartamentoReceptora],['idRol','3']])->first();

            $departamentoSolicitante =Departamento::with('organo')->where('id', $solicitud->idDepartamentoSolicitante)->first();
            $departamentoAtencion =Departamento::with('organo')->where('id', $idAntiguoDepartamentoAtencion)->first();
            $departamentoAtencionTurnado = Departamento::with('organo')->where('id', $newIdDepartamentoReceptora)->first();

            // checamos si la consulta arroja algo si no trae algo tenemos que realizar una modificación
            if (count((array)$usuario) > 0) {
                # si hay registros tenemos información

                $usuarioTransferido = User::where([['idOrganoDepartamento', $departamentoAtencionTurnado->id],['idRol','3']])->first();
                $infoDirectorSolicitante = User::with('organo')->where([['idOrganoDepartamento', $departamentoSolicitante->organo->id],['idRol',2]])->first(); //info director de unidad solicitante
                $infoDirectorAtencion = User::with('organo')->where([['idOrganoDepartamento', $departamentoAtencion->organo->id],['idRol',2]])->first(); //info director de unidad de atencion
                $infoDirectorSolicitudTurnado = User::with('organo')->where([['idOrganoDepartamento', $departamentoAtencion->organo->id],['idRol',2]])->first(); //info director de unidad de atencion
                $infoDirectorAtencionTurnado = User::with('organo')->where([['idOrganoDepartamento', $departamentoAtencionTurnado->organo->id],['idRol',2]])->first(); //info director de unidad de atencion

                $contenido = [
                    [ //contenido para jefe de area solicitante
                        'idusuario' => $usuario->idUsuario,
                        'usuario' => $usuario->name,
                        'areaatencion' => $departamentoAtencion->area,
                        'titulo' => $departamentoAtencion->area . ' Ha turnado tu solicitud al area ' .  $departamentoAtencionTurnado->area,
                        'contenido' =>  $servicio,
                        'idunidad' => $usuario->idDepartamento,
                        'notifiable' => true,

                    ],
                    [ //contenido para jefe de area transferida
                        'idusuario' => $usuarioTransferido->idUsuario,
                        'usuario' => $usuarioTransferido->name,
                        'areaatencion' => $departamentoAtencion->area,
                        'titulo' => $departamentoAtencion->area . ' Te ha turnado una solicitud del area ' . $departamentoSolicitante->area,
                        'contenido' =>  $servicio,
                        'idunidad' => $usuarioTransferido->idDepartamento,
                        'notifiable' => true,

                    ],
                    [ //contenido para director area de atencion
                        'idusuario' => $infoDirectorAtencion->idUsuario,
                        'usuario' => $infoDirectorAtencion->name,
                        'areaatencion' => $departamentoAtencion->area,
                        'titulo' => $departamentoAtencion->area .  ' Ha turnado la  solicitud de' . ' ' . $departamentoSolicitante->area . ' al area de ' . $departamentoAtencionTurnado->area,
                        'contenido' =>  $servicio,
                        'idunidad' => $departamentoAtencion->idparent,
                        'notifiable' => true,
                    ],
                    [ //contenido para director area solicitante
                        'idusuario' => $infoDirectorSolicitante->idUsuario,
                        'usuario' => $infoDirectorSolicitante->name,
                        'areaatencion' => $departamentoAtencion->area,
                        'titulo' => $departamentoAtencion->area . ' Turnó la solicitud de ' . $departamentoSolicitante->area . ' al area de ' . $departamentoAtencionTurnado->area,
                        'contenido' =>  $servicio,
                        'idunidad' => $departamentoSolicitante->idparent,
                        'notifiable' => true,
                    ],
                    [ //contenido para director area de atencion transferida
                        'idusuario' => $infoDirectorSolicitudTurnado->idUsuario,
                        'usuario' => $infoDirectorSolicitudTurnado->name,
                        'areaatencion' => $departamentoAtencionTurnado->area,
                        'titulo' => $departamentoAtencion->area .  ' Ha turnado una solicitud del area' . ' ' . $departamentoSolicitante->area . ' al area de ' . $departamentoAtencionTurnado->area,
                        'contenido' =>  $servicio,
                        'idunidad' => $departamentoAtencionTurnado->idparent,
                        'notifiable' => true,
                    ],
                    [ //contenido para director area de atencion transferida
                        'idusuario' => $infoDirectorAtencionTurnado->idUsuario,
                        'usuario' => $infoDirectorAtencionTurnado->name,
                        'areaatencion' => $departamentoAtencionTurnado->area,
                        'titulo' => $departamentoAtencion->area .  ' Ha turnado una solicitud del area' . ' ' . $departamentoSolicitante->area . ' al area de ' . $departamentoAtencionTurnado->area,
                        'contenido' =>  $servicio,
                        'idunidad' => $departamentoAtencionTurnado->idparent,
                        'notifiable' => true,
                    ],

                ];

                if ($infoDirectorAtencion->idUsuario == $infoDirectorSolicitante->idUsuario) {
                    $contenido[2]['notifiable'] = false;
                }

                if (isset($usuario->idUsuario)) {
                    foreach ($contenido as $notificacion) {

                        $letter = [
                            'titulo' => $notificacion['titulo'],
                            'servicio' => $servicio,
                            'detalles' => $request->descripcionTransferencia . '' ?? 'N/A',
                            'url' => '/siata/detalles/' . $id,
                        ];
                        $letter = json_encode($letter);

                        Notificacion::create([
                            'idSolicitud' => $id,
                            'type' => 'App\Notifications\SupreNotification',
                            'notifiable_type' => 'App\Models\User',
                            'notifiable_id' => $notificacion['idusuario'],
                            'data' => $letter,
                            'created_at' => Carbon::now(),
                            'read_at' => null,
                            'updated_at' => null,
                            'read_movil' => false
                        ]);

                        if ($notificacion['notifiable']) {
                            $this->enviarNotificacion($notificacion['idusuario'], $notificacion['titulo'], $notificacion['contenido']); //se envia notificacion a jefe y/o directores  de area de atencion
                        }
                    }
                }

            } else {
                // si no hay registros
            }

            DB::commit();

            session(['message' => 'Se ha enviado la respuesta correctamente']);
            session(['alert' => 'alert-success']);

            $doneArray = [
                'success' => true,
                'message' => 'Se ha dado atención a la solicitud correctamente',
                'data' => 'OK',
                'url' => url('/recibidos')
            ];
            return response()->json($doneArray, 200);

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }

        // return redirect()->route('bandejaEntrada');
    }





    public function descargarArchivo($id)
    {

        $file = UrlArchivo::toBase()->where('id', $id)->get()[0];

        $path = '../public/' . $file->urlArchivo;


        return Response()->download($path);
    }


    public function verArchivo($id)
    {

        $file = UrlArchivo::toBase()->where('id', $id)->get()[0];
        $file = $file->urlArchivo;

        return response()->file($file);
    }

    public function infoDepartamento($id)
    {
        $departamento = Departamento::where('idDepartamento', $id)->get();

        return response()->json($departamento);
    }


    public function enviarNotificacion($destinatario, $titulo, $cuerpo)
    {
        $user = User::where('idUsuario', $destinatario)->first();
        $token = [$user->token_movil];

        // dd($token, $titulo, $cuerpo);
        sendNotification($token, $titulo, $cuerpo);
        return response()->json('notificacion enviada');
    }



    public function mergeArray($registro) //metodo para hacer un arreglo de n dimensiones en 1 (arreglo de arreglos en arreglo)
    {
        $reporte = [];
        foreach ($registro as $r) {
            foreach ($r as $item) {
                array_push($reporte, $item);
            }
        }
        return $reporte;
    }

    public function filtrar(Request $request)
    {
        // return response()->json($request);

        $recibidos = DB::select('CALL `listaRecibidos`(?,?,?)', array('filtrar', $request->id, $request->filtro)); //obtie
        // dd($recibidos);


        $recibidos = [];
        $unidadesDirector = [];
        $director = false;
        $unidadUsuario = DB::select('CALL `unidadUsuario`(?)', array(Auth::id()))[0];


        if ($unidadUsuario->idDireccion == 1) {
            $director = true;
            $unidadesDirector = Departamento2::toBase()->where([['idparent', $unidadUsuario->idArea], ['estatus', 1]])->orWhere('id', $unidadUsuario->idArea)->get(); //obtiene las areas de la direccion
            // dump($unidadesDirector);
            foreach ($unidadesDirector as $unidad) {
                $recibidosAux = DB::select('CALL `listaRecibidos`(?,?,?)', array('filtrar', $unidad->id, $request->filtro)); //obtie
                // dump('$recibidosAux -> '.$request->id);
                // dump($recibidosAux);


                foreach ($recibidosAux as $recibido) { //itera cada solicitud de una area
                    if ($recibido->estatusSolicitud != 'Pendiente') {
                        $historial = HistorialServicios::toBase()->where('idSolicitud', $recibido->id)->orderBy('id', 'DESC')->first();
                        $recibido->detallesServicio = $recibido->detallesServicio . ' - ' . $historial->motivo; // obtiene detalles de cada solicitud
                    }
                }
                // dump($recibidosAux);

                $unidad->recibidos = $recibidosAux;
                $recibidosAux = [];
                // agrega las solicitudes al array de cada area de la direccion
            }
            // dump($unidadesDirector->toArray());
            // dd($unidadesDirector);
        } else {
            $recibidos = DB::select('CALL `listaRecibidos`(?,?,?)', array('filtrar', $request->id, $request->filtro)); //obtie
            foreach ($recibidos as $recibido) {
                if ($recibido->estatusSolicitud != 'Pendiente') {
                    $historial = HistorialServicios::toBase()->where('idSolicitud', $recibido->id)->orderBy('id', 'DESC')->first();
                    // $municipios = Municipio::toBase()->where('estatus', '1')->where('idEstado', 30)->orderBy('nombreMunicipio', 'ASC')->get();
                    $recibido->detallesServicio = $recibido->detallesServicio . ' - ' . $historial->motivo;
                }
            }
        }
        // return view('servicios\bandejas\BandejaEntradaDirector', ['unidadesDirector'=>$unidadesDirector]);

        return response()->json($unidadesDirector);
    }

    public function turnados(Request $request){
        if (Auth::user()->idRol == 3 || Auth::user()->idRol == 1) { //para jefes de departamentos
            $usuario = User::with('rol', 'departamento')->where([['estatus', 1], ['idUsuario', Auth::id()]])->first();
        } else {
            $usuario = User::with('rol', 'organo')->where([['estatus', 1], ['idUsuario', Auth::id()]])->first();
        }



        $historial  = \DB::table('historialsolicitudes')
                        ->select('deptoReceptor.departamento as receptor', 'solicitudes.descripcion', 'deptoSolicitante.departamento as solicitante', 'dpReceptor.departamento as receptorTurnado', 'servicios.descripcion as servicioDescripcion', 'solicitudes.descripcion as detallesServicio')
                        ->join('solicitudes', function($join){
                            $join->on('historialsolicitudes.idSolicitud', '=', 'solicitudes.id')
                            ->join('departamento as deptoSolicitante', 'deptoSolicitante.id', '=', 'solicitudes.idDepartamentoSolicitante')
                            ->join('departamento as deptoReceptor', 'deptoReceptor.id', '=', 'solicitudes.idDepartamentoReceptora');
                        })
                        ->join('departamento as dpReceptor', 'dpReceptor.id', '=', 'historialsolicitudes.idDepartamentoReceptora')
                        ->join('servicios', 'servicios.idServicio', '=', 'historialsolicitudes.idServicio')
                        ->where([['historialsolicitudes.idUsuarioAlta', $usuario->idUsuario], ['historialsolicitudes.estatusSolicitud', 'Turnado']])
                        ->get();

        // ->orWhere('historialsolicitudes.idDepartamentoReceptora', $usuario->idOrganoDepartamento)
        $director = ($usuario->rol->id == 2 ? true : false); // checa si tiene rol de director


        return view('servicios.bandejaTurnado', compact('historial', 'director'));
    }
}
