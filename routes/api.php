<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\{
  AuthController,
  SanctumController,
};

use App\Http\Controllers\Api\{
  UserController,
  TaskController,
  RoleController,
  AuthorController,
  CourseController,
  LessonController,
  StudentController,
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
Route::namespace('Api')->group(function() {
  Route::prefix('v1')->group(function () {
    // Authorization
    Route::prefix('auth')->group(function () {
      Route::post('signin',         [SanctumController::class, 'token']);
      Route::post('signup-email',   [SanctumController::class, 'signupEmail']);
      Route::post('logout',         [AuthController::class, 'logout']);
      // Route::post('sms-code',   [AuthController::class, 'sms_code']);
    });

    // Application
    Route::prefix('app')->group(function() {
      Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::prefix('students')->group(function() {
          Route::get('/',                 [StudentController::class, 'list']);
          Route::get('{student_id}',      [StudentController::class, 'get'])->whereNumber('student_id');
          Route::post('/',                [StudentController::class, 'create']);
          Route::put('{student_id}',      [StudentController::class, 'update'])->whereNumber('student_id');
          Route::delete('{student_id}',   [StudentController::class, 'delete'])->whereNumber('student_id');
        });

        Route::prefix('profile')->group(function() {
          Route::get('/',                 [UserController::class, 'profile']);
        });
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
    });
  });
});

