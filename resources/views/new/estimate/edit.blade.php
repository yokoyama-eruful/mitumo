@extends('new.layouts.app')

@section('title', 'mitumo Estimate')

@section('main')
    <section class="mb-5 flex items-end justify-between gap-4">
        <div>
            <p class="new-page-kicker new-page-kicker-estimate">New Estimate</p>
            <h2 class="new-page-title">普通・総合火災共済</h2>
        </div>
        <a href="{{ route('new.top.type', ['type' => 'futsu']) }}" class="new-ghost-button">
            一覧へ戻る
        </a>
    </section>

    @if ($errors->any())
        <div class="new-danger-panel mb-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @livewire('new.estimate-form', ['estimate' => $estimate])
@endsection
