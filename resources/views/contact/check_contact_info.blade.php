@extends('layouts.home')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Check Contact Information</h1>

        <!-- Display error messages -->
        <div id="error-message" class="alert alert-danger" style="display: none;"></div>

        <!-- Form to input Contact ID -->
        <form id="check-contact-form" class="mb-4">
            @csrf
            <div class="form-group">
                <label for="contact_id">Enter Contact ID:</label>
                <input type="text" name="contact_id" id="contact_id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Check Contact</button>
        </form>

        <!-- Display contact information -->
        <div id="contact-info" class="card" style="display: none;">
            <div class="card-body">
                <h2 class="card-title">Contact Information</h2>
                <ul class="list-group list-group-flush text-black">
                    <li class="list-group-item">Full Name: <span id="contact-full-name"></span></li>
                    <li class="list-group-item">Email: <span id="contact-email"></span></li>
                    <li class="list-group-item">Phone: <span id="contact-phone"></span></li>
                    <li class="list-group-item">Balance: <span id="contact-balance"></span></li>
                    <li class="list-group-item">Shipping Address: <span id="contact-shipping-address"></span></li>
                    <li class="list-group-item">@lang('lang_v1.contact_custom_field1'): <span id="contact-custom-field1"></span></li>
                    <li class="list-group-item">@lang('lang_v1.contact_custom_field2'): <span id="contact-custom-field2"></span></li>
                    <li class="list-group-item">@lang('lang_v1.contact_custom_field4'): <span id="contact-custom-field4"></span></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Include jQuery (if not already included in your project) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JavaScript to handle the form submission and display results -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#check-contact-form').on('submit', function(e) {
                e.preventDefault();

                var contactId = $('#contact_id').val();

                $.ajax({
                    url: '{{ route('checkContact') }}',
                    type: 'GET',
                    data: {
                        contact_id: contactId
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#contact-full-name').text(response.data.full_name || 'N/A');
                            $('#contact-email').text(response.data.email || 'N/A');
                            $('#contact-phone').text(response.data.phone || 'N/A');
                            $('#contact-commission-percentage').text(response.data
                                .commission_percentage || 'N/A');
                            $('#contact-balance').text(response.data.balance || 'N/A');
                            $('#contact-shipping-address').text(response.data
                                .shipping_address || 'N/A');
                            $('#contact-custom-field1').text(response.data.custom_field1 ||
                                'N/A');
                            $('#contact-custom-field2').text(response.data.custom_field2 ||
                                'N/A');
                            $('#contact-custom-field4').text(response.data.custom_field4 ||
                                'N/A');
                            $('#contact-info').show();
                            $('#error-message').hide();
                        } else {
                            $('#error-message').text(response.message).show();
                            $('#contact-info').hide();
                        }
                    },
                    error: function() {
                        $('#error-message').text(
                            'An error occurred while fetching contact information.').show();
                        $('#contact-info').hide();
                    }
                });
            });
        });
    </script>
@endsection
