<?php

declare(strict_types=1);

namespace App\Livewire\New;

use App\Livewire\Old\EstimateForm as OldEstimateForm;

final class EstimateForm extends OldEstimateForm
{
    public function render()
    {
        $view = parent::render();

        return view('new.estimate.livewire.form', $view->getData());
    }
}
