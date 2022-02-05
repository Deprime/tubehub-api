<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\{
  AuthController
};

use App\Http\Controllers\Api\{
  UserController,
  RoleController,
  AuthorController,
  CourseController,
  LessonController,
};

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//   return $request->user();
// });

Route::namespace('Api')->group(function() {
  Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
      // Route::post('sms-code',   [AuthController::class, 'sms_code']);
      Route::post('signup',     [AuthController::class, 'signup']);
      Route::post('signin',     [AuthController::class, 'login']);
      Route::post('logout',     [AuthController::class, 'logout']);
    });

    Route::prefix('authors')->group(function () {
      Route::get('/',           [AuthorController::class, 'list']);
      Route::get('{author_id}', [AuthorController::class, 'get'])->whereNumber('author_id');

      Route::group(['prefix' => '/{author_id}/courses', 'where' => ['author_id' => '[0-9]+']], function () {
        Route::get('/',           [CourseController::class, 'list']);
        Route::get('{course_id}', [CourseController::class, 'get'])->whereNumber('course_id');

        Route::group(['prefix' => '/{course_id}/lessons', 'where' => ['course_id' => '[0-9]+']], function () {
          Route::get('/',           [LessonController::class, 'list']);
          Route::get('{lesson_id}', [LessonController::class, 'get'])->whereNumber('lesson_id');
        });
      });
    });

    Route::group(['middleware' => ['jwt.auth']], function() {
      # Dashboard
      Route::prefix('dashboard')->group(function () {
        // Route::get('/',         [DashboardController::class, 'index']);
      });
      Route::prefix('roles')->group(function () {
        Route::get('/',     [RoleController::class, 'list']);
      });
      Route::prefix('users')->group(function () {
        Route::get('/',     [UserController::class, 'list']);
      });
    });
  });
});
