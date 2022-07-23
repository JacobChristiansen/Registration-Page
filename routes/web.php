<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;

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
    if(is_resource(@fsockopen(env('AZ_SERVER_IP'),env('AZ_SERVER_PORT'), $errno, $errstr, 3)))
    {
        $status = true;
        fclose(@fsockopen(env('AZ_SERVER_IP'),env('AZ_SERVER_PORT')));
    }
    else
        $status = false;
    $uptime = DB::table('uptime')->orderby('starttime', 'desc')->first();
    return view('home', ['uptime' => $uptime, 'status' => $status]);
});
Route::post('/register', [RegisterController::class, 'main']);