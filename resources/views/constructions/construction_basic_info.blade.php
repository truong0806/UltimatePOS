<!-- <strong>{{ $contact->name }}</strong><br><br> -->
<h3 class="profile-username">
    <i class="fa-solid fa-traffic-cone fa-2xs"></i>
    {{ $construction->name }}
    <br>
    <small class="text-muted">
        {{ $construction->description }}<br>
    </small>
</h3><br>
<strong><i class="fa-regular fa-user fa-3xs"></i> @lang('construction.customer')</strong>
<p class="text-muted">
    {{ $construction->customer_name . ' - ' }}<a data-href="" target="_blank"
        href="/UltimatePOS/public/contacts/{{ $construction->contact_id }}">{{ $construction->customer_code }}</a>
    <br>
</p>
<strong><i class="fa-regular fa-user fa-3xs"></i> @lang('construction.introducer')</strong>
<p class="text-muted">
    {{ $construction->introducer_name . ' - ' }}<a data-href="" target="_blank"
        href="/UltimatePOS/public/contacts/{{ $construction->introducer_id }}">{{ $construction->introducer_code }}</a>
    <br>
</p>
<strong><i class="fa-regular fa-calendar-days fa-3xs"></i> @lang('construction.start_date')</strong>
<p class="text-muted">
    {{ $construction->start_date }}<br>
</p>
<strong><i class="fa-regular fa-calendar-days fa-3xs"></i> @lang('construction.end_date')</strong>
<p class="text-muted">
    {{ $construction->end_date . ' - ' }} <br>
</p>

@if ($contact->landline)
    <strong><i class="fa fa-phone margin-r-5"></i> @lang('contact.landline')</strong>
    <p class="text-muted">
        {{ $contact->landline }}
    </p>
@endif
@if ($contact->alternate_number)
    <strong><i class="fa fa-phone margin-r-5"></i> @lang('contact.alternate_contact_number')</strong>
    <p class="text-muted">
        {{ $contact->alternate_number }}
    </p>
@endif
@if ($contact->dob)
    <strong><i class="fa fa-calendar margin-r-5"></i> @lang('lang_v1.dob')</strong>
    <p class="text-muted">
        {{ @format_date($contact->dob) }}
    </p>
@endif
