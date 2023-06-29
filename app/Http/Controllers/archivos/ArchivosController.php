<?php

namespace App\Http\Controllers\archivos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\servicios\UrlArchivo;

class ArchivosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // obtener los archivos
        $files = UrlArchivo::WHERE('id', $id)->first();

        if (!$files)
        {
            abort(404);
        } else {

            $filepath = $files->urlArchivo;
            if (!\Storage::disk('public')->exists($filepath)) {
                # checando si el archivo éxiste
                abort(404); // si no hay abortamos
            }
            $disk = \Storage::disk('public')->get($filepath);
            $type = \Storage::disk('public')->getMimeType($filepath);
            $response = \Response::make($disk, 200);
            $response->header("Content-Type", $type);
            return $response;
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
