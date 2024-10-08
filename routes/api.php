<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CmsController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\PracticeSetController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Add the Forgot Password route
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

// CMS API (FRONTEND)
Route::get('banners', [CmsController::class, 'banners']);
Route::get('popular-exam-data', [CmsController::class, 'popularExamData']);
Route::get('help-data', [CmsController::class, 'helpData']);
Route::get('whyus-data', [CmsController::class, 'whyusData']);
Route::get('faq-data', [CmsController::class, 'faqData']);
Route::get('faq-data', [CmsController::class, 'faqData']);
Route::get('resource-data', [CmsController::class, 'resourceData']);
Route::get('get-started', [CmsController::class, 'getStarted']);



Route::get('site-setting', [CmsController::class, 'siteSetting']);

Route::get('faq', [CmsController::class, 'faq']);

Route::get('course', [CmsController::class, 'course']);
Route::get('course-pack/{id}', [CmsController::class, 'coursePackage']);

Route::get('popular-exams', [CmsController::class, 'popularExams']);
Route::get('exam/{slug}', [CmsController::class, 'examDetail']);
Route::get('latest-resources', [CmsController::class, 'latestResources']);
Route::get('/resource/{slug}', [CmsController::class, 'resourceDetail']);

// FOR PAGES
Route::get('exams', [CmsController::class, 'exams']);
Route::get('resource', [CmsController::class, 'resources']);

// DYNAMIC PAGES
Route::get('pages', [CmsController::class, 'pages']);
Route::get('page/{slug}', [CmsController::class, 'pageDetail']);

// CONTACT US
Route::post('contact-us', [CmsController::class, 'contactUs']);


// ABOUT PAGE 
Route::get('about', [CmsController::class, 'about']);

// PRICING PAGE
Route::get('pricing',[CmsController::class, 'pricing']);


// API Route
// Route::middleware(['checkAuthToken'])->post('/logout', [AuthController::class, 'logout']);
// Route::middleware(['checkAuthToken'])->get('/profile', [AuthController::class, 'profile']);


// AUTHENTICATED API
Route::middleware('checkAuthToken')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/logout-all', [AuthController::class, 'logoutFromAllLoginDevices']);

    // ------ STUDENT ------- //
    # SYLLABUS
    Route::get('/syllabus', [StudentController::class, 'syllabus']);

    # EXAM
    Route::get('/exam-type', [StudentController::class, 'examType']);
    Route::get('/all-exams', [StudentController::class, 'allExams']);
    Route::get('/exam-detail/{slug}', [StudentController::class, 'examDetail']);
    
    ## PLAY EXAM
    Route::get('/play-exam/{slug}', [ExamController::class, 'playExam']);

    # QUIZ
    Route::get('/quiz-type', [StudentController::class, 'quizType']);
    Route::get('/all-quiz', [StudentController::class, 'allQuiz']);
    Route::get('/quiz-detail/{slug}', [StudentController::class, 'quizDetail']);

    ## PLAY Quiz
    Route::get('/play-quiz/{slug}',[QuizController::class, 'playQuiz']);
    Route::post('/save-quiz-progress/{uuid}',[QuizController::class, 'saveQuizProgress']);
    Route::post('/finish-quiz/{uuid}',[QuizController::class, 'finishQuiz']);

    Route::get('/quiz-result/{uuid}',[QuizController::class, 'quizResult']);

    # Practice Set
    Route::get('/practice-set', [StudentController::class, 'practiceSet']);
    Route::get('/practice-set-detail/{slug}', [StudentController::class, 'practiceSetDetail']);
    Route::get('/play-practice-set/{slug}', [PracticeSetController::class, 'playPracticeSet']);

    Route::get('/all-question', [PracticeSetController::class, 'allQuestion']);


    # Lesson
    Route::get('/all-lesson',[StudentController::class, 'allLesson']);
    Route::get('/lesson-detail/{slug}',[StudentController::class, 'lessonDetail']);

    # Video
    Route::get('/all-video',[StudentController::class, 'allVideo']);
    Route::get('/video-detail/{slug}',[StudentController::class, 'videoDetail']);

});



