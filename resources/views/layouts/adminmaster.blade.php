<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.admin.styles')

    @if (setting('GOOGLE_ANALYTICS_ENABLE') == 'yes')
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('GOOGLE_ANALYTICS') }}"></script>
        <script>
            $(function() {
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());

                gtag('config', '{{ setting('GOOGLE_ANALYTICS') }}');
            })
        </script>
    @endif

</head>

<body
    class="app sidebar-mini
	{{ getIsRtl() }}
    @if (setting('SPRUKOADMIN_P') == 'off')
        @if (setting('DARK_MODE') == 1 || (Auth::check() && Auth::user()->getRoleNames()[0] == 'superadmin' && Auth::user()->darkmode == 1)) dark-mode @endif
    @else
        @if (Auth::check() && Auth::user()->darkmode == 1) dark-mode @endif
	@endif
	@if (setting('sidemenu_icon_style') == 'on') icon-overlay sidenav-toggled @endif
	">



    <div class="page">
        <div class="page-main">
            @include('includes.admin.verticalmenu')
            <div class="app-content main-content">
                <div class="side-app">
                    @include('includes.admin.menu')

                    @if (setting('MAINTENANCE_MODE') == 'on')

                        <div class="alert alert-danger sprukoclosebtn mt-5 fs-15">
                            <i class="fa fa-hourglass-half fa-spin me-2 fs-15" aria-hidden="true"></i>
                            {{ lang('This application is in maintenance mode. We are performing scheduled maintenance.') }}
                        </div>

                    @endif

                    @if (setting('mail_host') == 'smtp.mailtrap.io' && Auth::user()->getRoleNames()[0] == 'superadmin')
                        <div class="alert alert-warning sprukoclosebtn mt-5 fs-15">
                            <i class="fa fa-exclamation-triangle me-2 fs-18" aria-hidden="true"></i>
                            {{ lang('It is necessary to set up your email settings first in order to send and receive emails.') }}
                            <div class="">
                                <a href="{{ route('email.setting.alert') }}" class="btn btn-dark btn-sm mt-2"
                                    target="_blank"> <i class="fa fa-cogs me-2 fs-15"
                                        aria-hidden="true"></i>{{ lang('Email Setup') }} </a>
                                <a href="https://youtu.be/2jwH9P9-R4E" class="btn btn-dark btn-sm mt-2" target="_blank">
                                    <i class="fa fa-link me-2 fs-15"
                                        aria-hidden="true"></i>{{ lang('Setup Reference ') }}</a>
                            </div>
                        </div>
                    @endif

                    @php
                        $cronset = \App\Models\Setting::where('key', 'cronjob_set')->first();
                        $cronworking = $cronset->updated_at->addDay(1) >= \Carbon\Carbon::now();
                    @endphp

                    {{-- @if ($cronworking != true || $cronset->value == 'installed')
                        @if (Auth::user()->getRoleNames()[0] == 'superadmin')
                            <div class="alert alert-primary sprukoclosebtn mt-5 fs-15 gap-2 d-flex align-items-center flex-wrap">
                                <div>
                                    <i class="fa fa-exclamation-triangle me-2 fs-18" aria-hidden="true"></i>
                                    {{ lang('It is necessary to set up your cron job first in order for the auto functions to work.') }}
                                </div>
                                <div class="">
                                    <a href="https://youtu.be/uqkZsQdU_TE" class="btn btn-dark btn-sm"
                                        target="_blank"> <i class="fa fa-link me-2 fs-15"
                                            aria-hidden="true"></i>{{ lang('Setup Reference ') }}</a>
                                </div>
                            </div>
                        @endif
                    @endif --}}

                    <div class="modal effect-scale fade" id="adminautologout" data-bs-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered text-center" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-body px-6 py-5 text-center">
                                    <div class="mb-5 text-center">
                                        <div class="mb-5">
                                            <span class="avatar avatar-xl brround bg-warning-transparent"><i
                                                    class="ri-alert-line fw-normal"></i></span>
                                        </div>
                                        <div>
                                            <h4 class="mb-1">{{lang('Session Timeout')}}</h4>
                                            <p class="fw-semibold mb-1">
                                                {{ lang('You have been inactive since last') }}
                                                {{ setting('admin_users_inactive_auto_logout_time') }}{{ lang(' minutes. Do you wish to stay?') }}
                                            </p>


                                            <span
                                                class="d-block text-muted fw-normal">{{ lang('Your session will be timed out in') }}
                                                <h3 class="countdown mb-0"></h3><span>{{ lang('seconds') }}</span>


                                        </div>
                                    </div>
                                    <button
                                        class="btn btn-primary w-lg adminstayin">{{ lang('Stay Signed In') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Start Tikcet create -->
                    <div class="modal effect-scale fade" data-bs-backdrop="static" id="ticketcreateforcust" aria-hidden="true">
                        <div class="modal-dialog  modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ lang('Create Ticket') }}</h5>
                                    <button  class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <form method="POST" id="create_ticket_form">
                                    @csrf
                                    @honeypot
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="form-label">
                                                {{ lang('Subject') }}
                                            <span class="text-red">*</span>
                                            <small class="text-muted float-end mt-2 subjectmaxtexts" id="subjectmaxtexts">{{lang('Maximum')}} <b>{{setting('TICKET_CHARACTER')}}</b> {{lang('Characters')}}</small>
                                            </label>
                                            <input type="text" class="form-control" name="subject" maxlength="{{setting('TICKET_CHARACTER')}}" placeholder="Enter Ticket Subject" id="subjects">

                                            <span id="subjectError" class="text-danger alert-message"></span>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-12 col-md-12 col-xl-12">
                                                <label for="namecust" class="form-label">
                                                    {{ lang('Customer Name') }}
                                                </label>
                                                <input type="text" class="form-control" name="customername" id="namecust" placeholder="Enter Customer Name">
                                                <span id="customerNameError" class="text-danger alert-message"></span>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-6 col-xl-6">
                                                <label for="emailcust" class="form-label">
                                                    {{ lang('Customer Email') }}
                                                <span class="text-red">*</span>
                                                </label>
                                                <input type="email" class="form-control" name="email" id="emailcust" placeholder="Enter Customer Email">
                                                <span id="emailError" class="text-danger alert-message"></span>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-6 col-xl-6">
                                                <div class="row">
                                                    <div class="">
                                                        <label class="form-label">{{lang('Category')}} <span class="text-red">*</span></label>
                                                    </div>
                                                    <div class="">
                                                        <select class="form-control select2create-show-search select2create" data-placeholder="{{lang('Select Category')}}" name="category" id="creattcategory">
                                                            <option label="{{lang('Select Category')}}"></option>
                                                            @foreach (\App\Models\Ticket\Category::whereIn('display', ['ticket', 'both'])->where('status', '1')->get() as $category)

                                                            <option value="{{ $category->id }}" @if(old('category')) selected @endif>{{ $category->name }}</option>
                                                            @endforeach

                                                        </select>
                                                        <span id="CategorysError" class="text-danger alert-message"></span>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" id="selectssSubCategorys" style="display: none;">
                                            <label class="form-label mb-0 mt-2">{{lang('Subcategory')}}</label>
                                            <select  class="form-control select2create-show-search  select2create"  data-placeholder="{{lang('Select SubCategory')}}" name="subscategory" id="subscategorys">
                                            </select>
                                            <span id="subcategoryError" class="text-danger alert-message"></span>
                                        </div>

                                        <div class="form-group" id="envatopurchases">
                                        </div>

                                        @if(setting('ENVATO_ON') == 'on')
                                            <div class="row d-none mb-2" id="hideelementt">
                                                <div class="">
                                                    <label class="form-label mb-2 mt-2">{{lang('Envato Item Name')}}<span class="text-red">*</span></label>
                                                </div>
                                                <div class="">
                                                    <input type="hidden" name="productname" id="itemnamee">
                                                    <input type="text" id="productnamee" class="form-control" placeholder="Envato Project Name">

                                                </div>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="form-group col-sm-12 col-md-6 col-xl-6">
                                                <div class="row">
                                                    <div class="">
                                                        <label class="form-label">{{lang('Priority')}}</label>
                                                    </div>
                                                    <div class="">
                                                        <select class="form-control select2create-show-search select2create" data-placeholder="{{lang('Select Priority')}}" name="priority" id="priorityy">
                                                            <option label="{{lang('Select Priority')}}"></option>
                                                            <option value="Low">{{lang('Low')}}</option>
                                                            <option value="Medium">{{lang('Medium')}}</option>
                                                            <option value="High">{{lang('High')}}</option>
                                                            <option value="Critical">{{lang('Critical')}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-6 col-xl-6">
                                                <div class="row">
                                                    <div class="">
                                                        <label class="form-label">{{lang('Assignee')}} <span class="text-red">*</span></label>
                                                    </div>
                                                    <div class="">
                                                        <select
                                                            class="form-control select2create-show-search  select2create"
                                                            data-placeholder="{{lang('Select Assignee')}}" name="assigneeid">

                                                            <option value="unassign">{{ lang("Un Assign") }}</option>
                                                            @foreach (\App\Models\User::where('status',1)->get() as $userdata)
                                                                @if ($userdata->id == Auth::user()->id)
                                                                    <option value="{{ $userdata->id }}" selected>{{ $userdata->name }} ({{lang('You')}})</option>
                                                                @else
                                                                    <option value="{{ $userdata->id }}" @if(old('assignee')) selected @endif>{{ $userdata->name }}</option>
                                                                @endif
                                                            @endforeach

                                                        </select>
                                                        <span id="assigneeError" class="text-danger alert-message"></span>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="message" class="form-label">
                                            {{ lang('Description') }}
                                        <span class="text-red">*</span>
                                        </label>
                                        <textarea class="form-control mb-2 textarea-chatoptions" id="message"  name="message" rows="4" cols="50" placeholder="Type your message here..."></textarea>
                                        <span id="messageError" class="text-danger alert-message"></span>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="#" class="btn btn-outline-danger" id="btnclose" data-bs-dismiss="modal">{{lang('Close')}}</a>
                                        <button type="submit" class="btn btn-secondary" id="create_ticket_form_btn">{{lang('Save')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Tikcet create -->
                    @yield('content')

                </div>
            </div><!-- end app-content-->
        </div>
        @include('includes.admin.footer')

    </div>

    @include('includes.admin.scripts')

    @if (Session::has('error'))
        <script>
            toastr.error("{!! Session::get('error') !!}");
        </script>
    @elseif(Session::has('success'))
        <script>
            toastr.success("{!! Session::get('success') !!}");
        </script>
    @elseif(Session::has('info'))
        <script>
            toastr.info("{!! Session::get('info') !!}");
        </script>
    @elseif(Session::has('warning'))
        <script>
            toastr.warning("{!! Session::get('warning') !!}");
        </script>
    @elseif(Session::has('adminreplied'))
        <script>
            toastr.success("{!! Session::get('adminreplied') !!}");
        </script>
        @php
            Session::pull('adminreplied', 'The response to the ticket was successful.');
        @endphp
    @endif

    @yield('modal')

</body>

</html>
