<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseVideoController;
use App\Http\Controllers\CoursePackController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\PracticeSubjectController;
use App\Http\Controllers\PracticeTopicController;
use App\Http\Controllers\PracticeSubtopicController;
use App\Http\Controllers\PracticeQuestionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TestExamController;
use App\Http\Controllers\TestQuestionController;
use App\Http\Controllers\TestSeriesController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// use UniSharp\LaravelFilemanager\Lfm;

Route::get('/',[PageController::class,'home'])->name('home');
Route::get('/about',[PageController::class,'about'])->name('about');
Route::get('/contact',[PageController::class,'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'store'])->name('contact.store');
Route::get('/terms',[PageController::class,'terms'])->name('terms');
Route::get('/privacy',[PageController::class,'privacy'])->name('privacy');
Route::get('/material',[PageController::class,'material'])->name('material.index');
Route::get('/career',[PageController::class,'career'])->name('career.index');
Route::get('/test/calcy',[PageController::class,'calcy'])->name('test.calcy');
Route::view('/fallback','fallback');
Route::view('/calcy','calcy');

// Route::get('/page/{page}', [BlogController::class,'listByPage']);
Route::get('/blog/detail/{ulink}', [BlogController::class,'detail'])->name('blog.detail');
Route::get('/blog', [BlogController::class,'list'])->name('blog.list');

Route::get('/course/video/{ulink}/{chapter?}',[CourseController::class,'video'])->name('course.video');
// Route::get('/course/coursedetail/{url}',[CourseController::class,'coursedetail'])->name('course.coursedetail');
Route::get('/course/coursepack/{id}',[CoursePackController::class,'coursePack'])->name('course.packDetail');
Route::get('/course/{url}',[CourseController::class,'detail'])->name('course.detail');
Route::get('/course',[CourseController::class,'course_Index'])->name('course.index');

// routes/web.php
Route::get('/payment/status/{paymentId}', [PaymentController::class, 'paymentStatus'])->name('payment.status');
Route::get('/payment/webhook',[PaymentController::class,'paymentWebhook'])->name('payment.webhook');
Route::post('/payment/payment-gateway',[PaymentController::class,'paymentGateway'])->name('payment.gateway');
Route::post('/payment/manual',[PaymentController::class,'manualPay'])->name('payment.manual');
Route::get('/purchase/{coursepack?}/{ulink?}',[CourseController::class,'purchase'])->name('page.purchase');

Route::get('/testimonial',[TestimonialController::class,'testimonial_index'])->name('testimonial.index');

Route::get('/practice/start/{subject}/{topic?}/{subtopic?}/{priority?}', [PracticeController::class, 'start'])->name('practice.start');
Route::post('/practice/ans-submit',[PracticeController::class,'anssubmit'])->name('practice.submit');

Route::get('/practice/subtopic/{subject}/{topic}',[PracticeController::class,'subtopic'])->name('practice.subtopic');
Route::get('/practice/topic/{subject}',[PracticeController::class,'topic'])->name('practice.topic');
Route::get('/practice',[PracticeController::class,'subject'])->name('practice.subject');

Route::get('/import-questions', [TestController::class, 'importQuestions']);
Route::post('/test/submit',[TestController::class,'submitTest']);
Route::get('/test/validate',[TestController::class,'validateTest'])->name('test.validate');
Route::get('/test/load-question',[TestController::class,'loadQuestion']);
Route::post('/test/save-answer',[TestController::class,'saveAnswer']);
Route::get('/test/series',[TestController::class,'list'])->name('test.series.list');
Route::get('/test/start/{exam_id}', [TestController::class, 'start'])->name('test.start');
Route::get('/test/main/{exam_id}', [TestController::class, 'main'])->name('test.main');
Route::get('/test/report{exam_id}',[TestController::class,'testReport'])->name('test.report');

Route::get('/user',[UserController::class,'login']);
Route::get('/user/profile',[UserController::class,'profile'])->name('user.profile');
Route::post('/user/login-submit',[UserController::class,'login_submit']);
Route::get('/user/login',[UserController::class,'login'])->name('user.login');
Route::get('/user/register',[UserController::class,'register'])->name('user.register');
Route::post('/user/register_submit',[UserController::class,'register_submit']);
Route::get('/user/edit_profile',[UserController::class,'edit_profile'])->name('user.edit_profile');
Route::post('/user/update_profile',[UserController::class,'update_profile'])->name('user.update-profile');
Route::get('/user/edit_password',[UserController::class,'edit_password'])->name('user.edit_password');
Route::post('/user/update_password',[UserController::class,'update_password'])->name('user.update_password');
Route::post('/user/update_forget_password',[UserController::class,'update_forget_password'])->name('user.update_forget_password');
Route::get('/user/forget-password',[UserController::class,'forget_password'])->name('user.forget_password');
Route::get('/user/check-user',[UserController::class,'checkUser'])->name('user.checkUser');
Route::get('/user/student_register',[UserController::class,'student_register'])->name('user.student_register');
Route::post('/user/update_studentregister',[UserController::class,'update_studentregister'])->name('user.update_studentregister');
Route::get('/user/logout', [UserController::class, 'logout'])->name('user.logout');

Route::get('/tutorial/tutorialindex',[TutorialController::class, 'tutorialindex'])->name('tutorial.tutorialindex');

// Admin pages
Route::prefix('/admin')->group(function () {
    Route::get('', [AdminController::class, 'home'])->middleware('AdminAuth')->name('admin');
    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/authenticate', [AdminController::class, 'authenticate'])->name('admin.authenticate');
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::resource('/user', UserController::class)->middleware('AdminAuth')->names(generateRouteNames('admin.user'));
    Route::resource('/banner', BannerController::class)->middleware('AdminAuth')->names(generateRouteNames('admin.banner'));

    Route::resource('/blog', BlogController::class)->middleware('AdminAuth')->names(generateRouteNames('admin.blog'));


    Route::get('/test/question/',[TestQuestionController::class,'manage'])->name('admin.test.question.manage');
    Route::post('/test/question/submit',[TestQuestionController::class,'questionSubmit'])->name('admin.test.question.submit');

    Route::resource('/test/exam', TestExamController::class)->middleware('AdminAuth')->names(generateRouteNames('admin.test.exam'));
    Route::prefix('/test/exam')->group(function () {
        Route::get('/arrange',[TestExamController::class,'arrange'])->name('admin.test.exam.arrange');
        Route::post('/updatePriority',[TestExamController::class,'updatePriority']);
    });

    Route::resource('/test/series', TestSeriesController::class)->middleware('AdminAuth')->names(generateRouteNames('admin.test.series'));

    Route::prefix('/test/series')->group(function () {
        Route::get('/arrange',[TestSeriesController::class,'arrange'])->name('admin.test.series.arrange');
        Route::post('/updatePriority',[TestSeriesController::class,'updatePriority']);
    });

    Route::prefix('/practice')->group(function () {
        Route::get('', [PracticeController::class, 'index'])->name('admin.practice.index');
        Route::get('/subject/arrange',[PracticeSubjectController::class,'arrange'])->name('admin.practice.subject.arrange');
        Route::post('/subject/updatePriority',[PracticeSubjectController::class,'updatePriority']);
        Route::get('/topic/arrange',[PracticeTopicController::class,'arrange'])->name('admin.practice.topic.arrange');
        Route::post('/topic/updatePriority',[PracticeTopicController::class,'updatePriority']);
        Route::get('/subtopic/arrange',[PracticeSubtopicController::class,'arrange'])->name('admin.practice.subtopic.arrange');
        Route::post('/subtopic/updatePriority',[PracticeSubtopicController::class,'updatePriority']);

        Route::resource('/subject', PracticeSubjectController::class)->middleware('AdminAuth')->names(generateRouteNames('admin.practice.subject'));
        Route::resource('/topic', PracticeTopicController::class)->middleware('AdminAuth')->names(generateRouteNames('admin.practice.topic'));
        Route::resource('/subtopic', PracticeSubtopicController::class)->middleware('AdminAuth')->names(generateRouteNames('admin.practice.subtopic'));

        Route::get('/question/manage/{subject}/{topic}/{subtopic}/{priority?}',[PracticeQuestionController::class,'manage'])->name('admin.practice.question.manage');
        Route::post('/question/question-submit',[PracticeQuestionController::class,'questionSubmit'])->name('admin.practice.question.submit');

        Route::resource('/question', PracticeQuestionController::class)->middleware('AdminAuth')->names(generateRouteNames('admin.practice.question'));
        Route::resource('/result', PracticeController::class)->middleware('AdminAuth')->names(generateRouteNames('admin.practice.result'));
    });

    Route::resource('/testimonial', TestimonialController::class)->middleware('AdminAuth')->names(generateRouteNames('admin.testimonial'));

    Route::resource('/course-video', CourseVideoController::class)->middleware('AdminAuth')->names(generateRouteNames('admin.video'));
    Route::resource('/course', CourseController::class)->middleware('AdminAuth')->names(generateRouteNames('admin.course'));

    Route::post('/tiny-image/store', [PracticeQuestionController::class, 'storeTinyImage'])
        ->middleware('AdminAuth')
        ->name('admin.store-tiny-image');
    Route::post('/upload-image/store',[PracticeQuestionController::class,'storeImage'])->name('admin.store-image');
    Route::get('/upload-image', [PracticeQuestionController::class, 'uploadImage'])->name('admin.upload-image');
});

function generateRouteNames($prefix)
{
    return [
        'index' => $prefix . '.index',
        'create' => $prefix . '.create',
        'store' => $prefix . '.store',
        'show' => $prefix . '.show',
        'edit' => $prefix . '.edit',
        'update' => $prefix . '.update',
        'destroy' => $prefix . '.destroy',
    ];
}

Route::post('/admin/tinymce-upload', [FileUploadController::class, 'upload'])->withoutMiddleware([VerifyCsrfToken::class]);;

// Route::group(['prefix' => 'admin/file-manager', 'middleware' => ['web']], function () {
//     Lfm::routes();
// });