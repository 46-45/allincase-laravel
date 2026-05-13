{{-- <div class="table-responsive"> --}}
    <table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="support-visitorlist">
        <thead>
            <tr>
                <th>{{ lang('Name') }}</th>
                <th>{{ lang('Created Date') }}</th>
                <th>{{ lang('Country') }}</th>
                <th>{{ lang('Browser') }}</th>
                <th>{{ lang('Visited Source') }}</th>
                <th>{{ lang('Actions') }}</th>
            </tr>
        </thead>
        <tbody id="customers-table-body">
            @foreach ($customers as $customer)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="me-2 lh-1 profilediv">
                                <span class="avatar brround" id="new-chat-user-bg" style="background-color: {{ $customer->profile_bg_color }}">
                                    @if($customer->status == 'online')
                                        <span class="avatar-status bg-green"></span>
                                    @else
                                        <span class="avatar-status"></span>
                                    @endif
                                    <span class="new-chat-user-letter">{{ strtoupper(substr($customer->visitor_unique_id, 0, 1)) }}</span>
                                </span>
                            </div>
                            <div>
                                <div class="uniqueiddiv" data-id="{{ $customer->visitor_unique_id }}">
                                    <h5 class="d-inline">#{{ $customer->visitor_unique_id }}</h5>
                                </div>
                                <small class="fs-12 text-muted">
                                    <span class="font-weight-normal1">{{ Str::limit($customer->username, '40') }}</span>
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-success-light visit-time" data-initial-24time='{{ \Carbon\Carbon::parse($customer->created_at)->timezone(setting('default_timezone')) }}'>
                            {{ \Carbon\Carbon::parse($customer->created_at)->diffForHumans() }}
                        </span>
                    </td>
                    <td>
                        {{ $customer->country }}
                    </td>
                    <td>
                        {{ $customer->browser_info }}
                    </td>
                    <td>
                        {{ $customer->Login_url }}
                    </td>
                    <td>
                        <div class = "d-flex">
                            @if (Auth::user()->can('Customer Chat Process'))
                                <a href="{{ route('livechat.livevisitorengage', ['custId' => $customer->id]) }}" class="action-btns1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ lang('Start Chat') }}"><i class="fe fe-message-square text-primary"></i></a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
{{-- </div> --}}

{{ $customers->links('admin.viewticket.pagination') }}
<script type="text/javascript">
    $(function() {

        let prev = {!! json_encode(lang('Previous')) !!};
        let next = {!! json_encode(lang('Next')) !!};
        let nodata = {!! json_encode(lang('No data available in table')) !!};
        let noentries = {!! json_encode(lang('No entries to show')) !!};
        let showing = {!! json_encode(lang('showing page')) !!};
        let ofval = {!! json_encode(lang('of')) !!};
        let maxRecordfilter = {!! json_encode(lang('- filtered from ')) !!};
        let maxRecords = {!! json_encode(lang('records')) !!};
        let entries = {!! json_encode(lang('entries')) !!};
        let show = {!! json_encode(lang('Show')) !!};
        let search = {!! json_encode(lang('Search')) !!};
        let currentpagenumber = {!! json_encode($customers->currentPage()) !!};
        let lastpagenumber = {!! json_encode($customers->lastPage()) !!};

        $('#support-visitorlist').dataTable({
            language: {
                searchPlaceholder: search,
                scrollX: "100%",
                sSearch: '',
                paginate: {
                    previous: prev,
                    next: next
                },
                emptyTable: nodata,
                infoFiltered: `${maxRecordfilter} _MAX_ ${maxRecords}`,
                info: `${showing} ${currentpagenumber} ${ofval} ${lastpagenumber}`,
                infoEmpty: noentries,
                lengthMenu: `${show} _MENU_ ${entries} `,
            },
            order: [],
            columnDefs: [{
                "orderable": false,
                "targets": [0, 1, 3]
            }],
        });

        $('.form-select').select2({
            minimumResultsForSearch: Infinity,
            width: '100%'
        });

        let end = @json($perPage);
        $('.form-select').val(end).trigger('change');

        $('.form-select').on('select2:select', function(e) {
            var selectedData = e.params.data;

            $.ajax({
                url: location.origin + location.pathname +
                    `?page=1&per_page=${selectedData.text}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('.fetchedtabledata').html(data.rendereddata);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                }
            });
        });

        $('.paginationDatafetch').on('click', function() {
            var selectedpage = $(this).data('id');

            $.ajax({
                url: location.origin + location.pathname + `?page=${selectedpage}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('.fetchedtabledata').html(data.rendereddata);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                }
            });
        });


        let paginationexists = @json($customers->hasPages());
        if (paginationexists) {
            document.querySelector('.dataTables_wrapper .dataTables_paginate').style.display = 'none';
        } else {
            document.querySelector('.dataTables_wrapper .dataTables_paginate').style.display = 'block';
        }

        function initializeTooltips() {
            var tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltipElements.forEach(function(element) {
                new bootstrap.Tooltip(element);
            });
        }
        initializeTooltips();
    })
</script>
