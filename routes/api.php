<?php

use App\Constants\RouteNameConstants;
use App\Http\Controllers\FeedbackController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// CRM

Route::post("/export-feedbacks/mega-crm/{site_slug}", [FeedbackController::class, 'exportFeedbacksMegaCrm'])
  ->name(RouteNameConstants::FEEDBACK_MEGA_CRM);
