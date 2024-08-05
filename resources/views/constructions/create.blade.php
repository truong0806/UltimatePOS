@extends('layouts.app')

@php
    $title = __('construction.add_construction');
@endphp

@section('title', $title)

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>{{ $title }}</h1>
    </section>
    <!-- Main content -->
    <section class="content no-print">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open([
                    'url' => action([\App\Http\Controllers\ConstructionController::class, 'createConstruction']),
                    'method' => 'post',
                    'id' => 'add_construction_form',
                ]) !!}

                <div class="box box-solid">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('name', __('construction.name') . ':*') !!}
                                    {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __('construction.name')]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('description', __('construction.description') . ':') !!}
                                    {!! Form::textarea('description', null, [
                                        'class' => 'form-control',
                                        'placeholder' => __('construction.description'),
                                        'rows' => 3,
                                    ]) !!}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('contact_id', __('construction.customer') . ':') !!}
                                    {!! Form::select('contact_id', $contacts, null, [
                                        'class' => 'form-control',
                                        'placeholder' => __('construction.select_introducer'),
                                    ]) !!}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('introducer_id', __('construction.introducer') . ':') !!}
                                    {!! Form::select('introducer_id', $contacts, null, [
                                        'class' => 'form-control',
                                        'placeholder' => __('construction.select_introducer'),
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('start_date', __('construction.start_date') . ':') !!}
                                    {!! Form::date('start_date', null, ['class' => 'form-control', 'placeholder' => __('construction.start_date')]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('end_date', __('construction.end_date') . ':') !!}
                                    {!! Form::date('end_date', null, ['class' => 'form-control', 'placeholder' => __('construction.end_date')]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('budget', __('construction.budget') . ':') !!}
                                    {!! Form::number('budget', null, ['class' => 'form-control', 'placeholder' => __('construction.budget')]) !!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection
