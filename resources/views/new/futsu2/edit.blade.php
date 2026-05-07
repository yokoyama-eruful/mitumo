@extends('new.layouts.app')

@section('title', 'mitumo Futsu2')

@section('main')
    <section class="mb-5 flex items-end justify-between gap-4">
        <div>
            <p class="new-page-kicker new-page-kicker-futsu2">New Estimate</p>
            <h2 class="new-page-title">普通火災共済II</h2>
        </div>
        <a href="{{ route('new.top.type', ['type' => 'futsu2']) }}" class="new-ghost-button">
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

    @livewire('new.futsu2-form', ['estimate' => $estimate])
@endsection
