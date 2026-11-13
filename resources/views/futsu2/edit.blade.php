@extends('layouts.app')

@section('main')
    @livewire('futsu2-form', ['estimate' => $estimate])

@endsection
