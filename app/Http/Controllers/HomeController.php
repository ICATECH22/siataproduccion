<?php

namespace App\Http\Controllers;

use App\Models\catalogos\Departamento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */

    public function infoDepartamento()
    {
        $idUsuario =  Auth::id();
        
        $datosUsuario = User::toBase()->where([
            [
                'estatus',
                1
            ],
            ['idUsuario', Auth::id()]
        ])->get()->toArray();
        $infoUnidadCapacitacion = DB::select('CALL `getUnidadCapacitacion`(?)', array(Auth::id()))[0];

        return response()->json([$datosUsuario, $infoUnidadCapacitacion]);
    }
    public function index()
    {

        $fechaActual = Carbon::now()->toDateString() . ' 00:00:00';

        $datosUsuario = User::toBase()->where([
            [
                'estatus',
                1
            ],
            ['idUsuario', Auth::id()]
        ])->get()->toArray()[0];
        $recibidosHoy = [];// = DB::select('CALL `ranking`(?,?,?)', array('totalActual', $datosUsuario['idDepartamento'], $fechaActual));
        $masRecurrente = [];// = DB::select('CALL `ranking`(?,?,?)', array('masRecurrente', $datosUsuario['idDepartamento'], $fechaActual));
        $unidaMasEnvia = [];// = DB::select('CALL `ranking`(?,?,?)', array('unidaMasEnvia', $datosUsuario['idDepartamento'], $fechaActual));
        $totalRecibidosSemana = [];// = DB::select('CALL `ranking`(?,?,?)', array('totalRecibidosSemana', $datosUsuario['idDepartamento'], $fechaActual));
        $topMasSolicitado = [];// = DB::select('CALL `ranking`(?,?,?)', array('topMasSolicitado', $datosUsuario['idDepartamento'], $fechaActual));
        return view('dashboard', compact('recibidosHoy', 'masRecurrente', 'unidaMasEnvia', 'totalRecibidosSemana', 'topMasSolicitado'));
    }

    public function obtenerRanking()
    {
        $fechaActual = Carbon::now()->toDateString() . ' 00:00:00';

        $datosUsuario = User::with('departamento')->where([
            ['estatus', 1],
            ['idUsuario', Auth::id()]
        ])->get()->toArray()[0];
        $recibidosHoy = DB::select('CALL `ranking`(?,?,?)', array('totalActual', $datosUsuario['idDepartamento'], $fechaActual));
        $masRecurrente = DB::select('CALL `ranking`(?,?,?)', array('masRecurrente', $datosUsuario['idDepartamento'], $fechaActual));
        $unidaMasEnvia = DB::select('CALL `ranking`(?,?,?)', array('unidaMasEnvia', $datosUsuario['idDepartamento'], $fechaActual));
        $historialRecibidosAño = DB::select('CALL `ranking`(?,?,?)', array('totalRecibidosAño', $datosUsuario['idDepartamento'], ''));
        $totalRecibidosSemana = DB::select('CALL `ranking`(?,?,?)', array('totalRecibidosSemana', $datosUsuario['idDepartamento'], $fechaActual));
        if ($totalRecibidosSemana != []) {
            $lunes = 0;
            $martes = 0;
            $miercoles = 0;
            $jueves = 0;
            $viernes = 0;

            foreach ($totalRecibidosSemana as $total) {
                $lunes += $total->lunes;
                $martes += $total->martes;
                $miercoles += $total->miercoles;
                $jueves += $total->jueves;
                $viernes += $total->viernes;
            }

            $totalRecibidosSemana[0]->lunes = $lunes;
            $totalRecibidosSemana[0]->martes = $martes;
            $totalRecibidosSemana[0]->miercoles = $miercoles;
            $totalRecibidosSemana[0]->jueves = $jueves;
            $totalRecibidosSemana[0]->viernes = $viernes;
        }



        $data = [
            'recibidosHoy' => $recibidosHoy,
            'masRecurrente' => $masRecurrente,
            'unidaMasEnvia' => $unidaMasEnvia,
            'historialRecibidosAño' => $historialRecibidosAño,
            'totalRecibidosSemana' => $totalRecibidosSemana
        ];


        return response()->json($data);
    }

    public function enviarNotificacionPrueba()
    {
     
        
        $token = ['dq4amCepRJymXKj7kQdiSx:APA91bG5xEnWHTDqbU_1hFyOgrZSYf-xdnvOjlWCkrkNcnWxgJzwCXd2yRln5PUDG0wk9SmO-McVG_Ttp1x9xyWqBqp42fxhl_phif4cDgLx1yfSc2-wPFTVYMVuvxhqxFGMfxXvMWJf'];
        sendNotification($token,' $titulo', '$cuerpo->descripcion');
        return response()->json('test');
    }
}
