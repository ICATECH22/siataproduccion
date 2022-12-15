<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Controllers\Controller;
use App\Models\catalogos\Departamento;
use App\Models\catalogos\DepartamentoServicios;
use App\Models\catalogos\Organo;
use App\Models\catalogos\Servicios;
use App\Models\catalogos\Unidad;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogoDepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $departamentos = Departamento::with('organo')->where('estatus', '1')->get();
        // dd($departamentos->toArray());
        return view('catalogos\departamentos\departamentosLista', compact('departamentos'));
    }

    public function create(Request $request)
    {
        //
        $organos = Organo::toBase()->where('estatus', '1')->get();
        return view('catalogos\departamentos\departamentosCrear', compact('organos'));
    }
    

    

    public function asignarServicios($idDepartamento)
    {
        $servicios = Servicios::toBase()->where('estatus', 1)->get();
        $departamento = Departamento::where('id', $idDepartamento)->first();
        return view('catalogos\departamentos\asignarServicio', compact('servicios','departamento','idDepartamento'));
    }
    public function storeAsignarServicios(Request $request, $idDepartamento)
    {
        
        // dd($request->toArray(), $idDepartamento);
        DB::beginTransaction();
        try {
            $idServicio = $request->idServicio;
            
            if($request->isNuevoServicio == 'on'){
                $idServicio = Servicios::create([
                    'descripcion' => $request->nuevoServicio,
                    'estatus' => 1,
                    'idUsuarioAlta' => Auth::id(),
                    'fechaUMod' => null,
                    
                ]);
                $idServicio = $idServicio->idServicio;
            }

            DepartamentoServicios::create([
                'idDepartamento' => $idDepartamento,
                'idServicio' => $idServicio,
                'estatus' => 1,
                'idUsuarioAlta' => Auth::id(),
                'fechaUMod' => null,
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

        return redirect()->route('departamentosLista');
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
        // dd($request->toArray());
        $validated = $request->validate([
            "area" => 'required',
            "titular" => 'required',
            "puesto" => 'required',
            "email" => 'required',
            "direccion" => 'required',//idOrgano
            "telefono" => 'required',
        ]);


        DB::beginTransaction();
        try {
            Departamento::create([
                'idOrgano' => $request->direccion,
                'departamento' => $request->area,
                'titular' => $request->titular,
                'correo' => $request->email,
                'telefono' => $request->telefono,
                'celular' => $request->telefono ,
                'estatus' => 1,
                'idUsuarioAlta' => Auth::id(),
                'fechaUMod' => null,
                // 'idUsuarioUMod' => Auth::id()
            ]);
            DB::commit();
            session(['message' => 'El registro se ha guardado']);
            session(['alert' => 'alert-success']);
        } catch (\Exception $e) {
            throw $e;
            DB::rollback();
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }

        return redirect()->route('departamentosLista');
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
    }

    public function edit($id)
    {
        //

        $departamento = Departamento::toBase()->where('idDepartamento', $id)->first();
        $unidades = Unidad::toBase()->where('estatus', '1')->get();
        return view('catalogos\departamentos\departamentoEditar', compact('departamento', 'unidades'));
    }


    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'descripcion' => 'required',
            'idUnidad' => 'required'
        ]);
        // dd($request->toArray());

        DB::beginTransaction();
        try {
            DB::table('departamento')
                ->where('idDepartamento', $id)
                ->update([
                    'idUnidad' => $request['idUnidad'],
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
        return redirect()->route('departamentosLista');
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
        DB::beginTransaction();
        try {
            DB::table('departamento')
                ->where('idDepartamento', $id)
                ->update([
                    'estatus' => 0,
                    'idUsuarioEliminacion' => Auth::id(),
                    'fechaEliminacion' => Carbon::now()
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
    }
}
