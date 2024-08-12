@if (!empty($activities_commission))
    <table class="table table-condensed">
        <tr>
            <th>@lang('lang_v1.date')</th>
            <th>@lang('messages.action')</th>
            <th>@lang('lang_v1.invoice_id')</th>
            <th>@lang('lang_v1.change')</th>
            <th>@lang('lang_v1.order_from_contact')</th>
        </tr>
        @forelse($activities_commission as $activity)
            <tr>
                <td>{{ @format_datetime($activity->created_at) }}</td>
                <td>
                    {{ __('lang_v1.' . $activity->description) }}
                </td>


                <td>
                    {{ $activity->getExtraProperty('invoice_id') ?? '' }}
                </td>
                <td>
                    @php
                        $change = $activity->change;
                    @endphp
                    <span style="color: {{ $change < 0 ? 'red' : 'green' }}">
                        {{ $activity->formatted_change }}
                    </span>
                </td>
                <td>
                    @if ($activity->getExtraProperty('contact_id'))
                        {{ $activity->getExtraProperty('contact_id') }}
                    @endif
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">
                    @lang('purchase.no_records_found')
                </td>
            </tr>
        @endforelse
    </table>
@endif
