<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return "Success";
});

Route::get('/migrate-link', function () {
    Artisan::call('migrate');
    return "Success";
});

Route::get('/', [PublicController::class, 'welcome'])->name('welcome');
Route::post('/contact-us-submit', [PublicController::class, 'contact_us_submit'])->name('contact-us-submit');
Route::get('/about', [PublicController::class, 'about_us'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::get('/chapter/{slug}', [PublicController::class, 'chapter'])->name('chapter');
Route::get('/all-chapter', [PublicController::class, 'all_chapter'])->name('all-chapter');
Route::get('/lecture/{slug}', [PublicController::class, 'lecture'])->name('lecture');
// Route::get('/single-lecture/{slug}', [PublicController::class, 'single_lecture'])->name('single.lecture');



Auth::routes();
Route::get('/home', function() {
    return redirect()->route('user.dashboard');
});
Route::group(['as'=>'user.','prefix'=>'user','middleware'=>['auth','user']], function (){
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/', function() {
        return redirect()->route('user.dashboard');
    });
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile-update', [DashboardController::class, 'profile_update'])->name('profile-update');
    Route::post('/password-update', [DashboardController::class, 'password_update'])->name('password-update');
    
    Route::get('/quiz/{slug}', [DashboardController::class, 'quiz'])->name('quiz');
    Route::put('/quiz-store/{id}', [QuizController::class, 'quiz_store'])->name('quiz.store');
    Route::get('/result/{slug}', [QuizController::class, 'result'])->name('result');
    // Route::get('/quiz-result/{slug}', [QuizController::class, 'quiz'])->name('quiz');
    // Route::resource('quiz', 'QuizController');

    Route::get('/counrse-info', [DashboardController::class, 'counrse_info'])->name('counrse.info');

});


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/admin/login', [AdminAuthController::class, 'admin_login'])->name('admin-login');
Route::post('/admin-login-check', [AdminAuthController::class, 'admin_login_check'])->name('admin-login-check');
Route::get('/admin/password/reset', [AdminAuthController::class, 'admin_password_reset'])->name('admin-password-reset');
Route::post('/admin/password/forgot',[AdminAuthController::class,'sendResetLink'])->name('admin.forgot.password.link');
Route::get('/admin/password/reset/{token}',[AdminAuthController::class,'showResetForm'])->name('admin.reset.password.form');
Route::post('/admin/password/reset',[AdminAuthController::class,'resetPassword'])->name('admin.reset.password');


Route::group(['as'=>'admin.','prefix'=>'admin','middleware'=>['auth:admin']], function (){
    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/', function() {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    //-----------------------------SLIDER MANAGEMENT START----------------------------
    Route::resource('chapter', 'Admin\ChapterController');
    Route::get('chapter-list/{type}','Admin\ChapterController@index')->name('chapter-list.index');
    Route::get('chapter/status-change/{id}','Admin\ChapterController@status_change')->name('chapter.status.change');
    //-----------------------------SLIDER MANAGEMENT END-----------------------------

    //-----------------------------LECTURE MANAGEMENT START----------------------------
    Route::resource('lecture', 'Admin\LectureController');
    Route::get('lecture-list/{type}','Admin\LectureController@index')->name('lecture-list.index');
    Route::get('lecture/status-change/{id}','Admin\LectureController@status_change')->name('lecture.status.change');
    Route::get('lecture/pdf-view/{id}','Admin\LectureController@pdf_view')->name('lecture.pdf-view');
    //-----------------------------LECTURE MANAGEMENT END-----------------------------

    //-----------------------------MCQ MANAGEMENT START----------------------------
    Route::resource('mcq', 'Admin\MCQController');
    Route::get('mcq-list/{type}','Admin\MCQController@index')->name('mcq-list.index');
    Route::get('mcq/status-change/{id}','Admin\MCQController@status_change')->name('mcq.status.change');
    //-----------------------------MCQ MANAGEMENT END-----------------------------

    //-----------------------------ABOUT US MANAGEMENT START----------------------------
    Route::resource('about-us', 'Admin\AboutController');
    Route::get('about-us/status-change/{id}','Admin\AboutController@status_change')->name('about-us.status.change');
    //-----------------------------ABOUT US MANAGEMENT END-----------------------------

    //-----------------------------SLIDER MANAGEMENT START----------------------------
    Route::resource('slider-list', 'Admin\SliderController');
    Route::get('slider-list/inactive/{id}', [SliderController::class, 'inactive'])->name('slider-list.inactive');
    Route::get('slider-list/active/{id}', [SliderController::class, 'active'])->name('slider-list.active');
    //-----------------------------SLIDER MANAGEMENT END-----------------------------


    //-----------------------------System Settings START----------------------------
    Route::get('settings/system-info','Admin\SettingController@system_info')->name('system.settings');
    Route::post('settings/system-info-update','Admin\SettingController@system_info_update')->name('system.settings.store');

    Route::get('contact-us','Admin\SettingController@contact_us')->name('contact.us');
    //-----------------------------System Settings END-----------------------------


    //-----------------------------Subscriber MANAGEMENT START----------------------------
    Route::resource('subscriber', 'Admin\SubscriberController');
    //-----------------------------Subscriber MANAGEMENT END-----------------------------

    //-----------------------------Student MANAGEMENT START----------------------------
    Route::get('students', [StudentController::class, 'index'])->name('students.index');
    Route::get('students/details/{id}', [StudentController::class, 'details'])->name('students.details');
    //-----------------------------Student MANAGEMENT END-----------------------------

});