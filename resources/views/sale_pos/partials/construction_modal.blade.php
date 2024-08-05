<div id="constructionModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">@lang('messages.construction_list')</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="d-flex align-items-center">
                        {!! Form::label('construction_id', __('messages.select_construction') . ':', ['class' => 'mr-2']) !!}
                        {!! Form::select('construction_id', [], null, [
                            'class' => 'form-control flex-grow-1',
                            'id' => 'constructionSelect',
                        ]) !!}
                        <button id="addNewConstructionButton" type="button" class="btn btn-primary ml-2"
                            data-toggle="modal" data-target="#createConstructionModal">
                            <i class="fa fa-plus"></i> @lang('construction.add_new_construction')
                        </button>
                    </div>
                </div>
                <div id="constructionDetails"></div>
            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('messages.ok')</button>

            </div>
        </div>
    </div>
</div>
