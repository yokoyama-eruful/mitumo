@extends('layouts.app')

@section('main')
    @livewire('estimate-form', ['estimate' => $estimate])

@endsection
