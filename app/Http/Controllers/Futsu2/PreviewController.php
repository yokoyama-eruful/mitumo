<?php

declare(strict_types=1);

namespace App\Http\Controllers\Futsu2;

use App\Domains\Assess\AssessNormal;
use App\Domains\ComprehensiveInsurancePremium;
use App\Domains\EarthquakeStandardInsurancePremium;
use App\Domains\FactoryPropertyBasePremium;
use App\Domains\Futsu2Compensation;
use App\Domains\OccupationalSurcharge;
use App\Domains\RecommendStandardInsurancePremium;
use App\Domains\StandardInsurancePremium;
use App\Domains\WorkSurcharge;
use App\Http\Controllers\Controller;
use App\Services\Futsu2\Futsu2Service;
use Illuminate\Support\Facades\Auth;

class PreviewController extends Controller
{
    public function __construct(
        private Futsu2Service $futsu2Service,
    ) {
    }

    public function standard(int $id): \Illuminate\View\View
    {
        $estimate = $this->futsu2Service->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        $standardPremium = new StandardInsurancePremium($estimate);
        $standardCompensation = new Futsu2Compensation($estimate->property_type);

        return view('futsu2.preview.standard', compact('estimate', 'standardPremium', 'standardCompensation'));
    }

    public function standardOfRecommend(int $id)
    {
        $estimate = $this->futsu2Service->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        $standardPremium = new StandardInsurancePremium($estimate);
        $recommendStandardPremium = new RecommendStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());

        return view('futsu2.preview.standard-of-recommend', compact('estimate', 'standardPremium', 'recommendStandardPremium'));
    }

    public function standardOfEarthquake(int $id)
    {
        $estimate = $this->futsu2Service->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        $standardPremium = new StandardInsurancePremium($estimate);
        $earthquakePremium = new EarthquakeStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());

        return view('futsu2.preview.standard-of-earthquake', compact('estimate', 'standardPremium', 'earthquakePremium'));
    }

    public function calculation(int $id)
    {
        $estimate = $this->futsu2Service->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        $standard = new StandardInsurancePremium($estimate);

        $occupationalSurchargeItem = new OccupationalSurcharge('estimateFutsu2/surchargeTable/occupationalSurchargeTable.csv');
        $workSurchargeItem = new WorkSurcharge('estimateFutsu2/surchargeTable/workSurchargeTable.csv');
        $factoryPropertyBasePremiumItem = new FactoryPropertyBasePremium();
        $comprehensive = new ComprehensiveInsurancePremium($estimate);

        return view('futsu2.preview.calculation', compact('estimate', 'standard', 'comprehensive', 'occupationalSurchargeItem', 'workSurchargeItem', 'factoryPropertyBasePremiumItem'));
    }
}
