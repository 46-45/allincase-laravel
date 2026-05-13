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
		<h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Customers', 'menu')}}</span></h4>
	</div>
</div>
<!--End Page header-->

<!-- Customer List -->
<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card ">
		<div class="card-header border-0 d-md-max-block">
			<h4 class="card-title">{{lang('Customers List')}}</h4>
		</div>
		<div class="card-body overflow-scroll custom-show-data" style="min-height: 250px;">
			<div class="spruko-delete">
				@can('Customers Delete')

				<button id="massdelete" class="btn btn-outline-light btn-sm mb-4 data-table-btn" style="display: none;"><i class="fe fe-trash"></i> {{lang('Delete')}}</button>
				@endcan
                <div class="fetchedtabledata">
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

<script type="text/javascript">
    "use strict";

    document.addEventListener("DOMContentLoaded", (event) => {

        let tableData = @json($customers);

        let dateFormat = @json(setting('date_format'));
        let delPerm = @json(Auth::user()->can('Customers Delete'));
        let custChatPerm = @json(Auth::user()->can('Customer Chat Process'));
        let editPerm = @json(Auth::user()->can('FAQ Category Edit'));

        var dataTable;
        var tableDropDown;
        let userId = @json(Auth::id());

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
                var engagedConversArray = JSON.parse(data.engage_conversation || '[]');
                var engagedUserData = (engagedConversArray.length > 0 && engagedConversArray.find(item => item.id === userId)) || engagedConversArray.length == 0 ? 'chacticonopen' : '';
                var unbanCustomerUrl = `{{ route('admin.unbancustomer') }}?id=${data.id}`;
                var hisroyRouteUrl = "{{ route('admin.customer.tickethistory', ['cust_id' => ':id']) }}".replace(':id', data.encrypted_id);
                var custchatsexist = data.livechatconversation.length > 0;
                var routeUrl;
                var statuscust;
                if(data.status == 'solved'){
                    routeUrl = "{{ route('admin.solvedChats') }}";
                    statuscust = 'solved';
                } else if (data.engage_conversation != null){
                    routeUrl = "{{ route('admin.myOpenedChats') }}";
                    statuscust = 'myopened';
                }else{
                    routeUrl = "{{ route('admin.livechat') }}";
                    statuscust = 'newchat';
                }

                return `
                    <tr>
                        <td>${index + 1}</td>
                        <td class="text-center">
                            ${delPerm
                                ? `<input type="checkbox" name="customer_checkbox[]" class="checkall form-check-input" value="${data.encrypted_id}" />`
                                : '~'
                            }

                        </td>

                        <td>
                            <div>
                                <h5 class="d-inline">${truncateTitle(data.username)}</h5>
                                ${data.banstatus == 'banned' ? `<span class="badge badge-danger-light"><i class="fa fa-exclamation-triangle text-danger"></i>{{ __('Banned') }}</span>` : ''}
                            </div>
                            <small class="fs-12 text-muted">
                                <span class="font-weight-normal1">${data.email ? truncateTitle(data.email) : data.email}</span>
                            </small>
                        </td>

                        <td>
                            <span class="badge badge-success-light">
                                ${dateTime(data.created_at)}
                            </span>
                        </td>

                        <td>${(data.userType == "WhatsApp" || data.userType ==  "Instagram") ? 'N/A' : data.country}</td>

                        <td>${(data.userType == "WhatsApp" || data.userType ==  "Instagram") ? 'N/A' : data.browser_info}</td>

                        <td>${(data.userType == "WhatsApp" || data.userType ==  "Instagram") ? 'N/A' : data.Login_url}</td>

                        <td>
                            <div class = "d-flex">
                                ${custChatPerm ? (custchatsexist && engagedUserData == 'chacticonopen' ? `<a href="javascript:void(0)" class="action-btns1 localstorageadding" data-id="${data.id}" data-url="${routeUrl}" data-status="${statuscust}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ lang('View chat') }}"><i class="fe fe-message-square text-primary"></i></a>` : '' ) : ''}

                                ${delPerm ? `<a href="javascript:void(0)" class="action-btns1" data-id="${data.encrypted_id}" id="show-delete" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ lang('Delete') }}"><i class="feather feather-trash-2 text-danger"></i></a>` : '~'
                                }

                                ${data.tickets.length > 0 ? `<a href="${hisroyRouteUrl}" class="action-btns1" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ lang('Tickets History') }}"> <i class="feather-rotate-ccw text-primary"></i></a>` : ''
                                }

                                ${data.verified == 'banned' ? `<a href="${unbanCustomerUrl}" class="action-btns1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ lang('Remove from Banned list') }}"><i class="feather feather-user-check text-primary"></i></a>` : ''
                                }
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
            document.getElementById('support-customerlist').appendChild(createTbody);

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
                let search = {!! json_encode(lang('Search...')) !!};

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
                        info: `${showing} _PAGE_ ${ofval} _PAGES_`,
                        infoEmpty: noentries,
                        lengthMenu: `${show} _MENU_ ${entries} `,
                    },
                    order: [],
                    columnDefs: [{
                        "orderable": false,
                        "targets": [0, 1, 3]
                    }]
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

                $('body').on('click', '.localstorageadding', function () {
                    console.log('onclick working');
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

                    window.open(url, '_blank');
                });

                // Delete the customer
                $('body').on('click', '#show-delete', function () {
                    let parent = $(this).closest('tr');
                    var _id = $(this).data("id");
                    swal({
                        title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                        text: "{{lang('This might erase your records permanently', 'alerts')}}",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "get",
                                url: SITEURL + "/admin/customer/delete/"+_id,
                                success: function (data) {
                                    toastr.success(data.success);
                                    $(parent[0]).addClass('fade-out');
                                    let newData = tableData.filter(item => item.encrypted_id != _id);
                                    tableData = newData;
                                    setTimeout(() => {
                                        parent[0].remove();
                                        if ($.fn.DataTable.isDataTable('#support-customerlist')) {
                                            $('#support-customerlist').DataTable().clear().destroy();
                                        }
                                        loadTable(tableData);
                                    }, 1500);
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                    if(data.responseJSON.message == 'The payload is invalid.'){
                                        toastr.error('You are not allow to delete this customer.');
                                    }
                                }
                            });
                        }
                    });
                });

                // Mass Delete
                $('body').on('click', '#massdelete', function () {
                    var parent = [];
                    var id = [];
                    $('.checkall:checked').each(function(){
                        parent.push($(this).closest('tr'));
                        id.push($(this).val());
                    });
                    if(id.length > 0){
                        swal({
                            title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                            text: "{{lang('This might erase your records permanently', 'alerts')}}",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                $.ajax({
                                    url:"{{ url('admin/masscustomer/delete')}}",
                                    method:"GET",
                                    data:{id:id},
                                    success:function(data)
                                    {
                                        toastr.success(data.success);
                                        parent.forEach(function(row) {
                                            row.addClass('fade-out');
                                        });
                                        let newData = tableData.filter(item => !id.includes(String(item.encrypted_id)));
                                        tableData = newData;
                                        setTimeout(() => {
                                            parent.forEach(function(row) {
                                                row.remove();
                                            });
                                            if ($.fn.DataTable.isDataTable('#support-customerlist')) {
                                                $('#support-customerlist').DataTable().clear().destroy();
                                            }
                                            $('#customCheckAll').prop('checked', false);
                                            loadTable(tableData);
                                        }, 1500);
                                    },
                                    error:function(data){
                                        if(data.responseJSON.message == 'The payload is invalid.'){
                                            toastr.error('You are not allow to delete this customer.');
                                        }
                                    }
                                });
                            }
                        });
                    }else{
                        toastr.error('{{lang('Please select at least one check box.', 'alerts')}}');
                    }

                });


                // resend verification code to the customer
                $('body').on('click', '#resendverification', function () {
                    var _id = $(this).data("id");

                    swal({
                        title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                        text: "{{lang('This is to resend email verification link to the customer', 'alerts')}}",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "get",
                                url: SITEURL + "/admin/customer/resendverification/"+_id,
                                success: function (data) {
                                    toastr.success(data.success);
                                    location.reload();
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                    if(data.responseJSON.message == 'The payload is invalid.'){
                                        toastr.error('You are not allow to delete this customer.');
                                    }
                                }
                            });
                        }
                    });
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

            })(jQuery);
        });
    });

</script>
@endsection


