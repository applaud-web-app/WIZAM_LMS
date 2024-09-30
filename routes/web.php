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
use App\Http\Controllers\admin\ManageLearning;
use App\Http\Controllers\admin\ManageTest;
use App\Http\Controllers\admin\FileManagerController;
use App\Http\Controllers\admin\PaymentController;
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
        Route::post('/import-users', 'importUser')->name('import-users');
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

        // FOR PAGES
        Route::get('/pages', 'viewPages')->name('view-pages');
        Route::get('/add-page', 'addPage')->name('add-page');
        Route::post('store-page','storePage')->name('store-page');
        Route::get('/edit-page', 'editPages')->name('edit-page');
        Route::post('/edit-page', 'updatePage')->name('update-page');
        Route::get('/delete-page', 'deletePage')->name('delete-page');
    });

    // Setting Controller
    Route::controller(SettingController::class)->group(function () {

        // DASHBOARD
        Route::get('dashboard', 'dashboard')->name('admin-dashboard');

        // FOR SETTING
        Route::get('/general-settings', 'generalSettings')->name('general-settings');
        Route::post('/update-settings', 'updateGeneralSetting')->name('update-settings');
        Route::post('/update-contact-info', 'updateContactInfo')->name('update-contact-info');

        // FOR PROFILE SETTING
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/update-profile', 'updateProfile')->name('update-profile');


        // FOR EMAIL-SETTING
        Route::get('/email-settings', 'emailSettings')->name('email-settings');
        Route::post('/update-email-settings', 'updateEmailSettings')->name('update-email-settings');

        // FOR PAYMENT-DETAIL
        Route::get('/payment-settings', 'paymentSettings')->name('payment-settings');
        Route::post('/update-payment-setting', 'updatePaymentSetting')->name('update-payment-setting');
        Route::post('/paypal-detail', 'paypalDetail')->name('paypal-detail');
        Route::post('/stripe-detail', 'stripeDetail')->name('stripe-detail');
        Route::post('/razorpay-detail', 'razorpayDetail')->name('razorpay-detail');
        Route::post('/bank-detail', 'bankDetail')->name('bank-detail');

        // FOR BILLING & TAX SETTING
        Route::get('/billing-tax-setting', 'billingTaxSetting')->name('billing-tax-setting');
        Route::post('/save-billing', 'saveBillingData')->name('save-billing');
        Route::post('/save-tax', 'saveTaxData')->name('save-tax');
        Route::get('/get-states', 'getStates')->name('get-states');
        Route::get('/get-cities', 'getCities')->name('get-cities');
    
        // FOR MAINTANACE SETTING
        Route::get('/maintenance-setting', 'maintenanceSetting')->name('maintenance-setting');
        Route::post('/save-maintenance-setting', 'saveMaintenanceSetting')->name('save-maintenance-setting');

        // FOR TERM & CONDITION
        // Route::get('/term-condition', 'termCondition')->name('term-condition'); // removed

        // FOR PRIVACY POLICY
        // Route::get('/privacy-policy', 'privacyPolicy')->name('privacy-policy'); // removed

        // FOR HOME PAGE
        Route::get('/home-settings', 'homeSetting')->name('home-settings');
        Route::post('/update-banner', 'updateBanner')->name('update-banner');
        Route::post('/update-exam', 'updateExam')->name('update-exam');
        Route::post('/update-help', 'updateHelp')->name('update-help');
        Route::post('/update-whyus', 'updateWhyus')->name('update-whyus');
        Route::post('/update-faq', 'updateFaq')->name('update-faq');
        Route::post('/update-resource', 'updateResource')->name('update-resource');
        Route::post('/update-get-started', 'updateGetstarted')->name('update-get-started');

        // ABOUT PAGE
        Route::get('/about-settings', 'aboutSetting')->name('about-settings');
        Route::post('/update-mission', 'updateMission')->name('update-mission');
        Route::post('/update-vision', 'updateVision')->name('update-vision');
        Route::post('/update-values', 'updateValues')->name('update-values');
        Route::post('/update-strategy', 'updateStrategy')->name('update-strategy');
        Route::post('/update-operate', 'updateOperate')->name('update-operate');
        Route::post('/update-best-data', 'updateBestData')->name('update-best-data');

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

    // Manage Learning
    Route::controller(ManageLearning::class)->group(function () {
        // Practice Set
        Route::get('/practice-sets', 'practiceSets')->name('view-practice-set');
        Route::get('/delete-practice-set', 'deletePracticeSet')->name('delete-practice-sets');
        Route::get('/create-practice-set', 'createPracticeSets')->name('create-practice-set');
        Route::post('/save-practice-set', 'savePracticeSets')->name('save-practice-set');
        Route::get('/practice-set/{id}/detail', 'practiceSetDetail')->name('practice-set-detail');
        Route::post('/practice-set/{id}/detail', 'updatePracticeSetDetail')->name('update-practice-set-detail');
        Route::get('/practice-set/{id}/setting', 'practiceSetSetting')->name('practice-set-setting');
        Route::post('/practice-set/{id}/setting', 'updatePracticeSetSetting')->name('update-practice-set-setting');
        Route::get('/practice-set/{id}/question', 'practiceSetQuestion')->name('practice-set-question');
        Route::post('/filter-practice-set-question', 'filterPracticeSetQuestion')->name('filter-practice-set-question');
        Route::post('/practice-set/{id}/question', 'updatePracticeSetQuestion')->name('update-practice-set-question');
        Route::post('/fetch-practice-set-question', 'fetchPracticeSetQuestion')->name('fetch-practice-set-question');
        Route::post('/remove-practice-set-question', 'removePracticeSetQuestion')->name('remove-practice-set-question');

        // Lessons
        Route::get('/configure-lessons', 'configureLessons')->name('configure-lessons');
        Route::post('/save-configure-lessons', 'saveConfigureLessons')->name('save-configure-lessons');
        Route::get('/practice/{category}/{skill}/lessons', 'practiceLessons')->name('practice-lessons');
        Route::post('/filter-practice-lesson', 'filterPracticeLesson')->name('filter-practice-lesson');
        Route::post('/practice/{category}/{skill}/lessons', 'updatePracticeLessons')->name('update-practice-lessons');
        Route::post('/fetch-practice-lessons', 'fetchPracticeLessons')->name('fetch-practice-lessons');
        Route::post('/remove-practice-lessons', 'removePracticeLessons')->name('remove-practice-lessons');

        // Videos
        Route::get('/configure-videos', 'configureVideos')->name('configure-videos');
        Route::post('/save-configure-videos', 'saveConfigureVideos')->name('save-configure-videos');
        Route::get('/practice/{category}/{skill}/videos', 'practiceVideos')->name('practice-videos');
        Route::post('/filter-practice-videos', 'filterPracticeVideos')->name('filter-practice-videos');
        Route::post('/practice/{category}/{skill}/videos', 'updatePracticeVideos')->name('update-practice-videos');
        Route::post('/fetch-practice-videos', 'fetchPracticeVideos')->name('fetch-practice-videos');
        Route::post('/remove-practice-videos', 'removePracticeVideos')->name('remove-practice-videos');
    });

    // Manage Test
    Route::controller(ManageTest::class)->group(function () {

        // Exam Type
        Route::get('/exam-types', 'examTypes')->name('exam-types');
        Route::post('/add-exam-types', 'addExamTypes')->name('add-exam-types');
        Route::post('/edit-exam-types', 'editExamTypes')->name('edit-exam-types');
        Route::get('/delete-exam-types', 'deleteExamTypes')->name('delete-exam-types');

        // Quiz Type
        Route::get('/quiz-types', 'quizTypes')->name('quiz-types');
        Route::post('/add-quiz-types', 'addQuizTypes')->name('add-quiz-types');
        Route::post('/edit-quiz-types', 'editQuizTypes')->name('edit-quiz-types');
        Route::get('/delete-quiz-types', 'deleteQuizTypes')->name('delete-quiz-types');

        // Quizzes
        Route::get('/quizzes', 'viewQuizzes')->name('view-quizzes');
        Route::get('/create-quizzes', 'createQuizzes')->name('create-quizzes');
        Route::post('/save-quizzes', 'saveQuizzes')->name('save-quizzes');
        Route::get('/delete-quizzes', 'deleteQuizzes')->name('delete-quizzes');

        Route::get('/quizzes/{id}/detail', 'quizzesDetail')->name('quizzes-detail');
        Route::post('/quizzes/{id}/detail', 'updateQuizzesDetail')->name('update-quizzes-detail');

        Route::get('/quizzes/{id}/setting', 'quizzesSetting')->name('quizzes-setting');
        Route::post('/quizzes/{id}/setting', 'updateQuizzesSetting')->name('update-quizzes-setting');
        Route::get('/quizzes/{id}/question', 'quizzesQuestion')->name('quizzes-question');
        Route::post('/filter-quizzes-question', 'filterQuizzesQuestion')->name('filter-quizzes-question');
        Route::post('/quizzes/{id}/question', 'updateQuizzesQuestion')->name('update-quizzes-question');
        Route::post('/fetch-quizzes-question', 'fetchQuizzesQuestion')->name('fetch-quizzes-question');
        Route::post('/remove-quizzes-question', 'removeQuizzesQuestion')->name('remove-quizzes-question');

        Route::get('/quizzes/{id}/schedules', 'quizzesSchedules')->name('quizzes-schedules');
        Route::post('/quizzes/{id}/schedules', 'updateQuizzesSchedules')->name('update-quizzes-schedules');
        Route::post('/quizzes/{id}/schedules-save', 'saveQuizzesSchedules')->name('save-quizzes-schedules');
        Route::get('/quizzes/schedules-delete', 'deleteQuizzesSchedules')->name('delete-quizzes-schedules');

        // Exams
        Route::get('/exams', 'viewExam')->name('view-exams');
        Route::get('/create-exams', 'createExams')->name('create-exams');
        Route::post('/save-exams', 'saveExams')->name('save-exams');
        Route::get('/delete-exam', 'deleteExam')->name('delete-exam');

        Route::get('/exam/{id}/detail', 'examDetail')->name('exam-detail');
        Route::post('/exam/{id}/detail', 'updateExamDetail')->name('update-exam-detail');

        Route::get('/exam/{id}/setting', 'examSetting')->name('exam-setting');
        Route::post('/exam/{id}/setting', 'updateExamSetting')->name('update-exam-setting');

        Route::get('/exam/{id}/section', 'examSection')->name('exam-section');
        Route::post('/exam/{id}/add-section', 'addExamSection')->name('add-exam-section');
        Route::post('/exam/edit-section', 'editExamSection')->name('edit-exam-section');
        Route::get('/exam/delete-section', 'deleteExamSection')->name('delete-exam-section');

        Route::get('/exam/{id}/questions', 'examQuestions')->name('exam-questions');
        Route::post('filter-exam-question', 'filterExamQuestion')->name('filter-exam-question'); 
        Route::post('exam/{id}/questions', 'updateExamQuestion')->name('update-exam-question');
        Route::post('fetch-exam-question', 'fetchExamQuestion')->name('fetch-exam-question'); 
        Route::post('/remove-exam-question', 'removeExamQuestion')->name('remove-exam-question');

        Route::get('/exam/{id}/schedules', 'examSchedules')->name('exam-schedules');
        Route::post('/exam/{id}/schedules', 'updateExamSchedules')->name('update-exam-schedules');
        Route::post('/exam/{id}/schedules-save', 'saveExamSchedules')->name('save-exam-schedules');
        Route::get('/exam/schedules-delete', 'deleteExamSchedules')->name('delete-exam-schedules');

    });

    // Manage File Manager
    Route::controller(FileManagerController::class)->group(function () {
        // ALL FILES
        Route::get('/file-manager', 'fileManager')->name('file-manager');

        Route::post('/add-folder', 'addFolder')->name('add-folder');
        Route::post('/save-directory-media', 'saveDirectoryMedia')->name('save-directory-media');
        Route::post('/delete-directory', 'deleteDirectory')->name('delete-directory');

        Route::get('/fetch-directory', 'fetchDirectoryData')->name('fetch-directory-data');



        // PARENT DIRECTORY
        Route::post('/add-directory', 'addDirectory')->name('add-directory');
        // Route::post('/fetch-directory-data', 'fetchDirectoryData')->name('fetch-directory-data');


        // FOR MEDIA UPLOAD/REMOVE
        Route::post('/upload-media', 'uploadMedia')->name('upload-media');
        Route::post('/remove-media', 'removeMedia')->name('remove-media');

    });

    // Manage Payment
    Route::controller(PaymentController::class)->group(function () {
        Route::get('/plans', 'viewPlans')->name('view-plans');
        Route::get('/create-plan', 'createPlan')->name('create-plan');
        Route::post('/save-plan', 'savePlan')->name('save-plan');

        Route::get('/edit-plan', 'editPlan')->name('edit-plan');
        Route::post('/edit-plan', 'updatePlan')->name('update-plan');

        Route::get('/delete-plan', 'deletePlan')->name('delete-plan');
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


