<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\PrescriptionsController;


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
// routes/web.php



Route::get('/', function () {
    return redirect('home');
});

Route::get('/home', function () {
    return view('dashboard');
})->middleware(['auth','verified'])->name('dashboard');

Route::post('api/fetch-doctors', [PrescriptionsController::class, 'fetchDoctors']);

Route::resource('patients', PatientsController::class)->except(['index','create'])->middleware('role:patient','verified');

Route::get('profile', [PatientsController::class,'showEmail'])->name('myprofile')->middleware('role:patient','verified');

Route::resource('prescriptions', PrescriptionsController::class,[
    'only' => ['create','destroy','store']
])->middleware('role:patient','verified');

Route::resource('prescriptions', PrescriptionsController::class,[
    'only' => ['edit','update']
])->middleware('role:doctor','verified');

Route::group(['middleware' => ['role:doctor,patient']], function () {
    Route::resource('prescriptions', PrescriptionsController::class,[
        'only' => ['index','show']
    ]);
});

Route::get('/download/{id}', [PrescriptionsController::class,'downloadPDF'])->name('prescriptions.download')->middleware('role:patient','verified');

Route::resource('doctors', DoctorsController::class)->except(['edit'])->middleware('role:admin','verified');


require __DIR__.'/auth.php';


#create, read, update, destroy, index
