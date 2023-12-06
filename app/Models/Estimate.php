<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Estimate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company',
        'address',
        'phone_number',
        'staff_name',
        'contractee',
        'location',

        'floor_area',
        'exclusive_area',
        'structure',
        'other_structure',
        'pillar',
        'other_pillar',
        'fire_resistance',
        'other_fire_resistance',
        'usage',
        'other_usage',
        'floor',
        'building',
        'other',

        'property_type',
        'rank',
        'start_date',
        'building_year',
        'building_age',
        'pay_method',
        'insurance_year',
        'insurance_month',
        'insurance_day',
        'assess_item',

        'property_detail',
        'assess_detail',
    ];

    protected $casts = [
        'property_detail' => 'json',
        'assess_detail' => 'json',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
