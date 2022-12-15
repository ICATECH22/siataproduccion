<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Controllers\Controller;
use App\Models\catalogos\Departamento2;
use App\Models\catalogos\Unidad;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogoOrganoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $unidades = DB::select('CALL `listaUnidades`()');

        return view('catalogos\organos\organosLista', compact('unidades'));
    }

    public function create()
    {
        //
        $direcciones = Departamento2::toBase()->where([['estatus', '1'], ['idparent', 1]])->get();
        return view('catalogos\organos\organoCrear', compact('direcciones'));
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

        DB::beginTransaction();
        try {
            Departamento2::create([
                'idparent' => $request->direccion,
                'area' => $request->area,
                'titular' => $request->titular,
                'puesto' => $request->puesto,
                'correo' => $request->email,
                'telefono' => $request->telefono,
                'celular' => $request->celular,
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
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }

        return redirect()->route('organosLista');
    }



    public function edit(Request $request, $id)
    {
        //
        $organo = Unidad::toBase()->where([['estatus', '1'], ['idUnidad', $id]])->get()->first();
        $unidad = Departamento2::toBase()->where('id', $id)->get()->first();
        $direcciones = Departamento2::toBase()->where([['estatus', '1'], ['idparent', 1]])->get();

        // dd($organo); 
        return view('catalogos\organos\organoEditar', compact('organo', 'unidad', 'direcciones'));
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
            DB::table('departamento2')
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
        return redirect()->route('organosLista');
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
            DB::table('departamento2')
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
            throw ($e);
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }
    }
}
