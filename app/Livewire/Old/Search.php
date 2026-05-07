<?php

declare(strict_types=1);

namespace App\Livewire\Old;

use App\Domains\Version;
use App\Models\Estimate;
use App\Models\Futsu2;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class Search extends Component
{
    use WithPagination;

    public string $type;

    #[Url(as: 'q')]
    public string $keyword = '';

    public function updatingKeyword($value)
    {
        $this->resetPage();
    }

    public function render()
    {
        $keyword = $this->keyword;
        $estimateModel = $this->type == 'futsu' ? Estimate::class : Futsu2::class;
        if ($keyword) {
            $estimates =
               $estimateModel::where('user_id', Auth::id())
                   ->where('contractee', 'LIKE', '%' . $keyword . '%')
                   ->orderByDesc('id')
                   ->paginate(10);
        } else {
            $estimates =
             $estimateModel::where('user_id', Auth::id())
                 ->orderByDesc('id')
                 ->paginate(10);
        }

        return view('old.livewire.search', [
            'estimates' => $estimates,
        ]);
    }

    #[Computed]
    public function versionDomain()
    {
        return new Version();
    }
}
