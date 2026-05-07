<?php

declare(strict_types=1);

namespace App\Livewire\New;

use App\Livewire\Old\Futsu2Form as OldFutsu2Form;

final class Futsu2Form extends OldFutsu2Form
{
    public function render()
    {
        $view = parent::render();

        return view('new.futsu2.livewire.form', $view->getData());
    }
}
