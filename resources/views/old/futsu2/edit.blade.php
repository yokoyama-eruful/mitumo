@extends('layouts.app')

@section('main')
    <div class="text-center font-bold text-lg">普通火災共済Ⅱ見積書の作成・編集</div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-2 py-1 rounded relative mt-6" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @livewire('old.futsu2-form', ['estimate' => $estimate])

@endsection
