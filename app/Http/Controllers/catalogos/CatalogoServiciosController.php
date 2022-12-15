<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Controllers\Controller;
use App\Models\catalogos\Departamento;
use App\Models\catalogos\Departamento2;
use App\Models\catalogos\Servicios;
use App\Models\catalogos\Unidad;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogoServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $servicios = Servicios::toBase()->where('estatus', '1')->get();

        return view('catalogos\servicios\serviciosLista', compact('servicios'));
    }

    public function create()
    {
        //
        $unidades = Unidad::with('organos.departamentos')->where('estatus', '1')->get(); // se obtiene todos los servicios de todos los departamentos de todas los organos de todas las unidades
        $usuario = User::with('rol')->where('idUsuario', Auth::id())->first();

        return view('catalogos\servicios\serviciosCrear', compact('unidades', 'usuario'));
    }
    public function buscador(Request $request)
    {
        $servicio = DB::table('servicios')->where('descripcion', 'like', '%'.$request->servicio.'%')->get();
        return response()->json($servicio);
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

        $request->validate([
            'descripcion' => 'required',
        ]);


        DB::beginTransaction();
        try {
            Servicios::create([
                'descripcion' => $request->descripcion,
                'estatus' => 1,
                'idUsuarioAlta' => Auth::id(),
                // 'idUsuarioUMod' => Auth::id()
            ]);
            DB::commit();
            session(['message' => 'El registro se ha guardado']);
            session(['alert' => 'alert-success']);
        } catch (\Exception $e) {
            DB::rollback();
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
            return $e;
        }

        return redirect()->route('serviciosLista');
    }



    public function editar($id)
    {
        //
        $servicio = Servicios::toBase()->where('idServicio', $id)->first();
        $departamentos = DB::select('CALL `listaUnidades`()');
        return view('catalogos\servicios\servicioEditar', compact('servicio', 'departamentos'));
    }


    public function update(Request $request, $id)
    {
        //


        $validated = $request->validate([
            'descripcion' => 'required',
            'direccion' => 'required'
        ]);
        // dd($request->toArray());

        DB::beginTransaction();
        try {
            DB::table('servicios')
                ->where('idServicio', $id)
                ->update([
                    'idDepartamento' => $request['direccion'],
                    'descripcion' => $request['descripcion'],
                    'idUsuarioUMod' => Auth::id(),
                    'fechaUMod' => Carbon::now()
                ]);
            DB::commit();


            session(['message' => 'El registro se ha actualizado']);
            session(['alert' => 'alert-success']);
        } catch (\Exception $e) {
            DB::rollback();
            throw ($e);
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }
        return redirect()->route('serviciosLista');
    }




    public function destroy($id)
    {
        //

        DB::beginTransaction();
        try {
            DB::table('servicios')
                ->where('idServicio', $id)
                ->update([
                    'estatus' => 0,
                    'idUsuarioEliminacion' => Auth::id(),
                    'fechaEliminacion' => Carbon::now()
                ]);
            DB::commit();


            session(['message' => 'El registro se ha eliminado']);
            session(['alert' => 'alert-success']);
        } catch (\Exception $e) {
            DB::rollback();
            throw ($e);
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }
    }
}
