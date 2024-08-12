<div id="payoutModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">@lang('messages.construction_list')</h4>
            </div>

            <form id="payoutForm" method="POST">
                @csrf
                <input type="hidden" id="contact_id" name="contact_id">
                <div class="modal-body">
                    <div id="alertContainer"></div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="0"
                            required>
                    </div>
                    <div>
                        {!! Form::label('payment_method', __('messages.payment_method') . ':', ['class' => 'mr-2']) !!}
                        {!! Form::select('payment_method', $payment_types, null, [
                            'class' => 'form-control flex-grow-1',
                            'id' => 'payment_method',
                        ]) !!}
                    </div>
                    <div class="mb-3" id="bank_account_field" style="display: none;">
                        <label for="bank_account_number" class="form-label">Bank Account Number</label>
                        <input type="text" class="form-control" id="bank_account_number" name="bank_account_number"
                            placeholder="Enter bank account number">
                    </div>
                    {{-- <div class="mb-3" id="card_field" style="display: none;" data-type="card">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('card_number', __('lang_v1.card_no')) !!}
                                {!! Form::text('card_number', $payment_line['card_number'], [
                                    'class' => 'form-control',
                                    'placeholder' => __('lang_v1.card_no'),
                                    'id' => 'card_number',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('card_holder_name', __('lang_v1.card_holder_name')) !!}
                                {!! Form::text('card_holder_name', $payment_line['card_holder_name'], [
                                    'class' => 'form-control',
                                    'placeholder' => __('lang_v1.card_holder_name'),
                                    'id' => 'card_holder_name',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('card_transaction_number', __('lang_v1.card_transaction_no')) !!}
                                {!! Form::text('card_transaction_number', $payment_line['card_transaction_number'], [
                                    'class' => 'form-control',
                                    'placeholder' => __('lang_v1.card_transaction_no'),
                                    'id' => 'card_transaction_number',
                                ]) !!}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('card_type', __('lang_v1.card_type')) !!}
                                {!! Form::select(
                                    'card_type',
                                    ['credit' => 'Credit Card', 'debit' => 'Debit Card', 'visa' => 'Visa', 'master' => 'MasterCard'],
                                    $payment_line['card_type'],
                                    ['class' => 'form-control', 'id' => 'card_type'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('card_month', __('lang_v1.month')) !!}
                                {!! Form::text('card_month', $payment_line['card_month'], [
                                    'class' => 'form-control',
                                    'placeholder' => __('lang_v1.month'),
                                    'id' => 'card_month',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('card_year', __('lang_v1.year')) !!}
                                {!! Form::text('card_year', $payment_line['card_year'], [
                                    'class' => 'form-control',
                                    'placeholder' => __('lang_v1.year'),
                                    'id' => 'card_year',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('card_security', __('lang_v1.security_code')) !!}
                                {!! Form::text('card_security', $payment_line['card_security'], [
                                    'class' => 'form-control',
                                    'placeholder' => __('lang_v1.security_code'),
                                    'id' => 'card_security',
                                ]) !!}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmPayout" class="btn btn-primary">Confirm Payout</button>
                </div>
            </form>
        </div>
    </div>
</div>
