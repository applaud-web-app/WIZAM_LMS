<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CmsController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\PracticeSetController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\DashboardController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Add the Forgot Password route
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

// CMS API (FRONTEND)
Route::get('banners', [CmsController::class, 'banners']);
Route::get('youtube', [CmsController::class, 'youtube']);
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
Route::get('course-exam-type', [CmsController::class, 'courseExamType']);

Route::get('popular-exams', [CmsController::class, 'popularExams']);
Route::get('exam/{slug}', [CmsController::class, 'examDetail']);
Route::get('latest-resources', [CmsController::class, 'latestResources']);
Route::get('/resource/{slug}', [CmsController::class, 'resourceDetail']);
Route::get('/resource/archive/{slug}', [CmsController::class, 'resourceArchive']);

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
Route::get('about-page', [CmsController::class, 'aboutPage']);


Route::get('/contact-form', [CmsController::class, 'contactForm']);
Route::get('/site-seo', [CmsController::class, 'siteSeo']);


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
    Route::get('/exam-all',[ExamController::class, 'examAll']);
    Route::get('/exam-type', [StudentController::class, 'examType']);
    Route::get('/all-exams', [StudentController::class, 'allExams']);
    Route::get('/exam-detail/{slug}', [StudentController::class, 'examDetail']);
    Route::get('/play-exam/{slug}', [ExamController::class, 'playExam']);
    Route::post('/finish-exam/{uuid}',[ExamController::class, 'finishExam']);
    Route::get('/exam-result/{uuid}',[ExamController::class, 'examResult']);
    Route::post('/save-answer-progress/{uuid}',[ExamController::class,'saveAnswerProgress']);
    Route::get('/download-exam-report/{uuid}',[ExamController::class, 'downloadExamReport']);
    // Route::get('/get-saved-progress', [ExamController::class, 'getSavedProgress']); 


    # QUIZ
    Route::get('/quiz-all',[QuizController::class, 'quizAll']);
    Route::get('/quiz-type', [StudentController::class, 'quizType']);
    Route::get('/all-quiz', [StudentController::class, 'allQuiz']);
    Route::get('/quiz-detail/{slug}', [StudentController::class, 'quizDetail']);
    Route::get('/play-quiz/{slug}',[QuizController::class, 'playQuiz']);
    Route::post('/finish-quiz/{uuid}',[QuizController::class, 'finishQuiz']);
    Route::get('/quiz-result/{uuid}',[QuizController::class, 'quizResult']);
    Route::post('/save-quiz-answer-progress/{uuid}',[QuizController::class,'saveQuizAnswerProgress']);
    Route::get('/download-quiz-report/{uuid}',[QuizController::class, 'downloadQuizReport']);
    // Route::post('/save-quiz-progress/{uuid}',[QuizController::class, 'saveQuizProgress']);


    # Practice Set
    Route::get('/practice-set', [StudentController::class, 'practiceSet']);
    Route::get('/practice-set-detail/{slug}', [StudentController::class, 'practiceSetDetail']);
    Route::get('/play-practice-set/{slug}', [PracticeSetController::class, 'playPracticeSet']);
    Route::post('/finish-practice-set/{uuid}',[PracticeSetController::class, 'finishPracticeSet']);
    Route::get('/practice-set-result/{uuid}',[PracticeSetController::class, 'practiceSetResult']);
    Route::get('/download-practice-set-report/{uuid}',[PracticeSetController::class, 'downloadPracticeSetReport']);
    Route::post('/save-practice-set-answer-progress/{uuid}',[PracticeSetController::class,'savePracticeSetAnswerProgress']);

    # Question
    Route::get('/all-question', [PracticeSetController::class, 'allQuestion']);

    # Lesson
    Route::get('/all-lesson',[StudentController::class, 'allLesson']);
    Route::get('/lesson-detail/{slug}',[StudentController::class, 'lessonDetail']);

    # Video
    Route::get('/all-video',[StudentController::class, 'allVideo']);
    Route::get('/video-detail/{slug}',[StudentController::class, 'videoDetail']);

    // DASHBOARD
    Route::get('/student-dashboard',[DashboardController::class, 'studentDashboard']);

    // MY PROGRESS
    Route::get('/exam-progress',[ExamController::class, 'examProgress']);
    Route::get('/quiz-progress',[QuizController::class, 'quizProgress']);
    Route::get('/pratice-set-progress',[PracticeSetController::class, 'praticeSetProgress']);
    
    // PRICING PAGE
    Route::post('create-checkout-session',[CmsController::class, 'createCheckoutSession']);

    // CHECK USER SUBSCRIPTION
    Route::get('user-subscription',[StudentController::class,'userSubscription']);

    // USER SUBSCRIPTION & PAYMENT
    Route::get('my-subscription',[StudentController::class,'mySubscription']);
    Route::get('my-payment',[StudentController::class,'myPayment']);
    Route::get('cancel-subscription',[CmsController::class,'cancelSubscription']);
    Route::get('invoice-detail/{payment_id}',[StudentController::class,'invoiceDetail']);
});


