@extends('layouts.app')

@section('title', __('lang_v1.all_constructions'))
<script>
    @if (session('success'))
        $(document).ready(function() {
            toastr.success("{{ session('success') }}");
        });
    @endif
</script>


@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header no-print">
        <h1>@lang('lang_v1.constructions')</h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_constructions')])
            @if (auth()->user()->can('create_construction'))
                @slot('tool')
                    <div class="box-tools">
                        <a class="btn btn-block btn-primary"
                            href="{{ action([\App\Http\Controllers\ConstructionController::class, 'showCreateForm']) }}">
                            <i class="fa fa-plus"></i> @lang('messages.add')
                        </a>
                    </div>
                @endslot
            @endif

            <table class="table table-bordered table-striped " id="construction_table">
                <thead>
                    <tr>
                        <th>@lang('messages.action')</th>
                        <th>@lang('lang_v1.name')</th>
                        <th>@lang('lang_v1.description')</th>
                        <th>@lang('lang_v1.contact_name')</th>
                        <th>@lang('lang_v1.introducer_name')</th>
                        <th>@lang('lang_v1.start_date')</th>
                        <th>@lang('lang_v1.end_date')</th>
                        <th>@lang('lang_v1.budget')</th>
                        <th>@lang('lang_v1.created')</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr class="bg-gray font-17 footer-total text-center">
                        <td colspan="5"><strong>@lang('lang_v1.total'):</strong></td>
                        <td class="footer_total_budget"></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <!-- Modal -->
            @include('constructions.view-modal')
            @include('constructions.edit')
        @endcomponent
    </section>

@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            var construction_table = $('#construction_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/constructions/data",
                    data: function(d) {},
                    error: function(xhr, error, thrown) {
                        console.log(xhr.responseText);
                    }
                },
                columns: [{
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'contact_name',
                        name: 'contact_name'
                    },
                    {
                        data: 'introducer_name',
                        name: 'introducer_name'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        render: function(data, type, row) {
                            if (data) {
                                const date = new Date(data);
                                return date.toLocaleDateString(
                                    'en-GB');
                            }
                            return '';
                        }
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                        render: function(data, type, row) {
                            if (data) {
                                const date = new Date(data);
                                return date.toLocaleDateString(
                                    'en-GB');
                            }
                            return '';
                        }
                    },
                    {
                        data: 'budget',
                        name: 'budget'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row) {
                            if (data) {
                                const date = new Date(data);
                                return date.toLocaleDateString(
                                    'en-GB');
                            }
                            return '';
                        }
                    }
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:eq(0)').addClass('text-center');
                }
            });

            $(document).on('click', '.delete-construction', function() {
                var url = $(this).attr('href');
                if (confirm('@lang('lang_v1.are_you_sure')')) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function(result) {
                            construction_table.ajax.reload();
                        },
                        error: function(xhr, error, thrown) {
                            console.log(xhr.responseText);
                        }
                    });
                }
                return false;
            });

            $(document).on('click', '.edit-btn', function(e) {
                e.preventDefault();

                var constructionId = $(this).data('id');

                if (!constructionId) {
                    console.error('Construction ID not found');
                    return;
                }

                $.ajax({
                    url: '/constructions/' + constructionId + '/edit',
                    method: 'GET',
                    success: function(data) {
                        console.log("🚀 ~ $ ~ data:", data)
                        console.log("🚀 ~ $ ~ data.customers:", data.customers)
                        $('#name').val(data.construction.name);
                        $('#description').val(data.construction.description || '');
                        $('#start_date').val(data.construction.start_date);
                        $('#end_date').val(data.construction.end_date);
                        $('#budget').val(data.construction.budget);
                        var customerSelect = $('#customer');
                        customerSelect.empty();
                        if (data.customers && typeof data.customers === 'object') {
                            $.each(data.customers, function(key, value) {
                                if (value.name !== 'Walk-In Customer') {
                                    customerSelect.append(new Option(value.name, value
                                        .id));
                                }
                            });
                            customerSelect.val(data.construction.contact_id).change();
                        } else {
                            console.error('Invalid customer data:', data.customers);
                            customerSelect.append(new Option('No customers available', ''));
                        }

                        var introducerSelect = $('#introducer');
                        introducerSelect.empty();
                        if (data.introducers && typeof data.introducers === 'object') {
                            $.each(data.introducers, function(key, value) {
                                if (value.name !== 'Walk-In Customer') {
                                    introducerSelect.append(new Option(value.name, value
                                        .id));
                                }
                            });
                            introducerSelect.val(data.construction.introducer_id).change();
                        } else {
                            console.error('Invalid introducer data:', data.introducers);
                            introducerSelect.append(new Option('No introducers available', ''));
                        }
                        $('#editConstructionForm').data('id', constructionId);
                        $('#editConstructionModal').modal('show');
                    },
                    error: function() {
                        alert('Failed to fetch construction details.');
                    }
                });

            });

            $('#saveChanges').click(function() {
                var constructionId = $('#editConstructionForm').data('id');
                var formData = {
                    name: $('#name').val(),
                    description: $('#description').val(),
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                    budget: $('#budget').val(),
                    contact_id: $('#customer').val(),
                    introducer_id: $('#introducer').val(),
                    _method: 'PUT'
                };

                $.ajax({
                    url: '/constructions/' + constructionId,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#editConstructionModal').modal('hide');
                        construction_table.ajax.reload();
                        alert('Construction updated successfully');
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        alert('Failed to update construction');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.view-btn', function(event) {
                event.preventDefault();

                var constructionId = $(this).data('id');

                if (!constructionId) {
                    console.error('Construction ID not found');
                    return;
                }

                $.ajax({
                    url: '/constructions/' + constructionId,
                    method: 'GET',
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            var modalContent = '<h4>' + data.name + '</h4>';
                            modalContent += '<p><strong>Description:</strong> ' + (data
                                .description || 'N/A') + '</p>';
                            modalContent += '<p><strong>Start Date:</strong> ' + data
                                .start_date + '</p>';
                            modalContent += '<p><strong>End Date:</strong> ' + data.end_date +
                                '</p>';
                            modalContent += '<p><strong>Budget:</strong> ' + data.budget +
                                '</p>';
                            modalContent += '<p><strong>Customer:</strong> ' + data
                                .customer_name + '</p>';
                            modalContent += '<p><strong>Introducer:</strong> ' + data
                                .introducer_name + '</p>';


                            $('#constructionModal .modal-body').html(modalContent);

                            $('#modalTitle').text('Construction Details');

                            $('#constructionModal').modal('show');
                        }
                    },
                    error: function() {
                        alert('Failed to fetch construction details.');
                    }
                });
            });
        });
    </script>
@endsection
