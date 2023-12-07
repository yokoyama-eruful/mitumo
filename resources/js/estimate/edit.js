
window.onload = (event) => {
    triggerProperty();
    triggerAssess();
};

const resetViewArea = () => {
    const viewContainerIds = document.querySelectorAll(`[id*='-view-container']`);

    viewContainerIds.forEach(element => {
        element.style.display = 'none';
    });
}

const noneViewArea = (tagList) => {
    tagList.forEach(id => {
        const element = document.getElementById(id + '-view-container');
        if (element) {
            element.style.display = 'none';
        }
    });
}

const showViewArea = (tagList) => {
    tagList.forEach(id => {
        const element = document.getElementById(id + '-view-container');
        if (element) {
            element.style.display = 'flex';
        }
    });
}

const triggerProperty = () => {
    const selectedValue = document.getElementById('property_type').value;

    resetViewArea();
    let showAreaList = [];
    switch (selectedValue) {
        case '住宅':
            showAreaList = [
                'actual_loss_factor', 'building_insurance_payout',
                'household_insurance_payout', 'insurance_purpose'
            ];
            break;

        case '普通':
            showAreaList = [
                'actual_loss_factor', 'other_discount', 'chattel_surcharge_category',
                'occupational_surcharge', 'work_surcharge', 'building_insurance_payout',
                'household_insurance_payout', 'facility_insurance_payout',
                'other_facility_insurance_payout',
                'item_insurance_payout', 'other_item_insurance_payout', 'insurance_purpose'
            ];
            break;

        case '工場':
            showAreaList = [
                'base_rate', 'chattel_surcharge', 'actual_loss_factor', 'other_discount',
                'building_insurance_payout', 'household_insurance_payout', 'facility_insurance_payout',
                'other_facility_insurance_payout', 'item_insurance_payout',
                'other_item_insurance_payout',
                'insurance_purpose'
            ];
            break;
    };

    showViewArea(showAreaList);
    switchDisplayRecommendArea();
    switchDisplayEarthquakeArea();
};

// 物件のタイプをトリガーとした表示・非表示
document.getElementById('property_type').addEventListener('change', triggerProperty);


const switchDisplayRecommendArea = () => {
    const payMethod = document.getElementById('pay_method').value;
    const insuranceYear = document.getElementById('insurance_year').value;

    const element = document.getElementById('recommend-view-container');
    if (!element) {
        return;
    }

    //払込方法が「一時払」かつ共済期間が１年の場合
    if (payMethod === '一時払' && insuranceYear == 1) {
        element.style.display = 'block';

    } else {
        element.style.display = 'none';
    }
}

document.getElementById('pay_method').addEventListener('change', switchDisplayRecommendArea);
document.getElementById('insurance_year').addEventListener('change', switchDisplayRecommendArea);


const switchDisplayEarthquakeArea = () => {
    const propertyType = document.getElementById('property_type').value;
    const insuranceYear = document.getElementById('insurance_year').value;

    const element = document.getElementById('earthquake-view-container');
    if (!element) {
        return;
    }

    //物件が「普通」or「工場」かつ共済期間が１年の場合
    if ((propertyType == '工場' || propertyType == '普通') && insuranceYear == 1) {
        element.style.display = 'block';
    } else {
        element.style.display = 'none';
    }
}

document.getElementById('property_type').addEventListener('change', switchDisplayEarthquakeArea);
document.getElementById('insurance_year').addEventListener('change', switchDisplayEarthquakeArea);

const triggerAssess = () => {
    const selectedValue = document.getElementById('assess_item').value;
    console.log(selectedValue);
    resetViewArea()
    let showAreaList = [];
    switch (selectedValue) {
        case '通常':
            showAreaList = ['assess_usage', 'assess_structure'];
            break;

        case 'ピロティー付':
            showAreaList = ['assess_usage', 'assess_structure', 'piloti_floor_area'];
            break;

        case '太陽光付':
            showAreaList = ['assess_usage', 'assess_structure', 'installation_date', 'installation_cost'];
            break;

        case '分譲マンション':
            showAreaList = ['assess_usage', 'assess_structure'];
            break;

        case '年次取得':
            showAreaList = ['assess_usage', 'assess_structure', 'annual_price'];
            break;

        case '改装物件':
            showAreaList = ['replacement_price', 'renovation_cost',
                'conventional_age', 'depreciation_rate', 'renovation_age',
                'renovation_depreciation_rate'
            ];
            break;
    };

    showViewArea(showAreaList);
}


//評価項目をトリガーとした表示・非表示
document.getElementById('assess_item').addEventListener('change', triggerAssess);

//築年数を始期日と建築年から計算
document.getElementById('building_year').addEventListener('keypress', (event) => {
    if (event.key === 'Enter') {
        const value = event.target.value;
        let year = ''

        const isNotInteger = (value) => {
            return !/^[０-９0-9]+$/.test(value);
        }

        const isFullWidthInteger = (value) => {
            return /^[０-９]+$/.test(value);
        }

        if (isNotInteger(value)) {
            console.log('不正な値です');
            document.getElementById('building_year').value = year;
            return;
        } else if (isFullWidthInteger(value)) {
            console.log('全角な値です');
            year = value.replace(/[０-９]/g, function (s) {
                return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
            });
            document.getElementById('building_year').value = year;
        }
    }
});

document.getElementById('calcBuildingAgeButton').addEventListener('click', () => {
    const kenchiku = document.getElementById('building_year').value;
    const start_date = document.getElementById('start_date').value;


    if (kenchiku && start_date) {
        const calcYears = (startDate, endDate) => {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const years = end.getFullYear() - start.getFullYear();

            return years;
        }

        document.getElementById('building_age').value = calcYears(`${kenchiku}-01-01`, `${start_date}-01`);
    }

});

