@extends('layouts.adminmaster')

@section('styles')
    <!-- INTERNAL Data table css -->
    <link href="{{ asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/assets/plugins/datatable/buttonbootstrap.min.css') }}" rel="stylesheet" />

    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{ asset('build/assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet" />

    <link href="{{asset('build/assets/plugins/daterangepicker/daterangepicker.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
    <link href="{{asset('build/assets/plugins/daterangepicker/jsvectormap.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />


    <style>
        .uhelp-reply-badge {
            inset-inline-end: 14px;
            bottom: 10px;
            z-index: 1;
        }

        .pulse-badge {
            animation: pulse 1s linear infinite;
        }

        .pulse-badge.disabled {
            color: #b5c0df;
            animation: none;
        }

        @-webkit-keyframes pulse {
            0% {
                color: rgba(13, 205, 148, 0);
            }

            50% {
                color: rgba(13, 205, 148, 1);
            }

            100% {
                color: rgba(13, 205, 148, 0);
            }
        }

        @keyframes pulse {
            0% {
                -moz-color: rgba(13, 205, 148, 0);
                color: rgba(13, 205, 148, 0);
            }

            50% {
                -moz-color: rgba(13, 205, 148, 1);
                color: rgba(13, 205, 148, 1);
            }

            100% {
                -moz-color: rgba(13, 205, 148, 0);
                color: rgba(13, 205, 148, 0);
            }
        }
        #vector-map{
        height: 21.875rem;
        }
        @media (min-width: 768px) and (max-width: 811.98px) {
            .daterangepicker.select-month-range  {
                right: 294px !important;
            }
            .daterangepicker.select-year-range  {
                right: 140px !important;
            }
        }
        @media (min-width: 812px) {
            .daterangepicker.select-month-range  {
                right: 204px !important;
            }
        }

    </style>
@endsection

@section('content')


    <!--- Custom notification -->
    @php
        $mailnotify = auth()->user()->unreadNotifications()->where('data->status', 'mail')->get();

    @endphp
    @if ($mailnotify->isNotEmpty())
        <div class="alert alert-warning-light br-13 mt-6 align-items-center border-0 d-flex" role="alert">
            <div class="d-flex">
                <svg class="alt-notify me-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path fill="#eec466"
                        d="M19,20H5a3.00328,3.00328,0,0,1-3-3V7A3.00328,3.00328,0,0,1,5,4H19a3.00328,3.00328,0,0,1,3,3V17A3.00328,3.00328,0,0,1,19,20Z" />
                    <path fill="#e49e00"
                        d="M22,7a3.00328,3.00328,0,0,0-3-3H5A3.00328,3.00328,0,0,0,2,7V8.061l9.47852,5.79248a1.00149,1.00149,0,0,0,1.043,0L22,8.061Z" />
                </svg>
            </div>
            <ul class="notify vertical-scroll5 custom-ul ht-0 me-5">
                @if (auth()->user())
                    @forelse($mailnotify as $notification)
                        @if ($notification->data['status'] == 'mail')
                            <li class="item">
                                <p class="fs-13 mb-0">{{ $notification->data['mailsubject'] }}
                                    {{ Str::limit($notification->data['mailtext'], '400', '...') }} <a
                                        href="{{ route('admin.notiication.view', $notification->id) }}"
                                        class="ms-3 text-blue mark-as-read">{{ lang('Read more') }}</a></p>
                            </li>
                        @endif
                    @empty
                    @endforelse
                @endif
            </ul>
            <div class="d-flex ms-6 sprukocnotify">
                <button class="btn-close ms-2 mt-0 text-warning" data-bs-dismiss="alert" aria-hidden="true">×</button>
            </div>
        </div>
    @endif
    <!--- End Custom notification -->

    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{ lang('Dashboard') }}</span>
            </h4>
        </div>
        <div class="page-rightheader ms-md-auto">
            <div class="d-flex align-items-end flex-wrap my-auto end-content breadcrumb-end">
                <div class="d-flex breadcrumb-res align-items-center">
                    <div class="header-datepicker me-2">
                        <div class="input-group px-3 py-1 rounded">
                            <div class="input-group-text">
                                <i class="feather feather-calendar"></i>
                            </div>
                            <span
                                class="form-control fc-datepicker pb-0 pt-1 pe-0">{{ now(setting('default_timezone'))->format(setting('date_format')) }}</span>
                        </div>
                    </div>
                    <div class="header-datepicker picker2 me-0">
                        <div class="input-group px-3 py-1 rounded">
                            <div class="input-group-text">
                                <i class="feather feather-clock"></i>
                            </div><!-- input-group-text -->
                            <span id="tpBasic" placeholder="" class="form-control input-small pb-0 pt-1 pe-0">

                                {{ \Carbon\Carbon::now(setting('default_timezone'))->format(setting('time_format')) }}

                            </span>

                        </div>
                    </div><!-- wd-150 -->
                </div>
            </div>
        </div>
    </div>
    <!--End Page header-->

    <!--Dashboard Chats List-->
    @can('Customer Chat Process')
        <h6 class="mb-3 fw-semibold">{{ lang('Chats') }}</h6>
        <div class="row ">
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-sm-6">
                <div class="card">
                    <div class="card-body p-4">
                        <a href="{{ route('admin.livechat') }}">
                            <div class="d-flex">
                                <div class="icon2 bg-primary text-white my-auto me-3">
                                    <i class="fe fe-message-square"></i>
                                </div>
                                <div>
                                    <p class="fs-14 font-weight-semibold mb-1">{{ lang('New Chats') }} </p>
                                    <h5 class="mb-0">{{ $newchatcount }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-sm-6">
                <div class="card">
                    <div class="card-body p-4">
                        <a href="{{ route('admin.myOpenedChats') }}">
                            <div class="d-flex">
                                <div class="icon2 bg-secondary text-white my-auto me-3">
                                    <i class="fe fe-message-square"></i>
                                </div>
                                <div>
                                    <p class="fs-14 font-weight-semibold mb-1">{{ lang('My Opened Chats') }} </p>
                                    <h5 class="mb-0">{{ $myopenedcount }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-sm-6">
                <div class="card">
                    <div class="card-body p-4">
                        <a href="{{ route('admin.solvedChats') }}">
                            <div class="d-flex">
                                <div class="icon2 bg-success text-white my-auto me-3">
                                    <i class="fe fe-message-square"></i>
                                </div>
                                <div>
                                    <p class="fs-14 font-weight-semibold mb-1">{{ lang('Solved Chats') }} </p>
                                    <h5 class="mb-0">{{ $solvedcount }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <!--Dashboard Chats List-->
    <!--Dashboard Tickets List-->
    <h6 class="mb-3 fw-semibold">{{ lang('Tickets') }}</h6>
    <div class="row ">
        @can('All Tickets')
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-sm-6">
                <div class="card">
                    <div class="card-body p-4">
                        <a href="{{ route('admin.alltickets', ['ticketdata' => 'alltickets'])}}">
                            <div class="d-flex">
                                <div class="icon2 bg-primary text-white my-auto me-3">
                                    <i class="las la-ticket-alt"></i>
                                </div>
                                <div>
                                    <p class="fs-14 font-weight-semibold mb-1">{{ lang('All Tickets') }} </p>
                                    <h5 class="mb-0">{{ $allticketscount }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        @endcan
        @can('Unassigned Tickets')
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-sm-6">
                <div class="card">
                    <div class="card-body p-4">
                        <a href="{{ route('admin.unassignedtickets', ['ticketdata' => 'unassignedtickets'])}}">
                            <div class="d-flex">
                                <div class="icon2 bg-warning text-white my-auto me-3">
                                    <i class="las la-ticket-alt"></i>
                                </div>
                                <div>
                                    <p class="fs-14 font-weight-semibold mb-1">{{ lang('Unassigned Tickets') }} </p>
                                    <h5 class="mb-0">{{ $unassingedticketscount }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        @endcan
        @can('My Tickets')
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-sm-6">
                <div class="card">
                    <div class="card-body p-4">
                        <a href="{{ route('admin.mytickets', ['ticketdata' => 'mytickets'])}}">
                            <div class="d-flex">
                                <div class="icon2 bg-info text-white my-auto me-3">
                                    <i class="las la-ticket-alt"></i>
                                </div>
                                <div>
                                    <p class="fs-14 font-weight-semibold mb-1">{{ lang('My Tickets') }} </p>
                                    <h5 class="mb-0">{{ $myticketscount }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        @endcan
        @can('Solved')
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-sm-6">
                <div class="card">
                    <div class="card-body p-4">
                        <a href="{{ route('admin.myclosedtickets', ['ticketdata' => 'myclosedtickets'])}}">
                            <div class="d-flex">
                                <div class="icon2 bg-success text-white my-auto me-3">
                                    <i class="las la-ticket-alt"></i>
                                </div>
                                <div>
                                    <p class="fs-14 font-weight-semibold mb-1">{{ lang('My Solved Tickets') }} </p>
                                    <h5 class="mb-0">{{ $myclosedticketscount }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        @endcan
    </div>
    <!--Dashboard Tickets List-->


    <div class="card custom-card">
        <div class="card-header py-2 d-flex justify-content-between flex-wrap gap-2 border-0">
            <div class="card-title">
                {{ lang('Chats / Tickets') }}
            </div>
            <div class="page-rightheader">
                <div class="mb-0 d-flex gap-2 flex-wrap">
                    <a class="btn btn-white date-range-btn" href="javascript:void(0)" id="daterange-btn">
                        <svg class="header-icon2 me-3" x="1008" y="1248" viewBox="0 0 24 24" height="100%" width="100%" preserveAspectRatio="xMidYMid meet" focusable="false">
                                <path d="M5 8h14V6H5z" opacity=".3"/><path d="M7 11h2v2H7zm12-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-4 3h2v2h-2zm-4 0h2v2h-2z"/>
                            </svg> <span>{{ lang('Select Range') }}
                            <i class="fa fa-caret-down"></i></span>
                    </a>
                    <a class="btn btn-white date-range-btn" href="javascript:void(0)" id="daterange-btn2">
                        <svg class="header-icon2 me-3" x="1008" y="1248" viewBox="0 0 24 24" height="100%" width="100%" preserveAspectRatio="xMidYMid meet" focusable="false">
                                <path d="M5 8h14V6H5z" opacity=".3"/><path d="M7 11h2v2H7zm12-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-4 3h2v2h-2zm-4 0h2v2h-2z"/>
                            </svg> <span>{{ lang('Select Year') }}
                            <i class="fa fa-caret-down"></i></span>
                    </a>
                </div>
            </div>
        </div>
        <div id="chart"></div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header border-0">
                    <div class="card-title">{{ lang('Most Visited Users Countries Using Google Chart') }}</div>
                </div>
                <div id="regions_div" style="height: 500px;"></div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <!-- INTERNAL Vertical-scroll js-->
    <script src="{{ asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js') }}"></script>

    <!-- INTERNAL Data tables -->
    <script src="{{ asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/datatable/datatablebutton.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/datatable/buttonbootstrap.min.js') }}"></script>


    <!-- INTERNAL Index js-->
    @vite(['resources/assets/js/support/support-sidemenu.js'])
    @vite(['resources/assets/js/select2.js'])

    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{ asset('build/assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>

    <!-- INTERNAL Apexchart js-->
    <script src="{{ asset('build/assets/plugins/apexchart/apexcharts.js') }}"></script>

    <script src="{{ asset('build/assets/plugins/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/tzmoment/timezonedata.min.js') }}"></script>

    <script src="{{ asset('build/assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/daterangepicker/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/daterangepicker/world-merc.js') }}"></script>

    <script src="{{ asset('build/assets/plugins/daterangepicker/googlestaticchart.js') }}"></script>
    <script type="text/javascript">
      google.charts.load('current', {
        'packages':['geochart'],
      });
      google.charts.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap() {
        var data = google.visualization.arrayToDataTable([
          ['Country', 'Visitors'],
          @foreach ($countrriesdataCounts as $country => $count)
            ['{{ $country }}', {{ $count }}],
          @endforeach
        ]);

        var options = {};

        var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

        chart.draw(data, options);
      }
    </script>

    <script type="text/javascript">
        $(function() {
            "use strict";

            (function($) {

                var loginornotchecking = '{{ Auth::check() }}',
                    SITEURL = '{{ url('') }}',
                    defaultTimezone = "{{ setting('default_timezone') }}",
                    timeFormat = "{{ setting('time_format') }}";

                function formatDateWithTimezone(date, format, timezone) {
                    return moment.tz(date, timezone).format(format);
                }
                switch (timeFormat) {
                    case 'h:i A':
                        timeFormat = "hh:mm A";
                        break;
                    case 'h:i:s A':
                        timeFormat = "hh:mm:ss A";
                        break;
                    case 'H:i':
                        timeFormat = "HH:mm";
                        break;
                    case 'H:i:s':
                        timeFormat = "HH:mm:ss";
                        break;
                }
                var currentDate = new Date();
                var formattedDate = formatDateWithTimezone(currentDate, timeFormat, defaultTimezone);
                $('#tpBasic').html(formattedDate)
                setInterval(() => {
                    var currentDate = new Date();
                    var formattedDate = formatDateWithTimezone(currentDate, timeFormat, defaultTimezone);
                    $('#tpBasic').html(formattedDate)

                }, 1000);

                // csrf field
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // TICKET DELETE SCRIPT
                $('body').on('click', '#show-delete', function() {
                    var _id = $(this).data("id");
                    swal({
                            title: `{{ lang('Are you sure you want to continue?', 'alerts') }}`,
                            text: "{{ lang('This might erase your records permanently', 'alerts') }}",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                $.ajax({
                                    type: "get",
                                    url: SITEURL + "/admin/delete-ticket/" + _id,
                                    success: function(data) {
                                        toastr.success(data.success);
                                        location.reload();
                                    },
                                    error: function(data) {
                                        console.log('Error:', data);
                                    },
                                });
                            }
                        });

                });
                // TICKET DELETE SCRIPT END

                // when user click its get modal popup to assigned the ticket
                $('body').on('click', '#assigned', function() {
                    var assigned_id = $(this).data('id');
                    $('.select2_modalassign').select2({
                        dropdownParent: ".sprukosearch",
                        minimumResultsForSearch: '',
                        placeholder: "Search",
                        width: '100%'
                    });
                    $.get('admin/assigned/' + assigned_id, function(data) {
                        $('#AssignError').html('');
                        $('#assigned_id').val(data.assign_data.id);
                        $(".modal-title").text('{{ lang('Assign To Agent') }}');
                        $('#username').html(data.table_data);
                        if(data.assign_user_exist == 'no'){
                            $('#username').val([]).trigger('change')
                        }
                        $('#addassigned').modal('show');
                    });
                });

                // Assigned Submit button
                $('body').on('submit', '#assigned_form', function(e) {
                    e.preventDefault();
                    var actionType = $('#btnsave').val();
                    var fewSeconds = 2;
                    $('#btnsave').html('Sending ... <i class="fa fa-spinner fa-spin"></i>');
                    $('#btnsave').prop('disabled', true);
                    setTimeout(function() {
                        $('#btnsave').prop('disabled', false);
                    }, fewSeconds * 1000);
                    var formData = new FormData(this);
                    $.ajax({
                        type: 'POST',
                        url: SITEURL + "/admin/assigned/create",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,

                        success: (data) => {

                            $('#AssignError').html('');
                            $('#assigned_form').trigger("reset");
                            $('#addassigned').modal('hide');
                            $('#btnsave').html('{{ lang('Save Changes') }}');
                            $('#assigned').html('gfhffh');
                            location.reload();
                            toastr.success(data.success);
                        },
                        error: function(data) {
                            $('#AssignError').html('');
                            $('#AssignError').html("The assigned agent field is required");
                            // $('#AssignError').html(data.responseJSON.errors.assigned_user_id);
                            $('#btnsave').html('{{ lang('Save Changes') }}');
                        }
                    });
                });

                // Remove the assigned from the ticket
                $('body').on('click', '#btnremove', function() {
                    var asid = $(this).data("id");
                    swal({
                            title: `{{ lang('Are you sure you want to unassign this agent?', 'alerts') }}`,
                            text: "{{ lang('This agent may no longer exist for this ticket.', 'alerts') }}",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {

                                $.ajax({
                                    type: "get",
                                    url: SITEURL + "/admin/assigned/update/" + asid,
                                    success: function(data) {
                                        location.reload();
                                        toastr.success(data.success);

                                    },
                                    error: function(data) {
                                        console.log('Error:', data);
                                    }
                                });

                            }
                        });
                });

                //Mass Delete
                $('body').on('click', '#massdelete', function() {

                    var id = [];
                    $('.checkall:checked').each(function() {
                        id.push($(this).val());
                    });
                    if (id.length > 0) {
                        swal({
                                title: `{{ lang('Are you sure you want to continue?', 'alerts') }}`,
                                text: "{{ lang('This might erase your records permanently', 'alerts') }}",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            })
                            .then((willDelete) => {
                                if (willDelete) {
                                    $.ajax({
                                        url: "{{ url('admin/ticket/delete/tickets') }}",
                                        method: "POST",
                                        data: {
                                            id: id
                                        },
                                        success: function(data) {
                                            location.reload();
                                            toastr.success(data.success);

                                        },
                                        error: function(data) {

                                        }
                                    });
                                }
                            });
                    } else {
                        toastr.error('{{ lang('Please select at least one check box.', 'alerts') }}');
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
                // Datatable
                $('#supportticket-dashe').dataTable({
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
                        "targets": [0, 1, 4]
                    }],
                });

                $('.form-select').select2({
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });




                $(document).ready(function() {

                    $(document).on('click', '#customCheckAll', function() {
                        $('.checkall').prop('checked', this.checked);
                        updateMassDeleteVisibility();
                    });

                    // Handle individual checkboxes
                    $(document).on('click', '.checkall', function() {
                        updateCustomCheckAll();
                        updateMassDeleteVisibility();
                    });

                    // Handle pagination controls
                    $(document).on('click', '.pagination a', function() {
                        // Assuming '.pagination a' is the selector for your pagination controls
                        setTimeout(function() {
                            updateMassDeleteVisibility();
                        }, 100);
                    });

                    // Initialize the "Select All" checkbox to unchecked
                    $('#customCheckAll').prop('checked', false);

                    // Function to update the visibility of the mass delete button
                    function updateMassDeleteVisibility() {
                        if ($('.checkall:checked').length == 0) {
                            $('#massdelete').hide();
                        } else {
                            $('#massdelete').show();
                        }
                    }

                    // Function to update the state of the "Select All" checkbox
                    function updateCustomCheckAll() {
                        var totalCheckboxes = $('.checkall').length;
                        var checkedCheckboxes = $('.checkall:checked').length;

                        if (checkedCheckboxes === totalCheckboxes) {
                            $('#customCheckAll').prop('checked', true);
                        } else {
                            $('#customCheckAll').prop('checked', false);
                        }
                    }
                });

                $('body').on('click', '#selfassigid', function(e) {

                    e.preventDefault();

                    let id = $(this).data('id');

                    $.ajax({
                        method: 'POST',
                        url: '{{ route('admin.selfassign') }}',
                        data: {
                            id: id,
                        },
                        success: (data) => {
                            toastr.success(data.success);
                            location.reload();
                        },
                        error: function(data) {

                        }
                    });
                })

                $(".vertical-scroll5").bootstrapNews({
                    newsPerPage: 1,
                    autoplay: true,
                    pauseOnHover: true,
                    navigation: false,
                    direction: 'down',
                    newsTickerInterval: 2500,
                    onToDo: function() {

                    }
                });

            })(jQuery);

            function fetchAndUpdateChartData(startDate, endDate, datatype) {
                let fetchurl = '{{ url('/') }}';
                fetch(`${fetchurl}/admin/chartdatafetch/${startDate}/${endDate}/${datatype}`)
                    .then(response => response.json())
                    .then(data => {
                        const categories = data.map(item => item.label);
                        const series1 = data.map(item => item.value1);
                        const series2 = data.map(item => item.value2);
                        const series3 = '{{ setting('enableInstagram') }}' == 'on' ? data.map(item => item.value3) : 'notallowed';
                        const series4 = '{{ setting('enableWhatsapp') }}' == 'on' ? data.map(item => item.value4) : 'notallowed';

                        const series = [
                            { name: 'LiveChats', data: series1 },
                            { name: 'Tickets', data: series2 }
                        ];

                        if (series3 !== 'notallowed') {
                            series.push({ name: 'InstagramChats', data: series3 });
                        }

                        if (series4 !== 'notallowed') {
                            series.push({ name: 'WhatsAppChats', data: series4 });
                        }

                        chart.updateOptions({
                            xaxis: {
                                categories: categories
                            },
                            series: series
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            }

            $('#daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month')
            }, function(start, end) {
                $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            })

            setTimeout(() => {
                fetchAndUpdateChartData(moment().startOf('month'), moment().endOf('month'), 'daterange');
                // document.querySelector('#daterange-btn.daterangepicker').classList.add("select-month-range");
                document.querySelector('.daterangepicker').classList.add("select-month-range");
            }, 100);
            // Function to generate year ranges
            function generateYearRanges() {
                let yearRanges = {};
                let currentYear = moment().year();
                for (let i = 0; i < 10; i++) {
                    let startOfYear = moment().subtract(i, 'years').startOf('year');
                    let endOfYear = moment().subtract(i, 'years').endOf('year');
                    yearRanges[startOfYear.format('YYYY')] = [startOfYear, endOfYear];
                }
                return yearRanges;
            }

            // Generate the year ranges
            let yearRanges = generateYearRanges();
            let latestYear = moment().year();

            $('#daterange-btn2').daterangepicker({
                ranges: yearRanges,
                startDate: moment(`${latestYear}-01-01`),
                endDate: moment(`${latestYear}-12-31`),
                showCustomRangeLabel: false
            }, function(start, end, label) {
                $('#daterange-btn2 span').html(start.format('YYYY'));
            });

            $('#daterange-btn2').on('apply.daterangepicker', function(ev, picker) {
                var year = picker.startDate.format('YYYY');
                fetchAndUpdateChartData(year, null, 'year');
            });

            setTimeout(() => {
                document.querySelectorAll(".daterangepicker").forEach((datarangeelement)=>{
                    if(!datarangeelement.querySelector('.select-month-range')){
                        datarangeelement.classList.add("select-year-range");
                    }else{
                        datarangeelement.classList.remove("select-year-range");
                    }
                })
            }, 100);

            const options = {
                chart: {
                    type: 'line',
                    height: 350
                },
                series: [],
                xaxis: {
                    categories: []
                }
            };

            const chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

            $('#daterange-btn').on('apply.daterangepicker', function(ev, picker) {
                var startDate = picker.startDate.format('YYYY-MM-DD');
                var endDate = picker.endDate.format('YYYY-MM-DD');
                fetchAndUpdateChartData(startDate, endDate, 'daterange');
            });

        });
    </script>
@endsection

@section('modal')
    @include('admin.modalpopup.assignmodal')
@endsection
