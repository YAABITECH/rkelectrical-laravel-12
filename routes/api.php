<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PracticeQuestionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/load-subjects', [PracticeQuestionController::class,'loadSubjects']);
Route::get('/load-topics', [PracticeQuestionController::class,'loadTopics']);
Route::get('/load-subtopics', [PracticeQuestionController::class,'loadSubtopics']);
