<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	// return view('welcome');
	if (!Auth::check()) {
		return view('auth/login');
	} else {
		return redirect()->route('home');
	}
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::group(['middleware' => 'auth'], function () {
	
	Route::get('infoUser', ['as' => 'infoUser', 'uses' => 'App\Http\Controllers\HomeController@infoDepartamento']);
	Route::get('icons', function () {return view('pages.icons');})->name('icons'); 
	
	
	//servicios
	Route::get('recibidos', ['as' => 'bandejaEntrada', 'uses' => 'App\Http\Controllers\servicios\ServicioController@indexRecibidos']);
	Route::get('recibidos/detalles/{idServicio}', ['as' => 'infoServicio', 'uses' => 'App\Http\Controllers\servicios\ServicioController@detalles']);
	Route::get('recibidos/filtrar', ['as' => 'filtrarRecibidos', 'uses' => 'App\Http\Controllers\servicios\ServicioController@filtrar']);
	
	Route::get('enviados/detalles/{idServicio}', ['as' => 'infoServicio2', 'uses' => 'App\Http\Controllers\servicios\ServicioController@detalles2']);
	Route::get('enviados', ['as' => 'enviados', 'uses' => 'App\Http\Controllers\servicios\ServicioController@indexEnviados']);
	Route::get('crear', ['as' => 'crearServicio', 'uses' => 'App\Http\Controllers\servicios\ServicioController@create']);
	Route::post('guardar', ['as' => 'guardarServicio', 'uses' => 'App\Http\Controllers\servicios\ServicioController@store']);
	Route::get('descargar/{id}', ['as' => 'descargarArchivo', 'uses' => 'App\Http\Controllers\servicios\ServicioController@descargarArchivo']);
	Route::get('ver/{id}', ['as' => 'verArchivo', 'uses' => 'App\Http\Controllers\servicios\ServicioController@verArchivo']);
	Route::post('solicitud/rechazar/{id}', ['as' => 'rechazarSolicitud', 'uses' => 'App\Http\Controllers\servicios\ServicioController@rechazarSolicitud']);
	Route::post('solicitud/aceptar/{id}', ['as' => 'aceptarSolicitud', 'uses' => 'App\Http\Controllers\servicios\ServicioController@aceptarSolicitud']);
	Route::post('solicitud/corregir/{id}', ['as' => 'corregirSolicitud', 'uses' => 'App\Http\Controllers\servicios\ServicioController@corregirSolicitud']);
	Route::post('solicitud/transferir/{id}', ['as' => 'transferirSolicitud', 'uses' => 'App\Http\Controllers\servicios\ServicioController@transferirSolicitud']);
	
	
	Route::get('departamento/info/{id}', ['as' => 'infoDepartamento', 'uses' => 'App\Http\Controllers\servicios\ServicioController@infoDepartamento']);

	//ranking
	Route::get('ranking', ['as' => 'ranking', 'uses' => 'App\Http\Controllers\HomeController@obtenerRanking']);


	// Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);


	// Route::get('usuario/crear', ['as' => 'usuarioCrear', 'uses' => 'App\Http\Controllers\UsuarioController@index']);
	// Route::get('usuario/crear', ['as' ]'UsuarioController@index')->name('usuarioCrear');

	// Route::middleware(['admin'])->group(function () {//quite el middleware del admin por que no recuerdo como lo implemente
		
		// Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
		Route::get('profile/actualizar', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
		Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
		// Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade'); 
		Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);


		Route::get('catalogos/lista', function () {
			return view('catalogos.catalogosLista');
		})->name('catalogosLista');



		Route::get('catalogos/unidad/lista', ['as' => 'unidadesLista', 'uses' => 'App\Http\Controllers\catalogos\CatalogoUnidadController@index']);
		Route::get('catalogos/unidad/crear', ['as' => 'unidadesCrear', 'uses' => 'App\Http\Controllers\catalogos\CatalogoUnidadController@create']);
		Route::post('catalogos/unidad/guardar', ['as' => 'unidadesGuardar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoUnidadController@store']);
		Route::get('catalogos/unidad/editar/{id}', ['as' => 'unidadesEditar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoUnidadController@edit']);
		Route::post('catalogos/unidad/actualizar/{id}', ['as' => 'unidadesActualizar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoUnidadController@update']);
		Route::delete('catalogos/unidad/eliminar/{id}', ['as' => 'unidadesEliminar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoUnidadController@destroy']);



	
		Route::get('catalogos/organos/lista', ['as' => 'organosLista', 'uses' => 'App\Http\Controllers\catalogos\OrganoController@index']);
		Route::get('catalogos/organos/crear', ['as' => 'organosCrear', 'uses' => 'App\Http\Controllers\catalogos\OrganoController@create']);
		Route::post('catalogos/organos/guardar', ['as' => 'organosGuardar', 'uses' => 'App\Http\Controllers\catalogos\OrganoController@store']);
		Route::get('catalogos/organos/editar/{id}', ['as' => 'organosEditar', 'uses' => 'App\Http\Controllers\catalogos\OrganoController@edit']);
		Route::post('catalogos/organos/actualizar/{id}', ['as' => 'organosActualizar', 'uses' => 'App\Http\Controllers\catalogos\OrganoController@update']);
		Route::delete('catalogos/organos/eliminar/{id}', ['as' => 'organosEliminar', 'uses' => 'App\Http\Controllers\catalogos\OrganoController@destroy']);


		// Route::get('catalogos/organos/lista', ['as' => 'organoLista', 'uses' => 'App\Http\Controllers\catalogos\CatalogoOrganoController@index']);
		// Route::get('catalogos/organos/crear', ['as' => 'organoCrear', 'uses' => 'App\Http\Controllers\catalogos\CatalogoOrganoController@create']);
		// Route::post('catalogos/organos/guardar', ['as' => 'organoGuardar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoOrganoController@store']);
		// Route::get('catalogos/organos/editar/{id}', ['as' => 'organoEditar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoOrganoController@edit']);
		// Route::post('catalogos/organos/actualizar/{id}', ['as' => 'organoActualizar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoOrganoController@update']);
		// Route::delete('catalogos/organos/eliminar/{id}', ['as' => 'organoEliminar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoOrganoController@destroy']);


		//catalogos departamentos 
		Route::get('catalogos/departamentos/lista', ['as' => 'departamentosLista', 'uses' => 'App\Http\Controllers\catalogos\CatalogoDepartamentoController@index']);
		Route::get('catalogos/departamentos/crear', ['as' => 'departamentosCrear', 'uses' => 'App\Http\Controllers\catalogos\CatalogoDepartamentoController@create']);
		Route::post('catalogos/departamentos/guardar', ['as' => 'departamentoGuardar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoDepartamentoController@store']);
		Route::get('catalogos/departamentos/actualizar/{id}', ['as' => 'departamentoActualizar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoDepartamentoController@edit']);
		Route::post('catalogos/departamentos/actualizar/{id}', ['as' => 'departamentoUpdate', 'uses' => 'App\Http\Controllers\catalogos\CatalogoDepartamentoController@update']);
		Route::get('catalogos/departamentos/asignar-servicio/{id}', ['as' => 'asignarServicios', 'uses' => 'App\Http\Controllers\catalogos\CatalogoDepartamentoController@asignarServicios']);
		Route::post('catalogos/departamentos/asignar-servicio/{id}', ['as' => 'asignarServiciosStore', 'uses' => 'App\Http\Controllers\catalogos\CatalogoDepartamentoController@storeAsignarServicios']);
		Route::delete('catalogos/departamentos/eliminar/{id}', ['as' => 'departamentoEliminar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoDepartamentoController@destroy']);


		//catalogo servicios
		Route::get('catalogos/servicios/lista', ['as' => 'serviciosLista', 'uses' => 'App\Http\Controllers\catalogos\CatalogoServiciosController@index']);
		Route::get('catalogos/servicios/crear', ['as' => 'servicioCrear', 'uses' => 'App\Http\Controllers\catalogos\CatalogoServiciosController@create']);
		Route::get('catalogos/servicios/buscador', ['as' => 'servicioBuscar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoServiciosController@buscador']);
		Route::post('catalogos/servicios/guardar', ['as' => 'servicioGuardar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoServiciosController@store']);
		Route::get('catalogos/servicios/editar/{id}', ['as' => 'servicioEditar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoServiciosController@editar']);
		Route::post('catalogos/servicios/editar/{id}', ['as' => 'servicioActualizar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoServiciosController@update']);
		Route::delete('catalogos/servicios/eliminar/{id}', ['as' => 'servicioEliminar', 'uses' => 'App\Http\Controllers\catalogos\CatalogoServiciosController@destroy']);

		//Usuarios
		Route::get('usuarios/lista', ['as' => 'usuariosLista', 'uses' => 'App\Http\Controllers\usuarios\UsuarioController@index']);
		Route::get('usuarios/lista/deshabilitados', ['as' => 'usuariosListaDeshabilitados', 'uses' => 'App\Http\Controllers\usuarios\UsuarioController@indexDeshabilitados']);
		Route::get('usuarios/crear', ['as' => 'usuarioCrear', 'uses' => 'App\Http\Controllers\usuarios\UsuarioController@create']);
		Route::post('usuarios/guardar', ['as' => 'usuarioGuardar', 'uses' => 'App\Http\Controllers\usuarios\UsuarioController@store']);
		Route::get('usuarios/actualizar/{id}', ['as' => 'usuarioEditar', 'uses' => 'App\Http\Controllers\usuarios\UsuarioController@actualizar']);
		Route::put('usuarios/actualizar/{id}', ['as' => 'usuarioActualizar', 'uses' => 'App\Http\Controllers\usuarios\UsuarioController@update']);
		Route::delete('usuarios/desactivar/{id}', ['as' => 'usuarioDesactivar', 'uses' => 'App\Http\Controllers\usuarios\UsuarioController@destroy']);
		
		//buscadores
		Route::get('usuarios/getDepartamentos/{idOrgano}', ['as' => 'usuarioDepartamento', 'uses' => 'App\Http\Controllers\usuarios\UsuarioController@getDepartamentos']);
		Route::get('unidad/getOrgano/{idUnidad}', ['as' => 'unidadOrgano', 'uses' => 'App\Http\Controllers\catalogos\CatalogoUnidadController@getOrganos']);
		Route::get('departamentos/servicios/{idOrgano}', ['as' => 'serviciosDepartamentos', 'uses' => 'App\Http\Controllers\catalogos\CatalogoUnidadController@getServiciosDepartamentos']);
});
