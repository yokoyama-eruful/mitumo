<?php

declare(strict_types=1);

namespace App\Http\Requests\Futsu2;

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
            'company' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'phone_number' => ['nullable', 'regex:/^[0-9-]+$/'],
            'staff_name' => ['nullable', 'string'],
            'certificate_number' => ['nullable', 'numeric'],
            'contractee' => ['nullable', 'string'],
            'location' => ['nullable', 'string'],
            'floor_area' => ['nullable', 'numeric', 'min:0'],
            'exclusive_area' => ['nullable', 'numeric', 'min:0'],
            'structure' => ['nullable', 'string'],
            'other_structure' => ['nullable', 'string'],
            'pillar' => ['nullable', 'string'],
            'other_pillar' => ['nullable', 'string'],
            'fire_resistance' => ['nullable', 'string'],
            'other_fire_resistance' => ['nullable', 'string'],
            'usage' => ['nullable', 'string'],
            'other_usage' => ['nullable', 'string'],
            'floor' => ['nullable'],
            'building' => ['nullable'],
            'other' => ['nullable', 'string'],
            'property_type' => ['required', 'string', 'in:住宅,普通,工場'],
            'rank' => ['required', 'string'],
            'start_date' => ['nullable', 'required_if:assess_item,通常,ピロティー付,太陽光付,分譲マンション,年次取得,大規模改装', 'date'],
            'building_year' => ['nullable', 'required_if:assess_item,通常,ピロティー付,太陽光付,分譲マンション,年次取得,大規模改装',  'integer', 'min:0', 'max:'.(date('Y') + 1)],
            'building_age' => ['nullable', 'required_if:assess_item,通常,ピロティー付,太陽光付,分譲マンション,年次取得,大規模改装', 'integer'],
            'pay_method' => ['required', 'string'],
            'insurance_year' => ['nullable', 'required_without_all:insurance_month,insurance_day',  'integer', 'min:1', 'max:10'],
            'insurance_month' => ['nullable', 'required_without_all:insurance_year,insurance_day', 'integer', 'min:1', 'max:11'],
            'insurance_day' => ['nullable', 'required_without_all:insurance_year,insurance_month,', 'integer', 'min:1', 'max:31'], //わからん

            //おすすめ契約の項目
            'recommend1' => ['nullable', new Recommend()],
            'recommend2' => ['nullable', new Recommend()],
            'recommend3' => ['nullable', new Recommend()],
            //地震特約の項目
            'earthquake1' => ['nullable'],
            'earthquake2' => ['nullable'],
            'earthquake3' => ['nullable'],

            'building_base_rate' => ['nullable', 'required_with:building_insurance_payout', 'numeric', 'min:0'],
            'chattel_base_rate' => ['nullable', 'required_with:household_insurance_payout,facility_insurance_payout,other_facility_insurance_payout,other_facility_insurance_payout,item_insurance_payout,other_item_insurance_payout', 'numeric', 'min:0'],
            'factory_building_code' => ['nullable',  'string'],

            'is_sonpo_base_rate' => ['nullable'],
            'sonpo_building_base_rate' => ['nullable', 'required_with:is_sonpo_base_rate', 'numeric', 'min:0'],
            'sonpo_chattel_base_rate' => ['nullable',  'required_with:is_sonpo_base_rate','numeric', 'min:0'],

            // // 動的に変化する項目（物件トリガー
            'chattel_surcharge' => ['nullable', 'string'],

            'actual_loss_factor' => ['nullable', 'numeric', 'min:0'],
            'discount_risk_code' => ['nullable', 'numeric'],
            'discount_risk_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount_fire_extinguisher' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount_security' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount_public' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'other_discount' => ['nullable', 'numeric', 'min:0', 'max:50'],

            'chattel_surcharge_category' => ['nullable', 'string'],

            'occupational_surcharge_code' => ['nullable',  'string'], // 'required_if:property_type,普通　は一旦省略
            'occupational_surcharge_rate' => ['nullable',  'numeric', 'min:0', 'max:99.99'],  // 'required_if:property_type,普通　は一旦省略
            'work_surcharge_code' => ['nullable', 'string'], // 'required_if:property_type,普通　は一旦省略
            'work_surcharge_rate' => ['nullable',  'numeric', 'min:0', 'max:99.99'], // 'required_if:property_type,普通　は一旦省略

            'building_insurance_payout' => ['nullable', 'numeric', 'min:0'],
            'household_insurance_payout' => ['nullable', 'numeric', 'min:0'],
            'facility_insurance_payout' => ['nullable', 'numeric', 'min:0'],
            'other_facility_insurance_payout' => ['nullable', 'numeric', 'min:0'],
            'item_insurance_payout' => ['nullable', 'numeric', 'min:0'],
            'other_item_insurance_payout' => ['nullable', 'numeric', 'min:0'],

            //動的に変化する項目（評価トリガー）
            'assess_item' => ['nullable', 'in:通常,ピロティー付,太陽光付,分譲マンション,年次取得,大規模改装,選択しない'],

            'assess_usage' => ['nullable', 'required_if:assess_item,通常,ピロティー付,太陽光付,分譲マンション,年次取得', 'string'],
            'assess_structure' => ['nullable', 'required_if:assess_item,通常,ピロティー付,太陽光付,分譲マンション,年次取得', 'string'],

            'piloti_floor_area' => ['nullable', 'required_if:assess_item,ピロティー付', 'numeric', 'min:0'],

            'replacement_price' => ['nullable', 'required_if:assess_item,大規模改装', 'numeric', 'min:0'],
            'renovation_cost' => ['nullable', 'required_if:assess_item,大規模改装', 'numeric', 'min:0'],
            'conventional_age' => ['nullable', 'required_if:assess_item,大規模改装', 'numeric', 'min:0'],
            'depreciation_rate' => ['nullable', 'required_if:assess_item,大規模改装', 'numeric', 'min:0'],
            'renovation_age' => ['nullable', 'required_if:assess_item,大規模改装', 'numeric', 'min:0'],
            'renovation_depreciation_rate' => ['nullable', 'required_if:assess_item,大規模改装', 'numeric', 'min:0'],

            'installation_date' => ['nullable', 'required_if:assess_item,太陽光付', 'date'],
            'installation_cost' => ['nullable', 'required_if:assess_item,太陽光付', 'numeric', 'min:0'],

            'annual_price' => ['nullable', 'required_if:assess_item,年次取得', 'numeric', 'min:0'],

            'remarks' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (($this->input('household_insurance_payout') || $this->input('facility_insurance_payout') ||
            $this->input('other_facility_insurance_payout') || $this->input('item_insurance_payout') ||
            $this->input('other_item_insurance_payout')) && $this->input('property_type') == '工場' && ! $this->input('chattel_surcharge')) {
                $validator->errors()->add('エラー', '動産を指定する場合は、動産割増を指定してください');
            }
        });

        $validator->after(function ($validator) {
            if (($this->input('household_insurance_payout') || $this->input('facility_insurance_payout') ||
                  $this->input('other_facility_insurance_payout') || $this->input('item_insurance_payout') ||
                  $this->input('other_item_insurance_payout')) && $this->input('property_type') == '普通' && ! $this->input('chattel_surcharge_category')) {
                $validator->errors()->add('エラー', '動産を指定する場合は、動産割増を指定してください');
            }
        });

        $validator->after(function ($validator) {
            if ($this->input('insurance_year') && ($this->input('insurance_month') || $this->input('insurance_day'))) {
                $validator->errors()->add('エラー', '共済期間が１年以上の場合は、月・日数は選択できません。');
            }
        });

        $validator->after(function ($validator) {
            if ($this->input('insurance_year') == 1 && $this->input('pay_method') == '長期年払') {
                $validator->errors()->add('エラー', '共済期間が１年の場合、払込方法「長期年払」は選択できません。');
            }
        });

        $validator->after(function ($validator) {
            if ($this->input('assess_item') == '分譲マンション' && ! $this->input('exclusive_area')) {
                $validator->errors()->add('エラー', '分譲マンションの評価を行う場合、専有面積は必須です。');
            }
        });

        $validator->after(function ($validator) {
            if (in_array($this->input('assess_item'), ['通常', 'ピロティー付', '太陽光付', '年次取得', '大規模改装'], true) && ! $this->input('floor_area')) {
                $validator->errors()->add('エラー', 'この物件の評価を行うには、延床面積の入力が必須です。');
            }
        });

        $validator->after(function ($validator) {
            if ($this->input('assess_item') == '分譲マンション' && $this->input('structure') != 'コンクリート造等') {
                $validator->errors()->add('エラー', '分譲マンションを評価する場合、「構造」が「コンクリート造等」でなくてはいけません。');
            }
        });

        $validator->after(function ($validator) {
            if ($this->input('assess_item') == '分譲マンション' && $this->input('property_type') == '工場') {
                $validator->errors()->add('エラー', '分譲マンションを評価する場合、「物件」が「住宅」または「普通」でなくてはいけません。');
            }
        });

        $validator->after(function ($validator) {
            if ($this->input('assess_item') == '分譲マンション' &&
            ($this->input('property_type') == '住宅' && $this->input('rank') != 'M' ||
            $this->input('property_type') == '普通' && ($this->input('rank') != '1級' || $this->input('occupational_surcharge_code') != 899))) {

                if ($this->input('property_type') == '住宅' && $this->input('rank') != 'M') {
                    $validator->errors()->add('エラー', '「物件」が「住宅」で分譲マンションを評価する場合「級別」は「M」でなくてはいけません。');
                }

                if ($this->input('property_type') == '普通' && ($this->input('rank') != '1級' || $this->input('occupational_surcharge_code') != 899)) {
                    $validator->errors()->add('エラー', '「物件」が「普通」で分譲マンションを評価する場合「級別」は「1級」かつ「職業割増」で販売用物件、空き家を選択する必要があります。');
                }
            }
        });

        $validator->after(function ($validator) {
            if (in_array($this->input('property_type'), ['普通', '住宅']) && ($this->input('item_insurance_payout') || $this->input('other_item_insurance_payout')) && $this->input('chattel_surcharge_category') == '-') {
                $validator->errors()->add('エラー', '商品共済金額がある場合、動産割増を選択してください。');
            }
        });

        // $validator->after(function ($validator) {
        //     if ($this->input('property_type') == '住宅' && ($this->input('building_insurance_payout') && (! $this->input('start_date') || ! $this->input('building_year') || ! $this->input('building_age')))) {
        //         $validator->errors()->add('エラー', '物件が「住宅」であり、建物共済掛金の入力がある場合、築年数は必須です。');
        //     }
        // });
        $validator->after(function ($validator) {
            if (($this->input('household_insurance_payout') || $this->input('facility_insurance_payout') || $this->input('other_facility_insurance_payout') || $this->input('item_insurance_payout') || $this->input('other_item_insurance_payout')) && $this->input('insurance_year') > 5) {
                $validator->errors()->add('エラー', '動産に共済金額がある場合、共済期間は5年以下である必要があります。');
            }
        });

        $validator->after(function ($validator) {
            if ($this->input('assess_item') == '年次取得' && ($this->input('building_year') > 2020 || $this->input('building_year') < 1976)) {
                $validator->errors()->add('エラー', '建築年は「2020年~1976年」でなくてはいけません。');
            }
        });

        $validator->after(function ($validator) {
            if ($this->input('floor')) {
                if (preg_match('/^[+]?[0]*[1-9]*[.]?[0-9 ０-９]*$/', $this->input('floor')) == 0) {
                    $validator->errors()->add('エラー', '階は整数でなくてはいけません。');
                }
            }
        });

        $validator->after(function ($validator) {
            if ($this->input('building')) {
                if (preg_match('/^[+]?[0]*[1-9]*[.]?[0-9 ０-９]*$/', $this->input('building')) == 0) {
                    $validator->errors()->add('エラー', '棟は整数でなくてはいけません。');
                }
            }
        });
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
