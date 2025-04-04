<?php
use App\Http\Controllers\csvController;

Route::post('csv/upload', [csvController::class, 'uploadCSV']);