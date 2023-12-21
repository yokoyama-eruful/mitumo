<?php

declare(strict_types=1);

namespace App\Http\Requests\Estimate;

use Illuminate\Foundation\Http\FormRequest;

class DownloadRequest extends FormRequest
{
    public function getFileType()
    {
        return $this->query('fileType') ?? 'excel';
    }

    public function getKeisyo()
    {
        return $this->query('keisyo') ?? '様';
    }
}
