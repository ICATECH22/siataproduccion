<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Controllers\Controller;
use App\Models\catalogos\Departamento;
use App\Models\catalogos\Departamento2;
use App\Models\catalogos\Organo;
use App\Models\catalogos\Servicios;
use App\Models\catalogos\Unidad;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogoUnidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $unidades = Unidad::toBase()->where('estatus', '1')->get();

        return view('catalogos\unidades\unidadLista', compact('unidades'));
    }

    public function create()
    {
        //
        $direcciones = Unidad::toBase()->where([['estatus', '1']])->get();
        $usuarios = User::toBase()->where('estatus', '1')->get();
        
        return view('catalogos\unidades\unidadCrear', compact('direcciones', 'usuarios'));
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
        
        DB::beginTransaction();
        try {
            Unidad::create([
                'descripcion' => $request->unidad,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'estatus' => 1,
                'fechaAlta' => Carbon::now(),
                'fechaUMod' => null,
                'idUsuarioAlta' => Auth::id(),
            ]);
            DB::commit();
            session(['message' => 'El registro se ha guardado']);
            session(['alert' => 'alert-success']);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }

        return redirect()->route('unidadesLista');
    }



    public function edit(Request $request, $id)
    {
        //
        $organo = Unidad::toBase()->where([['estatus', '1'], ['idUnidad', $id]])->get()->first();
        $unidad = Unidad::toBase()->where('id', $id)->get()->first();
        $direcciones = Unidad::toBase()->where([['estatus', '1'], ['idparent', 1]])->get();

        // dd($organo); 
        return view('catalogos\unidades\unidadEditar', compact('organo', 'unidad', 'direcciones'));
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
        
        DB::beginTransaction();
        try {
            DB::table('unidad')
                ->where('id', $id)
                ->update([
                    'idparent' => $request->direccion,
                    'area' => $request->area,
                    'titular' => $request->titular,
                    'puesto' => $request->puesto,
                    'correo' => $request->email,
                    'telefono' => $request->telefono,
                    'celular' => $request->celular,
                    'estatus' => 1,
                    'idUsuarioUMod' => Auth::id(),
                    'fechaUMod' => Carbon::now()
                ]);
            DB::commit();


            session(['message' => 'El registro se ha actualizado']);
            session(['alert' => 'alert-success']);
        } catch (\Exception $e) {
            DB::rollback();
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }
        return redirect()->route('unidadLista');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            DB::table('unidad')
                ->where('id', $id)
                ->update([
                    'estatus' => 0,
                    'idUsuarioUMod' => Auth::id(),
                    'fechaUMod' => Carbon::now()
                ]);
            DB::commit();


            session(['message' => 'El registro se ha eliminado']);
            session(['alert' => 'alert-success']);
        } catch (\Exception $e) {
            DB::rollback();
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
            throw ($e);
        }
    }

    public function getOrganos($idUnidad)
    {
        $organos = Organo::toBase()->where([['estatus', '1'],['idUnidad',$idUnidad]])->get();
        return response()->json($organos);
    }
    public function getServiciosDepartamentos($idDepartamento)
    {
     
        $departamentos = Departamento::select('departamento.id','departamento.departamento','departamento.titular','departamentoservicios.idDepartamento','departamentoservicios.idServicio','servicios.idServicio','servicios.descripcion')
        ->join('departamentoservicios', 'departamentoservicios.idDepartamento','=', 'departamento.id')
        ->join('servicios', 'servicios.idServicio','=', 'departamentoservicios.idServicio')
        ->where([['departamento.estatus', '1'],['departamento.idOrgano',$idDepartamento]])
        ->orderby('departamento.departamento')
        ->get();
        return response()->json($departamentos);
    }
}
