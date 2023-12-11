<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\TopController;
use Illuminate\Support\Facades\Route;

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
            });

        Route::controller(EstimateController::class)
            ->prefix('estimate')
            ->name('estimate.')
            ->group(function () {
                Route::get('create', 'create')->name('create');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('save/{id}', 'save')->name('save');
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
            });

        Route::controller(DownloadController::class)
            ->prefix('estimate/download/{id}')
            ->name('estimate.download.')
            ->group(function () {
                Route::get('select', 'index')->name('index');
            });
    });
