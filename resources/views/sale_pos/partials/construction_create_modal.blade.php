<div id="createConstructionModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">@lang('construction.add_new_construction')</h4>
            </div>
            <div class="modal-body">
                {!! Form::open([
                    'url' => '/constructions',
                    'method' => 'post',
                    'id' => 'create_construction_form',
                ]) !!}
                <div class="form-group">
                    {!! Form::label('name', __('construction.name') . ':*') !!}
                    {!! Form::text('name', null, [
                        'class' => 'form-control',
                        'required',
                        'placeholder' => __('construction.name'),
                        'id' => 'construction_name',
                    ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('description', __('construction.description') . ':') !!}
                    {!! Form::textarea('description', null, [
                        'class' => 'form-control',
                        'placeholder' => __('construction.description'),
                        'rows' => 3,
                        'id' => 'construction_description',
                    ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('start_date', __('construction.start_date') . ':') !!}
                    {!! Form::date('start_date', null, [
                        'class' => 'form-control',
                        'placeholder' => __('construction.start_date'),
                        'id' => 'construction_start_date',
                    ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('end_date', __('construction.end_date') . ':') !!}
                    {!! Form::date('end_date', null, [
                        'class' => 'form-control',
                        'placeholder' => __('construction.end_date'),
                        'id' => 'construction_end_date',
                    ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('budget', __('construction.budget') . ':') !!}
                    {!! Form::number('budget', null, [
                        'class' => 'form-control',
                        'placeholder' => __('construction.budget'),
                        'id' => 'construction_budget',
                    ]) !!}
                </div>
                <div class="modal-footer">
                    <button type="button" id="saveConstructionButton"
                        class="btn btn-primary">@lang('messages.save')</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
