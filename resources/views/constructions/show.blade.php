<!-- resources/views/constructions/show.blade.php -->

@extends('layouts.app')

@section('title', __('messages.view_construction'))

@section('content')
    <section class="content-header">
        <h1>@lang('messages.view_construction')</h1>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ $construction->name }}</h3>
            </div>
            <div class="box-body">
                <p><strong>@lang('lang_v1.name'):</strong> {{ $construction->name }}</p>
                <p><strong>@lang('lang_v1.description'):</strong> {{ $construction->description }}</p>
                <p><strong>@lang('lang_v1.start_date'):</strong> {{ $construction->start_date }}</p>
                <p><strong>@lang('lang_v1.end_date'):</strong> {{ $construction->end_date }}</p>
                <p><strong>@lang('lang_v1.budget'):</strong> {{ number_format($construction->budget, 2) }}</p>
            </div>
            <div class="box-footer">
                <a href="{{ action([\App\Http\Controllers\ConstructionController::class, 'edit'], [$construction->id]) }}"
                    class="btn btn-primary">@lang('messages.edit')</a>
                <a href="{{ url()->previous() }}" class="btn btn-default">@lang('messages.back')</a>
            </div>
        </div>
    </section>
@endsection
