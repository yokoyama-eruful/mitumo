<?php

declare(strict_types=1);

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AssessController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\Futsu2\AssessController as Futsu2AssessController;
use App\Http\Controllers\Futsu2\DownloadController as Futsu2DownloadController;
use App\Http\Controllers\Futsu2\Futsu2Controller;
use App\Http\Controllers\Futsu2\PreviewController as Futsu2PreviewController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\TopController;
use Illuminate\Support\Facades\Route;


Route::get('/test',function(){
    return view('test');
});
Route::controller(AuthController::class)
    ->name('auth.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('login', 'index')->name('login');
        Route::post('login', 'login')->name('login');
        Route::post('logout', 'logout')->name('logout');
    });

Route::middleware('auth:web')
    ->group(function () {
        Route::controller(TopController::class)
            ->prefix('top')
            ->name('top.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/version/show', 'show')->name('show');
            });

        Route::controller(AccountController::class)
            ->prefix('account')
            ->name('account.')
            ->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('save/{id}', 'save')->name('save');
                Route::delete('destroy/{id}', 'destroy')->name('destroy');
            });

        Route::controller(EstimateController::class)
            ->prefix('estimate')
            ->name('estimate.')
            ->group(function () {
                Route::get('create', 'create')->name('create');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('save/{id}', 'save')->name('save');
                Route::post('duplication/{id}', 'duplication')->name('duplication');
                Route::delete('destroy/{id}', 'destroy')->name('destroy');
            });

        Route::controller(PreviewController::class)
            ->prefix('estimate/preview/{id}')
            ->name('estimate.preview.')
            ->group(function () {
                Route::get('standard', 'standard')->name('standard');
                Route::get('comprehensive', 'comprehensive')->name('comprehensive');
                Route::get('standardAndComprehensive', 'standardAndComprehensive')->name('standardAndComprehensive');
                Route::get('standardOfRecommend', 'standardOfRecommend')->name('standardOfRecommend');
                Route::get('comprehensiveOfRecommend', 'comprehensiveOfRecommend')->name('comprehensiveOfRecommend');
                Route::get('standardOfRecommendAndComprehensiveOfRecommend', 'standardOfRecommendAndComprehensiveOfRecommend')->name('standardOfRecommendAndComprehensiveOfRecommend');
                Route::get('standardOfEarthquake', 'standardOfEarthquake')->name('standardOfEarthquake');
                Route::get('comprehensiveOfEarthquake', 'comprehensiveOfEarthquake')->name('comprehensiveOfEarthquake');
                Route::get('calculation', 'calculation')->name('calculation');
            });

        Route::controller(AssessController::class)
            ->prefix('estimate/assess')
            ->name('estimate.assess.')
            ->group(function () {
                Route::get('{id}', 'show')->name('show');
                Route::post('{id}/download/{fileType}', 'download')->name('download');
            });

        Route::controller(DownloadController::class)
            ->prefix('estimate/download/{id}')
            ->name('estimate.download.')
            ->group(function () {
                Route::get('index/', 'index')->name('index');
                Route::post('{category}', 'download')->name('type');
            });

        Route::controller(Futsu2Controller::class)
            ->prefix('futsu2/')
            ->name('futsu2.')
            ->group(function () {
                Route::get('create', 'create')->name('create');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('save/{id}', 'save')->name('save');
                Route::post('duplication/{id}', 'duplication')->name('duplication');
                Route::delete('destroy/{id}', 'destroy')->name('destroy');
            });

        Route::controller(Futsu2PreviewController::class)
            ->prefix('futsu2/preview/{id}')
            ->name('futsu2.preview.')
            ->group(function () {
                Route::get('standard', 'standard')->name('standard');
                Route::get('standardOfRecommend', 'standardOfRecommend')->name('standardOfRecommend');
                Route::get('standardOfEarthquake', 'standardOfEarthquake')->name('standardOfEarthquake');
                Route::get('calculation', 'calculation')->name('calculation');
            });

        Route::controller(Futsu2DownloadController::class)
            ->prefix('futsu2/download/{id}')
            ->name('futsu2.download.')
            ->group(function () {
                Route::get('index/', 'index')->name('index');
                Route::post('{category}', 'download')->name('type');
            });

        Route::controller(Futsu2AssessController::class)
            ->prefix('futsu2/assess')
            ->name('futsu2.assess.')
            ->group(function () {
                Route::get('{id}', 'show')->name('show');
                Route::post('{id}/download/{fileType}', 'download')->name('download');
            });
    });
