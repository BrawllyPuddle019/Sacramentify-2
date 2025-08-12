<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ActaController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\SacerdoteController;
use App\Http\Controllers\ObispoController;
use App\Http\Controllers\DiocesiController;
use App\Http\Controllers\ParroquiaController;
use App\Http\Controllers\ErmitaController;
use App\Http\Controllers\PlaticaController;
use App\Http\Controllers\SacramentoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\CalendarioController;



Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();

// Google OAuth Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Ruta para generar reporte PDF del dashboard
Route::get('/dashboard/report/pdf', [App\Http\Controllers\HomeController::class, 'generateReport'])->name('dashboard.report.pdf')->middleware('auth');

Route::resource('personas', PersonaController::class);

// Rutas de Actas con middleware de créditos
Route::resource('actas', ActaController::class)->except(['create', 'store']);
Route::get('/actas/create', [ActaController::class, 'create'])->name('actas.create')->middleware('check.credits');
Route::post('/actas', [ActaController::class, 'store'])->name('actas.store')->middleware('check.credits');
Route::post('/actas/validar-sacramento', [ActaController::class, 'validarSacramento'])->name('actas.validar-sacramento');
Route::post('/actas/verificar-duplicado', [ActaController::class, 'verificarDuplicado'])->name('actas.verificar-duplicado');

// Rutas para Soft Deletes de Actas
Route::get('/actas/trashed/index', [ActaController::class, 'trashed'])->name('actas.trashed');
Route::patch('/actas/{id}/restore', [ActaController::class, 'restore'])->name('actas.restore');
Route::delete('/actas/{id}/force-delete', [ActaController::class, 'forceDelete'])->name('actas.force-delete');

Route::resource('municipios', MunicipioController::class);

Route::resource('estados', EstadoController::class);

Route::resource('sacerdotes', SacerdoteController::class);

Route::resource('obispos', ObispoController::class);

Route::resource('diocesis', DiocesiController::class);

Route::resource('ermitas', ErmitaController::class);
// Rutas para gestión de ubicaciones de ermitas
Route::get('/ermitas/{ermita}/location', [ErmitaController::class, 'getLocation'])->name('ermitas.location.get');
Route::post('/ermitas/{ermita}/location', [ErmitaController::class, 'saveLocation'])->name('ermitas.location.save');

Route::resource('parroquias', ParroquiaController::class);
// Rutas para gestión de ubicaciones de parroquias
Route::get('/parroquias/{parroquia}/location', [ParroquiaController::class, 'getLocation'])->name('parroquias.location.get');
Route::post('/parroquias/{parroquia}/location', [ParroquiaController::class, 'saveLocation'])->name('parroquias.location.save');

Route::resource('platicas', PlaticaController::class);

Route::resource('sacramentos', SacramentoController::class);


Route::get('/load-counters', [HomeController::class, 'loadCounters'])->name('load-counters');


Route::resource('users', UserController::class);

Route::patch('users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])
    ->name('users.toggle-admin')
    ->middleware('superadmin');


Route::get('municipios', [MunicipioController::class, 'index'])->name('municipios.index');
Route::get('municipios/create', [MunicipioController::class, 'create'])->name('municipios.create');
Route::post('municipios', [MunicipioController::class, 'store'])->name('municipios.store');
// Otros métodos para editar y eliminar municipios

Route::get('estados', [EstadoController::class, 'index'])->name('estados.index');
Route::get('estados/create', [EstadoController::class, 'create'])->name('estados.create');
Route::post('estados', [EstadoController::class, 'store'])->name('estados.store');
// Otros métodos para editar y eliminar estados

Route::get('sacerdotes', [SacerdoteController::class, 'index'])->name('sacerdotes.index');
Route::get('sacerdotes/create', [SacerdoteController::class, 'create'])->name('sacerdotes.create');
Route::post('sacerdotes', [SacerdoteController::class, 'store'])->name('sacerdotes.store');
// Otros métodos para editar y eliminar municipios

Route::get('obispos', [ObispoController::class, 'index'])->name('obispos.index');
Route::get('obispos/create', [ObispoController::class, 'create'])->name('obispos.create');
Route::post('obispos', [ObispoController::class, 'store'])->name('obispos.store');
// Otros métodos para editar y eliminar estados


Route::get('/personas/{id}/pdf', [PersonaController::class, 'generarPDF'])->name('personas.pdf');

Route::get('/actas/{id}/pdf', [ActaController::class, 'generarPDF'])->name('actas.pdf');

// Email Routes
Route::get('/actas/{acta}/email/form', [EmailController::class, 'showSendForm'])->name('actas.email.form');
Route::post('/actas/{acta}/email/send', [EmailController::class, 'sendActaPdf'])->name('actas.email.send');
Route::get('/actas/{acta}/email/history', [EmailController::class, 'getEmailHistory'])->name('actas.email.history');
Route::get('/email/config', [EmailController::class, 'checkEmailConfig'])->name('email.config');

// Calendario Routes
Route::prefix('calendario')->group(function () {
    Route::get('/', [CalendarioController::class, 'index'])->name('calendario.index');
    Route::get('/eventos', [CalendarioController::class, 'obtenerEventos'])->name('calendario.eventos');
    Route::post('/eventos', [CalendarioController::class, 'store'])->name('calendario.store');
    Route::get('/eventos/{evento}', [CalendarioController::class, 'show'])->name('calendario.show');
    Route::put('/eventos/{evento}', [CalendarioController::class, 'update'])->name('calendario.update');
    Route::delete('/eventos/{evento}', [CalendarioController::class, 'destroy'])->name('calendario.destroy');
    Route::patch('/eventos/{evento}/estado', [CalendarioController::class, 'cambiarEstado'])->name('calendario.cambiar-estado');
});

// Payment Routes
use App\Http\Controllers\PaymentController;

Route::prefix('payments')->middleware('auth')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/history', [PaymentController::class, 'history'])->name('payments.history');
    Route::post('/checkout', [PaymentController::class, 'createCheckoutSession'])->name('payments.checkout');
    Route::get('/success/{payment}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/cancel/{payment}', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

// Stripe Webhook (sin middleware de autenticación)
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');






