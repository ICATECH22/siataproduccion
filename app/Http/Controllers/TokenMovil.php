<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TokenMovil extends Controller
{
    //

    public function test(Request $request)
    {
        return response()->json('hola');
    }
    public function login(Request $request)
    {
        $correo = $request->email;
        $token = $request->token;
        $usuario = User::where('email', $correo)->first();
        if (!isset($usuario->id)) {
            User::where('email', $correo)->update([ //se actualiza su token movil
                'token_movil' => $token
            ]);
            $data = User::select('idUsuario', 'name')->where('email', $correo)->first();
            return response()->json('usuario no registrado en siata', 204);
        } else {
            return response()->json('usuario no registrado en siata', 204);
        }
    }


    public function getNotificaciones(Request $request)
    {
        $email = User::where('email', $request->email)->first();
        if (isset($email->idUsuario)) {
            $id = $email->idUsuario;
            $notificaciones = DB::table('notifications')
                ->where('notifications.notifiable_id', $id)
                ->select('notifications.*')
                // ->orderBy('created_at')
                ->orderByDesc('notifications.created_at')
                ->paginate(15, ['notifications.*']);
            // ->get();

            return response()->json($notificaciones, 200);
        } else {
            return response()->json('usuario no registrado en siata', 204);
        }
    }


    public function updateRead(Request $request)
    {
        $id = $request->id;
        $read = $request->read;
        DB::table('notifications')->where('id', $id)->update(['read_movil' => $read, 'read_at' => Carbon::now()]);
        return response()->json('success', 200);
    }

    public function updateToken(Request $request)
    {
        $id = $request->id;
        User::where('id', $id)->update(['token_movil' => null]);
        return response()->json('success', 200);
    }
}
