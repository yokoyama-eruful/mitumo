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
    public function download($estimate, $isPdfType)
    {
        $assessType = $estimate->assess_item;

        switch ($assessType) {
            case '通常' :
                $this->dropNormal($estimate, $isPdfType);
                break;

            case 'ピロティー付' :
                $this->dropPiloti($estimate, $isPdfType);
                break;

            case '太陽光付' :
                $this->dropSolarPanel($estimate, $isPdfType);
                break;

            case '年次取得' :
                $this->dropAnnual($estimate, $isPdfType);
                break;

            case '分譲マンション' :
                $this->dropCondo($estimate, $isPdfType);
                break;

            default:
                $this->dropRenovation($estimate, $isPdfType);
                break;
        }
    }

    private function dropNormal($estimate, $isPdfType)
    {
        $assessData = new AssessNormal($estimate);
        $assessDownloader = new AssessNormalDownloader();
        $assessDownloader->dropFile($estimate, $assessData, $isPdfType);
    }

    private function dropPiloti($estimate, $isPdfType)
    {
        $assessData = new AssessPiloti($estimate);
        $assessDownloader = new AssessPilotiDownloader();
        $assessDownloader->dropFile($estimate, $assessData, $isPdfType);
    }

    private function dropSolarPanel($estimate, $isPdfType)
    {
        $assessData = new AssessSolarPanel($estimate);
        $assessDownloader = new AssessSolarPanelDownloader();
        $assessDownloader->dropFile($estimate, $assessData, $isPdfType);
    }

    private function dropAnnual($estimate, $isPdfType)
    {
        $assessData = new AssessAnnual($estimate);
        $assessDownloader = new AssessAnnualDownloader();
        $assessDownloader->dropFile($estimate, $assessData, $isPdfType);
    }

    private function dropCondo($estimate, $isPdfType)
    {
        $assessData = new AssessCondo($estimate);
        $assessDownloader = new AssessCondoDownloader();
        $assessDownloader->dropFile($estimate, $assessData, $isPdfType);
    }

    private function dropRenovation($estimate, $isPdfType)
    {
        $assessData = new AssessRenovation($estimate);
        $assessDownloader = new AssessRenovationDownloader();
        $assessDownloader->dropFile($estimate, $assessData, $isPdfType);
    }
}
