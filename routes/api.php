<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CmsController;

// Route::post('register', [AuthController::class, 'register']);
// Route::post('login', [AuthController::class, 'login']);
// Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['api', 'cors'])->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});



// CMS API (FRONTEND)
Route::get('site-setting', [CmsController::class, 'siteSetting']);

Route::get('faq', [CmsController::class, 'faq']);
Route::get('course', [CmsController::class, 'course']);
Route::get('popular-exams', [CmsController::class, 'popularExams']);
Route::get('exam/{slug}', [CmsController::class, 'examDetail']);
Route::get('latest-resources', [CmsController::class, 'latest-resources']);
Route::get('/resource/{slug}', [BlogController::class, 'resourceDetail']);

// FOR PAGES
Route::get('exams', [CmsController::class, 'exams']);
Route::get('resource', [CmsController::class, 'resources']);

// DYNAMIC PAGES
Route::get('pages', [CmsController::class, 'pages']);
Route::get('page/{slug}', [CmsController::class, 'pageDetail']);

// CONTACT US
Route::post('contact-us', [CmsController::class, 'contactUs']);


// Authorized Routes
Route::middleware('auth:sanctum')->get('/profile', [UserController::class, 'profile']);
