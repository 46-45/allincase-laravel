<table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="support-customerlist">
    <thead>
        <tr>
            <th width="10">{{ lang('Sl.No') }}</th>
            @can('Customers Delete')
                <th width="10">
                    <input type="checkbox" id="customCheckAll" class="form-check-input">
                    <label for="customCheckAll"></label>
                </th>
            @endcan
            @cannot('Customers Delete')
                <th width="10">
                    <input type="checkbox" id="customCheckAll" class="form-check-input" disabled>
                    <label for="customCheckAll"></label>
                </th>
            @endcannot

            <th>{{ lang('Name') }}</th>
            <th>{{ lang('Created Date') }}</th>
            <th>{{ lang('Country') }}</th>
            <th>{{ lang('Browser') }}</th>
            <th>{{ lang('Login Source') }}</th>
            <th>{{ lang('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp
        @foreach ($customers as $customer)
            <tr>
                <td>{{ $i++ }}</td>
                <td>
                    @if (Auth::user()->can('Customers Delete'))
                        <input type="checkbox" name="customer_checkbox[]" class="checkall form-check-input"
                            value="{{ encrypt($customer->id) }}" />
                    @else
                        <input type="checkbox" name="customer_checkbox[]" class="checkall form-check-input"
                            value="{{ encrypt($customer->id) }}" disabled />
                    @endif
                </td>
                <td>
                    <div>
                        <h5 class="d-inline">{{ Str::limit($customer->username, '40') }}</h5>
                        @if ($customer->banstatus == 'banned')
                            <span class="badge badge-danger-light"><i class="fa fa-exclamation-triangle text-danger"></i>{{ __('Banned') }}</span>
                        @endif
                    </div>
                    <small class="fs-12 text-muted">
                        <span class="font-weight-normal1">{{ Str::limit($customer->email, '40') }}</span>
                    </small>
                </td>
                <td>
                    <span class="badge badge-success-light">
                        {{ \Carbon\Carbon::parse($customer->created_at)->format(setting('date_format')) }}
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
                            @php
                                $custchatsexist = \App\Models\LiveChatConversations::where('unique_id',$customer->cust_unique_id)->first();
                                if($customer->status == 'solved'){
                                    $routeurl = route('admin.solvedChats');
                                    $statuscust = 'solved';
                                }elseif($customer->engage_conversation != null){
                                    $routeurl = route('admin.myOpenedChats');
                                    $statuscust = 'myopened';
                                }else{
                                    $routeurl = route('admin.livechat');
                                    $statuscust = 'newchat';
                                }
                            @endphp

                            @if($custchatsexist != null)
                                <a href="javascript:void(0)" class="action-btns1 localstorageadding" data-id="{{ $customer->id }}" data-url="{{ $routeurl }}" data-status="{{ $statuscust }}"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ lang('View chat') }}">
                                    <i class="fe fe-message-square text-primary"></i>
                                </a>
                            @endif
                        @endif

                        @if (Auth::user()->can('Customers Delete'))
                            <a href="javascript:void(0)" class="action-btns1" data-id="{{ encrypt($customer->id) }}"
                                id="show-delete" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ lang('Delete') }}">
                                <i class="feather feather-trash-2 text-danger"></i>
                            </a>
                        @else
                            ~
                        @endif

                        @php
                            $ticketCount = \App\Models\Ticket\Ticket::where('cust_id', $customer->id)->count();
                        @endphp

                        @if ($ticketCount > 0)
                            <a href="{{ route('admin.customer.tickethistory', encrypt($customer->id)) }}"
                                class="action-btns1" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ lang('Tickets History') }}">
                                <i class="feather-rotate-ccw text-primary"></i>
                            </a>
                        @endif

                        @if ($customer->banstatus == 'banned')
                            <a href="{{ route('admin.unbancustomer') }}?id={{$customer->id}}"
                                class="action-btns1" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ lang('Remvove from Banned list') }}">
                                <i class="feather feather-user-check text-primary"></i>
                            </a>
                        @endif

                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

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

        let supportCustomerList = $('#support-customerlist').dataTable({
            language: {
                searchPlaceholder: search,
                scrollX: "100%",
                sSearch: '',
                paginate: {
                    previous: prev,
                    next: next
                },
                searching: true,
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
            createdRow: function (row, data, index) {
                var serialNumber = (currentpagenumber - 1) * {{ $customers->perPage() }} + index + 1;
                $('td:eq(0)', row).html(serialNumber);
            }
        });

        let DebounceSearch;
        $('input[type="search"]').on('keyup', function(event) {
            event.preventDefault();
            clearTimeout(DebounceSearch);

            DebounceSearch = setTimeout(() => {
                let searchValue = $(this).val();
                var selectedOption = $('.form-select').val();
                $('.sprukoloader-img').fadeIn();
                $.ajax({
                    url: "{{ route('admin.customer') }}",
                    method: 'GET',
                    data: {
                        search: searchValue,
                        page: 1,
                        per_page: selectedOption,
                    },
                    success: function(response) {
                        $('.sprukoloader-img').fadeOut();
                        $('.fetchedtabledata').html(response.rendereddata);
                        setTimeout(() => {
                            document.querySelector('input[type="search"]').value = searchValue;
                            document.querySelector('input[type="search"]').focus();
                        }, 100);
                    },
                    error: function(xhr, status, error) {
                        $('.sprukoloader-img').fadeOut();
                        console.error('AJAX Error:', error);
                    }
                });

            }, 500);
        });

        $('.form-select').select2({
            minimumResultsForSearch: Infinity,
            width: '100%'
        });

        let end = @json($perPage);
        $('.form-select').val(end).trigger('change');

        $('.form-select').on('select2:select', function(e) {
            var selectedData = e.params.data;
            var searchdatafetch = document.querySelector('input[type="search"]').value

            $.ajax({
                url: location.origin + location.pathname + `?page=1&per_page=${selectedData.text}&search=${searchdatafetch}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('.fetchedtabledata').html(data.rendereddata);
                    setTimeout(() => {
                        document.querySelector('input[type="search"]').value = searchdatafetch;
                        document.querySelector('input[type="search"]').focus();
                    }, 100);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                }
            });
        });

        $('.paginationDatafetch').on('click', function() {
            var selectedpage = $(this).data('id');
            var selectedOption = $('.form-select').val();
            var searchdatafetch = document.querySelector('input[type="search"]').value

            $.ajax({
                url: location.origin + location.pathname + `?page=${selectedpage}&per_page=${selectedOption}&search=${searchdatafetch}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('.fetchedtabledata').html(data.rendereddata);
                    setTimeout(() => {
                        document.querySelector('input[type="search"]').value = searchdatafetch;
                        document.querySelector('input[type="search"]').focus();
                    }, 100);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                }
            });
        });

        $('.localstorageadding').on('click', function() {
            var id = $(this).data('id');
            var url = $(this).data('url');
            var status = $(this).data('status');

            if(status == 'solved'){
                localStorage.setItem("livechatSolvedCustomer",id);
            }else if(status == 'myopened'){
                localStorage.setItem("livechatMyopenedCustomer",id);
            }else{
                localStorage.setItem("livechatCustomer",id);
            }

            location.href = `${url}`;
        });


        let paginationexists = @json($customers->hasPages());
        if (paginationexists) {
            document.querySelector('.dataTables_wrapper .dataTables_paginate') ? document.querySelector('.dataTables_wrapper .dataTables_paginate').style.display = 'none' : null;
        } else {
            document.querySelector('.dataTables_wrapper .dataTables_paginate') ? document.querySelector('.dataTables_wrapper .dataTables_paginate').style.display = 'block' : null;
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
