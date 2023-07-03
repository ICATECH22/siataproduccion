<?php

namespace App\Http\Controllers\servicios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\servicios\SolicitudServicio;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class FiltrarServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            //consulta de datos
            if (Auth::user()->idRol == 3 || Auth::user()->idRol == 1) { //para jefes de departamentos
                $usuario = User::with('rol', 'departamento')->where([['estatus', 1], ['idUsuario', Auth::id()]])->first();
            } else {
                $usuario = User::with('rol', 'organo')->where([['estatus', 1], ['idUsuario', Auth::id()]])->first();
            }
            $recibidos = SolicitudServicio::select('solicitudes.id', 'solicitudes.descripcion as detallesServicio', 'ds.departamento as departamentoSolicitante', 'dr.departamento as departamentoReceptora', 'solicitudes.estatusSolicitud', 'solicitudes.visto', 'solicitudes.lector', 'solicitudes.estatus', 'solicitudes.fechaAlta', 's2.descripcion as nombreServicio')
                    ->join('departamento as ds', 'ds.id', '=', 'solicitudes.idDepartamentoSolicitante')
                    ->join('departamento as dr', 'dr.id', '=', 'solicitudes.idDepartamentoReceptora')
                    ->join('servicios as s2', 's2.idServicio', '=', 'solicitudes.idServicio')
                    ->where([['dr.id', $usuario->idOrganoDepartamento], ['solicitudes.estatus', '1']])->orderBy('solicitudes.fechaAlta', 'DESC')->get();

            return Response::json(['Response' => $recibidos]);
        } catch (\QueryException $th) {
            //throw $th;
            return Response::json(['Exception'=> $th->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            $usuario = User::with('rol', 'departamento')->where([['estatus', 1], ['idUsuario', Auth::id()]])->first();
            //realizamos la consulta
            $recibidos = SolicitudServicio::select('solicitudes.id', 'solicitudes.descripcion as detallesServicio', 'ds.departamento as departamentoSolicitante', 'dr.departamento as departamentoReceptora', 'solicitudes.estatusSolicitud', 'solicitudes.visto', 'solicitudes.lector', 'solicitudes.estatus', 'solicitudes.fechaAlta', 's2.descripcion as nombreServicio')
                ->join('departamento as ds', 'ds.id', '=', 'solicitudes.idDepartamentoSolicitante')
                ->join('departamento as dr', 'dr.id', '=', 'solicitudes.idDepartamentoReceptora')
                ->join('servicios as s2', 's2.idServicio', '=', 'solicitudes.idServicio')
                ->where([['dr.id', $usuario->idOrganoDepartamento], ['solicitudes.estatus', '1'], ['solicitudes.idServicio', $id]])->get();

            return Response::json(['dataResponse'=>$recibidos]);
        } catch (\QueryException $e) {
            //throw $th;
           return Response::json(['Exception'=> $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
