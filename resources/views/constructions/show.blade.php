@extends('layouts.app')
@section('title', __('contact.view_contact'))

@section('content')

    <!-- Main content -->
    <section class="content no-print">
        <div class="row no-print">
            <div class="col-md-4">
                <h3>@lang('construction.view_construction')</h3>
            </div>
            <div class="col-md-4 col-xs-12 mt-15 pull-right">
                {!! Form::select('construction_id', $construction_dropdown, $construction->id, [
                    'class' => 'form-control select2',
                    'id' => 'construction_id',
                ]) !!}
            </div>
        </div>
        <div class="hide print_table_part">
            <style type="text/css">
                .info_col {
                    width: 25%;
                    float: left;
                    padding-left: 10px;
                    padding-right: 10px;
                }
            </style>
            <div style="width: 100%;">
                <div class="info_col">
                    @include('constructions.construction_basic_info')
                </div>
                <div class="info_col">
                    @include('constructions.construction_more_info')
                </div>
                {{-- @if ($construction->type != 'customer')
                    <div class="info_col">
                        @include('constructions.construction_tax_info')
                    </div>
                @endif
                <div class="info_col">
                    @include('constructions.construction_payment_info')
                </div> --}}
            </div>
        </div>
        <input type="hidden" id="sell_list_filter_construction_id" value="{{ $construction->id }}">
        <input type="hidden" id="purchase_list_filter_supplier_id" value="{{ $construction->id }}">
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-body">
                        @include('constructions.partials.constructions_info_tab')
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs nav-justified">
                        <li
                            class="
                        @if (!empty($view_type) && $view_type == 'ledger') active
                        @else
                            '' @endif">
                            <a href="#ledger_tab" data-toggle="tab" aria-expanded="true"><i class="fas fa-scroll"
                                    aria-hidden="true"></i> @lang('lang_v1.ledger')</a>
                        </li>
                        <li
                            class="
                    @if (!empty($view_type) && $view_type == 'sales') active
                    @else
                        '' @endif">
                            <a href="#sales_tab" data-toggle="tab" aria-expanded="true"><i class="fas fa-arrow-circle-up"
                                    aria-hidden="true"></i> @lang('sale.sells')</a>
                        </li>

                        <li
                            class="
                            @if (!empty($view_type) && $view_type == 'payments') active
                            @else
                                '' @endif">
                            <a href="#payments_tab" data-toggle="tab" aria-expanded="true"><i class="fas fa-money-bill-alt"
                                    aria-hidden="true"></i> @lang('sale.payments')</a>
                        </li>

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane
                        @if (!empty($view_type) && $view_type == 'ledger') active
                        @else
                            '' @endif"
                            id="ledger_tab">
                            @include('constructions.partials.ledger_tab')
                        </div>

                        <div class="tab-pane 
                        @if (!empty($view_type) && $view_type == 'sales') active
                        @else
                            '' @endif"
                            id="sales_tab">

                            <div class="row">
                                <div class="col-md-12">
                                    @include('sale_pos.partials.sales_table')
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane 
                        @if (!empty($view_type) && $view_type == 'payments') active
                        @else
                            '' @endif"
                            id="payments_tab">
                            <div id="contact_payments_div" style="height: 500px;overflow-y: scroll;"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
    <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade pay_contact_due_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade" id="edit_ledger_discount_modal" tabindex="-1" role="dialog"
        aria-labelledby="gridSystemModalLabel">
    </div>

@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#ledger_date_range').daterangepicker(
                dateRangeSettings,
                function(start, end) {
                    $('#ledger_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(
                        moment_date_format));
                }
            );
            $('#ledger_date_range, #ledger_location').change(function() {
                get_construction_ledger();
            });
            get_construction_ledger();



            // rp_log_table = $('#rp_log_table').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     aaSorting: [
            //         [0, 'desc']
            //     ],
            //     ajax: '/sells?customer_id={{ $construction->id }}&rewards_only=true',
            //     columns: [{
            //             data: 'transaction_date',
            //             name: 'transactions.transaction_date'
            //         },
            //         {
            //             data: 'invoice_no',
            //             name: 'transactions.invoice_no'
            //         },
            //         {
            //             data: 'rp_earned',
            //             name: 'transactions.rp_earned'
            //         },
            //         {
            //             data: 'rp_redeemed',
            //             name: 'transactions.rp_redeemed'
            //         },
            //     ]
            // });



            $('#construction_id').change(function() {
                if ($(this).val()) {
                    window.location = "{{ url('/constructions') }}/" + $(this).val();
                }
            });

            $('a[href="#sales_tab"]').on('shown.bs.tab', function(e) {
                sell_table.ajax.reload();
            });

            //Date picker
            $('#discount_date').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                ignoreReadonly: true,
            });

            $(document).on('submit', 'form#add_discount_form, form#edit_discount_form', function(e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success === true) {
                            $('div#add_discount_modal').modal('hide');
                            $('div#edit_ledger_discount_modal').modal('hide');
                            toastr.success(result.msg);
                            form[0].reset();
                            form.find('button[type="submit"]').removeAttr('disabled');

                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });

            $(document).on('click', 'button.delete_ledger_discount', function() {
                swal({
                    title: LANG.sure,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then(willDelete => {
                    if (willDelete) {
                        var href = $(this).data('href');
                        var data = $(this).serialize();

                        $.ajax({
                            method: 'DELETE',
                            url: href,
                            dataType: 'json',
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    }
                });
            });
        });

        function get_construction_ledger() {

            var start_date = '';
            var end_date = '';
            var transaction_types = $('input.transaction_types:checked').map(function(i, e) {
                return e.value
            }).toArray();
            var show_payments = $('input#show_payments').is(':checked');
            var location_id = $('#ledger_location').val();

            if ($('#ledger_date_range').val()) {
                start_date = $('#ledger_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                end_date = $('#ledger_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
            }

            var format = $('input[name="ledger_format"]:checked').val();
            var data = {
                start_date: start_date,
                transaction_types: transaction_types,
                show_payments: show_payments,
                end_date: end_date,
                format: format
            }
            $.ajax({
                url: '/constructions/ledger?construction_id={{ $construction->id }}',
                data: data,
                dataType: 'html',
                success: function(result) {
                    $('#construction_ledger_div')
                        .html(result);
                    __currency_convert_recursively($('#construction_ledger_div'));

                    $('#ledger_table').DataTable({
                        searching: false,
                        ordering: false,
                        paging: false,
                        dom: 't'
                    });
                },
            });
        }

        // $(document).on('shown.bs.modal', '#edit_ledger_discount_modal', function(e) {
        //     $('#edit_ledger_discount_modal').find('#edit_discount_date').datetimepicker({
        //         format: moment_date_format + ' ' + moment_time_format,
        //         ignoreReadonly: true,
        //     });
        // })





        $(document).one('shown.bs.tab', 'a[href="#payments_tab"]', function() {
            get_constraction_payments();
        })

        $(document).on('click', '#contact_payments_pagination a', function(e) {
            e.preventDefault();
            get_constraction_payments($(this).attr('href'));
        })

        function get_constraction_payments(url = null) {
            if (!url) {
                url =
                    "{{ action([\App\Http\Controllers\ConstructionController::class, 'getConstructionPayments'], [$construction->id]) }}";
            }
            $.ajax({
                url: url,
                dataType: 'html',
                success: function(result) {
                    $('#contact_payments_div').fadeOut(400, function() {
                        $('#contact_payments_div')
                            .html(result).fadeIn(400);
                    });
                },
            });
        }

        $(document).on('click', '#print_ledger_pdf', function() {
            var start_date = $('#ledger_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var end_date = $('#ledger_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');

            var format = $('input[name="ledger_format"]:checked').val();

            var location_id = $('#ledger_location').val();

            var url = $(this).data('href') + '&start_date=' + start_date + '&end_date=' + end_date + '&format=' +
                format + '&location_id=' + location_id;
            window.open(url);
        });

    </script>
    @include('sale_pos.partials.sale_table_javascript')
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#purchase_list_filter_date_range').daterangepicker(
                dateRangeSettings,
                function(start, end) {
                    $('#purchase_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end
                        .format(moment_date_format));
                    purchase_table.ajax.reload();
                }
            );
            $('#purchase_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
                $('#purchase_list_filter_date_range').val('');
                purchase_table.ajax.reload();
            });
        });
    </script>
@endsection
