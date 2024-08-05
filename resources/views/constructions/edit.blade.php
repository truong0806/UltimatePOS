<!-- Modal Edit -->
<div class="modal fade" id="editConstructionModal" tabindex="-1" role="dialog" aria-labelledby="editConstructionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editConstructionModalLabel">@lang('messages.edit_construction')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editConstructionForm" action="" method="POST">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="name">@lang('lang_v1.name')</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">@lang('lang_v1.description')</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="start_date">@lang('lang_v1.start_date')</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">@lang('lang_v1.end_date')</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                    <div class="form-group">
                        <label for="budget">@lang('lang_v1.budget')</label>
                        <input type="number" step="0.01" class="form-control" id="budget" name="budget"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="customer">@lang('lang_v1.contact_name')</label>
                        <select id="customer" name="customer_id" class="form-control" required>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="introducer">@lang('lang_v1.introducer_name')</label>
                        <select id="introducer" name="introducer_id" class="form-control">
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.close')</button>
                <button type="button" id="saveChanges" class="btn btn-primary">@lang('messages.save_changes')</button>
            </div>
        </div>
    </div>
</div>
