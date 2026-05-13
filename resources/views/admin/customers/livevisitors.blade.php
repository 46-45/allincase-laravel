@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAL Data table css -->
<link href="{{asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
<link href="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

<!-- INTERNAL Sweet-Alert css -->
<link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

<style>
    #hideAfterloading {
        position: absolute;
        top: 100px;
        left: 45%;
    }
</style>

@endsection

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
	<div class="page-leftheader">
		<h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Live Visitors', 'menu')}} : <span class="badge bg-success livechatvisitorscount" data-id="{{ $livevisitorscount }}">{{ $livevisitorscount }}</span></span></h4>
	</div>
    <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Overall Visitors', 'menu')}} : <span class="badge bg-success overallvisitorscount" data-id="{{ $overallvisitorscount }}">{{ $overallvisitorscount }}</span></span></h4>
</div>
<!--End Page header-->

<!-- Customer List -->
<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card ">
		<div class="card-header border-0 d-md-max-block">
			<h4 class="card-title">{{lang('Live Visitors List')}}</h4>
		</div>
		<div class="card-body overflow-scroll custom-show-data" style="min-height: 250px;">
			<div class="">

                <div class="fetchedtabledata">
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
                        <div id="hideAfterloading"><img src="{{ asset('build/assets/images/loader.svg') }}" alt="">
                            <p>{{lang('loading.. Please Wait')}}</p>
                        </div>
                    </table>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- End Customer List -->
@endsection

@section('scripts')


<!-- INTERNAL Web Socket -->
<script domainName='{{url('')}}' wsPort="{{setting('liveChatPort')}}" src="{{ asset('build/assets/plugins/livechat/web-socket.js') }}"></script>

@vite(['resources/assets/js/select2.js'])

<!-- INTERNAL Vertical-scroll js-->
<script src="{{asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}?v=<?php echo time(); ?>"></script>

<!-- INTERNAL Data tables -->
<script src="{{asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/dataTables.responsive.min.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.min.js')}}?v=<?php echo time(); ?>"></script>

<!-- INTERNAL Index js-->
@vite(['resources/assets/js/support/support-sidemenu.js'])

<!-- INTERNAL Sweet-Alert js-->
<script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}?v=<?php echo time(); ?>"></script>


<script src="{{ asset('build/assets/plugins/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('build/assets/plugins/tzmoment/timezonedata.min.js') }}"></script>

<script type="text/javascript">
    "use strict";

    document.addEventListener("DOMContentLoaded", (event) => {

        let tableData = @json($customers);

        let dateFormat = @json(setting('date_format'));
        let custChatPerm = @json(Auth::user()->can('Customer Chat Process'));

        var dataTable;
        var tableDropDown;

        loadTable(tableData);

        function truncateTitle(title) {
            const maxLength = 40;
            return title.length > maxLength ? title.substring(0, maxLength) + '...' : title;
        }

        function dateTime(createdTime) {

            const jsDate = new Date(createdTime);

            let format = @json(setting('date_format'));
            switch (format) {
                case 'd M, Y':
                    return jsDate.toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                case 'm.d.y':
                    return jsDate.toLocaleDateString('en-US', {
                        day: '2-digit',
                        month: '2-digit',
                        year: '2-digit'
                    }).replace(/\//g, '.');
                case 'Y-m-d':
                    return jsDate.toISOString().split('T')[0];
                case 'd-m-Y':
                    return jsDate.toLocaleDateString('en-GB').replace(/\//g, '-');
                case 'd/m/Y':
                    return jsDate.toLocaleDateString('en-GB');
                case 'Y/m/d':
                    return jsDate.toISOString().split('T')[0].replace(/-/g, '/');
                default:
                    return jsDate.toLocaleDateString(); // Default format
            }

        }

        function formatTime(inputTime) {
            const initialDate = new Date(inputTime);
            const now = new Date();

            const diffInMinutes = Math.floor((now - initialDate) / 60000);
            let timeText;

            if (diffInMinutes < 1) {
                timeText = 'Just now';
            } else if (diffInMinutes < 60) {
                timeText = `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;
            } else {
                const diffInHours = Math.floor(diffInMinutes / 60);
                if (diffInHours < 24) {
                    timeText = `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;
                } else {
                    const diffInDays = Math.floor(diffInHours / 24);
                    timeText = `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;
                }
            }

            return timeText;
        }

        function loadTable(tableData) {
            document.getElementById('hideAfterloading')?.classList.remove('d-none');
            document.getElementById('theadShow')?.classList.add('d-none');
            if (tableDropDown)
                tableDropDown.destroy();

            if (dataTable)
                dataTable.destroy();

            if (document.getElementById('tableBody'))
                document.getElementById('tableBody').remove();

            let createTbody = document.createElement('tbody');
            createTbody.setAttribute('id', "tableBody");
            let rows = tableData.map((data, index) => {
                var visitorengageRouteUrl = `{{ route('livechat.livevisitorengage') }}?custId=${data.id}`;

                return `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-2 lh-1 profilediv">
                                    <span class="avatar brround" id="new-chat-user-bg" style="background-color: ${data.profile_bg_color}">
                                        ${data.status == 'online' ? `<span class="avatar-status bg-green"></span>` : `<span class="avatar-status"></span>`}
                                        <span class="new-chat-user-letter">${data.visitor_unique_id.charAt(0).toUpperCase()}</span>
                                    </span>
                                </div>
                                <div>
                                    <div class="uniqueiddiv" data-id="${data.visitor_unique_id}">
                                        <h5 class="d-inline">#${data.visitor_unique_id}</h5>
                                    </div>
                                    <small class="fs-12 text-muted">
                                        <span class="font-weight-normal1">${truncateTitle(data.username)}</span>
                                    </small>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="badge badge-success-light visit-time" data-initial-24time='${data.created_at}'>
                                ${formatTime(data.created_at)}
                            </span>
                        </td>

                        <td>${data.country}</td>

                        <td>${data.browser_info}</td>

                        <td>${data.Login_url}</td>

                        <td>
                            <div class = "d-flex">
                                ${custChatPerm ? `<a href="${visitorengageRouteUrl}" target="_blank" class="action-btns1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ lang('Start Chat') }}"><i class="fe fe-message-square text-primary"></i></a>` : ''}
                            </div>

                        </td>

                    </tr>
                `;
            });

            if (tableData.length == 0)
                createTbody.innerHTML = ` `;
            else
                createTbody.innerHTML = rows.join('');


            // Append the tbody to your table
            // Replace 'your-table-id' with the actual ID of your table element
            document.getElementById('support-visitorlist').appendChild(createTbody);

            document.getElementById('hideAfterloading')?.classList.add('d-none');
            document.getElementById('theadShow')?.classList.remove('d-none');

            // var myTable = document.querySelector("#support-category");

            $(document).ready(function() {

                var SITEURL = '{{ url('') }}';

                // Csrf field
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

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
                        info: `${showing} _PAGE_ ${ofval} _PAGES_`,
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

                $('.form-select').on('focus', function() {
                    $(this).attr('aria-hidden', 'false');
                });
            });


            initializeTooltips();

            function initializeTooltips() {
                var tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipElements.forEach(function(element) {
                    new bootstrap.Tooltip(element);
                });
            }


        }

        $(function() {
            (function($)  {
                // Variables
                var SITEURL = '{{url('')}}';
            })(jQuery);
        });
    });

</script>
<script type="text/javascript">

    // Live Time Update
    document.addEventListener('DOMContentLoaded', () => {
        function updateTime() {
            const timeElements = document.querySelectorAll('.visit-time');

            timeElements.forEach(element => {
                const initialTime = element.getAttribute('data-initial-24time');
                const initialDate = new Date(initialTime);
                const now = new Date();

                const diffInMinutes = Math.floor((now - initialDate) / 60000);
                let timeText;

                if (diffInMinutes < 1) {
                    timeText = 'Just now';
                } else if (diffInMinutes < 60) {
                    timeText = `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;
                } else {
                    const diffInHours = Math.floor(diffInMinutes / 60);
                    if (diffInHours < 24) {
                        timeText = `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;
                    } else {
                        const diffInDays = Math.floor(diffInHours / 24);
                        timeText = `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;
                    }
                }

                element.textContent = timeText;
            });
        }

        // Update time every minute
        updateTime();
        setInterval(updateTime, 60000);
    });

    var defaultTimezone = "{{ setting('default_timezone') }}";

    function formatDateWithTimezone(date, format, timezone) {
        return moment.tz(date, timezone);
    }

    let livechatvisitorscount = document.querySelector('.livechatvisitorscount');

    Echo.channel('liveChat').listen('ChatMessageEvent',(socket)=>{
        if(socket.visitoruiqueid && !socket?.comments?.includes(`${JSON.parse(socket?.agentInfo)?.[0]?.name} joined the discussion at`)){
            document.querySelector(`.uniqueiddiv[data-id='${socket.visitoruiqueid}']`)?.closest('td')?.querySelector('.avatar-status')?.classList.remove("bg-green");
            if(parseInt(livechatvisitorscount.innerText) != 0){
                livechatvisitorscount.innerText = parseInt(livechatvisitorscount.innerText) - 1;
            }
            if(parseInt(livechatvisitorscount.getAttribute('data-id')) != 0){
                livechatvisitorscount.setAttribute('data-id', parseInt(livechatvisitorscount.getAttribute('data-id')) - 1);
            }
        }

        if(socket.visitoronlinestatus){
            document.querySelector(`.uniqueiddiv[data-id='${socket.visitoronlinestatus.visitor_unique_id}']`)?.closest('td')?.querySelector('.avatar-status')?.classList.add("bg-green");
            livechatvisitorscount.innerText = parseInt(livechatvisitorscount.innerText) + 1;
            livechatvisitorscount.setAttribute('data-id', parseInt(livechatvisitorscount.getAttribute('data-id')) + 1);
        }

        if(socket?.comments && document.querySelector(`.uniqueiddiv[data-id='${socket?.visitoruiqueid}']`)?.getAttribute('data-id') && socket?.comments?.includes(`${JSON.parse(socket?.agentInfo)?.[0]?.name} joined the discussion at`)){
            document.querySelector(`.uniqueiddiv[data-id='${socket.visitoruiqueid}']`)?.closest('tr').remove();
            if(parseInt(livechatvisitorscount.innerText) != 0){
                livechatvisitorscount.innerText = parseInt(livechatvisitorscount.innerText) - 1;
            }
            if(parseInt(livechatvisitorscount.getAttribute('data-id')) != 0){
                livechatvisitorscount.setAttribute('data-id', parseInt(livechatvisitorscount.getAttribute('data-id')) - 1);
            }
        }

        if(socket.visitordata){
            let customer = socket.visitordata;
            livechatvisitorscount.innerText = parseInt(livechatvisitorscount.innerText) + 1;
            livechatvisitorscount.setAttribute('data-id', parseInt(livechatvisitorscount.getAttribute('data-id')) + 1);
            // Construct the route URL in JavaScript using a template literal
            const routeUrl = `{{ route('livechat.livevisitorengage', ['custId' => '__CUSTID__']) }}`.replace('__CUSTID__', encodeURIComponent(customer.id));

            const tableBody = document.getElementById('tableBody');
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <div class="me-2 lh-1">
                            <span class="avatar brround" id="new-chat-user-bg" style="background-color: ${customer.profile_bg_color}">
                                <span class="avatar-status bg-green"></span>
                                <span>${customer.visitor_unique_id.slice(0,1).toUpperCase()}</span>
                            </span>
                        </div>
                        <div>
                            <div class="uniqueiddiv" data-id="${customer.visitor_unique_id}">
                                <h5 class="d-inline">#${customer.visitor_unique_id}</h5>
                            </div>
                            <small class="fs-12 text-muted">
                                <span class="font-weight-normal1">${customer.username}</span>
                            </small>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge badge-success-light visit-time" data-initial-24time="${customer.created_at}">
                        just now
                    </span>
                </td>
                <td>${customer.country}</td>
                <td>${customer.browser_info}</td>
                <td>${customer.Login_url}</td>
                <td>
                    <div class="d-flex">
                        <a href="${routeUrl}" class="action-btns1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ lang('Start Chat') }}">
                            <i class="fe fe-message-square text-primary"></i>
                        </a>
                    </div>
                </td>
            `;

            tableBody.insertBefore(row, tableBody.firstChild);

        }

    })
</script>
@endsection


