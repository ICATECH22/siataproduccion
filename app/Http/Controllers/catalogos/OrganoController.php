<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Controllers\Controller;
use App\Models\catalogos\Organo;
use App\Models\catalogos\Unidad;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganoController extends Controller
{
    //
    public function index()
    {
        //
        $direcciones = Organo::toBase()->where([['estatus', '1']])->get();

        return view('catalogos\organo\organoLista', compact('direcciones'));
    }

    public function create()
    {
        //
        $unidades = Unidad::toBase()->where('estatus', '1')->get();
        return view('catalogos\organo\organoCrear', compact('unidades'));
    }

    public function store(Request $request)
    {


        //
        // $validated = $request->validate([
        //     'idUnidad' => 'required',
        //     'organo' => 'required',
        //     'titular' => 'required',
        //     'puesto' => 'required',
        //     'correo' => 'required',
        //     'telefono' => 'required',
        // ]);


        DB::beginTransaction();
        try {
            Organo::create([
                'idUnidad' => $request->idUnidad,
                'organo' => $request->area,
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
            throw $e;
            DB::rollback();
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }

        return redirect()->route('organosLista');
    }

    public function edit($id)
    {
        $direccion = Organo::where('id', $id)->first();
        return view('catalogos\organo\organoditar', compact('direccion'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->toArray());
        // $request->validate([
        //     'area' => 'required',
        //     'titular' => 'required',
        //     'puesto' => 'required',
        //     'correo' => 'required',
        //     'telefono' => 'required',
        // ]);


        DB::beginTransaction();
        try {

            DB::table('Organo')
                ->where('id', $id)
                ->update([
                    'area' => $request->area,
                    'titular' => $request->titular,
                    'puesto' => $request->puesto,
                    'correo' => $request->email,
                    'telefono' => $request->telefono,
                    'celular' => $request->celular,
                    'fechaUMod' => Carbon::now(),
                    'idUsuarioUMod' => Auth::id(),
                ]);
            DB::commit();
            session(['message' => 'El registro se ha actualizado']);
            session(['alert' => 'alert-success']);
        } catch (\Exception $e) {
            DB::rollback();
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }

        return redirect()->route('organoLista');
    }
    public function destroy($id)
    {
        // dd($request->toArray());
        // $request->validate([
        //     'area' => 'required',
        //     'titular' => 'required',
        //     'puesto' => 'required',
        //     'correo' => 'required',
        //     'telefono' => 'required',
        // ]);


        DB::beginTransaction();
        try {

            DB::table('Organo')
                ->where('id', $id)
                ->update([
                    'estatus' => 0,
                    'fechaEliminacion' => Carbon::now(),
                    'idUsuarioEliminacion' => Auth::id(),
                ]);
            DB::commit();
            session(['message' => 'El registro se ha eliminado']);
            session(['alert' => 'alert-success']);
        } catch (\Exception $e) {
            DB::rollback();
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }
    }
}
