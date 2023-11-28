<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FranchisorController;
use App\Http\Controllers\NewsCategoryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EducationCategoryController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\FranchiseController;
use App\Http\Controllers\FranchiseCategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Laravel\Socialite\Facades\Socialite;

/*
|-------------------------------------------------------------------------- 
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/aboutUs', function () {
    return view('aboutUs');
})->name('aboutUs');


Route::get('/admin/dashboard', function () {
    return view('admin.admin_index');
})->middleware(['auth', 'verified','admin'])->name('adminDashboard');

Route::controller(UserController::class)->group(function(){
});    

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'userDashboard'])->name('dashboard');
   
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

      // Display the profile update form
      Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    
      // Handle the profile update
      Route::put('/profile/update', [UserController::class, 'update'])->name('profile.update');
  
      Route::get('/change/password',[UserController::class, 'ChangePassword'])->name('change.password');
      Route::post('/update/password',[UserController::class, 'UpdatePassword'])->name('update.password');
});

// Admin Controller
Route::controller(AdminController::class)->group(function(){
    Route::get('/admin/login', 'login')->name('admin.login');

    Route::get('/admin/logout','destroy')->name('admin.logout');
    Route::get('/admin/profile','profile')->name('admin.profile');
    Route::get('/edit/profile','editProfile')->name('edit.profile');
    Route::post('/store/profile','storeProfile')->name('store.profile');
});

Route::controller(NewsCategoryController::class)->group(function(){
    Route::get('/admin/all/news/category','AllNewsCategory')->middleware('admin')->name('all.news.category');
    Route::get('/admin/add/news/category','AddNewsCategory')->middleware('admin')->name('add.news.category');
    Route::post('/admin/post/news/category','PostNewsCategory')->middleware('admin')->name('post.news.category');
    Route::get('/delete/news/{id}','DeleteNewsCategory')->middleware('admin')->name('delete.news.category');
});

Route::controller(NewsController::class)->group(function(){
    Route::get('/admin/all/news','AllNews')->middleware('admin')->name('all.news');
    Route::get('/admin/add/news','AddNews')->middleware('admin')->name('add.news');
    Route::get('/news/detail/{id}','NewsDetail')->name('news.detail');
    Route::post('/admin/post/news','PostNews')->middleware('admin')->name('post.news');
    Route::get('/news','news')->name('news');
    Route::get('/news/category/{category}','NewsByCategory')->name('news.by.category');
    Route::get('/news/tags/{tags}','NewsByTags')->name('news.by.tags');
});

Route::controller(EducationController::class)->group(function(){
    Route::get('/education', 'index')->name('education.index');
    Route::post('/education/search', 'search')->name('education.search');
    Route::get('/education/detail/{id}','detail')->name('education.detail');
    Route::get('/education/ratingView', 'ratingView');
    Route::post('/education/{id}/rate', 'rateEducation')->name('education.rate');
});

Route::controller(EducationCategoryController::class)->group(function(){
    
    Route::get('/admin/all/education/category','AllEducationCategory')->middleware('admin')->name('all.education.category');
    Route::get('/admin/add/education/category','AddEducationCategory')->middleware('admin')->name('add.education.category');
    Route::post('/admin/post/education/category','PostEducationCategory')->middleware('admin')->name('post.education.category');
    Route::get('/delete/education/category/{id}','DeleteEducationCategory')->middleware('admin')->name('delete.education.category');    
});


Route::controller(FranchisorController::class)->group(function(){
    Route::post('/admin/store/franchisor','StoreFranchisor')->name('store.franchisor');
    Route::get('/register/franchisor','RegisterFranchisor')->name('register.franchisor');
    Route::get('/admin/all/franchisor','AllFranchisor')->middleware('admin')->name('all.franchisor');
});

Route::controller(FranchiseController::class)->group(function(){
    Route::get('/admin/all/franchise','AllFranchise')->name('all.franchise');
    Route::get('/franchise','Franchise')->name('franchise');
    Route::get('/register/franchise','RegisterFranchise')->middleware('franchisor')->name('register.franchise');
    Route::post('/post/franchise','StoreFranchise')->middleware('franchisor')->name('store.franchise');
    Route::get('/dashboard/registerFranchise','RegisterFranchise')->middleware('franchisor')->name('dashboard.register.franchise');
});

//education route for admin
Route::middleware(['admin','auth'])->group(function(){
    Route::controller(EducationController::class)->group(function(){
        Route::get('/admin/all/education','AllEducation')->name('all.education');
        Route::get('/admin/add/education','AddEducation')->name('add.education');
        Route::post('/admin/post/education','PostEducation')->name('post.education');
    });

    Route::controller(FranchiseCategoryController::class)->group(function(){
        Route::get('/admin/all/franchise/category','AllFranchiseCategory')->name('all.franchise.category');
        Route::get('/admin/add/franchise/category','AddFranchiseCategory')->name('add.franchise.category');
        Route::post('/admin/post/franchise/category','PostFranchiseCategory')->name('post.franchise.category');
        Route::get('/delete/franchise/category/{id}','DeleteFranchiseCategory')->name('delete.franchise.category');    

        // Route::post('/admin/post/education','PostEducation')->name('post.education');
    });

    Route::controller(FranchiseController::class)->group(function(){
        Route::get('/admin/all/franchise','AllFranchise')->name('all.franchise');
        Route::get('/admin/all/franchise/Request','AllFranchiseRequest')->name('all.franchise.request');
        Route::get('/admin/approve/franchise/{id}','ApproveFranchise')->name('approve.franchise');
        Route::get('/admin/reject/franchise/{id}','RejectFranchise')->name('reject.franchise');
    });
});





//route for franchisor
// Route::middeware(['franchisor','auth'])->group(function(){
//     Route::controller()
// });

Route::get('/phpinfo', function(){
    phpinfo();
});

require __DIR__.'/auth.php';
