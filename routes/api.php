<?php

use App\Http\Controllers\PromotionCodeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/user/register', [UserController::class, 'userRegister']);
Route::put('/user/update', [UserController::class, 'userUpdate']);

Route::get('/backoffice/promotion-codes',[PromotionCodeController::class,'getPromotionCodes']);
Route::post('/backoffice/promotion-codes',[PromotionCodeController::class,'promotionCodeRegister']);
Route::get('/backoffice/promotion-codes/{id}',[PromotionCodeController::class,'getPromotionCodeById']);
Route::post('/assign-promotion',[PromotionCodeController::class,'assignPromotionCode']);

Route::group(['middleware' => ['jwt.verify']], function() {

});
