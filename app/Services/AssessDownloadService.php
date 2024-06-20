<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\Assess\AssessAnnual;
use App\Domains\Assess\AssessCondo;
use App\Domains\Assess\AssessNormal;
use App\Domains\Assess\AssessPiloti;
use App\Domains\Assess\AssessRenovation;
use App\Domains\Assess\AssessSolarPanel;
use App\Downloads\Assess\AssessAnnualDownloader;
use App\Downloads\Assess\AssessCondoDownloader;
use App\Downloads\Assess\AssessNormalDownloader;
use App\Downloads\Assess\AssessPilotiDownloader;
use App\Downloads\Assess\AssessRenovationDownloader;
use App\Downloads\Assess\AssessSolarPanelDownloader;

final class AssessDownloadService
{
    public function download($estimate, $fileType)
    {
        $assessType = $estimate->assess_item;

        switch ($assessType) {
            case '通常' :
                $this->dropNormal($estimate, $fileType);
                break;

            case 'ピロティー付' :
                $this->dropPiloti($estimate, $fileType);
                break;

            case '太陽光付' :
                $this->dropSolarPanel($estimate, $fileType);
                break;

            case '年次取得' :
                $this->dropAnnual($estimate, $fileType);
                break;

            case '分譲マンション' :
                $this->dropCondo($estimate, $fileType);
                break;

            default:
                $this->dropRenovation($estimate, $fileType);
                break;
        }
    }

    private function dropNormal($estimate, $fileType)
    {
        $assessData = new AssessNormal($estimate);
        $assessDownloader = new AssessNormalDownloader();
        $assessDownloader->dropFile($estimate, $assessData, $fileType);
    }

    private function dropPiloti($estimate, $fileType)
    {
        $assessData = new AssessPiloti($estimate);
        $assessDownloader = new AssessPilotiDownloader();
        $assessDownloader->dropFile($estimate, $assessData, $fileType);
    }

    private function dropSolarPanel($estimate, $fileType)
    {
        $assessData = new AssessSolarPanel($estimate);
        $assessDownloader = new AssessSolarPanelDownloader();
        $assessDownloader->dropFile($estimate, $assessData, $fileType);
    }

    private function dropAnnual($estimate, $fileType)
    {
        $assessData = new AssessAnnual($estimate);
        $assessDownloader = new AssessAnnualDownloader();
        $assessDownloader->dropFile($estimate, $assessData, $fileType);
    }

    private function dropCondo($estimate, $fileType)
    {
        $assessData = new AssessCondo($estimate);
        $assessDownloader = new AssessCondoDownloader();
        $assessDownloader->dropFile($estimate, $assessData, $fileType);
    }

    private function dropRenovation($estimate, $fileType)
    {
        $assessData = new AssessRenovation($estimate);
        $assessDownloader = new AssessRenovationDownloader();

        $assessDownloader->dropFile($estimate, $assessData, $fileType);
    }
}
