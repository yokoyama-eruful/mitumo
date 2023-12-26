<?php

declare(strict_types=1);

namespace App\Http\Requests\Estimate;

use App\Domains\Dto\EstimateAssessDetailDto;
use App\Domains\Dto\EstimatePropertyDetailDto;
use App\Domains\Dto\EstimateStandardInfoDto;
use App\Rules\Recommend;
use Illuminate\Foundation\Http\FormRequest;

final class SaveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company' => ['required', 'string'],
            'address' => ['required', 'string'],
            'phone_number' => ['required', 'regex:/^[0-9-]+$/'],
            'staff_name' => ['required', 'string'],
            'contractee' => ['required', 'string'],
            'location' => ['required', 'string'],
            'floor_area' => ['nullable', 'required_without:exclusive_area', 'numeric', 'min:0'],
            'exclusive_area' => ['nullable', 'numeric', 'min:0'],
            'structure' => ['required', 'string'],
            'other_structure' => ['nullable', 'string'],
            'pillar' => ['nullable', 'string'],
            'other_pillar' => ['nullable', 'string'],
            'fire_resistance' => ['nullable', 'string'],
            'other_fire_resistance' => ['nullable', 'string'],
            'usage' => ['required', 'string'],
            'other_usage' => ['nullable', 'string'],
            'floor' => ['required', 'integer'],
            'building' => ['required', 'integer'],
            'other' => ['nullable', 'string'],
            'property_type' => ['required', 'string', 'in:住宅,普通,工場'],
            'rank' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'building_year' => ['required', 'digits:4', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'building_age' => ['required', 'integer'],
            'pay_method' => ['required', 'string'],
            'insurance_year' => ['nullable',  'integer'],
            'insurance_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'insurance_day' => ['nullable', 'integer', 'min:1', 'max:100'], //わからん

            //おすすめ契約の項目
            'recommend1' => ['nullable', new Recommend()],
            'recommend2' => ['nullable', new Recommend()],
            'recommend3' => ['nullable', new Recommend()],
            //地震特約の項目
            'earthquake1' => ['nullable'],
            'earthquake2' => ['nullable'],
            'earthquake3' => ['nullable'],

            'building_base_rate' => ['required', 'numeric', 'min:0'],
            'factory_building_code' => ['nullable',  'string'],

            // // 動的に変化する項目（物件トリガー
            'chattel_surcharge' => ['nullable', 'required_if:property_type,工場', 'string'],

            'actual_loss_factor' => ['nullable', 'string'],
            'other_discount' => ['nullable', 'numeric', 'min:0'],

            'chattel_surcharge_category' => ['nullable', 'required_if:property_type,普通', 'string'],

            'occupational_surcharge_code' => ['nullable',  'string'], // 'required_if:property_type,普通　は一旦省略
            'occupational_surcharge_rate' => ['nullable',  'numeric', 'min:0'],  // 'required_if:property_type,普通　は一旦省略
            'work_surcharge_code' => ['nullable', 'string'], // 'required_if:property_type,普通　は一旦省略
            'work_surcharge_rate' => ['nullable',  'numeric', 'min:0'], // 'required_if:property_type,普通　は一旦省略

            'building_insurance_payout' => ['nullable', 'numeric', 'min:0'],
            'household_insurance_payout' => ['nullable', 'numeric', 'min:0'],
            'facility_insurance_payout' => ['nullable', 'required_if:property_type,普通,工場', 'numeric', 'min:0'],
            'other_facility_insurance_payout' => ['nullable', 'required_if:property_type,普通,工場', 'numeric', 'min:0'],
            'item_insurance_payout' => ['nullable', 'numeric', 'min:0'],
            'other_item_insurance_payout' => ['nullable', 'numeric', 'min:0'],

            //動的に変化する項目（評価トリガー）
            'assess_item' => ['nullable', 'in:通常,ピロティー付,太陽光付,分譲マンション,年次取得,改装物件'],

            'assess_usage' => ['nullable', 'required_if:assess_item,通常,ピロティー付,太陽光付,分譲マンション,年次取得', 'string'],
            'assess_structure' => ['nullable', 'required_if:assess_item,通常,ピロティー付,太陽光付,分譲マンション,年次取得', 'string'],

            'piloti_floor_area' => ['nullable', 'required_if:assess_item,ピロティー付', 'numeric', 'min:0'],

            'replacement_price' => ['nullable', 'required_if:assess_item,改装物件', 'numeric', 'min:0'],
            'renovation_cost' => ['nullable', 'required_if:assess_item,改装物件', 'numeric', 'min:0'],
            'conventional_age' => ['nullable', 'required_if:assess_item,改装物件', 'integer'],
            'depreciation_rate' => ['nullable', 'required_if:assess_item,改装物件', 'numeric', 'min:0'],
            'renovation_age' => ['nullable', 'required_if:assess_item,改装物件', 'integer'],
            'renovation_depreciation_rate' => ['nullable', 'required_if:assess_item,改装物件', 'numeric', 'min:0'],

            'installation_date' => ['nullable', 'required_if:assess_item,太陽光付', 'date'],
            'installation_cost' => ['nullable', 'required_if:assess_item,太陽光付', 'numeric', 'min:0'],

            'annual_price' => ['nullable', 'required_if:assess_item,年次取得', 'numeric', 'min:0'],
        ];
    }

    public function params(): array
    {
        $standardDto = new EstimateStandardInfoDto($this->all());
        $standardParams = $standardDto->params;

        $propertyDto = new EstimatePropertyDetailDto($this->property_type, $this->all());
        $propertyParams = $propertyDto->params;

        $assessDto = new EstimateAssessDetailDto($this->assess_item, $this->all());
        $assessParams = $assessDto->params;

        return array_merge(
            $standardParams,
            ['property_detail' => $propertyParams],
            ['assess_detail' => $assessParams]
        );
    }
}
