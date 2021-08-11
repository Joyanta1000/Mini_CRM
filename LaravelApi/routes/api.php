<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AuthBasic;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login', [UserController::class, 'login']);

 Route::middleware('auth:api')->get('/user', function (Request $request) {
     return $request->user();
 });

Route::post('add_company', [CompanyController::class, 'store']);

Route::get('show_companies', [CompanyController::class, 'index']);

Route::get('edit_companies_info/{id}', [CompanyController::class, 'edit']);

Route::post('update_companies_info/{id}', [CompanyController::class, 'update']);

Route::get('delete_companies_info/{id}', [CompanyController::class, 'delete']);

Route::get('company_info', [EmployeeController::class, 'company_info']);

Route::post('add_employee', [EmployeeController::class, 'store']);

Route::get('show_employees', [EmployeeController::class, 'index']);

Route::get('edit_employees_info/{id}', [EmployeeController::class, 'edit']);

Route::post('update_employees_info/{id}', [EmployeeController::class, 'update']);

Route::get('delete_employees_info/{id}', [EmployeeController::class, 'delete']);