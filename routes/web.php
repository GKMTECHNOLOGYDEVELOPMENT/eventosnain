<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\client\client;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\Calendario\Calendario;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\condiciones\CondicionesController;
use App\Http\Controllers\cotizaciones\CotizacionController;
use App\Http\Controllers\dashboard\Usuario;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\modulo\ModuloController;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\usuario\UsuarioController;
use App\Models\CondicionComercial;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Main Page Route


Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics')->middleware('auth');
Route::get('/usuario', [Usuario::class, 'index'])->name('dashboard-usuario')->middleware('auth');


//Client
Route::get('/client/clientList', [Client::class, 'index'])->name('client-clientList')->middleware('auth');
Route::get('/client/newClient', [Client::class, 'create'])->name('client-newClient')->middleware('auth');
Route::post('/cliente', [Client::class, 'store'])->name('client.store')->middleware('auth');
Route::get('/cliente/{id}/edit', [Client::class, 'edit'])->name('client.edit')->middleware('auth');
Route::put('/cliente/{id}', [Client::class, 'update'])->name('client.update')->middleware('auth');
Route::get('/cliente/{id}/status', [Client::class, 'status'])->name('client.status')->middleware('auth');
Route::get('/cliente/{id}/notification', [Client::class, 'notification'])->name('client.notification');
Route::get('/cliente/{id}/connections', [Client::class, 'connections'])->name('client.connections');
Route::get('/cliente/{id}', [Client::class, 'status'])->name('client.status')->middleware('auth');
Route::get('/clientes', [client::class, 'index'])->name('clientes.index')->middleware('auth');

//proceso
Route::get('/cliente/{id}/proceso', [Client::class, 'proceso'])->name('client.proceso')->middleware('auth');
Route::get('/cliente/{id}/informacion', [Client::class, 'informacion'])->name('client.informacion')->middleware('auth');
Route::get('/cliente/{id}/cotizaciones', [Client::class, 'cotizaciones'])->name('client.cotizaciones')->middleware('auth');
Route::post('/client/update-proceso', [client::class, 'updateproceso'])->name('client.updateproceso')->middleware('auth');
Route::post('/send-mail', [client::class, 'sendMail'])->name('send.mail')->middleware('auth');
Route::post('/cliente/store', [client::class, 'atencion'])->name('client.atencion')->middleware('auth');
//Usuarios 

Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
Route::delete('/usuarios/delete/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios/store', [UsuarioController::class, 'store'])->name('usuarios.store');

//calendario
Route::get('/client/clientCalendario', [Client::class, 'calendario'])->name('client-clientCalendario')->middleware('auth');

Route::post('client/events', [client::class, 'evento'])->name('events.store');


Route::get('/client/clientReunion', [Client::class, 'reunion'])->name('client-clientReunion')->middleware('auth');
Route::post('/client/store-call', [client::class, 'storeCall'])->name('client.storeCall')->middleware('auth');

Route::post('/client/store-reunion', [client::class, 'storeReunion'])->name('client.store-reunion')->middleware('auth');
Route::put('/client/update-reunion/{id_reunion}', [client::class, 'updateReunion'])->name('client.update-reunion')->middleware('auth');;

// routes/web.php
Route::post('/update-contract-status/{id}', [client::class, 'updateContractStatus'])->name('updateContractStatus')->middleware('auth');

// foto

Route::post('/client/{id}/upload-photo', [client::class, 'uploadPhoto'])->name('client.uploadPhoto')->middleware('auth');

// // web.php
// Route::put('/client/update-reunion/{id_reunion}', [client::class, 'updatere'])->name('client.update-reunion');


// routes/web.php

Route::put('clients/{id}/updateStatus', [client::class, 'updateStatus'])->name('client.updateStatus');



// llamada actualizaciones
// Route::put('/llamada/update/{id}', [client::class, 'updateLlamad'])->name('client.updateLlamada')->middleware('auth');

// informacion

Route::put('/actualizar-informacion', [client::class, 'updatei'])->name('actualizarInformacion');



Route::get('/cotizaciones/next-code', [CotizacionController::class, 'getNextCode'])->name('cotizaciones.next-code');




Route::post('client/enviar-cotizacion', [client::class, 'enviarCotizacion'])->name('enviarCotizacion');

Route::post('/client/update-contrato', [client::class, 'updateContrato'])->name('client.updateContrato');




Route::delete('/cliente/{id}', [Client::class, 'destroy'])->name('client.destroy')->middleware('auth');
// layout
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu')->middleware('auth');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar')->middleware('auth');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid')->middleware('auth');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container')->middleware('auth');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank')->middleware('auth');

// pages
Route::get('/pages/account-settings-account/{id?}', [AccountSettingsAccount::class, 'index'])
    ->name('pages-account-settings-account')
    ->middleware('auth');



Route::post('/pages/account-settings-account/update', [AccountSettingsAccount::class, 'update'])->name('update-account')->middleware('auth');


Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications')->middleware('auth');
Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections')->middleware('auth');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error')->middleware('auth');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance')->middleware('auth');



// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::post('/auth/login-basic', [LoginBasic::class, 'login']); // Asegúrate de que esta ruta sea POST

// Registro
Route::post('/logout', [LoginBasic::class, 'logout'])->name('logout');

Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic')->middleware('auth');
Route::post('/auth/register-basic', [RegisterBasic::class, 'register'])->name('auth-register');

// calendario
// routes/web.php
// Define la ruta para almacenar eventos
Route::post('client/client/clientCalendario', [client::class, 'storeEvento'])->name('client.evento');



Route::post('/eventos', [client::class, 'salida'])->name('client.salida');





// Ruta para mostrar el formulario de solicitud de restablecimiento de contraseña
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-forgot-password');

// Ruta para enviar el enlace de restablecimiento de contraseña
Route::post('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'sendResetLink'])->name('send-reset-link');

// Ruta para mostrar el formulario de restablecimiento de contraseña
Route::get('/auth/reset-password/{token}', [ForgotPasswordBasic::class, 'showResetForm'])->name('auth-reset-password-form');

// Ruta para procesar el restablecimiento de contraseña
Route::post('/auth/reset-password', [ForgotPasswordBasic::class, 'reset'])->name('auth-reset-password-post');

// Ruta para la página de restablecimiento de contraseña
Route::get('auth/reset-password/{token}', [ForgotPasswordBasic::class, 'showResetForm'])->name('password.reset');

// Ruta para el envío del formulario de restablecimiento de contraseña
Route::post('auth/reset-password', [ForgotPasswordBasic::class, 'reset'])->name('password.update');

// Ruta para el formulario de recuperacion
Route::post('auth/reset-password', [ForgotPasswordBasic::class, 'reset'])->name('auth-reset-password-post');



// cards
Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');


//Cotizaciones
Route::get('/clientes/search', [CotizacionController::class, 'search'])
    ->name('clientes.search');
//Modulo
Route::get('/modulo', [ModuloController::class, 'index'])->name('modulo-list')->middleware('auth');
Route::get('/modulo/Crear-Modulo', [ModuloController::class, 'create'])->name('new-modulo')->middleware('auth');
Route::post('/modulo/store', [ModuloController::class, 'store'])->name('modulos.store');
Route::get('/modulos/editar/{modulo}', [ModuloController::class, 'edit'])->name('modulos.edit');
Route::put('/modulos/actualizar/{modulo}', [ModuloController::class, 'update'])->name('modulos.update');
Route::delete('/modulos/{modulo}', [ModuloController::class, 'destroy'])
    ->name('modulos.destroy');
Route::delete('modulo/eliminar/imagenes/{id}', [ModuloController::class, 'destroyImagen'])->name('imagenes.destroy');
Route::post('/modulos/{modulo}/upload-imagenes', [ModuloController::class, 'uploadImagenes'])->name('modulos.uploadImagenes');
//COTIZACIONES
Route::get('/cotizaciones/Nueva-Cotizacion', [CotizacionController::class, 'create'])->name('cotizacion-newCotizacion')->middleware('auth');
Route::get('/cotizaciones', [CotizacionController::class, 'index'])->name('cotizaciones.index');
Route::post('/cotizaciones/store', [CotizacionController::class, 'store'])->name('cotizaciones.store');
Route::get('/cotizaciones/{id}/imprimir', [CotizacionController::class, 'imprimir'])->name('cotizaciones.imprimir');
Route::get('/cotizaciones/Nueva-Cotizacion', [CotizacionController::class, 'create'])->name('cotizacion-newCotizacion')->middleware('auth');
Route::get('/clientes/search', [CotizacionController::class, 'search'])
    ->name('clientes.search');
Route::get('/cotizaciones/{id}/pdf', [CotizacionController::class, 'exportarPdf'])->name('cotizaciones.exportarPdf');
Route::get('/cotizaciones/{cotizacion}/edit', [CotizacionController::class, 'edit'])->name('cotizaciones.edit');
Route::put('/cotizaciones/update/{cotizacion}', [CotizacionController::class, 'update'])->name('cotizaciones.update');
Route::get('cotizaciones/detalles/{id}', [CotizacionController::class, 'show'])->name('cotizaciones.show');
Route::put('/cotizaciones/{cotizacion}/estado', [CotizacionController::class, 'actualizarEstado'])->name('cotizaciones.actualizarEstado');
//CONDICIONES
Route::get('/condiciones/Nueva-Condicion', [CondicionesController::class, 'create'])->name('condiciones-newCondiciones')->middleware('auth');
Route::post('/condiciones-comerciales/guardar', [CondicionesController::class, 'guardar'])->name('condiciones.guardar');
Route::get('/condiciones', [CondicionesController::class, 'index'])->name('condiciones.index');
Route::get('/condiciones/{id}/edit', [CondicionesController::class, 'edit'])->name('condiciones.edit');
Route::put('/condiciones/{id}', [CondicionesController::class, 'update'])->name('condiciones.update');
Route::delete('/condiciones/{id}', [CondicionesController::class, 'destroy'])->name('condiciones.destroy');
Route::get('/condiciones-comerciales/{id}/descripcion', function ($id) {
    $condicion = CondicionComercial::findOrFail($id);
    return response()->json(['descripcion' => $condicion->descripcion]);
});
// User Interface
Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// icons
Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');

// form elements
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

// tables
Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');


// Archivo: routes/web.php



// Definir la ruta para eliminar la reunión








// Ruta para actualizar una reunión (método PUT)
Route::put('/reuniones/{id}', [client::class, 'updateReuniones'])->name('client.update-reuniones');

// Ruta para eliminar una reunión (método DELETE)
Route::delete('/reuniones/{id}', [client::class, 'destroyReunion'])->name('client.destroy-reunion');




// Ruta para actualizar una llamada (método PUT)
Route::put('/llamadas/{id}', [client::class, 'updateLlamada'])->name('client.update-llamada');

// Ruta para eliminar una llamada (método DELETE)
Route::delete('/llamadas/{id}', [client::class, 'destroyLlamada'])->name('client.destroy-llamada');


// Ruta para actualizar la información (método PUT)
Route::put('/informacion/{id}', [client::class, 'updateInformacion'])->name('client.update');

// Ruta para eliminar la información (método DELETE)
Route::delete('/informacion/{id}', [client::class, 'destroyInformacion'])->name('client.destroy');













// En web.php
// Route::delete('/events/{id}', [client::class, 'destroyevento'])->name('client.destroyevento');




// web.php
Route::put('/event/updateevento', [client::class, 'updateevento'])->name('client.updateevento');
Route::delete('/events/{id}', [client::class, 'destroyevento'])->name('client.destroyevento');


// web.php

// Ruta para actualizar la información (método PUT)
Route::put('/informacion/{id}', [client::class, 'updateInformacion'])->name('client.update-informacion');

// Ruta para eliminar la información (método DELETE)
Route::delete('/informacion/{id}', [client::class, 'destroyInformacion'])->name('client.destroy-informacion');


// En routes/web.php o routes/api.php
Route::post('/obtener-meta-por-evento', [Analytics::class, 'obtenerMetaPorEvento'])->name('analytics.obtener')->middleware('auth');

Route::get('/getTotalClientesPorEvento/{eventoId}', [Analytics::class, 'getTotalClientesPorEvento']);


Route::get('/generar-pdf-usuario/{userId}/{eventoId}/{fechaInicio}/{fechaFin}', [Analytics::class, 'generarPdfUsuario']);





// Calendario 

// En routes/web.php o routes/api.php
Route::post('/actualizar-llamada', [client::class, 'actualizar'])->name('llamada.actualizar');
Route::post('/actualizar-reunion', [client::class, 'actualizarreunion'])->name('reunion.actualizar');
Route::post('/actualizar-informacion', [client::class, 'actualizarinformacion'])->name('informacion.actualizar');


//eliminar

Route::post('/llamada/eliminar', [client::class, 'eliminar'])->name('llamada.eliminar');
Route::post('/reunion/eliminar', [client::class, 'eliminarReunion'])->name('reunion.eliminar');
// En web.php
Route::post('/evento/eliminar', [client::class, 'eliminarEvento'])->name('evento.eliminar');
Route::post('/evento/actualizar', [client::class, 'actualizarevento'])->name('evento.actualizar');

Route::post('/salida/actualizar', [client::class, 'actualizarSalida'])->name('salida.actualizar');
Route::post('/salida/eliminar', [client::class, 'eliminarSalida'])->name('salida.eliminar');


// routes/web.php
Route::post('/salida_user/guardar', [client::class, 'guardar'])->name('salida_user.guardar');
Route::get('/clientes', [Client::class, 'getClientes'])->name('api.clientes')->middleware('auth');


//evento 
Route::get('/evento/{id}/datos', function($id) {
      // Obtener fechas para mes actual y anterior
     $now = now();

    // Usamos copy() para evitar modificar la misma instancia
    $mesActualInicio = $now->copy()->startOfMonth()->format('Y-m-d');
    $mesActualFin = $now->copy()->endOfMonth()->format('Y-m-d');

    $mesAnterior = $now->copy()->subMonth();
    $mesAnteriorInicio = $mesAnterior->copy()->startOfMonth()->format('Y-m-d');
    $mesAnteriorFin = $mesAnterior->copy()->endOfMonth()->format('Y-m-d');

    // 🔍 Log para depurar fechas
    Log::info('Fechas de consulta cotizaciones:', [
        'mesActualInicio' => $mesActualInicio,
        'mesActualFin' => $mesActualFin,
        'mesAnteriorInicio' => $mesAnteriorInicio,
        'mesAnteriorFin' => $mesAnteriorFin
    ]);
    // Datos para cuando no hay ID (general)
    if($id == 'general') {
         $totalCotizaciones = DB::table('cotizaciones')->count();
        $cotizacionesMesActual = DB::table('cotizaciones')
            ->whereBetween('fecha_emision', [$mesActualInicio, $mesActualFin])
            ->count();
        $cotizacionesMesAnterior = DB::table('cotizaciones')
            ->whereBetween('fecha_emision', [$mesAnteriorInicio, $mesAnteriorFin])
            ->count();
        return response()->json([
             'cotizaciones_mes_actual' => $cotizacionesMesActual,
            'cotizaciones_mes_anterior' => $cotizacionesMesAnterior,
            'total_cotizaciones' => $totalCotizaciones,
            'porcentaje_mes_actual' => $totalCotizaciones > 0 ? ($cotizacionesMesActual / $totalCotizaciones) * 100 : 0,
            'porcentaje_mes_anterior' => $totalCotizaciones > 0 ? ($cotizacionesMesAnterior / $totalCotizaciones) * 100 : 0,
            'monto_mes_actual' => DB::table('cotizaciones')
                ->whereBetween('fecha_emision', [$mesActualInicio, $mesActualFin])
                ->sum('total_con_igv'),
            'monto_mes_anterior' => DB::table('cotizaciones')
                ->whereBetween('fecha_emision', [$mesAnteriorInicio, $mesAnteriorFin])
                ->sum('total_con_igv'),
            'total_clientes' => DB::table('cliente')->count(),
            'total_cotizaciones' => DB::table('cotizaciones')->count(),
            'tasa_exito' => DB::table('cotizaciones')->where('estado', 'aprobada')->count() / 
                            max(DB::table('cotizaciones')->count(), 1) * 100,
            'cotizaciones_vencidas' => DB::table('cotizaciones')
                                      ->where('estado', 'vencida')
                                      ->count(),
            'meta_registros' => DB::table('salida')->sum('meta_registros')

            
        ]);
    }
    
    // Datos específicos del evento
    $evento = DB::table('salida')->select('meta_registros')->find($id);
    
     $totalCotizacionesEvento = DB::table('cotizaciones')
        ->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
        ->where('cliente.events_id', $id)
        ->count();
        
    $cotizacionesMesActualEvento = DB::table('cotizaciones')
        ->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
        ->where('cliente.events_id', $id)
        ->whereBetween('cotizaciones.fecha_emision', [$mesActualInicio, $mesActualFin])
        ->count();
        
    $cotizacionesMesAnteriorEvento = DB::table('cotizaciones')
        ->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
        ->where('cliente.events_id', $id)
        ->whereBetween('cotizaciones.fecha_emision', [$mesAnteriorInicio, $mesAnteriorFin])
        ->count();

    return response()->json([
        'meta_registros' => $evento ? $evento->meta_registros : 0,
        'total_clientes' => DB::table('cliente')->where('events_id', $id)->count(),
        'total_cotizaciones' => DB::table('cotizaciones')
                                ->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
                                ->where('cliente.events_id', $id)
                                ->count(),
        'tasa_exito' => DB::table('cotizaciones')
                        ->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
                        ->where('cliente.events_id', $id)
                        ->where('cotizaciones.estado', 'aprobada')
                        ->count() / 
                        max(DB::table('cotizaciones')
                            ->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
                            ->where('cliente.events_id', $id)
                            ->count(), 1) * 100,
        'cotizaciones_vencidas' => DB::table('cotizaciones')
                                  ->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
                                  ->where('cliente.events_id', $id)
                                  ->where('cotizaciones.estado', 'vencida')
                                  ->count(),

                                   'cotizaciones_mes_actual' => $cotizacionesMesActualEvento,
        'cotizaciones_mes_anterior' => $cotizacionesMesAnteriorEvento,
        'total_cotizaciones' => $totalCotizacionesEvento,
        'porcentaje_mes_actual' => $totalCotizacionesEvento > 0 ? ($cotizacionesMesActualEvento / $totalCotizacionesEvento) * 100 : 0,
        'porcentaje_mes_anterior' => $totalCotizacionesEvento > 0 ? ($cotizacionesMesAnteriorEvento / $totalCotizacionesEvento) * 100 : 0,
        'monto_mes_actual' => DB::table('cotizaciones')
            ->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
            ->where('cliente.events_id', $id)
            ->whereBetween('cotizaciones.fecha_emision', [$mesActualInicio, $mesActualFin])
            ->sum('cotizaciones.total_con_igv'),
        'monto_mes_anterior' => DB::table('cotizaciones')
            ->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
            ->where('cliente.events_id', $id)
            ->whereBetween('cotizaciones.fecha_emision', [$mesAnteriorInicio, $mesAnteriorFin])
            ->sum('cotizaciones.total_con_igv')
    ]);
});


// Rutas para métricas de seguimiento
Route::get('/metricas-seguimiento/{eventoId}', function($eventoId) {
    $now = now();
    
    // Clientes en riesgo (sin contacto)
    $clientesRiesgo = DB::table('cliente')
        ->when($eventoId != 'general', function($query) use ($eventoId) {
            return $query->where('events_id', $eventoId);
        })
        ->whereNull('llamada')
        ->whereNull('whatsapp')
        ->whereNull('reunion')
        ->whereNull('contrato')
        ->select('id', 'nombre', 'empresa', 'fecharegistro')
        ->get();

    // Promedio días desde registro
    $promedioDias = DB::table('cliente')
        ->when($eventoId != 'general', function($query) use ($eventoId) {
            return $query->where('events_id', $eventoId);
        })
        ->selectRaw('AVG(DATEDIFF(?, fecharegistro)) as promedio', [$now->format('Y-m-d')])
        ->first();

    // Proceso estancado (>15 días sin interacción)
    $procesoEstancado = DB::table('cliente')
        ->when($eventoId != 'general', function($query) use ($eventoId) {
            return $query->where('events_id', $eventoId);
        })
        ->whereRaw('DATEDIFF(?, fecharegistro) > 15', [$now->format('Y-m-d')])
        ->where(function($query) {
            $query->whereNull('llamada')
                  ->orWhereNull('whatsapp')
                  ->orWhereNull('reunion')
                  ->orWhereNull('contrato');
        })
        ->select('id', 'nombre', 'empresa', 'fecharegistro', 'llamada', 'whatsapp', 'reunion', 'contrato')
        ->get();

    // Interacción completa (todos los canales)
    $interaccionCompleta = DB::table('cliente')
        ->when($eventoId != 'general', function($query) use ($eventoId) {
            return $query->where('events_id', $eventoId);
        })
        ->whereNotNull('llamada')
        ->whereNotNull('whatsapp')
        ->whereNotNull('reunion')
        ->whereNotNull('contrato')
        ->select('id', 'nombre', 'empresa', 'fecharegistro')
        ->get();

    return response()->json([
        'clientes_riesgo' => $clientesRiesgo,
        'promedio_dias' => round($promedioDias->promedio ?? 0),
        'proceso_estancado' => $procesoEstancado,
        'interaccion_completa' => $interaccionCompleta,
        'total_clientes_riesgo' => count($clientesRiesgo),
        'total_proceso_estancado' => count($procesoEstancado),
        'total_interaccion_completa' => count($interaccionCompleta)
    ]);
});

// Ruta para cargar detalles de clientes (usada en los modals)
Route::get('/metricas-seguimiento-detalle/{tipo}/{eventoId}', function($tipo, $eventoId) {
    $now = now();
    
    switch($tipo) {
        case 'riesgo':
            $clientes = DB::table('cliente')
                ->when($eventoId != 'general', function($query) use ($eventoId) {
                    return $query->where('events_id', $eventoId);
                })
                ->whereNull('llamada')
                ->whereNull('whatsapp')
                ->whereNull('reunion')
                ->whereNull('contrato')
                ->select('id', 'nombre', 'empresa', 'email', 'telefono', 'fecharegistro')
                ->get();
            break;
            
        case 'estancado':
            $clientes = DB::table('cliente')
                ->when($eventoId != 'general', function($query) use ($eventoId) {
                    return $query->where('events_id', $eventoId);
                })
                ->whereRaw('DATEDIFF(?, fecharegistro) > 15', [$now->format('Y-m-d')])
                ->where(function($query) {
                    $query->whereNull('llamada')
                          ->orWhereNull('whatsapp')
                          ->orWhereNull('reunion')
                          ->orWhereNull('contrato');
                })
                ->select('id', 'nombre', 'empresa', 'email', 'telefono', 'fecharegistro', 'llamada', 'whatsapp', 'reunion', 'contrato')
                ->get();
            break;
            
        case 'completa':
            $clientes = DB::table('cliente')
                ->when($eventoId != 'general', function($query) use ($eventoId) {
                    return $query->where('events_id', $eventoId);
                })
                ->whereNotNull('llamada')
                ->whereNotNull('whatsapp')
                ->whereNotNull('reunion')
                ->whereNotNull('contrato')
                ->select('id', 'nombre', 'empresa', 'email', 'telefono', 'fecharegistro')
                ->get();
            break;
            
        default:
            return response()->json([], 404);
    }
    
    return response()->json($clientes);
});


// Rutas para los tops de clientes y vendedores
Route::get('/top-metricas/{eventoId}', function($eventoId) {
    // Obtener los últimos 5 meses
    $now = now();
    $meses = [];
    $mesesNombres = [];
    for ($i = 4; $i >= 0; $i--) {
        $mes = $now->copy()->subMonths($i);
        $meses[] = $mes->format('Y-m');
        $mesesNombres[] = $mes->locale('es')->shortMonthName;
    }

    // Top 4 clientes con mayor monto cotizado
    $topClientes = DB::table('cotizaciones')
        ->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
        ->when($eventoId != 'general', function($query) use ($eventoId) {
            return $query->where('cliente.events_id', $eventoId);
        })
        ->select(
            'cliente.nombre as cliente_nombre',
            DB::raw('SUM(cotizaciones.total_con_igv) as monto_total'),
            DB::raw('COUNT(cotizaciones.id) as cantidad_cotizaciones')
        )
        ->groupBy('cliente.id', 'cliente.nombre')
        ->orderByDesc('monto_total')
        ->limit(4)
        ->get()
        ->pluck('cliente_nombre')
        ->toArray();

    // Datos mensuales para cada cliente top
    $datosClientes = [];
    foreach ($topClientes as $cliente) {
        $montosPorMes = [];
        foreach ($meses as $mes) {
            $monto = DB::table('cotizaciones')
                ->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
                ->where('cliente.nombre', $cliente)
                ->when($eventoId != 'general', function($query) use ($eventoId) {
                    return $query->where('cliente.events_id', $eventoId);
                })
                ->where(DB::raw("DATE_FORMAT(cotizaciones.fecha_emision, '%Y-%m')"), $mes)
                ->sum('cotizaciones.total_con_igv');
            
            $montosPorMes[] = $monto ? round($monto, 2) : 0;
        }
        $datosClientes[$cliente] = $montosPorMes;
    }

    // Top 4 vendedores con más cotizaciones
    $topVendedores = DB::table('cotizaciones')
        ->join('users', 'cotizaciones.user_id', '=', 'users.id')
        ->when($eventoId != 'general', function($query) use ($eventoId) {
            return $query->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
                        ->where('cliente.events_id', $eventoId);
        })
        ->select(
            'users.name as vendedor_nombre',
            DB::raw('COUNT(cotizaciones.id) as cantidad_cotizaciones')
        )
        ->groupBy('users.id', 'users.name')
        ->orderByDesc('cantidad_cotizaciones')
        ->limit(4)
        ->get()
        ->pluck('vendedor_nombre')
        ->toArray();

    // Datos mensuales para cada vendedor top
    $datosVendedores = [];
    foreach ($topVendedores as $vendedor) {
        $cotizacionesPorMes = [];
        foreach ($meses as $mes) {
            $count = DB::table('cotizaciones')
                ->join('users', 'cotizaciones.user_id', '=', 'users.id')
                ->when($eventoId != 'general', function($query) use ($eventoId) {
                    return $query->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
                                ->where('cliente.events_id', $eventoId);
                })
                ->where('users.name', $vendedor)
                ->where(DB::raw("DATE_FORMAT(cotizaciones.fecha_emision, '%Y-%m')"), $mes)
                ->count();
            
            $cotizacionesPorMes[] = $count;
        }
        $datosVendedores[$vendedor] = $cotizacionesPorMes;
    }

    return response()->json([
        'meses' => $mesesNombres,
        'top_clientes' => $topClientes,
        'datos_clientes' => $datosClientes,
        'top_vendedores' => $topVendedores,
        'datos_vendedores' => $datosVendedores
    ]);
});


// Ruta para la tasa de seguimiento activo
Route::get('/seguimiento-activo/{eventoId}', function($eventoId) {
    // Total de clientes
    $totalClientes = DB::table('cliente')
        ->when($eventoId != 'general', function($query) use ($eventoId) {
            return $query->where('events_id', $eventoId);
        })
        ->count();

    // Clientes con al menos una interacción
    $clientesConInteraccion = DB::table('cliente')
        ->when($eventoId != 'general', function($query) use ($eventoId) {
            return $query->where('events_id', $eventoId);
        })
        ->where(function($query) {
            $query->whereNotNull('llamada')
                  ->orWhereNotNull('whatsapp')
                  ->orWhereNotNull('reunion')
                  ->orWhereNotNull('contrato');
        })
        ->count();

    // Calcular porcentaje
    $porcentaje = $totalClientes > 0 ? round(($clientesConInteraccion / $totalClientes) * 100) : 0;

    return response()->json([
        'total_clientes' => $totalClientes,
        'clientes_con_interaccion' => $clientesConInteraccion,
        'porcentaje' => $porcentaje,
        'clientes_sin_interaccion' => $totalClientes - $clientesConInteraccion
    ]);
});

// Ruta para obtener clientes con interacción
Route::get('/clientes-interaccion/{eventoId}', function($eventoId) {
    $clientes = DB::table('cliente')
        ->when($eventoId != 'general', function($query) use ($eventoId) {
            return $query->where('events_id', $eventoId);
        })
        ->where(function($query) {
            $query->whereNotNull('llamada')
                  ->orWhereNotNull('whatsapp')
                  ->orWhereNotNull('reunion')
                  ->orWhereNotNull('contrato');
        })
        ->select('id', 'nombre', 'telefono', 'email', 'llamada', 'whatsapp', 'reunion', 'contrato')
        ->get();

    return response()->json($clientes);
});

// Ruta para los canales de contacto (general y por evento)
Route::get('/canales-contacto/{scope}/{eventoId?}', function($scope, $eventoId = null) {
    // Validar el scope
    if (!in_array($scope, ['general', 'evento'])) {
        return response()->json(['error' => 'Scope inválido'], 400);
    }

    // Base query para interacciones
    $query = DB::table('cliente');
    
    // Filtrar por evento si es necesario
    if ($scope === 'evento' && $eventoId) {
        $query->where('events_id', $eventoId);
    }

    // Contar interacciones por tipo
    $llamadas = (clone $query)->whereNotNull('llamada')->count();
    $whatsapp = (clone $query)->whereNotNull('whatsapp')->count();
    $correos = (clone $query)->whereNotNull('correo')->count();
    $reuniones = (clone $query)->whereNotNull('reunion')->count();

    // Calcular total de interacciones (sin duplicar clientes con múltiples interacciones)
    $totalInteracciones = $llamadas + $whatsapp + $correos + $reuniones;

    // Calcular porcentajes
    $porcentajes = [
        'llamadas' => $totalInteracciones > 0 ? round(($llamadas / $totalInteracciones) * 100) : 0,
        'whatsapp' => $totalInteracciones > 0 ? round(($whatsapp / $totalInteracciones) * 100) : 0,
        'correos' => $totalInteracciones > 0 ? round(($correos / $totalInteracciones) * 100) : 0,
        'reuniones' => $totalInteracciones > 0 ? round(($reuniones / $totalInteracciones) * 100) : 0
    ];

    return response()->json([
        'scope' => $scope,
        'evento_id' => $scope === 'evento' ? $eventoId : null,
        'total_interacciones' => $totalInteracciones,
        'llamadas' => $llamadas,
        'whatsapp' => $whatsapp,
        'correos' => $correos,
        'reuniones' => $reuniones,
        'porcentajes' => $porcentajes
    ]);
});



// Ruta para el promedio de cotizaciones
Route::get('/cotizaciones-promedio/{scope}/{eventoId?}', function($scope, $eventoId = null) {
    // Validar scope
    if (!in_array($scope, ['general', 'evento'])) {
        return response()->json(['error' => 'Scope inválido'], 400);
    }

    // Obtener año seleccionado (o actual por defecto)
    $anio = request()->input('anio', date('Y'));
    
    $data = [];
    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
              'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    // Consulta base
    $query = DB::table('cotizaciones')
        ->selectRaw('MONTH(fecha_emision) as mes, AVG(total_con_igv) as promedio')
        ->whereYear('fecha_emision', $anio)
        ->groupBy('mes')
        ->orderBy('mes');

    // Filtrar por evento si es necesario
    if ($scope === 'evento' && $eventoId) {
        $query->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
              ->where('cliente.events_id', $eventoId);
    }

    $resultados = $query->get();

    // Preparar datos para el gráfico
    $promedios = array_fill(0, 12, 0);
    foreach ($resultados as $row) {
        $promedios[$row->mes - 1] = round($row->promedio, 2);
    }

    return response()->json([
        'scope' => $scope,
        'evento_id' => $scope === 'evento' ? $eventoId : null,
        'anio' => $anio,
        'meses' => array_slice($meses, 0, date('n')), // Solo meses hasta el actual
        'promedios' => array_slice($promedios, 0, date('n')),
    ]);
});

// Ruta para el estado de cotizaciones
Route::get('/cotizaciones-estado/{scope}/{eventoId?}', function($scope, $eventoId = null) {
    // Validar scope
    if (!in_array($scope, ['general', 'evento'])) {
        return response()->json(['error' => 'Scope inválido'], 400);
    }

    // Consulta base
    $query = DB::table('cotizaciones')
        ->selectRaw('estado, COUNT(*) as cantidad');

    // Filtrar por evento si es necesario
    if ($scope === 'evento' && $eventoId) {
        $query->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
              ->where('cliente.events_id', $eventoId);
    }

    $resultados = $query->groupBy('estado')->get();

    // Mapear estados a categorías consistentes
    $estados = [
        'Aprobadas' => 0,
        'Pendientes' => 0,
        'Rechazadas' => 0
    ];

    foreach ($resultados as $row) {
        if (in_array(strtolower($row->estado), ['aprobada', 'aceptada'])) {
            $estados['Aprobadas'] += $row->cantidad;
        } elseif (in_array(strtolower($row->estado), ['pendiente', 'en espera'])) {
            $estados['Pendientes'] += $row->cantidad;
        } else {
            $estados['Rechazadas'] += $row->cantidad;
        }
    }

    return response()->json([
        'scope' => $scope,
        'evento_id' => $scope === 'evento' ? $eventoId : null,
        'estados' => $estados,
        'total' => array_sum($estados)
    ]);
});



Route::get('/comparacion-semanal/{scope}/{eventoId?}', function($scope, $eventoId = null) {
    // Validar scope
    if (!in_array($scope, ['general', 'evento'])) {
        return response()->json(['error' => 'Scope inválido'], 400);
    }

    // Obtener fechas
    $hoy = Carbon::now();
    $inicioSemanaActual = $hoy->copy()->startOfWeek();
    $finSemanaActual = $hoy->copy()->endOfWeek();
    $inicioSemanaPasada = $hoy->copy()->subWeek()->startOfWeek();
    $finSemanaPasada = $hoy->copy()->subWeek()->endOfWeek();

    // Consulta base
    $queryActual = DB::table('cliente')
        ->selectRaw('DAYOFWEEK(fecharegistro) as dia_semana, COUNT(*) as cantidad')
        ->whereBetween('fecharegistro', [$inicioSemanaActual, $finSemanaActual])
        ->groupBy('dia_semana');

    $queryAnterior = DB::table('cliente')
        ->selectRaw('DAYOFWEEK(fecharegistro) as dia_semana, COUNT(*) as cantidad')
        ->whereBetween('fecharegistro', [$inicioSemanaPasada, $finSemanaPasada])
        ->groupBy('dia_semana');

    // Filtrar por evento si es necesario
    if ($scope === 'evento' && $eventoId) {
        $queryActual->where('events_id', $eventoId);
        $queryAnterior->where('events_id', $eventoId);
    }

    $resultadosActual = $queryActual->get()->keyBy('dia_semana');
    $resultadosAnterior = $queryAnterior->get()->keyBy('dia_semana');

    // Mapear días de la semana (1=Domingo, 2=Lunes...7=Sábado en MySQL)
    $diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
    $semanaActual = array_fill(0, 7, 0);
    $semanaAnterior = array_fill(0, 7, 0);

    foreach ($resultadosActual as $dia => $row) {
        $index = ($dia - 1) % 7; // Convertir a índice 0-6
        $semanaActual[$index] = $row->cantidad;
    }

    foreach ($resultadosAnterior as $dia => $row) {
        $index = ($dia - 1) % 7; // Convertir a índice 0-6
        $semanaAnterior[$index] = $row->cantidad;
    }

    // Reordenar para que la semana empiece en Lunes
    $semanaActual = array_merge(array_slice($semanaActual, 1), array_slice($semanaActual, 0, 1));
    $semanaAnterior = array_merge(array_slice($semanaAnterior, 1), array_slice($semanaAnterior, 0, 1));

    return response()->json([
        'scope' => $scope,
        'evento_id' => $scope === 'evento' ? $eventoId : null,
        'dias_semana' => $diasSemana,
        'semana_actual' => $semanaActual,
        'semana_anterior' => $semanaAnterior
    ]);
});