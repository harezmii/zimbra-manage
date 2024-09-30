<?php

use App\Http\Controllers\GraphController;
use App\Http\Controllers\LdapController;
use App\Http\Controllers\ZimbraController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/graph/status',[GraphController::class,'setUserStatus']);
    Route::post('/graph/properties/phoneNumber',[GraphController::class,'updatePhoneNumber']);
    Route::post('/graph/auditLogs',[GraphController::class,'getAuditLogs']);
    Route::get('/', [ZimbraController::class,'getAccountAttributes']);
    Route::post('/dl/{functionName}', [ZimbraController::class,'getDistributionList']);
    Route::get('/zimbra/status', [ZimbraController::class,'getZimbraStatus']);
    Route::post('/account/setPassword', [ZimbraController::class,'setPassword']);
    Route::post('/account/status/{status}',[ZimbraController::class,'setZimbraMailStatus']);


    // LDAP ROUTES

    Route::post('/users', [LdapController::class, 'createUser']);
    Route::get('/users/{username}', [LdapController::class, 'searchUser']);
    Route::delete('/users/{username}', [LdapController::class, 'deleteUser']);
    Route::get('/users/recent', [LdapController::class, 'recentlyCreatedUsers']);
    Route::get('/ldap/check-connection', [LdapController::class, 'checkConnection']);

    // LDAP ROUTES END
});



