@extends('layouts.app')

@section('main')
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-2 py-1 rounded relative mt-6" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @livewire('futsu2-form', ['estimate' => $estimate])

@endsection
