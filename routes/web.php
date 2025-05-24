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

Route::put('clients/{id}/updateStatus', [client::class, 'updateStatus'])->name('client.updateStatus')->middleware('auth');



// llamada actualizaciones
// Route::put('/llamada/update/{id}', [client::class, 'updateLlamad'])->name('client.updateLlamada')->middleware('auth');

// informacion

Route::put('/actualizar-informacion', [client::class, 'updatei'])->name('actualizarInformacion');







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
Route::get('/cotizaciones/Nueva-Cotizacion', [CotizacionController::class, 'create'])->name('cotizacion-newCotizacion')->middleware('auth');
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
