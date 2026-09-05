@extends('layouts.app')

@section('title', 'My Report Card')

@section('content')
    <x-report-card-transcript :student="$student ?? null" />
@endsection
