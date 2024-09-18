<?php

// routes/web.php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\ManageSubject;
use App\Http\Controllers\admin\ManageCategory;
use App\Http\Controllers\admin\CMSController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\QuestionBankController;
use Illuminate\Support\Facades\Route;

// FRONTEND 
// Route::get('/',function(){
//     return "SITE HOME PAGE <a href='".$login."'>Login</a>";
// });

// PUBLIC ROUTES
Route::get('/', [LoginController::class, 'login'])->name('admin-login');
Route::prefix('admin')->group(function(){
    Route::get('login', [LoginController::class, 'login'])->name('admin-login');
    Route::post('verify-user', [LoginController::class, 'verifyUser'])->name('verify-user');
    Route::get('forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('verify-email', [LoginController::class, 'verifyEmail'])->name('verify-email');
    Route::get('update-forgot-password', [LoginController::class, 'updateForgotPassword'])->name('update-forgot-password');
    Route::post('update-forgot-password', [LoginController::class, 'updatepassword'])->name('update-password');
    Route::get('logout', [LoginController::class, 'logout'])->name('admin-logout');
});

// Authorized Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin-dashboard');

    // Manage Users
    Route::controller(UserController::class)->group(function () {
        // FOR USER GROUP
        Route::get('/user-groups', 'userGroups')->name('user-groups');
        Route::post('/user-group-delete', 'userGroupDelete')->name('user-groups-delete');
        Route::post('/add-new-group', 'addNewGroup')->name('add-new-group');
        Route::post('/update-group-data', 'updateGroupData')->name('update-group-data');

        // FOR USERS LIST
        Route::get('/users', 'viewUsers')->name('users');
        Route::get('/add-user', 'addUsers')->name('add-users');
        Route::post('/store-user-details', 'storeUserDetails')->name('store-user-details');
        Route::get('/edit-user-details', 'editUserdetails')->name('edit-user-details');
        Route::post('/edit-user-details', 'updateUserDetails')->name('edit-user-details');
        Route::get('/delete-user-data', 'deleteUserData')->name('delete-user-data');

        // FOR USER IMPORT
        Route::get('/import-users', 'showImportForm')->name('import-users');
        Route::post('/import-users', 'importUser')->name('import-users-post');
    });

    // Manage Subject
    Route::controller(ManageSubject::class)->group(function () {

        // FOR SECTION
        Route::get('/sections', 'viewSections')->name('view-sections');
        Route::post('/add-section', 'addSection')->name('add-sections');
        Route::post('/edit-section', 'editSection')->name('edit-section');
        Route::get('/delete-section', 'deleteSection')->name('delete-section');

        // FOR SKILLS
        Route::get('/skills', 'viewSkills')->name('view-skills');
        Route::post('/add-skill', 'addSkill')->name('add-skill');
        Route::post('/edit-skill', 'editSkill')->name('edit-skill');
        Route::get('/delete-skill', 'deleteSkill')->name('delete-skill');

        // FOR TOPIC
        Route::get('/topics', 'viewTopics')->name('view-topics');
        Route::post('/add-topic', 'addTopic')->name('add-topic');
        Route::post('/edit-topic', 'editTopic')->name('edit-topic');
        Route::get('/delete-topic', 'deleteTopic')->name('delete-topic');
    });

    // Manage Category
    Route::controller(ManageCategory::class)->group(function () {

        // FOR CATEGORY
        Route::get('/category', 'viewCategory')->name('view-category');
        Route::post('/add-category', 'addCategory')->name('add-category');
        Route::post('/edit-category', 'editCategory')->name('edit-category');
        Route::get('/delete-category', 'deleteCategory')->name('delete-category');

        // FOR SUB-CATEGORY
        Route::get('/sub-category', 'viewSubCategory')->name('view-sub-category');
        Route::post('/add-sub-category', 'addsubCategory')->name('add-sub-category');
        Route::post('/edit-sub-category', 'editsubCategory')->name('edit-sub-category');
        Route::get('/delete-sub-category', 'deletesubCategory')->name('delete-sub-category');

        // FOR TAGS
        Route::get('/tags', 'viewTags')->name('view-tags');
        Route::post('/add-tags', 'addTags')->name('add-tags');
        Route::post('/edit-tags', 'editTags')->name('edit-tags');
        Route::get('/delete-tags', 'deleteTags')->name('delete-tags');
    });

    // Manage CMS
    Route::controller(CMSController::class)->group(function () {

        // FOR FAQ
        Route::get('/faq', 'viewFaq')->name('view-faq');
        Route::post('/add-faq', 'addFaq')->name('add-faq');
        Route::post('/edit-faq', 'editFaq')->name('edit-faq');
        Route::get('/delete-faq', 'deleteFaq')->name('delete-faq');

        // FOR BLOG
        Route::get('/blog', 'viewBlog')->name('view-blog');
        Route::get('/add-blog', 'addBlog')->name('add-blog');
        Route::post('/store-blog', 'storeBlog')->name('store-blog');
        Route::get('/edit-blog', 'editBlog')->name('edit-blog');
        Route::post('/edit-blog', 'updateBlog')->name('update-blog');
        Route::get('/delete-blog', 'deleteBlog')->name('delete-blog');

        // FOR BLOG CATEGORY
        Route::get('/blog-category', 'viewBlogCategory')->name('view-blog-category');
        Route::post('/add-blog-category', 'addBlogCategory')->name('add-blog-category');
        Route::post('/edit-blog-category', 'editBlogCategory')->name('edit-blog-category');
        Route::get('/delete-blog-category', 'deleteBlogCategory')->name('delete-blog-category');
    });

    // Setting Controller
    Route::controller(SettingController::class)->group(function () {

        // FOR SETTING
        Route::get('/general-settings', 'generalSettings')->name('general-settings');
        Route::post('/update-settings', 'updateGeneralSetting')->name('update-settings');

        // FOR EMAIL-SETTING
        Route::get('/email-settings', 'emailSettings')->name('email-settings');
        Route::post('/update-email-settings', 'updateEmailSettings')->name('update-email-settings');

        // FOR PAYMENT-DETAIL
        Route::get('/payment-settings', 'paymentSettings')->name('payment-settings');

        // FOR HOME PAGE
        Route::get('/home-settings', 'homeSetting')->name('home-settings');
    });

    // Question Bank
    Route::controller(QuestionBankController::class)->group(function () {

        // QUESTION
        Route::get('/question-types', 'questionTypes')->name('question-types');
        Route::get('/questions', 'questions')->name('view-question');

        Route::get('/create-question', 'createQuestion')->name('create-question');
        Route::post('/create-question', 'saveMsaDetails')->name('save-msa-detail');

        Route::get('/questions/{id}/details','updateQuestionDetails')->name('update-question-details');
        Route::post('/questions/{id}/details','saveQuestionDetails')->name('save-question-details');

        Route::get('/questions/{id}/setting','updateQuestionSetting')->name('update-question-setting');
        Route::post('/questions/{id}/setting','saveQuestionSetting')->name('save-question-setting');

        Route::get('/questions/{id}/solution','updateQuestionSolution')->name('update-question-solution');
        Route::post('/questions/{id}/solution','saveQuestionSolution')->name('save-question-solution');
        
        Route::get('/questions/{id}/attachment','updateQuestionAttachment')->name('update-question-attachment');
        Route::post('/questions/{id}/attachment','saveQuestionAttachment')->name('save-question-attachment');


        // FOR MMA
        Route::post('/save-mma-details','saveMmaDetails')->name('save-mma-details');
        Route::post('/update-mma-details/{id}','updateMmaDetails')->name('update-mma-details');

        // FOR TOF
        Route::post('/save-tof-details','saveTofDetails')->name('save-tof-details');
        Route::post('/update-tof-details/{id}','updateTofDetails')->name('update-tof-details');

        // FOR SOQ
        Route::post('/save-soq-details','saveSoqDetails')->name('save-soq-details');
        Route::post('/update-soq-details/{id}','updateSoqDetails')->name('update-soq-details');

        // FOR MTF
        Route::post('/save-mtf-details','saveMtfDetails')->name('save-mtf-details');
        Route::post('/update-mtf-details/{id}','updateMtfDetails')->name('update-mtf-details');

        // FOR ORD
        Route::post('/save-ord-details','saveOrdDetails')->name('save-ord-details');
        Route::post('/update-ord-details/{id}','updateOrdDetails')->name('update-ord-details');

        // FOR FIB
        Route::post('/save-fib-details','saveFibDetails')->name('save-fib-details');
        Route::post('/update-fib-details/{id}','updateFibDetails')->name('update-fib-details');

        // FOR EMQ
        Route::post('/save-emq-details','saveEmqDetails')->name('save-emq-details');
        Route::post('/update-emq-details/{id}','updateEmqDetails')->name('update-emq-details');



        // Route::get('/create-msa-setting', 'createMsaSetting')->name('create-msa-setting');
        // Route::post('/save-msa-setting', 'saveMsaSetting')->name('save-msa-setting');
        // Route::get('/create-msa-solution', 'createMsaSolution')->name('create-msa-solution');
        // Route::post('/save-msa-solution', 'saveMsaSolution')->name('save-msa-solution');
        // Route::get('/create-msa-attachment', 'createMsaAttachment')->name('create-msa-attachment');
        // Route::post('/save-msa-attachment', 'saveMsaAttachment')->name('save-msa-attachment');

        

        Route::get('/delete-question','deleteQuestion')->name('delete-question');

        // COMPRIHENSION
        Route::get('/comprehensions', 'viewComprehension')->name('view-comprehension');
        Route::post('/add-comprehension', 'addComprehension')->name('add-comprehension');
        Route::post('/edit-comprehension', 'editComprehension')->name('edit-comprehension');
        Route::get('/delete-comprehension', 'deleteComprehension')->name('delete-comprehension');

        // LESSON
        Route::get('/lessons', 'viewLesson')->name('view-lesson');
        Route::get('/add-lesson', 'addLesson')->name('add-lesson');
        Route::post('/store-lesson', 'storeLesson')->name('store-lesson');
        Route::get('/edit-lesson', 'editLesson')->name('edit-lesson');
        Route::post('/edit-lesson', 'updateLesson')->name('update-lesson');
        Route::get('/delete-lesson', 'deleteLesson')->name('delete-lesson');

        // LESSON
        Route::get('/videos', 'viewVideo')->name('view-video');
        Route::get('/create-video', 'createVideo')->name('create-video');
        Route::post('/store-video', 'storeVideo')->name('store-video');
        Route::get('/edit-video', 'editVideo')->name('edit-video');
        Route::post('/edit-video', 'updateVideo')->name('update-video');
        Route::get('/delete-video', 'deleteVideo')->name('delete-video');
    });
});


Route::get('/general-setting', [DashboardController::class, 'generalSetting'])->name('general-setting');
Route::get('/email-setting', [DashboardController::class, 'emailSetting'])->name('email-setting');
Route::get('/billing-setting', [DashboardController::class, 'billingSetting'])->name('billing-setting');
Route::get('/homepage-setting', [DashboardController::class, 'homePageSetting'])->name('homepage-setting');
Route::get('/maintenance', [DashboardController::class, 'maintenance'])->name('maintenance');


Route::get('/all-users', [DashboardController::class, 'allUsers'])->name('all-users');
Route::get('/add-user', [DashboardController::class, 'addUser'])->name('add-user');
Route::get('/user-group', [DashboardController::class, 'userGroup'])->name('user-group');
// Route::get('/import-users', [DashboardController::class, 'importUsers'])->name('import-users');


