<?php

namespace App\Http\Controllers\usuarios;

use App\Http\Controllers\Controller;
use App\Models\catalogos\Departamento;
use App\Models\catalogos\Departamento2;
use App\Models\catalogos\Organo;
use App\Models\catalogos\Unidad;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $usuarios = User::with('rol','organo')->where('estatus', '1')->get();
        dd($usuarios->toArray());
        return view('usuarios\usuariosLista', compact('usuarios'));
    }

    public function indexDeshabilitados()
    {
        //
        $usuarios = DB::select('CALL `listaUsuariosDeshabilitados`()');
        return view('usuarios\usuariosDeshabilitadosLista', compact('usuarios'));
    }


    public function create()
    {
        $organos = Organo::toBase()->where('estatus', '1')->get();
        // dd($organos->toArray());
        $direcciones = Departamento::toBase()->where([['estatus', '1']])->get();
        return view('usuarios\usuarioCrear', compact('organos','direcciones'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $idRol = 3; //rol con id 3 es para los jefes de area/departamento
        $idArea = $request->idDepartamento;
        if($request->isDirector === "on"){
            $idRol = 2; //rol con id 2 es para los directores de unidad ejemplo "unidad ejecutiva"
            $idArea = $request->idDireccion;
        }

            
        // dd($request->toArray(), $idRol);

        // dd($request->toArray());

        DB::beginTransaction();
        try {
            User::create([
                'name' => $request->nombre,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'idOrganoDepartamento' => $idArea,
                'idRol' => $idRol ,
                'estatus' => 1,
                'idUsuarioAlta' => Auth::id(),
                'fechUMod' => null,
            ]);
            DB::commit();
            session(['message' => 'El registro se ha guardado']);
            session(['alert' => 'alert-success']);
        } catch (\Exception $e) {
            DB::rollback();
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
        }
        return redirect()->route('usuariosLista');
    }

    public function actualizar(Request $request, $id)
    {


        $usuario = User::with('rol','departamento')->where('idUsuario', $id)->first();
        
        $organos = Organo::toBase()->where('estatus', '1')->get();
        $departamentos = Departamento::toBase()->where([['estatus', '1']])->get();
        
        // dd($usuario->toArray(), $organos->toArray(), $departamentos->toArray());
        return view('usuarios\editarUsuario', compact('usuario', 'organos', 'departamentos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'idDepartamento' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            DB::table('users')
                ->where('idUsuario', $id)
                ->update([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'idDepartamento' => $request['idDepartamento'],
                    'idUsuarioUMod' => Auth::id(),
                    'fechaUMod' => Carbon::now()
                ]);
            DB::commit();

            $usuarios = User::toBase()->where('estatus', '1')->get();
            session(['message' => 'El registro se ha actualizado']);
            session(['alert' => 'alert-success']);
            return redirect()->route('usuariosLista')->with(['usuario' => $usuarios]);
        } catch (\Exception $e) {
            DB::rollback();
            throw ($e);
            session(['message' => 'Algo salió mal intente nuevamente']);
            session(['alert' => 'alert-danger']);
            return redirect()->route('adscripcionLista');
        }
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
            DB::table('users')
                ->where('idUsuario', $id)
                ->update([
                    'estatus' => 0,
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
    }


    public function getDepartamentos($idOrgano)
    {
        $departamentos = Departamento::toBase()->where([['estatus', '1'], ['idOrgano', $idOrgano]])->get();
        return response()->json($departamentos);
    }
}


// 0 => {#351 ▼
        //     +"idUsuario": 1
        //     +"idDepartamento": 2
        //     +"departamento": "Equipo de Desarrollo"
        //     +"name": "PabloPrueba"
        //     +"email": "pdevelop.c@gmail.com"
        //     +"idUnidad": 1
        //     +"unidad": "Informatica"
        //   }