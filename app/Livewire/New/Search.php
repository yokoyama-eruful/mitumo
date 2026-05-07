<?php

declare(strict_types=1);

namespace App\Livewire\New;

use App\Models\Estimate;
use App\Models\Futsu2;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class Search extends Component
{
    use WithPagination;

    public string $type;

    #[Url(as: 'q')]
    public string $keyword = '';

    public function updatingKeyword($value): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $estimateModel = $this->type === 'futsu' ? Estimate::class : Futsu2::class;

        $estimates = $estimateModel::where('user_id', Auth::id())
            ->when($this->keyword !== '', function ($query) {
                $query->where('contractee', 'LIKE', '%'.$this->keyword.'%');
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('new.livewire.search', [
            'estimates' => $estimates,
            'topRouteName' => 'new.top.type',
        ]);
    }
}
