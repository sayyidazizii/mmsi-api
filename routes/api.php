<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PPOBTransactionController;
use App\Http\Controllers\PulsaTransactionController;
use App\Http\Controllers\ListrikTransactionController;
use App\Http\Controllers\EMoneyTransactionController;
use App\Http\Controllers\BPJSTransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoreProgramController;
use App\Http\Controllers\AcctSavingsAccountController;
use App\Http\Controllers\PPOBTopUpController;
use App\Http\Controllers\WhatsappOTPController;

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

//Protected Route
Route::group(['middleware'=> ['auth:sanctum'], 'throttle:70,10'], function(){
    Route::get('/ppob-transaction', [PPOBTransactionController::class, 'index']);
    Route::post('/ppob-transaction', [PPOBTransactionController::class, 'store']);
    Route::get('/ppob-transaction/{id}', [PPOBTransactionController::class, 'show']);
    Route::get('/ppob-transactions/{id}', [PPOBTransactionController::class, 'shows']);
    Route::put('/ppob-transaction/{id}', [PPOBTransactionController::class, 'update']);
    Route::delete('/ppob-transaction/{id}', [PPOBTransactionController::class, 'destroy']);
    Route::get('/ppob-transaction/success/{member_id}', [PPOBTransactionController::class, 'success_transaction']);
    Route::get('/ppob-transaction/fail/{member_id}', [PPOBTransactionController::class, 'fail_transaction']);
    Route::get('/ppob-transaction/in-history/{member_id}', [PPOBTransactionController::class, 'getAcctSavingsAccountPPOBInHistory']);
    Route::get('/ppob-transaction/out-history/{member_id}', [PPOBTransactionController::class, 'getAcctSavingsAccountPPOBOutHistory']);
    
    Route::get('/pulsa-transaction', [PulsaTransactionController::class, 'index']); 
    Route::get('/pulsa-transaction/product', [PulsaTransactionController::class, 'product']);

    Route::put('/member/phone/{id}', [AuthController::class, 'update_member_phone']);
    Route::put('/member/password/{id}', [AuthController::class, 'update_password']);
    Route::put('/member/password-transaction/{id}', [AuthController::class, 'update_password_transaction']);
    Route::get('/member/{member_no}', [AuthController::class, 'check_member']);
    Route::get('/member/otp/{member_no}', [AuthController::class, 'otp_success']);


    Route::post('/ppob/pulsa/prepaid', [PulsaTransactionController::class, 'getPPOBPulsaPrePaid']);
    Route::post('/ppob/pulsa/prepaid/payment', [PulsaTransactionController::class, 'paymentPPOBPulsaPrePaid']);

    Route::post('/ppob/pln/postpaid', [ListrikTransactionController::class, 'getPPOBPLNPostPaid']);
    Route::post('/ppob/pln/postpaid/payment', [ListrikTransactionController::class, 'paymentPPOBPLNPostPaid']);
    Route::post('/ppob/pln/prepaid', [ListrikTransactionController::class, 'getPPOBPLNPrePaid']);
    Route::post('/ppob/pln/prepaid/payment', [ListrikTransactionController::class, 'paymentPPOBPLNPrePaid']);

    Route::get('/ppob/emoney/category', [EMoneyTransactionController::class, 'getPPOBTopUpEmoneyCategory']);
    Route::post('/ppob/emoney/product', [EMoneyTransactionController::class, 'getPPOBTopUpEmoneyProduct']);
    Route::post('/ppob/emoney/payment', [EMoneyTransactionController::class, 'paymentPPOBTopUpEmoney']);

    Route::post('/ppob/bpjs', [BPJSTransactionController::class, 'getPPOBBPJS']);
    Route::post('/ppob/bpjs/payment', [BPJSTransactionController::class, 'paymentPPOBBPJS']);

    Route::get('/check-token', [AuthController::class, 'check_token']);

    // CORE PROGRAM
    Route::get('/core-program', [CoreProgramController::class, 'getCoreProgram']);
    // Acct Savings Account
    Route::post('/savings-account', [AcctSavingsAccountController::class, 'getAcctSavingsAccountBalance']);
    


});

Route::group(['middleware'=> ['auth:sanctum']], function(){
    Route::post('/logout', [AuthController::class, 'logout']);
});

//Public Route
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login-old', [AuthController::class, 'login_old']);
Route::post('/log-login', [AuthController::class, 'log_login']);
Route::post('/log-create-password', [AuthController::class, 'log_create_password']);
Route::post('/log-reset-password', [AuthController::class, 'log_reset_password']);
Route::post('/ppob/topup', [PPOBTopUpController::class, 'store']);
Route::get('/member/open/{member_id}/{user_id}', [AuthController::class, 'open_block']);
Route::get('/member/reset_password/{member_no}/{member_id}/{user_id}', [AuthController::class, 'reset_password']);
Route::get('/create_password_member', [AuthController::class, 'create_password']);
Route::get('/log-temp/{code}', [AuthController::class, 'cek_log_temp']);
Route::get('/logout-expired/{member_id}', [AuthController::class, 'logout_expired']);
Route::post('/whatsapp-otp/send', [WhatsappOTPController::class, 'send']);
Route::post('/whatsapp-otp/verification', [WhatsappOTPController::class, 'verification']);
Route::get('/whatsapp-otp/resend/{member_no}', [WhatsappOTPController::class, 'resend']);