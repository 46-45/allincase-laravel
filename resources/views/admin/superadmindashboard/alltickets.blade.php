@extends('layouts.adminmaster')
@section('styles')
    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{ asset('build/assets/plugins/sweet-alert/sweetalert.css') }}?v=<?php echo time(); ?>" rel="stylesheet" />
    <style>
        .file-img {
            position: relative;
        }

        .file-img button {
            position: absolute;
            inset-block-start: -4px;
            inset-inline-end: -4px;
            height: 20px;
            width: 20px;
            padding: 0px;
            border: 0;
        }

        .liveChatImageViewer {
            display: block;
            position: fixed;
            z-index: 9999999999;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .liveChatImageClose {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            border: 1px solid;
            padding: 0px 20px;
            border-radius: 50%;
            cursor: pointer;
        }

        .liveChatImageTag {
            margin: auto;
            display: block;
            /* width: 80vh; */
            height: 80vh;
            /* max-width: 403px; */
            border-radius: 10px;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
        }

        .main-chat-msg p {
            word-wrap: break-word !important;
            word-break: break-word !important;
        }

        .dark-mode .main-chart-wrapper .chat-info {
            background-color: #191d43 !important
        }

        /* .main-chart-wrapper .chat-info ul li.checkforactive:hover .avatar {
            opacity: 0;
        } */
        .main-chart-wrapper .chat-info ul li.checkforactive:hover .ticket-select-check{
            display: block
        }
        .main-chart-wrapper .chat-info ul li.checkforactive {
            z-index: 10;
        }
        .dark-mode .main-chart-wrapper .chat-info ul li.checkforactive {
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dark-mode .checkforactive.active,
        .dark-mode .chat-reply-area {
            background-color: #191d43 !important
        }

        .chat-content li {
            border: 0 !important;
        }

        .dark-mode .main-chart-wrapper .main-chat-area .chat-content .main-chat-msg div p {
            color: #f1f4fb;
        }

        .dark-mode .main-chart-wrapper .main-chat-area .chat-content .chat-item-start .main-chat-msg div {
            background-color: rgb(37 38 74);
        }
        .ticket-select-check {
            position: absolute;
            inset-inline-start: 10px;
            inset-block-start: 9px; /* new-styles*/
            display: none;
            z-index: 100;
        }

        ul.show-checks li.checkforactive .ticket-select-check {
            display: block;
        }
        ul.show-checks li.checkforactive .checkingpreventclick > span {
            visibility: hidden;
        }
        .tickets-chat-head {
            position: relative;
        }
        .tickets-chat-head .data-table-btn1 {
            position: absolute;
            right: 55px;
        }
        .tickets-chat-head .data-table-btn3 {
            position: absolute;
            right: 15px;
        }
        .ticket-count-style {
            position: absolute;
            top: -10px;
            right: -8px;
            padding: 2px 5px !important;
            font-size: 11px;
            line-height: 1 !important;
        }
        .live-chat-checkbox.all-tickets-check.form-check-input {
            margin-top: 0
        }

        .search-input {
            flex-grow: 1;
            margin-inline-start: 19px;
        }

        .search-container{
            position: absolute !important;
            inset-inline-end: 10px;
            top: 7px;
            width: 100%;
            transition: 0.3s;
            display: flex !important;
            justify-content: end !important;
        }

        /* spell check and suggestion */
        #auto-expand {
            position: relative;
            border: 1px solid #ccc;
            padding: 10px;
            min-height: 40px;
            width: 100%;
            font-size: 16px;
            /* height: 100% !important; */
        }

        #suggestionText {
            padding: 4px 8px;
            font-size: 14px;
            color: #333;
            pointer-events: none;
            opacity: 0.5;
            z-index: 999;
            white-space: nowrap;
        }

        .misspelled {
            text-decoration: underline wavy red;
            cursor: pointer;
            position: relative;
        }

        .correctedtooltip {
            padding: 5px 10px;
            border-radius: 5px;
            color: #333;
            font-size: 12px;
            white-space: nowrap;
            z-index: 100000;
            cursor: pointer;
        }

        /* start text to speach */
        .msg-send-dropdown{
            display: none;
            position: absolute;
            position: absolute;
            inset-inline-end: 17px;
            inset-block-start: 9px;
        }
        .chat-list-inner{
            position: relative;
            /* padding: 1rem; */
            border-radius: 0.35rem;
            width:100%;
        }
        /* .chat-list-inner:hover{
            background-color: #f1f4fb;
        } */
        .chat-list-inner:hover .msg-send-dropdown {
            display: block
        }
        .chatting-user-info{
            margin-inline-end: 1.5rem;
        }
        .main-chart-wrapper .dropdown-menu li{
            margin-block-end:0px !important;
        }
        /* end text to speach */

        .editable-container {
            position: relative;
            width: 100%;
            height: auto;
        }

        .editable-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 8px;
            color: #aaa;
            pointer-events: none;
            user-select: none;
            transition: opacity 0.2s ease;
        }

        .editable-container div[contenteditable]:empty::before {
            content: 'Type message';
            opacity: 0.6;
        }

        .editable-container div[contenteditable]:not(:empty)::before {
            content: '';
        }

        .editable-container div[contenteditable] {
            padding: 8px;
            box-sizing: border-box;
        }

        .is-loading .agent-detail .font-weight-semibold,
        .is-loading .agent-detail .text-muted,
        .is-loading .agent-detail .avatar,
        .is-loading .agent-detail .name-email-container {
            background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
            border-radius: 5px;
            background-size: 200% 100%;
            /* Remove !important */
        }

        /* Adjust animation properties */
        @keyframes shine {
            to {
                background-position-x: 200%;
                /* Adjust direction */
            }
        }

        /* Apply animation to the elements */
        .is-loading .agent-detail .font-weight-semibold,
        .is-loading .agent-detail .text-muted,
        .is-loading .agent-detail .avatar,
        .is-loading .agent-detail .name-email-container {
            animation: shine 1.5s linear infinite;
        }


        .is-loading .agent-detail .avatar,
        .is-loading .agent-detail .name-email-container {
            background-image: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%) !important;
            border-radius: 5px
        }

        .is-loading .agent-detail .onlineOfflineIndicator {
            display: none
        }

        .is-loading .agent-detail .font-weight-semibold,
        .is-loading .agent-detail .text-muted {
            opacity: 0;
        }

        .is-loading#main-chat-content .avatar,
        .is-loading#main-chat-content .operator-conversation-Info .flex-fill,
        .is-loading#main-chat-content .avatar,
        .is-loading#main-chat-content .chatnameperson,
        .is-loading#main-chat-content .chatting-user-info,
        .is-loading#main-chat-content .chat-day-label span,
        .is-loading#main-chat-content .chat-list-inner .main-chat-msg div {
            background-image: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%) !important;
            border-radius: 5px;
            animation: shine 1.5s linear infinite;
            background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
            border-radius: 5px;
            background-size: 200% 100%;
        }

        .is-loading#main-chat-content .chat-day-label span {
            padding: 0.188rem 3.5rem !important
        }

        .is-loading#main-chat-content .chatnameperson,
        .is-loading#main-chat-content .chat-item-end .chatting-user-info {
            padding: 0.188rem 3.5rem;
            font-size: 0.7rem;
            background-color: rgba(51, 102, 255, 0.1);
            border-radius: 0.3rem;
            color: var(--primary);
        }
        .is-loading ul{
            background-image: url(https://laravelui.spruko.com/dayone/assets/images/svgs/loader.svg);
            display: block !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            padding: 2.5rem !important;
            background-size: auto !important;
            background-attachment: inherit !important;
        }

        .is-loading ul li{
            display: none !important;
        }

        .is-loading #operator-conversation-Info, .is-loading .chat-footer{
            display: none !important;
        }

        .btn-group > .btn:not(:last-child):not(.dropdown-toggle), .btn-group > .btn-group:not(:last-child) > .btn {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

    </style>
    <!-- Select2 css -->
    <link href="{{ asset('build/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

@endsection
@section('content')

    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title"><span
                    class="font-weight-normal text-muted ms-2">{{ lang($tickettypeshow, 'menu') }}</span>
            </h4>
        </div>
    </div>
    <!--End Page header-->
    <div class="main-chart-wrapper">
        <div class="row">
            <div class="col-xxl-3 col-xl-4 col-lg-5">
                {{-- sidebar --}}
                <div class="chat-info border card">
                    <div class="d-flex align-items-center justify-content-between w-100 p-4 ps-4 border-bottom">
                        <div>
                            <h5 class="fw-semibold mb-0">{{ lang($tickettypeshow) }}</h5>
                        </div>
                        <div class="search-container">
                            <div class="search-input" style="display: none;">
                              <input type="search" class="form-control" id="input-search" placeholder="{{ lang('Search here') }}">
                            </div>
                            <a href="javascript:void(0);" class="p-2 fs-16" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Search" onclick="toggleSearch()">
                              <i class="ri-search-line align-middle"></i>
                            </a>
                        </div>
                        @if($tickettype == 'trashedticketslocalstore')
                            <button id="massrestore" class="btn btn-outline-light btn-icon btn-sm data-table-btn3" style="display: none;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Restore"><i class="feather feather-rotate-ccw"></i> </button>
                        @endif
                        <button id="massdelete" data-id="{{ $tickettype == 'trashedticketslocalstore' ? 'trashed' : 'normal' }}" class="btn btn-outline-light btn-sm btn-icon data-table-btn1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" style="display: none;"><i class="fe fe-trash"></i> </button>
                        <span class="ms-6 mt-2" id="selectallbutton" style="display: none;">
                            <input aria-label="anchor" type="checkbox" id="customCheckAll" class="form-check-input">
                            <label for="customCheckAll"></label>
                        </span>
                    </div>
                    <div class="search-section">
                        <ul class="list-unstyled mb-0 chat-user-tab-list chat-users-tab overflow-auto" id="chat-msg-scroll">
                            @php
                                $emptyConversation = true;
                            @endphp

                            @foreach ($alltickets as $allticket)

                                {{ $emptyConversation = false }}
                                <li class="checkforactive" data-id={{ $allticket->id }}>
                                    <div class="d-flex align-items-start position-relative">
                                        <div class="me-2 lh-1 checkingpreventclick">
                                            <span class="avatar brround" style="background-color: {{ $allticket->profile_bg_color }}">
                                                <span
                                                    class="new-chat-user-letter">{{ strtoupper(substr($allticket->cust->username, 0, 1)) }}
                                                </span>
                                            </span>
                                            @if($tickettype == 'trashedticketslocalstore')
                                                @if(Auth::user()->can('Ticket Delete') || Auth::user()->can('Ticket Restore'))
                                                    <input aria-label="anchor" aria-expanded="false" type="checkbox" name="customer_checkbox[]" class="checkall ticket-select-check live-chat-checkbox all-tickets-check form-check-input" value="{{ encrypt($allticket->id) }}"/>
                                                @endif
                                            @else
                                                @if(Auth::user()->can('Ticket Delete'))
                                                    <input aria-label="anchor" aria-expanded="false" type="checkbox" name="customer_checkbox[]" class="checkall ticket-select-check live-chat-checkbox all-tickets-check form-check-input" value="{{ encrypt($allticket->id) }}"/>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="flex-fill">
                                            <div class="mb-0 d-flex align-items-start justify-content-between">
                                                <div>
                                                    <div class="font-weight-semibold">
                                                        <a href="javascript:void(0);" class="customer-name-change">{{ $allticket->cust->username }}</a>
                                                        <span class="badge badge-sm badge-success">#{{ $allticket->ticket_id }}</span>
                                                        <span class="badge badge-sm badge-danger-light overduestautsupdate{{ $allticket->id }} {{ $allticket->overduestatus == 'Overdue' ? '' : 'd-none' }}">{{ lang('Overdue') }}</span>
                                                    </div>
                                                    <div class="font-weight-semibold">
                                                        <a href="javascript:void(0);" >{{ $allticket->subject }}</a>
                                                    </div>
                                                </div>
                                                @php
                                                    if ($allticket->comments->isEmpty()) {
                                                        $datacreated = $allticket->created_at;
                                                        $createdmessage = $allticket->message;
                                                    } else {
                                                        $commenteddata = $allticket->comments->first();
                                                        $datacreated = $commenteddata->created_at;
                                                        $createdmessage = $commenteddata->comment;
                                                    }
                                                @endphp
                                                <div class="float-end text-muted fw-normal fs-12 chat-time"
                                                    data-initial-24time='{{ \Carbon\Carbon::parse($datacreated)->timezone(setting('default_timezone')) }}'>
                                                    {{ $datacreated->diffForHumans() }}
                                                </div>
                                                <div class="dropdown chat-actions lh-1">
                                                    <a aria-label="anchor" href="javascript:void(0);"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fe fe-more-vertical fs-18"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        @if($tickettype == 'trashedticketslocalstore')
                                                            @if(Auth::user()->can('Ticket Delete') || Auth::user()->can('Ticket Restore'))
                                                                <li>
                                                                    <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="slectTicket(event, {{ $allticket->id }})" data-id="{{ $allticket->id }}">
                                                                        <i class="ri-mail-check-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Select') }}
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @else
                                                            @if(Auth::user()->can('Ticket Delete'))
                                                                <li>
                                                                    <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="slectTicket(event, {{ $allticket->id }})" data-id="{{ $allticket->id }}">
                                                                        <i class="ri-mail-check-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Select') }}
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endif

                                                        @if($allticket->deleted_at == null)
                                                            @if (in_array(Auth::id(), json_decode($allticket->mark_as_unread ?? '[]')))
                                                                <li><a data-id="{{ $allticket->id }}" class="dropdown-item markAsreadBtn markAsReadSubmit" href="javascript:void(0);"><i class="ri-mail-check-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Mark As Read') }}</a></li>
                                                            @else
                                                                <li><a data-id="{{ $allticket->id }}" class="dropdown-item markAsUnreadBtn markAsUnReadSubmit" href="javascript:void(0);"><i class="ri-mail-unread-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Mark As Unread') }}</a></li>
                                                            @endif
                                                        @endif
                                                        @if($allticket->deleted_at != null && Auth::user()->can('Ticket Restore'))
                                                            <li>
                                                                <a class="dropdown-item" data-id="{{ $allticket->id }}" restoreRouteLink="{{ encrypt($allticket->id) }}">
                                                                    <i class="ri-refresh-line align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Restore') }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @can('Ticket Delete')
                                                        <li>
                                                            <a class="dropdown-item" deleteRouteLink="{{ encrypt($allticket->id) }}" data-id="{{ $allticket->id }}" data-tickettype="{{ $allticket->deleted_at != null ? 'trashed' : 'normal' }}">
                                                                <i class="ri-delete-bin-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Delete') }}
                                                            </a>
                                                        </li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                @if ($allticket->unreadIndexNumber)
                                                    <span
                                                        class="chat-msg text-truncate fs-13 text-default custrecentmessage font-weight-bold"
                                                        data-id="100">{{ str_replace('&nbsp;', ' ', strip_tags(html_entity_decode($createdmessage))) }}</span>
                                                    <span
                                                        class="ms-auto me-2 badge bg-success-transparent rounded-circle unReadIndexNumber">{{ $allticket->unreadIndexNumber }}</span>
                                                @else
                                                    <span
                                                        class="chat-msg text-truncate fs-13 text-default custrecentmessage"
                                                        data-id="100">{{ str_replace('&nbsp;', ' ', strip_tags(html_entity_decode($createdmessage))) }}</span>
                                                @endif
                                                @if (in_array(Auth::id(), json_decode($allticket->mark_as_unread ?? '[]')) && !$allticket->unreadIndexNumber)
                                                    <span
                                                        class="badge bg-success-transparent rounded-circle float-end unReadIndexNumber me-1 ms-auto"></span>
                                                @endif
                                                @if ($allticket->myassignuser_id != null && $allticket->selfassignuser_id != null)
                                                    <span class="avatar brround avatar-sm avatar-image-sm" style="background-image: url({{ userprofileimage($allticket->selfassign) }})" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $allticket->selfassign->name }}" reddy="" data-bs-original-title="" title=""></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                            @if ($emptyConversation)
                                <div class="text-center mt-5 px-2 py-4 bg-warning-transparent text-default mx-auto w-90 rounded-2">
                                    <span>{{ lang('As of now, there are no chat discussions in progress') }}</span>
                                </div>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-xl-8 col-lg-7">
                {{-- Chat Messages div --}}
                <div class="main-chat-area main-chat-area-new bg-white card border">
                    <div id="main-chat-content">
                        <div class="card shadow-none no-articles d-none"
                            style="
                        height: calc(100vh - 8rem);
                        background-color: transparent;
                        ">
                            <div class="card-body p-8">
                                <div class="main-content text-center">
                                    <div class="notification-icon-container p-4">
                                        <img src="{{ asset('build/assets/images/noarticle.png') }}" alt="">
                                    </div>
                                    <h4 class="mb-1">{{ lang('Currently, no active chat discussions at the moment') }}
                                    </h4>
                                    <p class="text-muted">
                                        {{ lang('There are currently no ongoing chat discussions at this time.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-footer bg-transparent shadow-none d-none border-top">
                        <div class="chat-reply-area chat-replyoptions flex-fill textareaDiv d-none">

                            <div class="form-group create-ticket d-none">
                                <label for="livechatTicketSubject" class="form-label">
                                    {{ lang('Subject') }}
                                    <span class="text-red">*</span>
                                </label>
                                <input type="text" class="form-control" id="livechatTicketSubject"
                                    placeholder="Enter Ticket Subject">
                            </div>

                            <div class="form-group d-none">
                                <div class="row">
                                    <div class="">
                                        <label class="form-label">{{ lang('Category') }} <span
                                                class="text-red">*</span></label>
                                    </div>
                                    <div class="">
                                        <select
                                            class="form-control select2-show-search  select2 @error('category') is-invalid @enderror"
                                            data-placeholder="{{ lang('Select Category') }}" name="category"
                                            id="category">
                                            <option label="{{ lang('Select Category') }}"></option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    @if (old('category')) selected @endif>{{ $category->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                        <span id="CategoryError" class="text-danger alert-message"></span>
                                        @error('category')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="envatopurchase">
                            </div>

                            <div class="form-group d-none" id="assigneediv">
                                <div class="row">
                                    <div class="">
                                        <label class="form-label">{{ lang('Assignee') }} <span
                                                class="text-red">*</span></label>
                                    </div>
                                    <div class="">
                                        <select
                                            class="form-control select2-show-search  select2 @error('assignee') is-invalid @enderror"
                                            data-placeholder="{{ lang('Select Assignee') }}" name="assignee"
                                            id="assignee">

                                            <option value="unassign">{{ lang('Un Assign') }}</option>
                                            @foreach ($userdatas as $userdata)
                                                @if ($userdata->id == Auth::user()->id)
                                                    <option value="{{ $userdata->id }}" selected>{{ $userdata->name }}
                                                        ({{ lang('You') }})</option>
                                                @else
                                                    <option value="{{ $userdata->id }}"
                                                        @if (old('assignee')) selected @endif>
                                                        {{ $userdata->name }}</option>
                                                @endif
                                            @endforeach

                                        </select>
                                        <span id="assigneeError" class="text-danger alert-message"></span>
                                        @error('assignee')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                            </div>

                            <label for="auto-expand" class="form-label create-ticket-description d-none">
                                {{ lang('Description') }}
                                <span class="text-red">*</span>
                            </label>
                            <div class="editable-container">
                                <div contenteditable="true" id="auto-expand" name="message" class="form-control mb-2 textarea-chatoptions"></div>
                            </div>
                            <div class="image-uploaded d-flex gap-2 flex-wrap"></div>
                            <div class="d-flex align-items-center px-2 flex-wrap">
                                <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Canned responses">
                                    <a aria-label="anchor" class="btn-emoji" href="javascript:void(0)"
                                        onclick="{openModal(event)}">
                                        <i class="ri-message-line"></i>
                                    </a>
                                </div>
                                <div id="chat-btn" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-title="Chat">
                                    <a aria-label="anchor" class="btn-emoji" href="javascript:void(0)">
                                        <i class="ri-chat-3-line text-primary"></i>
                                    </a>
                                </div>

                                <div class="d-flex align-items-center ms-auto gap-2 flex-wrap">
                                    <a aria-label="anchor" class="btn-emoji allEmojisBtn dropdown"
                                        data-bs-toggle="dropdown" href="javascript:void(0)" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="Emojis" data-bs-original-title=""
                                        title="">
                                        <i class="ri-emotion-line"></i>
                                    </a>
                                    <ul class="dropdown-menu" id="emojiGrid" style="height: 200px; overflow-y: scroll;">
                                    </ul>
                                    @if (setting('liveChatAgentFileUpload') == '1' || Auth::user()->getRoleNames()[0] == 'superadmin')
                                        <a aria-label="anchor" class="btn-attach" type="file"
                                            onclick="{document.querySelector('#chat-file-upload').click()}"
                                            href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="Upload" data-bs-original-title="" title="">
                                            <i class="ri-attachment-line"></i>
                                        </a>
                                        <input type="file" id="chat-file-upload" class="d-none"
                                            name="chat-file-upload" />
                                    @endif
                                    <div class="btn-group">
                                        <button aria-label="anchor" disabled="true" id="agentSendMessage" class="btn-reply btn-outline-primary btn shadow-none disabled" href="javascript:void(0)" value="">
                                        </button>
                                        <button type="button"
                                            class="btn btn-primary dropdown-toggle dropdown-toggle-split shadow-none"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" onclick="updateButton('Inprogress')">{{ lang('Inprogress') }}</a></li>
                                            <li><a class="dropdown-item" onclick="updateButton('Solved')">{{ lang('Solved') }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 main-chat-user-details card border">

                <button aria-label="button" type="button" class="btn btn-icon btn-outline-light my-1 ms-2 responsive-chat-close2">
                    <i class="ri-close-line"></i>
                </button>
                <div class="card shadow-none" id="ticketinfofethc">
                </div>

                {{-- Customer Info Div --}}
                <div class="chat-user-details bg-transparent p-0">
                    <div class="card d-none shadow-none" id="chat-user-details">
                        <div class="card-header border-0 pb-0">
                            <h4 class="card-title">{{ lang('Customer Info') }}</h4>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>

                    <div class="card d-none">
                        <div class="card-header pb-4 d-flex align-items-center border-0">
                            <h6>{{ lang('File Upload Permission') }}</h6>
                            <div class="switch-toggle ms-auto">
                                <a class="onoffswitch2 ">
                                    <input type="checkbox" id="fileUploadPermission" class="toggle-class onoffswitch2-checkbox">
                                    <label for="fileUploadPermission" class="toggle-class onoffswitch2-label"></label>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Re-Assign Model --}}

        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{ lang('Reassign the conversation') }}</h5>
                        <button  class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group select2-lg">
                            <label for="form-label">{{ lang('Reassign Users') }}</label>
                            <select class="form-control select2" placeholder="..." id="reassignSelect">
                                <option></option>
                                @foreach ($user as $users)
                                    <option value={{ $users->id }} data-kt-select2-country="{{ userprofileimage($users) }}">{{ $users->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ lang('Close') }}</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                            id="reassignSelectBtn">{{ lang('Reassign') }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Canned Responses --}}
        <div class="modal fade" id="canned-responses" data-bs-backdrop="static" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content tx-size-sm">
                    <div class="modal-body p-4">
                        <div class="form-group">
                            <label class="form-label">{{ lang('Canned Responses') }}<a href=""
                                    data-bs-target="#canned-add-responses" data-bs-toggle="modal"
                                    class="fw-semibold font-weight-semibold text-primary text-decoration-underline float-end"></a>
                                    <button  class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </label>
                            <select name="livechatPosition" id="cannedResponses"
                                class="form-control select2 select2-show-search" required>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
    <div class="liveChatImageViewer d-none">
        <span class="liveChatImageClose">×</span>
        <img class="liveChatImageTag" src="">
    </div>

    @include('admin.superadmindashboard.categorymodalpopup')
    @include('admin.superadmindashboard.prioritypopup')
    @include('admin.superadmindashboard.purchasecodedetails')
    @include('admin.modalpopup.assignmodal')
    @include('admin.superadmindashboard.texttospeachpopup')
    @include('admin.superadmindashboard.texttranslationpopup')
    <!-- Add note-->
    <div class="modal fade"  id="addnote" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" ></h5>
                    <button  class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data" id="note_form" name="note_form">
                    <input type="hidden" name="ticket_id" id="ticket_id">
                    @csrf
                    @honeypot
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="form-label">{{lang('Note:')}}</label>
                            <textarea class="form-control" rows="4" name="ticketnote" id="note" required></textarea>
                            <span id="noteError" class="text-danger alert-message"></span>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-outline-danger" data-bs-dismiss="modal">{{lang('Close')}}</a>
                        <button type="submit" class="btn btn-secondary" id="btnsave"  >{{lang('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End  Add note  -->
@endsection
@section('scripts')
    <!-- INTERNAL Web Socket -->
    <script domainName='{{ url('') }}' wsPort="{{ setting('liveChatPort') }}"
        src="{{ asset('build/assets/plugins/livechat/web-socket.js') }}"></script>

    @vite(['resources/assets/js/select2.js'])

    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{ asset('build/assets/plugins/sweet-alert/sweetalert.min.js') }}?v=<?php echo time(); ?>"></script>

    <script src="{{ asset('build/assets/plugins/typomaster/typojs.min.js') }}"></script>

    <script>

        var tickettypelocalstore = '{{ $tickettype }}';
        var notificationticketid = '{{ $ticketnotifyid }}';
        var ticketDeletePerm = @json(Auth::user()->can('Ticket Delete'));
        var ticketRestorePerm = @json(Auth::user()->can('Ticket Restore'));
        var ticketEditPerm = @json(Auth::user()->can('Ticket Edit'));

        // Function to update the state of the "Select All" checkbox
        function updateCustomCheckAll() {
            document.querySelector('.search-input').style.display = 'none';
            var totalCheckboxes = $('.checkall').length;
            var checkedCheckboxes = $('.checkall:checked').length;
            let chatUserTabList = document.querySelector('.chat-user-tab-list');

            if (checkedCheckboxes == 0) {
                $('#selectallbutton').hide();
                $('.search-container').show();
                chatUserTabList.classList.remove('show-checks');
            } else {
                $('#selectallbutton').show();
                $('.search-container').hide();
            }
            if (checkedCheckboxes === totalCheckboxes) {
                $('#customCheckAll').prop('checked', true);
            } else {
                $('#customCheckAll').prop('checked', false);
            }
        }

        var searchInput = document.querySelector('.search-input');
        function toggleSearch() {
            if (document.getElementById('search-chat-msg-scroll')) {
                document.getElementById('search-chat-msg-scroll').remove();
            }
            document.getElementById('chat-msg-scroll').classList.remove('d-none');
            document.getElementById('input-search').value = '';

            searchInput.style.display = (searchInput.style.display === 'none' || searchInput.style.display === '') ? 'block' : 'none';
            document.getElementById('input-search').focus();
        }

        function updateButton(text) {
            document.getElementById('agentSendMessage').value = text;
            let submitText = text == 'Inprogress' ? "{{ lang('Submit as Inprogress') }}" : "{{ lang('Submit as Solved') }}";
            document.getElementById('agentSendMessage').innerHTML = submitText;
        }

        // Live Time Update
        document.addEventListener('DOMContentLoaded', () => {

            let searchField = document.querySelector('#input-search');
            let completeData = @json($completeData);
            let debounceTimer;

            searchField.oninput = (e) =>{
                clearTimeout(debounceTimer); // Clear the previous timer if the user keeps typing

                debounceTimer = setTimeout(() => {
                    let searchValue = e.target.value.toLowerCase(); // To handle case insensitivity

                    if (searchValue.trim()) {
                        // Filter completeData based on search criteria
                        let filteredData = completeData.filter((item) => {
                            // Check if searchValue matches email or username
                            const isMatchInUser = item.message && item.message.toLowerCase().includes(searchValue) || item.subject && item.subject.toLowerCase().includes(searchValue) || item.ticket_id && item.ticket_id.toLowerCase().includes(searchValue) || item.cust.email && item.cust.email.toLowerCase().includes(searchValue) || item.cust.mobile_number && item.cust.mobile_number.toLowerCase().includes(searchValue) || item.cust.username && item.cust.username.toLowerCase().includes(searchValue);

                            // Check if searchValue matches any message in livechatconversation array
                            const comments = item.comments || []; // Default to empty array if livechatconversation is null or undefined
                            // Check each object in livechatconversation for a 'message' field
                            const isMatchInMessages = comments.some(chat => {
                                if (chat && typeof chat.comment === 'string') {
                                    return chat.comment.toLowerCase().includes(searchValue);
                                }
                                return false;
                            });

                            // Return true if either the user or message matches the searchValue
                            return isMatchInUser || isMatchInMessages;
                        });

                        // Sort the filtered data in descending order based on the 'id' field
                        filteredData.sort((a, b) => b.id - a.id);
                        document.getElementById('chat-msg-scroll').classList.add('d-none');

                        appendFilteredData(filteredData);
                    } else {
                        document.getElementById('chat-msg-scroll').classList.remove('d-none');
                        document.getElementById('search-chat-msg-scroll').classList.add('d-none');
                    }

                }, 500); // 500ms debounce
            }

            let appendFilteredData = (data) => {
                if (document.getElementById('search-chat-msg-scroll')) {
                    document.getElementById('search-chat-msg-scroll').remove();
                }

                let createdUl = document.createElement('ul')
                createdUl.className = 'list-unstyled mb-0 chat-user-tab-list chat-users-tab overflow-auto';
                createdUl.setAttribute('id', 'search-chat-msg-scroll')

                if (data.length > 0) {
                    const listItems = data.map(item => {
                        // Extract the initials for the avatar (e.g., first letter of the username)
                        const avatarLetter = item.cust.username.charAt(0).toUpperCase();
                        let lastMessageTime = item.comments.length > 0 ? item.comments[item.comments.length - 1].created_at : item.created_at;
                        let lastMessageMessage = item.comments.length > 0 ? item.comments[item.comments.length - 1].comment : item.message;

                        // Build the inner HTML for each <li> based on your template
                        return `

                            <li class="checkforactive" data-id="${item.id}">
                                <div class="d-flex align-items-start position-relative">
                                    <div class="me-2 lh-1 checkingpreventclick">
                                        <span class="avatar brround" style="background-color: ${item.cust.profile_bg_color}">
                                            <span class="new-chat-user-letter">${avatarLetter}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="flex-fill">
                                        <div class="mb-0 d-flex align-items-start justify-content-between">
                                            <div>
                                                <div class="font-weight-semibold">
                                                    <a href="javascript:void(0);" class="customer-name-change">${item.cust.username}</a>
                                                    <span class="badge badge-sm badge-success">#${item.ticket_id}</span>
                                                    <span class="badge badge-sm badge-danger-light overduestautsupdate${item.id} ${item.overduestatus == 'Overdue' ? '' : 'd-none'}">{{ lang('Overdue') }}</span>
                                                </div>
                                                <div class="font-weight-semibold">
                                                    <a href="javascript:void(0);">${item.message}</a>
                                                </div>
                                            </div>
                                            <div class="float-end text-muted fw-normal fs-12">${getTimeAgo(lastMessageTime)}</div>
                                            <div class="dropdown chat-actions lh-1 d-none">
                                                <a aria-label="anchor" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fe fe-more-vertical fs-18"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    ${tickettypelocalstore == 'trashedticketslocalstore' ?
                                                        (ticketDeletePerm || ticketRestorePerm ?
                                                            `<li>
                                                                <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="slectTicket(event, ${item.id})" data-id="${item.id}">
                                                                    <i class="ri-mail-check-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Select') }}
                                                                </a>
                                                            </li>`
                                                            :
                                                            ''
                                                        )
                                                        :
                                                        (ticketDeletePerm ?
                                                            `<li>
                                                                <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="slectTicket(event, ${item.id})" data-id="${item.id}">
                                                                    <i class="ri-mail-check-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Select') }}
                                                                </a>
                                                            </li>`
                                                            :
                                                            ''
                                                        )
                                                    }
                                                    <li>
                                                        <a href="https://localhost/projects/livachat/admin/livechat/addticketasUnread?id=${item.id}" class="dropdown-item markAsUnreadBtn">
                                                            <i class="ri-mail-unread-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>Mark As Unread
                                                        </a>
                                                    </li>
                                                    ${ticketDeletePerm ?
                                                        `
                                                        <li>
                                                            <a class="dropdown-item" deleteroutelink="${item.encryptId}" data-id="${item.id}" data-tickettype="${item.deleted_at != null ? 'trashed' : 'normal'}">
                                                                <i class="ri-delete-bin-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Delete') }}
                                                            </a>
                                                        </li>
                                                        ` : ''
                                                    }
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="chat-msg text-truncate fs-13 text-default custrecentmessage" data-id="100">${sanitizeMessage(lastMessageMessage)}</span>

                                        </div>
                                    </div>
                                </div>
                            </li>
                        `;
                    });
                    createdUl.innerHTML = listItems.join('');
                    document.querySelector('.search-section').appendChild(createdUl);

                    const liElements = createdUl.querySelectorAll('li');
                    liElements.forEach((li) => {
                        li.onclick = () => {
                            sideMenuOpenClickFunction(li);
                        };
                    });
                } else {
                    let noResults = document.createElement('div');
                    noResults.className = 'text-center mt-5 p-3 bg-warning-transparent text-default';
                    noResults.innerHTML = '{{ lang('No results for the keyword') }}'

                    createdUl.appendChild(noResults);

                    document.querySelector('.search-section').appendChild(createdUl);
                }
            }

            // Helper function to format time (e.g., "1 day ago")
            function getTimeAgo(time) {
                const timeDifference = new Date() - new Date(time);
                const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                return days > 0 ? `${days} day${days > 1 ? 's' : ''} ago` : 'Today';
            }

            function updateTime() {
                const timeElements = document.querySelectorAll('.chat-time');

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
    </script>

    <script>
        var optionFormat = function(item) {
            if (!item.id) {
                return item.text;
            }

            var span = document.createElement('span');
            var imgUrl = item.element.getAttribute('data-kt-select2-country');
            var template = '';

            template += '<img src="' + imgUrl + '" class="rounded-circle h-20px me-2" alt="image"/>';
            template += item.text;

            span.innerHTML = template;

            return $(span);
        }

        $('#reassignSelect').select2({
            templateSelection: optionFormat,
            templateResult: optionFormat,
            placeholder: "Reassign to:",
            allowClear: true,
            dropdownParent: $('#staticBackdrop')
        });

        // store the range
        let storedRange;
        function storeRange() {
            let message = document.querySelector('#auto-expand');
            const selection = document.getSelection();
            if (selection?.rangeCount) {
                let selected = selection.getRangeAt(0).commonAncestorContainer;
                if (message.contains(selected)) {
                    // Storing the range
                    storedRange = selection.getRangeAt(0);
                } else {
                    let newRng = document.createRange();
                    newRng.setStart(message, message.childNodes.length); // Start at the end of the content
                    newRng.setEnd(message, message.childNodes.length);
                    selection.removeAllRanges();
                    selection.addRange(newRng);
                    storedRange = selection.getRangeAt(0);
                }
            } else {
                let newRng = document.createRange();
                newRng.setStart(message, message.childNodes.length); // Start at the end of the content
                newRng.setEnd(message, message.childNodes.length);
                selection.removeAllRanges();
                selection.addRange(newRng);
                storedRange = selection.getRangeAt(0);
            }
        }

        function retrieveRange() {
            // Used to retrive the stored range value
            if (storedRange) {
                return storedRange;
            }
        }

        let newVal = "";
        // Get the Emojis Values
        function insertEmoji(emoji) {
            if (!storedRange) return;

            const textNode = document.createTextNode(emoji);
            storedRange.insertNode(textNode);

            // Move the caret after the inserted emoji
            let selection = document.getSelection();
            let newRng = document.createRange();
            newRng.setStartAfter(textNode);
            newRng.setEndAfter(textNode);
            selection.removeAllRanges();
            selection.addRange(newRng);

            // Update the stored range to the new position
            storedRange = newRng;
            document.getElementById("emojiGrid").classList.toggle('d-block');
        }

        // Adding the Emojis
        document.addEventListener("DOMContentLoaded", function() {
            let allEmojisBtn = document.querySelector('.allEmojisBtn');
            allEmojisBtn.addEventListener('click', ()=>{
                storeRange();
            })

            const emojiGrid = document.getElementById("emojiGrid");

            // Emojis data
            const emojisData = ['😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌',
                '😍', '🥰', '😘', '😗', '😙', '😚', '😋', '😛', '😜', '🤪', '😝', '🤑', '🤗', '🤭', '🤫', '🤔',
                '🤐', '🤨', '😐', '😑', '😶', '😏', '😒', '🙄', '😬', '😮', '😯', '😦', '😧', '😨', '😰', '😱',
                '😳', '😵', '😡', '😠', '😤', '😖', '😆', '😋', '😷', '😎', '🤓', '🤠', '😸', '😺', '😻', '😼',
                '😽', '🙀', '😿', '😾', '👐', '🙌', '👏', '🤝', '👍', '👎', '👊', '✊', '🤛', '🤜', '🤞', '✌️',
                '🤘', '👌', '👈', '👉', '👆', '👇', '✋', '🤚', '🖐', '🖖', '👋', '🤙', '💪', '🖕', '✍️', '🙏',
                '🦶', '🦵', '💍', '💔', '❤️', '💙', '💚', '💛', '💜', '🧡', '💔', '❤️', '💕', '💞', '💓', '💗',
                '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉',
                '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋',
                '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢',
                '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇',
                '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️',
                '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️',
                '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊',
                '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘',
                '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪',
                '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝',
                '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥',
                '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘',
                '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞',
                '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩',
                '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏',
                '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟',
                '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉',
                '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍',
                '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦',
                '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟',
                '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗',
                '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉',
                '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋',
                '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢',
                '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇',
                '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️',
                '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️',
                '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊',
                '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘',
                '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪',
                '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝',
                '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥',
                '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈'
            ];

            let emojis = [...new Set(emojisData)]

            // Number of emojis per row
            const emojisPerRow = 8;

            // Generate emoji grid
            for (let i = 0; i < emojis.length; i++) {
                if (i % emojisPerRow === 0) {
                    // Start a new row
                    const newRow = document.createElement("li");
                    newRow.className = "d-flex";
                    emojiGrid.appendChild(newRow);
                }

                // Create emoji element
                const col = document.createElement("span");
                col.style.padding = "0.5rem"
                col.classList.add("dropdown-item", ); // Adjust the grid layout as needed
                col.onclick = () => {
                    insertEmoji(emojis[i])
                }
                col.innerHTML = `${emojis[i]}`;

                // Append emoji to the current row
                const currentRow = emojiGrid.lastElementChild;
                document.querySelector("#agentSendMessage").disabled = false
                document.querySelector("#agentSendMessage").classList.remove('disabled')
                currentRow.appendChild(col);

            }
        })

        // delete note dunction
        function deletePost(event) {
            var id  = $(event).data("id");

            let _token   = $('meta[name="csrf-token"]').attr('content');

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
                        url: `{{url('/admin/ticketnote/delete/${id}')}}`,
                        type: 'DELETE',
                        data: {
                        _token: _token
                        },
                        success: function(response) {
                            toastr.success(response.success);
                            $("#ticketnote_"+id).remove();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });
        }

        // To add option the Cannedmessages to model
        @if ($alltickets->isNotEmpty())
            $.ajax({
                type: "get",
                url: `{{ route('admin.getticketCannedmessages') }}?id=${localStorage.getItem(tickettypelocalstore)}`,
                success: function(data) {
                    if (data.success == true) {
                        var selectElement = document.getElementById('cannedResponses');
                        selectElement.innerHTML = "<option></option>"
                        const cannedmessages = data.message.cannedmessages;
                        if(cannedmessages){
                            Object.values(cannedmessages).forEach(function(option) {
                                var optionElement = document.createElement('option');
                                optionElement.value = option.messages;
                                optionElement.textContent = option.title;
                                selectElement.appendChild(optionElement);
                            });

                            $(document).ready(function() {
                                $('#cannedResponses').select2({
                                    placeholder: "Canned Responses",
                                    allowClear: true,
                                    dropdownParent: $('#canned-responses')
                                });
                            });
                        }

                    }
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        @endif

        let elementToToggle = null;

        function openModal(event) {
            elementToToggle = event.target;
            $('#canned-responses').modal('show');
        }

        $('#canned-responses .modalclosecanned #sprukocategory_form #categorybtnsave').on('click', function() {
            elementToToggle.checked = !elementToToggle.checked;
        })

        // To add value in the text area
        document.getElementById('cannedResponses').onchange = (ele => {
            document.querySelector("#auto-expand").innerHTML = document.querySelector("#auto-expand").innerHTML + ele.target.value
            // to remove the disabled from the Message send btn
            document.querySelector("#agentSendMessage").removeAttribute('disabled')
            document.querySelector("#agentSendMessage").classList.remove('disabled')

            // To Close the Model
            $('#canned-responses').modal('toggle');
            // $('#cannedResponses').select2('destroy');
            $('#cannedResponses').select2({
                placeholder: {
                    id: '',
                    text: 'Canned Responses'
                },
                // allowClear: true
            });



            const divcontent = document.querySelector("#auto-expand");

            divcontent.style.height = "auto";
            divcontent.style.height = Math.min(divcontent.scrollHeight, 200) + "px";
            divcontent.scrollTop = 0;

            divcontent.focus();
            let selection = window.getSelection();
            let rng = selection.getRangeAt(0);
            let newRng = document.createRange();
            newRng.setStartAfter(divcontent.lastChild)
            newRng.setEndAfter(divcontent.lastChild)
            selection.removeAllRanges();
            selection.addRange(newRng);



        })

        const textarea = document.getElementById("auto-expand");
        textarea.addEventListener("input", () => {
            textarea.style.height = "auto";
            textarea.style.height = Math.min(textarea.scrollHeight, 200) + "px";
            textarea.scrollTop = 0;
        });

        let spellCheckEnabled = @json(setting('spellCheck'));
        let suggestionsEnabled = @json(setting('wordSuggestion'));

        if (spellCheckEnabled != 'off' || suggestionsEnabled != 'off') {

            let previousContent = "";
            let debounceTime;
            let latestInput = "";

            let userText;
            // Input event to process the text
            textarea.addEventListener("input", function () {
                if(document.getElementById('suggestionText')){
                    document.getElementById('suggestionText').remove()
                }
                userText = textarea.innerText.trim();
                const currentSelection = window.getSelection();
                const cursorAtEnd = currentSelection.rangeCount > 0 && currentSelection.getRangeAt(0).endOffset > 0 && textarea.innerText.length > 0;

                // Fetch only when cursor is at the end and content has changed
                if (cursorAtEnd && userText !== previousContent) {
                    previousContent = userText;
                    clearTimeout(debounceTime);

                    debounceTime = setTimeout(() => {
                        if (userText.length > 0) {
                            fetchCombinedRequest(userText);
                        }
                    }, 1000);
                } else {
                    if(document.getElementById('suggestionText')){
                        document.getElementById('suggestionText').innerText = "";
                    }
                }
            });

            async function fetchCombinedRequest(userInput) {
                try {
                    let url = '{{ route('geminiGet') }}';
                    const response = await fetch(url, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ value: userInput }),
                    });

                    if (!response.ok) throw new Error("Failed to fetch");

                    const data = await response.json();

                    handleCombinedResponse(data.generated_text);
                } catch (error) {
                    console.error("Error fetching data:", error);
                }
            }

            // Function to handle combined response
            function handleCombinedResponse(responseText) {
                if (spellCheckEnabled == 'on' && suggestionsEnabled == 'on') {
                    const spellCheckResponse = parseApiResponse(responseText); // Parse spell check response
                    const suggestionsResponse = responseText.match(/suggestion:\s*([a-zA-Z\s]+)/)?.[1];
                    if (spellCheckResponse) {
                        applyCorrections(spellCheckResponse);
                        placeCaretAtEnd();
                    }

                    // Display suggestions
                    if (suggestionsResponse) {
                        displaySuggestion(suggestionsResponse);
                    }
                } else if (spellCheckEnabled == 'on' && suggestionsEnabled == 'off') {
                    const correctedText = parseApiResponse(responseText);
                    applyCorrections(correctedText);
                    placeCaretAtEnd();
                } else if (suggestionsEnabled == 'on') {
                    displaySuggestion(responseText.trim());
                }
            }

            function parseApiResponse(responseText) {
                const corrections = {};
                let formattedResponse = responseText.replace(/['"]+/g, "");
                formattedResponse = formattedResponse.replace(/\n/g, ",");
                const pairs = formattedResponse.split(",");
                pairs.forEach(pair => {
                    const [misspelled, corrected] = pair.split(":").map(s => s.trim());
                    if (misspelled && corrected && misspelled != corrected) {
                        corrections[misspelled] = corrected;
                    }
                });
                return corrections;
            }

            function applyCorrections(corrections) {
                let htmlContent = textarea.innerHTML;
                Object.entries(corrections).forEach(([misspelled, corrected]) => {
                    const regex = new RegExp(`\\b${escapeRegExp(misspelled)}\\b`, 'gi');
                    htmlContent = htmlContent.replace(regex,
                        `<span class="misspelled" data-corrected="${corrected}">${misspelled}</span>`
                    );
                });
                textarea.innerHTML = htmlContent;
                placeCaretAtEnd();
            }

            function displaySuggestion(responseApi) {
                if(document.getElementById('suggestionText')){
                    document.getElementById('suggestionText').remove();
                }

                var suggestionTextData = document.createElement('span');
                suggestionTextData.setAttribute('id','suggestionText');
                suggestionTextData.innerHTML = responseApi.trim();

                const selection = window.getSelection();
                if (!selection || !selection.rangeCount) {
                    return;
                }
                if (userText == textarea.innerText.trim()) {
                    document.getElementById('auto-expand').append(suggestionTextData)
                }
            }
            textarea.addEventListener('keydown', function (e) {
                if (e.key === "Tab") {
                    e.preventDefault();

                    const suggestionTextData = document.getElementById('suggestionText');
                    if (suggestionTextData && suggestionTextData.innerText.trim().length > 0) {
                        let userInput = textarea.innerText.trim();
                        let suggestion = suggestionTextData.innerText.trim();
                        if (userInput.endsWith(suggestion)) {
                            userInput = userInput.slice(0, -suggestion.length).trim();
                        }
                        textarea.innerText = `${userInput} ${suggestion}`;
                        suggestionTextData.innerText = '';
                        if (suggestionTextData) {
                            suggestionTextData.remove();
                        }
                        placeCaretAtEnd();
                    }
                }
            });

            function escapeRegExp(string) {
                return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }

            document.body.addEventListener('mouseover', function (event) {
                const element = event.target;
                if (element.classList.contains('misspelled')) {
                    showTooltip(event, element);
                }
            });

            document.body.addEventListener('mouseout', function (event) {
                const element = event.target;
                const relatedTarget = event.relatedTarget;

                if (element.classList.contains('misspelled')) {
                    if (relatedTarget && relatedTarget.classList.contains('correctedtooltip')) {
                        return;
                    }
                    hideTooltip();
                } else if (element.classList.contains('correctedtooltip')) {
                    if (relatedTarget && relatedTarget.classList.contains('misspelled')) {
                        return;
                    }
                    hideTooltip();
                }
            });

            function showTooltip(event, element) {
                const correctedWord = element.getAttribute('data-corrected');
                hideTooltip();

                const tooltip = document.createElement('span');
                tooltip.className = 'correctedtooltip';
                tooltip.textContent = correctedWord;

                // Styling
                tooltip.style.backgroundColor = '#fff';
                tooltip.style.border = '1px solid #ccc';
                tooltip.style.padding = '5px';
                tooltip.style.zIndex = '1000';
                tooltip.style.boxShadow = '0px 2px 8px rgba(0, 0, 0, 0.2)';
                tooltip.style.borderRadius = '4px';

                element.parentNode.insertBefore(tooltip, element.nextSibling);

                tooltip.onclick = (e) => {
                    e.stopPropagation();
                    replaceWord(element, correctedWord);
                };

                element.tooltip = tooltip;
            }

            function hideTooltip() {
                const existingTooltip = document.querySelector('.correctedtooltip');
                if (existingTooltip) {
                    existingTooltip.remove();
                }
            }

            function replaceWord(element, correctedWord) {
                const parent = element.parentElement;
                if(parent.classList.contains('misspelled')){
                    parent.classList.remove('misspelled');
                }
                parent.innerHTML = parent.innerHTML.replace(
                    new RegExp(`<span[^>]*>${element.textContent}</span>`, 'gi'),
                    correctedWord
                );
                hideTooltip();
                placeCaretAtEnd()
            }

            function placeCaretAtEnd() {
                setTimeout(() => {
                    textarea.focus();
                    const range = document.createRange();
                    const selection = window.getSelection();
                    range.selectNodeContents(textarea);
                    range.collapse(false);
                    selection.removeAllRanges();
                    selection.addRange(range);
                }, 1);
            }
        }

        // When User try to Delete the chat to ask the are you sure alert
        function deletingticfunction(){
            document.querySelectorAll("[deleteroutelink]").forEach((ele) => {
                ele.onclick = () => {

                    let deleteurl;
                    let methodroute;
                    if(ele.getAttribute("data-tickettype") == 'trashed'){
                        deleteurl = SITEURL + "/admin/tickettrasheddestroy/"+ele.getAttribute("deleteroutelink");
                        methodroute = "post";
                    }else{
                        deleteurl = SITEURL + "/admin/delete-ticket/"+ele.getAttribute("deleteroutelink");
                        methodroute = "get";
                    }
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
                                type: methodroute,
                                url: deleteurl,
                                success: function (data) {
                                    toastr.success(data.success);

                                    var activecustId = document.querySelector(`.checkforactive.active`)?.getAttribute("data-id");
                                    var deletecustId = ele.closest('.checkforactive')?.getAttribute("data-id");
                                    document.querySelector(`.checkforactive[data-id="${deletecustId}"]`)?.remove()
                                    if(activecustId == deletecustId){
                                        localStorage.removeItem(tickettypelocalstore);
                                        document.querySelector(`#operator-conversation-Info[data-id="${deletecustId}"]`)?.remove()
                                        document.querySelector(`#operator-conversation[operator-id="${deletecustId}"]`)?.remove()
                                        document.querySelector(".chat-footer")?.classList.add("d-none")
                                        if(document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")){
                                            sideMenuOpenClickFunction(document.querySelector(`.checkforactive[data-id="${document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")}"]`))
                                        }else{
                                            let emptyconver = document.createElement("div");
                                            emptyconver.className = "text-center mt-5 px-2 py-4 bg-warning-transparent text-default mx-auto w-90 rounded-2";
                                            emptyconver.innerHTML = `<span>{{ lang('As of now, there are no chat discussions in progress') }}</span>`;
                                            document.querySelector("#chat-msg-scroll").append(emptyconver);
                                            document.querySelector(".no-articles").classList.remove("d-none");
                                        }
                                    }
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                    });
                }
            })
        }
        deletingticfunction();

        // When User try to restore the ticket data
        function restoringticfunction(){
            document.querySelectorAll("[restoreRouteLink]").forEach((ele) => {
                ele.onclick = () => {
                    swal({
                        title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                        text: "{{lang('This might restore your record', 'alerts')}}",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "post",
                                url: SITEURL + "/admin/tickettrashedrestore/"+ele.getAttribute("restoreRouteLink"),
                                success: function (data) {
                                    toastr.success(data.success);

                                    var activecustId = document.querySelector(`.checkforactive.active`)?.getAttribute("data-id");
                                    var deletecustId = ele.closest('.checkforactive')?.getAttribute("data-id");
                                    document.querySelector(`.checkforactive[data-id="${deletecustId}"]`)?.remove()
                                    if(activecustId == deletecustId){
                                        localStorage.removeItem(tickettypelocalstore);
                                        document.querySelector(`#operator-conversation-Info[data-id="${deletecustId}"]`)?.remove()
                                        document.querySelector(`#operator-conversation[operator-id="${deletecustId}"]`)?.remove()
                                        document.querySelector(".chat-footer")?.classList.add("d-none")
                                        if(document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")){
                                            sideMenuOpenClickFunction(document.querySelector(`.checkforactive[data-id="${document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")}"]`))
                                        }else{
                                            let emptyconver = document.createElement("div");
                                            emptyconver.className = "text-center mt-5 px-2 py-4 bg-warning-transparent text-default mx-auto w-90 rounded-2";
                                            emptyconver.innerHTML = `<span>{{ lang('As of now, there are no chat discussions in progress') }}</span>`;
                                            document.querySelector("#chat-msg-scroll").append(emptyconver);
                                            document.querySelector(".no-articles").classList.remove("d-none");
                                        }
                                    }
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                    });
                }
            })
        }
        restoringticfunction();

        // TICKET DELETE SCRIPT
        $('body').on('click', '#show-delete', function () {
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
                        url: SITEURL + "/admin/delete-ticket/"+_id,
                        success: function (data) {
                            toastr.success(data.success);
                            location.replace('{{route('admin.dashboard')}}');
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });

        });

        // For the Chat Btn
        document.querySelector("#chat-btn").onclick = () => {
            event.currentTarget.querySelector("i").classList.add("text-primary")
            document.querySelector(".chat-reply-area #create-ticket-btn i")?.classList.remove("text-primary")
            document.querySelector(".chat-reply-area .create-ticket").classList.add("d-none")
            document.querySelector("#assigneediv").classList.add("d-none")
            document.querySelector(".chat-reply-area #auto-expand").classList.add("border-0")
            document.querySelector(".chat-reply-area #auto-expand").classList.remove("border-1", "my-2")
            document.querySelector("#agentSendMessage").classList.remove("d-none")
            document.querySelector(".create-ticket-description").classList.add("d-none")


            // For the category
            document.querySelector("#category").closest('.form-group').classList.add("d-none")
            document.querySelector("#envatopurchase").classList.add("d-none")
        }

        // Enter Subject keyUp check
        document.getElementById('livechatTicketSubject').addEventListener('keyup', function(event) {
            let envatoId = true

            if (document.querySelector("#envato_id")) {
                envatoId = document.querySelector("#envato_id").getAttribute("readonly") == 'readonly'
            }
        });

        // category list
        $('body').on('click', '.sprukocategory', function(event) {
            elementToToggle = event.target;
            var category_id = $(this).data('id');
            $('.modal-title').html(`{{ lang('Category', 'menu') }}`);
            $('#CategoryError').html('');
            $('#addcategory').modal('show');
            $.ajax({
                type: "get",
                url: SITEURL + "/admin/category/list/" + category_id,
                success: function(data) {

                    $('.select4-show-search').select2({
                        dropdownParent: ".sprukosearchcategory",
                    });
                    $('.subcategoryselected').select2({
                        dropdownParent: ".sprukosearchcategory",
                    });
                    $('#sprukocategorylists').html(data.table_data);
                    $('.ticket_id').val(data.ticket.id);

                    @if (setting('ENVATO_ON') == 'on')
                        if (data.ticket.purchasecode != null && data.envatoassignstatus ==
                            'assignedtoenvato') {
                            $('#envato_id').val('');
                            $('#envato_id')?.empty();
                            $('#envatopurchasedid .row')?.remove();
                            let selectDiv = document.querySelector('#envatopurchasedid');
                            let Divrow = document.createElement('div');
                            Divrow.setAttribute('class', 'row');
                            let Divcol3 = document.createElement('div');
                            let selectlabel = document.createElement('label');
                            let span = document.createElement('span');
                            span.setAttribute('class', 'text-danger');
                            span.innerHTML = "*";
                            selectlabel.setAttribute('class', 'form-label')
                            selectlabel.innerHTML = "{{ lang('Envato Purchase Code ') }}";
                            let divcol9 = document.createElement('div');
                            let selecthSelectTag = document.createElement('input');
                            selecthSelectTag.setAttribute('class', 'form-control');
                            selecthSelectTag.setAttribute('type', 'search');
                            selecthSelectTag.setAttribute('id', 'envato_id');
                            selecthSelectTag.setAttribute('name', 'envato_id');
                            selecthSelectTag.setAttribute('placeholder',
                                '{{ lang('Update Your Purchase Code') }}');
                            let selecthSelectInput = document.createElement('input');
                            selecthSelectInput.setAttribute('type', 'hidden');
                            selecthSelectInput.setAttribute('id', 'envato_support');
                            selecthSelectInput.setAttribute('name', 'envato_support');
                            let envatoError = document.createElement('span');
                            envatoError.setAttribute('id', 'envatoError');
                            envatoError.setAttribute('class', 'text-danger alert-message');
                            selectDiv.append(Divrow);
                            Divrow.append(Divcol3);
                            Divcol3.append(selectlabel);
                            selectlabel.append(span);
                            divcol9.append(selecthSelectTag);
                            divcol9.append(selecthSelectInput);
                            Divrow.append(divcol9);
                            Divrow.append(envatoError);
                        }
                    @endif

                    if (data.ticket.subcategory != null) {

                        $('#selectssSubCategorydata').show()
                        $('#subscategory').html(data.subcategoryt);

                    } else {


                        if (!data.subcategoryt) {
                            $('#selectssSubCategorydata').hide();
                        } else {
                            $('#selectssSubCategorydata').show()
                            $('#subscategory').html(data.subcategoryt);
                        }
                    }
                    if (data.subCatStatus.length === 0) {
                        $('#selectssSubCategorydata').hide();
                    }


                },
                error: function(data) {

                }
            });
        });

        // when category change its get the subcat list
        $('body').on('change', '#sprukocategorylists', function(e) {
            var cat_id = e.target.value;
            $('#selectssSubCategorydata').hide();
            $.ajax({
                url:"{{ route('guest.subcategorylist') }}",
                type:"POST",
                    data: {
                    cat_id: cat_id
                    },
                success:function (data) {
                    if(data.subcategoriess){
                        $('#selectssSubCategorydata').hide()
                        $('.subcategoryselected').html(data.subcategoriess)
                        $('#selectssSubCategorydata').show()
                    }
                    else{
                        $('#selectssSubCategorydata').hide();
                        $('.subcategoryselected').html('')
                    }

                    if(data.subCatStatus.length === 0){
                        $('#selectssSubCategorydata').hide();
                    }

                    @if(setting('ENVATO_ON') == 'on')
                    // Envato access
                    if(data.envatosuccess.length >= 1){
                        $('#envato_idd').val('');
                        $('#envato_idd')?.empty();
                        $('#hideelement').addClass('d-none');
                        $('#envatopurchasedid .row')?.remove();
                        let selectDiv = document.querySelector('#envatopurchasedid');
                        let Divrow = document.createElement('div');
                        Divrow.setAttribute('class','row');
                        let Divcol3 = document.createElement('div');
                        let selectlabel =  document.createElement('label');
                        selectlabel.setAttribute('class','form-label')
                        selectlabel.innerHTML = "{{lang('Envato Purchase Code')}}";
                        let span =  document.createElement('span');
                        span.setAttribute('class','text-danger');
                        span.innerHTML = "*";
                        let divcol9 = document.createElement('div');
                        let selecthSelectTag =  document.createElement('input');
                        selecthSelectTag.setAttribute('class','form-control');
                        selecthSelectTag.setAttribute('type','search');
                        selecthSelectTag.setAttribute('id', 'envato_idd');
                        selecthSelectTag.setAttribute('name', 'envato_id');
                        selecthSelectTag.setAttribute('placeholder', 'Enter Your Purchase Code');
                        let selecthSelectInput =  document.createElement('input');
                        selecthSelectInput.setAttribute('type','hidden');
                        selecthSelectInput.setAttribute('id', 'envato_support');
                        selecthSelectInput.setAttribute('name', 'envato_support');
                        let envatoError = document.createElement('span');
                        envatoError.setAttribute('id', 'envatoError');
                        envatoError.setAttribute('class', 'text-danger alert-message');
                        selectDiv.append(Divrow);
                        Divrow.append(Divcol3);
                        Divcol3.append(selectlabel);
                        selectlabel.append(span);
                        divcol9.append(selecthSelectTag);
                        divcol9.append(selecthSelectInput);
                        Divrow.append(divcol9);
                        Divrow.append(envatoError);
                        $('.purchasecode').attr('disabled', true);

                    }else{
                        $('#envato_idd').val('');
                        $('#envato_idd')?.empty();
                        $('#hideelement').addClass('d-none');
                        $('#envatopurchasedid .row')?.remove();
                        $('.purchasecode').removeAttr('disabled');
                    }
                    @endif
                }
            })
        });

        var licensekeyy;

        $("body").on('keyup', '#envato_idd', function() {
            let value = $(this).val();
            if (value != '') {
                if(value.length == '36'){
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('guest.envatoverify') }}",
                        method: "POST",
                        data: {data: value, _token: _token},

                        dataType:"json",

                        success: function (data) {
                            if(data.valid == 'true'){
                                $('#envato_idd').addClass('is-valid');
                                $('#envato_idd').attr('readonly', true);
                                $('.purchasecode').removeAttr('disabled');
                                $('#envato_idd').css('border', '1px solid #02f577');
                                $('#envato_support').val('Supported');
                                $('#expired_note').addClass('d-none');
                                licensekeyy = data.key
                                toastr.success(data.message);
                            }
                            if(data.valid == 'expried'){
                                @if(setting('ENVATO_EXPIRED_BLOCK') == 'on')

                                    $('.purchasecode').attr('disabled', true);
                                    $('#envato_idd').css('border', '1px solid #e13a3a');
                                    $('#envato_support').val('Expired');
                                    $('#expired_note').removeClass('d-none');
                                    toastr.error(data.message);
                                @endif
                                @if(setting('ENVATO_EXPIRED_BLOCK') == 'off')
                                    $('#envato_idd').addClass('is-valid');
                                    $('#envato_idd').attr('readonly', true);
                                    $('.purchasecode').removeAttr('disabled');
                                    $('#envato_idd').css('border', '1px solid #02f577');
                                    $('#expired_note123').removeClass('d-none');
                                    $('#envato_support').val('Expired');
                                    licensekeyy = data.key
                                    toastr.warning(data.message);
                                @endif

                            }
                            if(data.valid == 'false'){
                                $('.purchasecode').attr('disabled', true);
                                $('#envato_idd').css('border', '1px solid #e13a3a');
                                toastr.error(data.message);
                            }


                        },
                        error: function (data) {

                        }
                    });
                }
            }else{
                toastr.error('Purchase Code field is Required');
                $('.purchasecode').attr('disabled', true);
                $('#envato_id').css('border', '1px solid #e13a3a');
            }
        });

        // category submit form
        $('body').on('submit', '#sprukocategory_form', function(e) {
            e.preventDefault();
            var actionType = $('#pribtnsave').val();
            var fewSeconds = 2;
            $('#categorybtnsave').html('Sending ... <i class="fa fa-spinner fa-spin"></i>');
            var formData = new FormData(this);
            formData.set('envato_id', licensekeyy);
            $.ajax({
                type: 'POST',
                url: SITEURL + "/admin/category/change",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,

                success: (data) => {

                    if (data?.error) {
                        $('#categorybtnsave').html('{{ lang('Save Changes') }}');
                        toastr.error(data.error);
                    } else {
                        $('#CategoryError').html('');
                        $('#sprukocategory_form').trigger("reset");
                        $('#addcategory').modal('hide');
                        $('#pribtnsave').html('{{ lang('Save Changes') }}');
                        toastr.success(data.success);
                        $('#categorybtnsave').html('{{ lang('Save Changes') }}');
                    }
                },
                error: function(data) {
                    console.log('errorData',data);
                    if(data?.responseJSON?.errors){
                        $('#CategoryError').html('');
                        $('#CategoryError').html(data.responseJSON.errors.category);
                        $('#categorybtnsave').html('{{ lang('Save Changes') }}');
                    }
                    if(data?.responseJSON?.message == 'envatoerror'){
                        $('#envatoError').html(data.responseJSON.error);
                        $('#categorybtnsave').html('{{ lang('Save Changes') }}');
                    }
                    if(data?.responseJSON?.message == 'subcaterror'){
                        $('#subsCategoryError').html(data.responseJSON.error);
                        $('#categorybtnsave').html('{{ lang('Save Changes') }}');
                    }
                }
            });
        });


        $('body').on('click', '#purchasecodebtn', function(){
            var envatopurchase_id = $(this).data('id');

            @if(!empty(Auth::user()->getRoleNames()[0]) && Auth::user()->getRoleNames()[0] == 'superadmin')
            var envatopurchase_i = envatopurchase_id;

            @else
                @if(setting('purchasecode_on') == 'on')
                var envatopurchase_i = envatopurchase_id;
                @else
                var trailingCharsIntactCount = 4;

                var envatopurchase_i = new Array(envatopurchase_id.length - trailingCharsIntactCount + 1).join('*') + envatopurchase_id.slice( -trailingCharsIntactCount);
                @endif
            @endif


            $('.modal-title').html('Purchase Details');
            $('.purchasecode').html(envatopurchase_i);
            $('#addpurchasecode').modal('show');
            $('#purchasedata').html('');

            $.ajax({
                url:"{{ route('admin.ticketlicenseverify') }}",
                type:"POST",
                data: {
                    envatopurchase_id: envatopurchase_id
                },
                success:function (data) {
                    $('#purchasedata').html(data.output);
                },
                error:function(data){
                    $('#purchasedata').html('');
                }

            });
        });

        // change priority
        $('body').on('click', '#changePriority', function(event) {
            elementToToggle = event.target;
            var ticketid = $(this).data('ticketid');
            var ticketpriority = $(this).data('priority');
            $('#PriorityError').html('');
            $('#btnsave').val("save");
            $('#priority_form').trigger("reset");
            $('.modal-title').html(`{{ lang('Priority') }}`);
            $('#addpriority').modal('show');
            $('.select2_modalpriority').select2({
                dropdownParent: ".sprukopriority",
                minimumResultsForSearch: '',
                placeholder: "Search",
                width: '100%'
            });
            $('#priority_id').val(ticketid);
            $('.selectingpriority').empty();
            var priorities = ['Low', 'Medium', 'High', 'Critical'];

            $.each(priorities, function(index, priority) {
                if (ticketpriority !== 'nopriority' && ticketpriority === priority) {
                    $('.selectingpriority').append('<option value="' + priority + '" selected>' + priority +
                        '</option>')
                } else {
                    $('.selectingpriority').append(
                        '<option label="{{ lang('Select Priority') }}"></option>')
                    $('.selectingpriority').append('<option value="' + priority + '">' + priority +
                        '</option>')
                }
            });

        });

        $('body').on('submit', '#priority_form', function(e) {
            e.preventDefault();
            var actionType = $('#pribtnsave').val();
            var fewSeconds = 2;
            $('#pribtnsave').html('Sending ... <i class="fa fa-spinner fa-spin"></i>');
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: SITEURL + "/admin/priority/change",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,

                success: (data) => {
                    $('#PriorityError').html('');
                    $('#priority_form').trigger("reset");
                    $('#addpriority').modal('hide');
                    $('#pribtnsave').html('{{ lang('Save Changes') }}');
                    toastr.success(data.success);


                },
                error: function(data) {
                    $('#PriorityError').html('');
                    $('#PriorityError').html(data.responseJSON.errors.priority_user_id);
                    $('#pribtnsave').html('{{ lang('Save Changes') }}');
                }
            });
        });
        // end priority

        // category list
        $('body').on('click', '#directinprogress', function() {
            var _id = $(this).data("id");
            swal({
                    title: `{{ lang('Are you sure you want to change the status to Inprogress?', 'alerts') }}`,
                    text: "{{ lang('You won’t be able to revert this!', 'alerts') }}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "post",
                            url: SITEURL + "/admin/adminticketinprogressing/" + _id,
                            success: function(data) {
                                toastr.success(data.success);
                            },
                            error: function(data) {
                                toastr.error(data.responseJSON.error);
                            }
                        });
                    }
                });
        });

        $('body').on('click', '#directsolvedticinfo', function() {
            var _id = $(this).data("id");
            swal({
                    title: `{{ lang('Are you sure you want to change the status to solved?', 'alerts') }}`,
                    text: "{{ lang('You won’t be able to revert this! and it is moving to solved tickets.', 'alerts') }}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "post",
                            url: SITEURL + "/admin/adminticketclosing/" + _id,
                            success: function(data) {
                                toastr.success(data.success);
                            },
                            error: function(data) {
                                toastr.error(data.responseJSON.error);
                            }
                        });
                    }
                });
        });

        // when user click its get modal popup to assigned the ticket
        $('body').on('click', '#assigned', function() {
            var assigned_id = $(this).data('id');
            $('.select2_modalassign').select2({
                dropdownParent: ".sprukosearch",
                minimumResultsForSearch: '',
                placeholder: "Search",
                width: '100%'
            });

            $.get('ticket-view/ticketassigneds/' + assigned_id, function(data) {
                $('#AssignError').html('');
                $('#assigned_id').val(data.assign_data.id);
                $(".modal-title").text('Assign To Agent');
                $('#username').html(data.table_data);
                $('#addassigned').modal('show');
            });

        });

        // Assigned Button submit
        $('body').on('submit', '#assigned_form', function(e) {
            e.preventDefault();
            var actionType = $('#assignbtnsave').val();
            var fewSeconds = 2;
            $('#assignbtnsave').html('Sending ... <i class="fa fa-spinner fa-spin"></i>');
            $('#assignbtnsave').prop('disabled', true);
            setTimeout(function() {
                $('#assignbtnsave').prop('disabled', false);
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
                    $('#assignbtnsave').html('{{ lang('Save Changes') }}');
                    toastr.success(data.success);
                },
                error: function(data) {
                    $('#AssignError').html('');
                    if(data?.responseJSON?.message == 'sameuser'){
                        $('#AssignError').html(data.responseJSON.error);
                    }else{
                        $('#AssignError').html("The assigned agent field is required");
                    }
                    // $('#AssignError').html(data.responseJSON.errors.assigned_user_id);
                    $('#assignbtnsave').html('{{ lang('Save Changes') }}');

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
                                toastr.success(data.success);
                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
        });

        // Function to update the visibility of the mass delete button
        function updateMassDeleteVisibility() {
            if ($('.checkall:checked').length == 0) {
                $('#massdelete').hide();
            } else {
                $('#massdelete').show();
            }
        }

        // Function to update the visibility of the mass restore button
        function updateMassRestoreVisibility() {
            if ($('.checkall:checked').length === 0) {
                $('#massrestore').hide();
            } else {
                $('#massrestore').show();
            }
        }

        // Handle parent li clicks
        document.querySelectorAll(".checkforactive").forEach((li) => {
            li.addEventListener('click', function(event) {
                // To Stop the Notification Sound in the SideBar Click
                if (li.getAttribute("data-id") == newMessageSoundCurrentAudio && newMessageSoundCurrentAudio.cusumerId && !li.classList.contains('active')) {
                    newMessageSoundCurrentAudio.pause()
                    clearInterval(intervalId);
                }

                sideMenuOpenClickFunction(li)
            });
        });

        // Handle checkbox clicks
        document.querySelectorAll(".checkall").forEach((checkbox) => {
            checkbox.addEventListener('click', function(event) {
                event.stopPropagation();
                updateCustomCheckAll();
                updateMassDeleteVisibility();
                updateMassRestoreVisibility();

                let target = event.target;
                let li = target.closest('li.checkforactive');
                let input = li.querySelector(".chat-actions .dropdown-item");
                let chatUserTabList = document.querySelector('.chat-user-tab-list');
                var checkedCheckboxes = $('.checkall:checked').length;
                let customCheckAll = document.querySelector('#customCheckAll');
                if(input){
                    if(target.checked){
                        chatUserTabList.classList.add('show-checks');
                        input.lastChild.textContent = "Deselect";
                        customCheckAll.nextElementSibling.textContent = checkedCheckboxes + ' '  + "Selected"
                    }else{
                        input.lastChild.textContent = "Select";
                            if(checkedCheckboxes == 0){
                                customCheckAll.nextElementSibling.textContent = ""
                            }else{
                                customCheckAll.nextElementSibling.textContent = checkedCheckboxes + ' '  + "Selected"
                            }
                    }
                }
            });
        });

        $(document).ready(function() {

            $(document).on('click', '#customCheckAll', function() {
                $('.checkall').prop('checked', this.checked);
                let chatUserTabList = document.querySelector('.chat-user-tab-list');
                let inputs = document.querySelectorAll(".checkforactive .chat-actions ul");
                var checkedCheckboxes = $('.checkall:checked').length;
                let customCheckAll = document.querySelector('#customCheckAll');
                inputs.forEach(ele =>{
                    let input = ele.querySelector('.dropdown-item');
                    if(input){
                        if(this.checked){
                        customCheckAll.nextElementSibling.textContent = checkedCheckboxes + ' ' + "Selected"
                        chatUserTabList.classList.add('show-checks');
                        input.lastChild.textContent = "Deselect";
                    }else{
                            if(checkedCheckboxes == 0){
                                customCheckAll.nextElementSibling.textContent = ""
                            }else{
                                customCheckAll.nextElementSibling.textContent = checkedCheckboxes + ' '  + "Selected"
                            }
                            chatUserTabList.classList.remove('show-checks');
                            input.lastChild.textContent = "Select";
                        }
                    }
                })
                updateMassDeleteVisibility();
                updateMassRestoreVisibility();
            });


            // Handle pagination controls
            $(document).on('click', '.pagination a', function() {
                // Assuming '.pagination a' is the selector for your pagination controls
                setTimeout(function() {
                    updateMassDeleteVisibility();
                    updateMassRestoreVisibility();
                }, 100);
            });

            // Initialize the "Select All" checkbox to unchecked
            $('#customCheckAll').prop('checked', false);

        });

        //Mass Delete
        $('body').on('click', '#massdelete', function() {
            var id = [];
            var custIds = [];
            $('.checkall:checked').each(function() {
                id.push($(this).val());
                custIds.push($(this).closest('li.checkforactive').data('id'));
            });

            let massdeleteurl;
            if($(this).data('id') == 'trashed'){
                massdeleteurl = "{{ url('admin/trashedticket/delete')}}";
            }else{
                massdeleteurl = "{{ url('admin/ticket/delete/tickets') }}";
            }

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
                            url: massdeleteurl,
                            method: "post",
                            data: {
                                id: id
                            },
                            success: function(data) {

                                //for client side
                                $.each(id, function(index, value) {
                                    $('#row_id' + value).remove();
                                });

                                toastr.success(data.success);

                                $('#selectallbutton').hide();
                                $('#massdelete').hide();

                                custIds.map((custId)=>{
                                    var activecustId = document.querySelector(`.checkforactive.active`)?.getAttribute("data-id");
                                    document.querySelector(`.checkforactive[data-id="${custId}"]`)?.remove()
                                    if(activecustId == custId){
                                        localStorage.removeItem(tickettypelocalstore);
                                        document.querySelector(`#operator-conversation-Info[data-id="${custId}"]`)?.remove()
                                        document.querySelector(`#operator-conversation[operator-id="${custId}"]`)?.remove()
                                        document.querySelector(".chat-footer")?.classList.add("d-none")
                                    }

                                    if(document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")){
                                        sideMenuOpenClickFunction(document.querySelector(`.checkforactive[data-id="${document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")}"]`))
                                    }else{
                                        let emptyconver = document.createElement("div");
                                        emptyconver.className = "text-center mt-5 px-2 py-4 bg-warning-transparent text-default mx-auto w-90 rounded-2";
                                        emptyconver.innerHTML = `<span>{{ lang('As of now, there are no chat discussions in progress') }}</span>`;
                                        document.querySelector("#chat-msg-scroll").append(emptyconver);
                                        document.querySelector(".no-articles").classList.remove("d-none");
                                    }
                                });

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

        //Mass retore
        $('body').on('click', '#massrestore', function () {
            var id = [];
            var custIds = [];
            $('.checkall:checked').each(function(){
                id.push($(this).val());
                custIds.push($(this).closest('li.checkforactive').data('id'));
            });

            if(id.length > 0){
                swal({
                    title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                    text: "{{lang('This might restore your record', 'alerts')}}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url:"{{ url('admin/trashedticket/restore')}}",
                            method:"POST",
                            data:{id:id},
                            success:function(data)
                            {
                                toastr.success(data.success);

                                $('#massrestore').hide();
                                $('#selectallbutton').hide();
                                $('#massdelete').hide();

                                custIds.map((custId)=>{
                                    var activecustId = document.querySelector(`.checkforactive.active`)?.getAttribute("data-id");
                                    document.querySelector(`.checkforactive[data-id="${custId}"]`)?.remove()
                                    if(activecustId == custId){
                                        localStorage.removeItem(tickettypelocalstore);
                                        document.querySelector(`#operator-conversation-Info[data-id="${custId}"]`)?.remove()
                                        document.querySelector(`#operator-conversation[operator-id="${custId}"]`)?.remove()
                                        document.querySelector(".chat-footer")?.classList.add("d-none")
                                    }
                                });

                                if(document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")){
                                    sideMenuOpenClickFunction(document.querySelector(`.checkforactive[data-id="${document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")}"]`))
                                }else{
                                    let emptyconver = document.createElement("div");
                                    emptyconver.className = "text-center mt-5 px-2 py-4 bg-warning-transparent text-default mx-auto w-90 rounded-2";
                                    emptyconver.innerHTML = `<span>{{ lang('As of now, there are no chat discussions in progress') }}</span>`;
                                    document.querySelector("#chat-msg-scroll").append(emptyconver);
                                    document.querySelector(".no-articles").classList.remove("d-none");
                                }
                            },
                            error:function(data){

                            }
                        });
                    }
                });
            }else{
                toastr.error('{{lang('Please select at least one check box.', 'alerts')}}');
            }
        });

        /*  When user click add note button */
        $('body').on('click', '#create-new-note', function () {
            $('#btnsave').val("create-product");
            $('#ticket_id').val($(this).data("id"));
            $('#note_form').trigger("reset");
            $('.modal-title').html(`{{lang('Add Note', 'menu')}}`);
            $('#addnote').modal('show');
        });

        // Note Submit button
        $('body').on('submit', '#note_form', function (e) {
            e.preventDefault();
            var actionType = $('#btnsave').val();
            var fewSeconds = 2;
            $('#btnsave').html(`{{lang('Sending ... ', 'menu')}} <i class="fa fa-spinner fa-spin"></i>`);
            $('#btnsave').prop('disabled', true);
                setTimeout(function(){
                    $('#btnsave').prop('disabled', false);
                }, fewSeconds*1000);
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: SITEURL + "/admin/note/create",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,

                success: (data) => {
                    $('#note_form').trigger("reset");
                    $('#btnsave').html('{{lang('Save Changes')}}');
                    toastr.success(data.success);
                    document.body.style.overflow = 'auto';

                },
                error: function(data){
                    console.log('Error:', data);
                    $('#btnsave').html('{{lang('Save Changes')}}');
                }
            });
        });
    </script>

    <script type="text/javascript">
        "use strict";

        // Variables
        var SITEURL = '{{ url('') }}';
        const autoID = '{{ Auth::user()->id }}'
        const autoUserInfo = JSON.parse('{!! addslashes(json_encode(Auth::user())) !!}');
        const AllUserDetails = JSON.parse('{!! addslashes(json_encode($user)) !!}');

        let customerData
        let ticketData

        function formatTime(inputTime) {
            const jsDate = new Date(inputTime);
            let timeFormat = @json(setting('time_format'));
            let formattedTime;
            switch (timeFormat) {
                case 'H:i:s': // 24-hour format with seconds
                    formattedTime = jsDate.toLocaleTimeString('en-GB', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    });
                    break;
                case 'h:i A': // 12-hour format with AM/PM
                    formattedTime = jsDate.toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                    break;
                case 'H:i': // 24-hour format without seconds
                    formattedTime = jsDate.toLocaleTimeString('en-GB', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });
                    break;
                default:
                    formattedTime = jsDate.toLocaleTimeString(); // Default format
            }

            return `${formattedTime}`;
        }

        // Make as unread click logic
        document.querySelectorAll(".markAsUnreadBtn").forEach((element) => {
            element.onclick = () => {
                localStorage.removeItem(tickettypelocalstore)
            }
        })

        // File Upload Permission Check Btn
        document.querySelector("#fileUploadPermission").onclick = () => {
            let data = {
                permission: document.querySelector("#fileUploadPermission").checked,
                custUser: document.querySelector("#fileUploadPermission").getAttribute('data-id'),
                ticketId: document.querySelector("#fileUploadPermission").getAttribute('data-ticketid')
            }

            $.ajax({
                type: "post",
                url: '{{ route('admin.liveChatCustFileUpload') }}',
                data: data,
                success: function(data) {
                    if (data.success) {
                        toastr.success(data.message)
                    }
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }


        // conversation Image Upload
        let liveChatFileUpload = "{{ setting('liveChatFileUpload') }}"
        let livechatMaxFileUpload = "{{ setting('USER_MAX_FILE_UPLOAD') }}"
        let livechatFileUploadMax = "{{ setting('USER_FILE_UPLOAD_MAX_SIZE') }}"
        let livechatFileUploadTypes = "{{ setting('USER_FILE_UPLOAD_TYPES') }}"

        // Chat Image Upload
        if (document.querySelector("#chat-file-upload")) {
            document.querySelector("#chat-file-upload").addEventListener('change', () => {
                var fileInput = document.querySelector("#chat-file-upload");
                var file = fileInput.files[0];
                fileInput.value = ""
                var ThereIsError = false
                // For check the File Upload permissions
                if (livechatMaxFileUpload <= document.querySelectorAll(".image-uploaded .file-img").length) {
                    ThereIsError = {
                        errorMessage: "The maximum file upload limit has been exceeded."
                    };
                } else if (file.size > parseInt(livechatFileUploadMax) * 1024 * 1024) {
                    ThereIsError = {
                        errorMessage: `File size exceeds ${livechatFileUploadMax} MB. Please choose a smaller file.`
                    };
                } else if (livechatFileUploadTypes && !livechatFileUploadTypes.split(',').some(ext => file.name
                        .toLowerCase().toLowerCase().endsWith(ext.toLowerCase().trim()))) {
                    ThereIsError = {
                        errorMessage: `Invalid file extension. Please choose a file with ${livechatFileUploadTypes} extension(s).`
                    };
                } else {
                    ThereIsError = false
                }

                // For add the Upload indication
                let uploadingIndication = document.createElement("p")
                uploadingIndication.className = "fw-lighter ms-4"
                uploadingIndication.id = "uploadingIndication"
                uploadingIndication.innerHTML = `uploading`
                // Upload The File
                if (file && !ThereIsError) {
                    // Adding the Uploding indication
                    document.querySelector(".image-uploaded").appendChild(uploadingIndication)


                    var formData = new FormData();
                    formData.append('file', file);

                    fetch(`{{ route('comments.storemedia') }}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            fileInput.insertAdjacentHTML('afterend',
                                '<input type="hidden" name="comments[]" originalName="' + data
                                .original_name + '" value="' + data.name + '">');
                            const imageDiv = document.createElement("div");
                            let uploadedFilename = data.name;
                            imageDiv.classList.add("file-img")
                            imageDiv.innerHTML = `
                        <img imageSrc="${SITEURL}/public/uploads/comment/${uploadedFilename}" src="${uploadedFilename.toLowerCase().endsWith(".jpg") || uploadedFilename.toLowerCase().endsWith(".png") ? `${SITEURL}/public/uploads/comment/${uploadedFilename}` : `${SITEURL}/build/assets/images/svgs/file.svg`}"
                            style="
                            width: 100%;
                            max-height: 55px;
                            border-radius: 5px;
                            ${uploadedFilename.toLowerCase().endsWith(".jpg") || uploadedFilename.toLowerCase().endsWith(".png") ? '' : 'height: 65px;'}"
                        >
                        <button class="btn-danger rounded-circle imageRemoveClick">
                            <i class="fe fe-x fs-12"></i>
                        </button>
                    `
                            // For the Image Remove Click
                            imageDiv.querySelector(".imageRemoveClick").onclick = (ele) => {
                                let fileImgElement = ele.currentTarget.closest(".file-img");
                                let data = {
                                    filename: uploadedFilename,
                                }

                                $.ajax({
                                    type: "post",
                                    url: '{{ route('admin.removeChatImage') }}',
                                    data: data,
                                    success: function(data) {
                                        // To remove the Image
                                        if (fileImgElement) {
                                            fileImgElement.remove();
                                        }
                                    },
                                    error: function(data) {
                                        console.log('Error:', data);
                                    }
                                });
                            }
                            // TO remove the uploading indication
                            document.querySelector("#uploadingIndication").remove()
                            document.querySelector(".image-uploaded").appendChild(imageDiv)
                            document.querySelector("#agentSendMessage").classList.remove('disabled')
                            document.querySelector("#agentSendMessage").removeAttribute('disabled')
                            document.querySelector("#auto-expand").focus()
                        })
                        .catch(error => {
                            console.error('Error uploading file:', error);
                        });
                } else {
                    toastr.error(ThereIsError.errorMessage)
                }

            })
        }


        // For the Customer Message Component
        let customerMessage = (data) => {
            let custLi = document.createElement("li");
            custLi.className = "chat-item-start"
            custLi.innerHTML = `
                    <div class="chat-list-inner">
                        <div class="chat-user-profile">
                            <span class="avatar avatar-md brround" style="background-color: ${data.ticket.profile_bg_color}"> <span class="new-chat-user-letter">${data.cust.username.slice(0,1).toUpperCase()}</span>
                            </span>
                        </div>
                        <div class="ms-3">
                            <span class="chatting-user-info">
                                <span class="chatnameperson">${data.cust.username}</span>
                                <span class="msg-sent-time">${formatTime(data.created_at)}</span>
                                ${texttospeachenabled == 'on' || texttranslateenabled == 'on' ?
                                    `<a aria-label="anchor" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class='float-end msg-send-dropdown'>
                                        <i class="fe fe-more-vertical fs-18"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        ${texttospeachenabled == 'on' ?
                                            `<li>
                                                <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="texttospeachbtn()">
                                                    <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Text To Speach') }}
                                                </a>
                                            </li>` : ''}
                                        ${texttranslateenabled == 'on' ?
                                            `<li>
                                                <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="langtranslatebtn()">
                                                    <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Language Translate') }}
                                                </a>
                                            </li>` : ''}
                                    </ul>`
                                    : ''
                                }
                            </span>
                            <div class="main-chat-msg">
                                <div style="white-space: pre-line;" class="text-start" ><p class="mb-0 text-break" >${data.comment}</p></div>
                                ${data.image_url && data.image_url.length > 0 ?
                                    `<div class="comment-images mt-2">` +
                                    data.image_url.map(image =>
                                        `<img onclick="AllFileViewer(this)" imageSrc="${image}" src="${image}" alt="${image}" class="img-fluid me-2" style="width: 50px; height: 50px;">`
                                    ).join('') +
                                    `</div>`
                                    : ''
                                }
                            </div>
                        </div>
                    </div>
            `
            return custLi
        }

        // To viewe the Pdf Files
        let AllFileViewer = (ele) => {
            window.open(ele.getAttribute("imagesrc"))
        }

        var texttospeachenabled = '{{ setting('CHAT_TEXT_TO_SPEACH_ENABLE') }}';
        var texttranslateenabled = '{{ setting('CHAT_TEXT_TRANSLATE_ENABLE') }}';
        function texttospeachbtn() {
            if(event.target.closest('.ms-3').querySelector('.main-chat-msg p')){
                $('.select2_texttospeach').select2({
                    dropdownParent: ".texttospeachmodal",
                    minimumResultsForSearch: '',
                    placeholder: "Search",
                    width: '100%'
                });
                $('#texttospeachcontent').val(event.target.closest('.ms-3').querySelector('.main-chat-msg p').textContent);
                $('#texttospeachmodal').modal('show');
            }else{
                toastr.error('{{ lang('There is no text to speach.', 'alerts') }}');
            }
        }

        function langtranslatebtn() {
            var targetElement = event.target.closest('.ms-3').querySelector('.main-chat-msg p');
            if(targetElement){
                $('.select2_texttranslation').select2({
                    dropdownParent: ".texttranslationmodal",
                    minimumResultsForSearch: '',
                    placeholder: "Search",
                    width: '100%'
                });
                $('#texttranslationmodal').modal('show');

                document.querySelector('#translateButtonSave').onclick = async () =>{
                    document.querySelector('#translateButtonSave').innerHTML = "{{ lang('Translating') }} ... <i class='fa fa-spinner fa-spin'></i>";
                    const textInput = targetElement.innerText;
                    const selectedLang = document.getElementById('translate-lang').value;

                    try {
                        const translatedText = await translateText(textInput, selectedLang);
                        targetElement.innerHTML = translatedText;
                        document.querySelector('#translateButtonSave').innerHTML = "{{ lang('Translate') }}";
                        $('#texttranslationmodal').modal('hide');
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }
            }else{
                toastr.error('{{ lang('There is no text to translate.', 'alerts') }}');
            }
        }

        // For the Agent Message Component
        let AgentMessage = (data) => {
            let agentLi = document.createElement("li");
            agentLi.className = "chat-item-start"
            agentLi.innerHTML = `
                <div class="chat-list-inner">
                    <div class="chat-user-profile">
                        <span class="avatar avatar-md brround" style="background-image: url(${data.profileurl})">
                        </span>
                    </div>
                    <div class="ms-3">
                        <span class="chatting-user-info">

                            ${data.user_id == '741741741' ? '{{ setting('bot_name') }}' : (!data.user_id ? '~' : (data.user.id == autoUserInfo.id ? 'You' : data.user.name))}
                            <span class="msg-sent-time">
                                ${formatTime(data.created_at)}
                            </span>
                            ${texttospeachenabled == 'on' || texttranslateenabled == 'on' ?
                                `<a aria-label="anchor" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class='float-end msg-send-dropdown'>
                                    <i class="fe fe-more-vertical fs-18"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    ${texttospeachenabled == 'on' ?
                                        `<li>
                                            <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="texttospeachbtn()">
                                                <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Text To Speach') }}
                                            </a>
                                        </li>` : ''}
                                    ${texttranslateenabled == 'on' ?
                                        `<li>
                                            <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="langtranslatebtn()">
                                                <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Language Translate') }}
                                            </a>
                                        </li>` : ''}
                                </ul>`
                                : ''
                            }
                        </span>
                        <div class="main-chat-msg">
                            <div style="white-space: pre-line;" class="text-start">
                                <p class="mb-0 text-break" >${data.comment}</p>
                            </div>
                            ${data.media && data.media.length > 0 ?
                                `<div class="comment-images mt-2">` +
                                data.media.map(image =>
                                    `<img onclick="AllFileViewer(this)" imageSrc="${image.original_url}" src="${image.original_url}" alt="${image.file_name}" class="img-fluid me-2" style="width: 50px; height: 50px;">`
                                ).join('') +
                                `</div>`
                                : ''
                            }
                        </div>
                    </div>
                </div>
            `
            return agentLi
        }

        // For The Comment Component
        let CommentMessage = (data) => {
            let agentLi = document.createElement("li");
            agentLi.className = "chat-join-notify"
            agentLi.innerHTML =
                `<span>${data.message.replace(autoUserInfo.name,'You')} ${formatTime(data.created_at)}</span>`
            return agentLi
        }

        let FeedbackComment = (data) => {
            data = JSON.parse(data.message)
            let agentLi = document.createElement("li");
            agentLi.className = "chat-join-notify"
            agentLi.innerHTML = `
                <span>Your chat has been rated ${data.starRating} out of 5 by the customer</span>
            `
            return agentLi
        }

        // markas as read and unread function
        function markAsUnReadSubmit(ele){
            var markId = ele.target.getAttribute('data-id')
            var element = ele.target;
            swal({
                title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                text: "{{lang('This might mark as unread the ticket', 'alerts')}}",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "get",
                        url: `{{ route('admin.addticketasUnread') }}?id=${markId}`,
                        success: function (data) {
                            toastr.success('Mark as unread successfully.');

                            document.querySelectorAll(`.markAsUnReadSubmit`).forEach(element => {
                                if(element.getAttribute("data-id") == markId){
                                    var anc = document.createElement('a');
                                    anc.className = 'dropdown-item markAsReadBtn markAsReadSubmit';
                                    anc.setAttribute('data-id',markId);
                                    anc.setAttribute('href','javascript:void(0)');

                                    anc.innerHTML = `<i class="ri-mail-check-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>
                                    {{ lang('Mark As Read') }}`;
                                    element.parentNode.replaceChild(anc, element);
                                }
                            });

                            $('.markAsReadSubmit').on('click', function (ele){
                                markAsReadSubmit(ele);
                            });

                            const messageElement = document.querySelector(`.checkforactive[data-id="${markId}"] .custrecentmessage`);

                            const newSpan = document.createElement('span');
                            newSpan.className = 'badge bg-success-transparent rounded-circle float-end unReadIndexNumber me-1 ms-auto';
                            newSpan.innerHTML = '';
                            if (messageElement) {
                                messageElement.parentNode.insertBefore(newSpan, messageElement.nextSibling);
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });
        }

        function markAsReadSubmit(ele){
                var markId = ele.target.getAttribute('data-id')
                var element = ele.target;

                swal({
                    title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                    text: "{{lang('This might mark as read the ticket', 'alerts')}}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "get",
                            url: `{{ route('admin.removeticketFromUnread') }}?id=${markId}`,
                            success: function (data) {
                                toastr.success('Mark as read successfully.');

                                document.querySelectorAll(`.markAsReadSubmit`).forEach(element => {
                                    if(element.getAttribute("data-id") == markId){
                                        var anc = document.createElement('a');
                                        anc.className = 'dropdown-item markAsUnreadBtn markAsUnReadSubmit';
                                        anc.setAttribute('data-id',markId);
                                        anc.setAttribute('href','javascript:void(0)');

                                        anc.innerHTML = `<i class="ri-mail-unread-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>
                                        {{ lang('Mark As Unread') }}`;
                                        element.parentNode.replaceChild(anc, element);
                                    }
                                });

                                $('.markAsUnReadSubmit').on('click', function (ele){
                                    markAsUnReadSubmit(ele);
                                });


                                const badgeExists = document.querySelector(`.checkforactive[data-id="${markId}"] .unReadIndexNumber`);
                                if(badgeExists){
                                    badgeExists.remove();
                                }
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
        }

        // SideBar and Online operators loop click
        function sideMenuOpenClickFunction(ele) {
            document.querySelector("#main-chat-content").classList.add('is-loading');
            document.querySelector(".chat-footer").classList.add('d-none');

            // operators Click
            $.ajax({
                type: "get",
                url: SITEURL + `/livechat/singleticketdata/${ele.getAttribute("data-id")}`,
                data: {
                    author: 'agent',
                    tickettype: tickettypelocalstore,
                },
                success: function(data) {
                    let userconversation = data.livechatdata
                    customerData = data.livechatcust
                    ticketData = data.ticket
                    // To remove the Font Bold
                    if (ele.querySelector(".chat-msg") && (ele.querySelector(".chat-msg").classList.contains(
                            'font-weight-bold') || ele.querySelector(".unReadIndexNumber"))) {
                        ele.querySelector(".chat-msg").classList.remove("font-weight-bold")
                    }

                    // To Change the Make as read to Mark as unread
                    if (ele.querySelector(".unReadIndexNumber")) {
                        ele.querySelector(".unReadIndexNumber").remove()
                        if (ele.querySelector(".markAsreadBtn") && ele.querySelector(".markAsreadBtn").innerText.trim().replace(/\s+/g, ' ') == 'Mark As Read') {
                            ele.querySelector('.markAsreadBtn').href = ele.querySelector('.markAsreadBtn').href.replace("remove-user-from-unread","conversation-unread")
                            ele.querySelector('.markAsreadBtn').classList.add("markAsUnreadBtn")
                            ele.querySelector('.markAsreadBtn').innerHTML = `<i class="ri-chat-check-line align-middle me-2 fs-18"></i> Mark As Unread`
                            ele.querySelector('.markAsreadBtn').onclick = () => {
                                localStorage.removeItem(tickettypelocalstore)
                            }
                        }
                    }

                    if(document.querySelector("#agentSendMessage")){
                        document.querySelector("#agentSendMessage").disabled = true;
                        document.querySelector("#agentSendMessage").classList.add('disabled');
                    }

                    if(ticketData.status == 'Closed'){
                        document.getElementById('agentSendMessage').value = 'Solved';
                        document.getElementById('agentSendMessage').innerHTML = `{{ lang('Submit as Solved') }}`;
                    }else{
                        document.getElementById('agentSendMessage').value = 'Inprogress';
                        document.getElementById('agentSendMessage').innerHTML = `{{ lang('Submit as Inprogress') }}`;
                    }

                    let mainChatContent = document.querySelector("#main-chat-content")
                    mainChatContent.classList.remove("d-none")
                    document.querySelector("#main-chat-content .no-articles").classList.add('d-none')
                    if (document.querySelector("#operator-conversation")) {
                        document.querySelector("#operator-conversation").remove()
                    }
                    if (document.querySelector("#operator-conversation-Info")) {
                        document.querySelector("#operator-conversation-Info").remove()
                    }

                    // For the messages conversation
                    let conversation = document.createElement("ul");
                    conversation.className = "list-unstyled chat-content overflow-auto"
                    conversation.id = "operator-conversation"


                    // For the Chat data
                    function formatDateString(inputDateStr) {
                        const jsDate = new Date(inputDateStr);

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
                    conversation.setAttribute('operator-id', ele.getAttribute("data-id"))


                    let currentDate = null;

                    // For ticket description or message
                    if(ticketData.message){
                        const messageDate = formatDateString(ticketData.created_at);
                        conversation.innerHTML += `
                            <li class="chat-day-label mt-2">
                                <span>${messageDate}</span>
                            </li>
                        `;
                        currentDate = messageDate;
                        if(ticketData.user_id){
                            let agentLi = document.createElement("li");
                            agentLi.className = "chat-item-start"
                            agentLi.innerHTML = `
                                <div class="chat-list-inner">
                                    <div class="chat-user-profile">
                                        <span class="avatar avatar-md brround" style="background-image: url(${ticketData?.users?.profileurl ? ticketData?.users?.profileurl : '../uploads/profile/user-profile.png'})">
                                        </span>
                                    </div>
                                    <div class="ms-3">
                                        <span class="chatting-user-info">
                                            ${ticketData?.users?.id == autoUserInfo.id ? 'You' : ticketData?.users?.name}
                                            <span class="msg-sent-time">
                                                ${formatTime(ticketData.created_at)}
                                            </span>
                                            ${texttospeachenabled == 'on' || texttranslateenabled == 'on' ?
                                                `<a aria-label="anchor" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class='float-end msg-send-dropdown'>
                                                    <i class="fe fe-more-vertical fs-18"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    ${texttospeachenabled == 'on' ?
                                                        `<li>
                                                            <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="texttospeachbtn()">
                                                                <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Text To Speach') }}
                                                            </a>
                                                        </li>` : ''}
                                                    ${texttranslateenabled == 'on' ?
                                                        `<li>
                                                            <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="langtranslatebtn()">
                                                                <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Language Translate') }}
                                                            </a>
                                                        </li>` : ''}
                                                </ul>`
                                                : ''
                                            }
                                        </span>
                                        <div class="main-chat-msg">
                                            <div style="white-space: pre-line;" class="text-start"><p class="mb-0 text-break" >${ticketData.message}</p></div>
                                            ${ticketData?.image_url && ticketData?.image_url?.length > 0 ?
                                                `<div class="comment-images mt-2">` +
                                                ticketData.image_url.map(image =>
                                                    `<img onclick="AllFileViewer(this)" imageSrc="${image}" src="${image}" alt="${image}" class="img-fluid me-2" style="width: 50px; height: 50px;">`
                                                ).join('') +
                                                `</div>`
                                                : ''
                                            }
                                        </div>
                                    </div>
                                </div>
                            `
                            conversation.appendChild(agentLi)
                        }else{
                            let custLi = document.createElement("li");
                            custLi.className = "chat-item-start"
                            custLi.innerHTML = `
                                <div class="chat-list-inner">
                                    <div class="chat-user-profile">
                                        <span class="avatar avatar-md brround" style="background-color: ${data.profile_bg_color}"> <span class="new-chat-user-letter">${data?.cust?.username.slice(0,1).toUpperCase()}</span>
                                        </span>
                                    </div>
                                    <div class="ms-3">
                                        <span class="chatting-user-info">
                                            <span class="chatnameperson">${data?.cust?.username}</span>
                                            <span class="msg-sent-time">${formatTime(data?.created_at)}</span>
                                            ${texttospeachenabled == 'on' || texttranslateenabled == 'on' ?
                                                `<a aria-label="anchor" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class='float-end msg-send-dropdown'>
                                                    <i class="fe fe-more-vertical fs-18"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    ${texttospeachenabled == 'on' ?
                                                        `<li>
                                                            <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="texttospeachbtn()">
                                                                <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Text To Speach') }}
                                                            </a>
                                                        </li>` : ''}
                                                    ${texttranslateenabled == 'on' ?
                                                        `<li>
                                                            <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="langtranslatebtn()">
                                                                <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Language Translate') }}
                                                            </a>
                                                        </li>` : ''}
                                                </ul>`
                                                : ''
                                            }
                                        </span>
                                        <div class="main-chat-msg">
                                            <div style="white-space: pre-line;" class="text-start" ><p class="mb-0 text-break" >${data?.comment}</p></div>
                                            ${data?.image_url && data?.image_url?.length > 0 ?
                                                `<div class="comment-images mt-2">` +
                                                data.image_url.map(image =>
                                                    `<img onclick="AllFileViewer(this)" imageSrc="${image}" src="${image}" alt="${image}" class="img-fluid me-2" style="width: 50px; height: 50px;">`
                                                ).join('') +
                                                `</div>`
                                                : ''
                                            }
                                        </div>
                                    </div>
                                </div>
                            `
                            conversation.appendChild(custLi)
                        }
                    }

                    // For the Agent Messages
                    if (userconversation) {
                        userconversation.map((chatdata) => {
                            const messageDate = formatDateString(chatdata.created_at);
                            if (messageDate !== currentDate) {
                                conversation.innerHTML += `
                                <li class="chat-day-label mt-2">
                                <span>${messageDate}</span>
                                </li>
                                `;
                                currentDate = messageDate;
                            }
                            // For the Agent Messages
                            if (chatdata.cust_id && chatdata.status != 'comment' && chatdata.message_type != "feedBack") {
                                conversation.appendChild(customerMessage(chatdata))
                            } else {
                                if (chatdata.commenttype == 'tickethistory') {
                                    let commentMessage = document.createElement("li");
                                    commentMessage.className = "chat-join-notify"
                                    commentMessage.innerHTML = `
                                        <span>${chatdata.comment} by ${chatdata?.user ? chatdata.user.name.replace(autoUserInfo.name,'You') : 'Auto process'} at ${formatTime(chatdata.created_at)}</span>
                                    `
                                    conversation.appendChild(commentMessage)
                                }
                                if (chatdata.status != 'comment' && chatdata.message_type != "feedBack" && chatdata.commenttype != 'tickethistory') {
                                    conversation.appendChild(AgentMessage(chatdata))
                                }
                                if (chatdata.status == 'comment' && chatdata.message_type != "feedBack") {
                                    conversation.appendChild(CommentMessage(chatdata))
                                }
                                if (chatdata.message_type == "feedBack") {
                                    conversation.appendChild(FeedbackComment(chatdata))
                                }
                            }

                        })
                    }

                    // For the receiver Info
                    let receiverInfo = document.createElement("div");
                    receiverInfo.className = "d-flex align-items-center chat-conversation-Info border-bottom flex-wrap"
                    receiverInfo.id = "operator-conversation-Info"
                    receiverInfo.setAttribute('data-id', ele.getAttribute("data-id"))
                    receiverInfo.innerHTML = `
                        <div class="flex-fill">
                            <span class="fs-16">${ticketData.subject}</span><br>
                            <span class="text-muted fs-12"><span class="text-primary">#${ticketData.ticket_id}</span>, created by ${ticketData?.users ? ticketData.users.name : ticketData?.cust?.username} On ${formatDateString(ticketData.created_at)}, ${formatTime(ticketData.created_at)}</span>
                        </div>
                        <div class="d-flex gap-1">
                            <button aria-label="button" class="btn btn-outline-light my-1 btn-wave waves-light waves-effect waves-light responsive-userinfo-open ms-2" type="button" aria-expanded="false">
                                {{ lang('Ticket Information') }}
                            </button>
                            <button aria-label="button" type="button" class="btn btn-icon btn-outline-light my-1 ms-2 responsive-chat-close"> <i class="ri-close-line"></i> </button>
                        </div>
                        </div>
                        </div>
                    `

                    mainChatContent.appendChild(receiverInfo)
                    mainChatContent.appendChild(conversation)

                    // To Scroll Down the Conversation
                    document.querySelector("#operator-conversation").scrollBy(0, document.querySelector(
                        "#operator-conversation").scrollHeight)

                    // To add the Image Viewer
                    if (document.querySelector(".imageMessageLiveChat")) {
                        // To Open the Image Viewer
                        document.querySelectorAll(".imageMessageLiveChat").forEach((element) => {
                            element.onclick = (ele) => {
                                document.querySelector(".liveChatImageViewer").classList.remove(
                                    "d-none")
                                document.querySelector(".liveChatImageViewer img").src = ele.target
                                    .getAttribute("imagesrc")
                                document.querySelector(".liveChatImageViewer .liveChatImageClose")
                                    .onclick = () => {
                                        // To Close the Image Viewer
                                        document.querySelector(".liveChatImageViewer").classList
                                            .add("d-none")
                                    }
                            }
                        })

                    }

                    // Set the sidebar active
                    localStorage.setItem(tickettypelocalstore, ele.getAttribute("data-id"))
                    document.querySelectorAll(".checkforactive").forEach((lielement) => {
                        lielement.classList.remove("active")
                        if (lielement.getAttribute("data-id") == ele.getAttribute("data-id")) {
                            lielement.classList.add("active")
                        }
                    })

                    let chatUserDetails = document.querySelector("#chat-user-details");
                    chatUserDetails.classList.remove("d-none");

                    // For the Side bar client Info
                    if (document.querySelector("#chat-client-info")) {
                        document.querySelector("#chat-client-info").remove();
                    }


                    // Ticket Information
                    if (data.ticketinfo) {
                        document.querySelector('#ticketinfofethc').innerHTML = data.ticketinfo;
                    }


                    // Client information
                    let chatUserDetailsUl = document.createElement("ul");
                    chatUserDetailsUl.className = "list-unstyled chat-client-info";
                    chatUserDetailsUl.id = "chat-client-info";
                    chatUserDetailsUl.innerHTML = `
                        <li>
                        <div class="d-flex gap-2 align-items-start text-break"><i class="ri-user-line font-weight-semibold flex-shrink-0 lh-1 align-middle icon-style1"></i>${customerData.username}</div>
                        </li>
                        <li>
                        <div class="d-flex gap-2 align-items-start text-break"><i class="ri-mail-line font-weight-semibold flex-shrink-0 lh-1 align-middle icon-style1"></i>${customerData.email}</div>
                        </li>
                        <li>
                        <div class="d-flex gap-2 align-items-start text-break"><i class="ri-phone-line font-weight-semibold flex-shrink-0 lh-1 align-middle icon-style1"></i>${customerData.mobile_number}</div>
                        </li>
                        <li>
                        <div class="d-flex gap-2 align-items-start text-break"><i class="ri-computer-line font-weight-semibold flex-shrink-0 lh-1 align-middle icon-style1"></i>${customerData.browser_info}</div>
                        </li>
                        <li>
                        <div class="d-flex gap-2 align-items-start text-break"><i class="ri-map-pin-range-line font-weight-semibold flex-shrink-0 lh-1 align-middle icon-style1"></i>${customerData.login_ip}</div>
                        </li>
                        <li>
                        <div class="d-flex gap-2 align-items-start text-break"><i class="ri-earth-line font-weight-semibold flex-shrink-0 lh-1 align-middle icon-style1"></i>${customerData.Login_url}</div>
                        </li>
                        <li>
                        <div class="d-flex gap-2 align-items-start text-break"><i class="ri-map-pin-line font-weight-semibold flex-shrink-0 lh-1 align-middle icon-style1"></i>
                            ${customerData.city ? customerData.city + "," : ''} ${customerData.state ? customerData.state + "," : ''} ${customerData.country}
                        </div>
                        </li>
                    `;

                    chatUserDetails.querySelector(".card-body").appendChild(chatUserDetailsUl);
                    // File permission Check Btn
                    if (document.querySelector('#fileUploadPermission') && !ticketData?.deleted_at) {
                        document.querySelector('#fileUploadPermission').closest('.card').classList.remove('d-none')
                        document.querySelector('#fileUploadPermission').setAttribute("data-id", customerData.id)
                        document.querySelector('#fileUploadPermission').setAttribute("data-ticketId", ticketData.id)
                        if (customerData.file_upload_permission) {
                            document.querySelector('#fileUploadPermission').checked = true
                        } else {
                            document.querySelector('#fileUploadPermission').checked = false
                        }
                    }

                    // On the Click removing the make as unread
                    $.ajax({
                        type: "get",
                        url: '{{ route('admin.removeUserFromUnread') }}',
                        data: {
                            "id": customerData.id
                        },
                        success: function(data) {},
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });

                    $('.markAsReadSubmit').on('click', function (ele){
                        markAsReadSubmit(ele);
                    });
                    $('.markAsUnReadSubmit').on('click', function (ele){
                        markAsUnReadSubmit(ele);
                    });

                    document.querySelectorAll(".markAsUnreadBtn").forEach((element) => {
                        element.onclick = () => {
                            localStorage.removeItem(tickettypelocalstore)
                        }
                    })

                    responsivechat();

                    // remove loading
                    setTimeout(() => {
                        document.querySelector("#main-chat-content").classList.remove("is-loading")

                        // Message conversation logic
                        let chatFooter = document.querySelector(".chat-footer")
                        chatFooter.classList.remove("d-none")

                        // To check that if the user was include in the conversation
                        if (data.replypermission == 'permitted') {
                            chatFooter.querySelector('.textareaDiv').classList.remove('d-none')
                        }
                    }, 100);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            })
        }

        // For the Side Bar Click
        function slectTicket(event, id) {
            let target = event.currentTarget;
            if(target){
                let li = target.closest('li.checkforactive');
                let input = li.querySelector("[name='customer_checkbox[]']");
                let chatUserTabList = document.querySelector('.chat-user-tab-list');
                event.stopPropagation();
                if(input){
                    if(input.checked){
                        input.checked = false;
                        target.lastChild.textContent = "Select";
                    }else{
                        input.checked = true;
                        target.lastChild.textContent = "Deselect";
                    }

                    var checkedCheckboxes = $('.checkall:checked').length;
                    let customCheckAll = document.querySelector('#customCheckAll');
                    if ($('.checkall:checked').length === 0) {
                        customCheckAll.nextElementSibling.textContent = ""
                        chatUserTabList.classList.remove('show-checks');
                        $('#massdelete').hide();
                        $('#selectallbutton').hide();
                    } else {
                        customCheckAll.nextElementSibling.textContent = checkedCheckboxes + ' '  + "Selected"
                        chatUserTabList.classList.add('show-checks');
                        $('#massdelete').show();
                        $('#selectallbutton').show();
                    }
                }
            }
            updateCustomCheckAll();
        }

        // Prevent the li loop click from the dropdown
        document.querySelectorAll('#chat-msg-scroll .chat-actions', '.checkall').forEach((ele) => {
            ele.addEventListener('click', function(event) {
                event.stopPropagation();
            })

            // markAsUnreadBtn
            if (ele.querySelector('.markAsUnreadBtn')) {
                ele.querySelector('.markAsUnreadBtn').addEventListener('click', function(event) {
                    event.stopPropagation();
                    if (localStorage.getItem(tickettypelocalstore) == ele.closest('.checkforactive').getAttribute('data-group-uniq') || localStorage.getItem(tickettypelocalstore) == ele.closest('.checkforactive').getAttribute('data-id')) {
                        localStorage.removeItem(tickettypelocalstore)
                    }
                })
            }

            // Delete Btn
            if (ele.querySelector('[href*="conversationdelete"]')) {
                ele.querySelector('[href*="conversationdelete"]').addEventListener('click', function(event) {
                    event.stopPropagation();
                    if (localStorage.getItem(tickettypelocalstore) == ele.closest('.checkforactive').getAttribute(
                            'data-group-uniq') || localStorage.getItem(tickettypelocalstore) == ele.closest(
                            '.checkforactive').getAttribute('data-id')) {
                        localStorage.removeItem(tickettypelocalstore)
                    }
                })
            }
        })

        // Active liveChat Message
        let AllticketsCustVal = localStorage.getItem(tickettypelocalstore) ?? document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id");
        if(notificationticketid){
            AllticketsCustVal = notificationticketid;
            const presenturl = new URL(window.location.href);
            const newpathdata = new URLSearchParams(presenturl.search).get('ticketdata');
            history.pushState(null,null,`${location.pathname}?ticketdata=${newpathdata}`)
        }

        if (AllticketsCustVal) {
            if (document.querySelector(`.checkforactive[data-id="${AllticketsCustVal}"]`)) {
                sideMenuOpenClickFunction(document.querySelector(`.checkforactive[data-id="${AllticketsCustVal}"]`))
            } else {
                if (document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")) {
                    sideMenuOpenClickFunction(document.querySelector(`.checkforactive[data-id="${document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")}"]`))
                } else {
                    document.querySelector(".no-articles").classList.remove("d-none");
                }
            }
        }

        if (!AllticketsCustVal) {
            document.querySelector(".no-articles").classList.remove("d-none");
        }

        const now = new Date();
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const period = hours >= 12 ? "PM" : "AM";

        const formattedTime = `${((hours + 11) % 12) + 1}:${minutes}${period}`;
        const autoUser = '{{ Auth::user()->name }}'

        // Send Message button
        document.querySelector("#agentSendMessage").onclick = () => {
            afterMessageSend = false
            let operatorConversation = document.querySelector("#operator-conversation")
            if(!document.querySelector("#auto-expand").textContent.trim()){
                toastr.error('{{ lang('Need to type any message') }}');
                return;
            }

            if (document.querySelector("#auto-expand").querySelector("#suggestionText")) {
                document.querySelector("#auto-expand").querySelector("#suggestionText").remove();
            }

            if (document.querySelector("#auto-expand").querySelector(".misspelled")) {
                document.querySelector("#auto-expand").querySelectorAll(".misspelled").forEach((element) => {
                    // Create a text node with the innerText of the current element
                    const textNode = document.createTextNode(element.innerText);
                    // Replace the misspelled element with the text node
                    element.parentNode.replaceChild(textNode, element);
                });
            }

            // Agent Message Send
            let PresentTimeFormatted =
                `${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}`;
            var imageInputs = document.querySelectorAll('input[name="comments[]"]');
            var imagesArray = [];
            imageInputs.forEach(function(input) {
                imagesArray.push(input.value);
            });

            let data = {
                comment: document.querySelector("#auto-expand").innerHTML,
                comments: imagesArray,
                status: document.getElementById('agentSendMessage').value,
                ticket_id: ticketData.id
            }

            if(!document.getElementById('buttonvaluechange')){
                toastr.error('{{ lang('You do not have permission to send reply.') }}');
                return;
            }

            if(data.status == 'Solved'){
                document.getElementById('buttonvaluechange').innerHTML = `{{ lang('Solved') }}`;
            }else{
                document.getElementById('buttonvaluechange').innerHTML = `{{ lang('Inprogress') }}`;
            }

            document.querySelector("#agentSendMessage").innerHTML = `Submiting ... <i class="fa fa-spinner fa-spin"></i>`;

            if (document.querySelector("#auto-expand").textContent.trim()) {
                $.ajax({
                    type: "post",
                    url: SITEURL + "/admin/ticket/" + ticketData.ticket_id,
                    data: data,
                    success: function(response) {
                        if(response?.success){
                            $('#cannedResponses').select2('destroy');
                            document.querySelector("#cannedResponses").selectedIndex = null
                            $('#cannedResponses').select2({
                                placeholder: {
                                    id: '',
                                    text: 'Canned Responses'
                                },
                                allowClear: true
                            });
                            document.querySelector('.image-uploaded').innerHTML = "";
                            // New Message will add to sidebar
                            const existingLi = Array.from(document.querySelectorAll("#chat-msg-scroll>li")).find(li => li.getAttribute('data-id') == document.querySelector("#operator-conversation").getAttribute('operator-id'));
                            if (!existingLi) {
                                const newMessageLiElement = document.createElement("li");
                                newMessageLiElement.className = "checkforactive active"
                                newMessageLiElement.setAttribute("data-id", document.querySelector(
                                    "#operator-conversation").getAttribute('operator-id'))
                                newMessageLiElement.innerHTML = `
                                    <div class="d-flex align-items-center">
                                        <div class="me-2 lh-1">
                                            <span class="avatar brround" style="${document.querySelector("#operator-conversation-Info .avatar-md").style.cssText.replace('"',"")}"></span>
                                            </div>
                                        <div class="flex-fill">
                                            <div class="mb-0 d-flex align-items-center justify-content-between">
                                                <div class="font-weight-semibold">
                                                    <a href="javascript:void(0);">${document.querySelector("#main-chat-content .responsive-userinfo-open").innerText}</a>

                                                </div>
                                                <div class="float-end text-muted fw-normal fs-12 chat-time">${new Date().toLocaleTimeString()}</div>
                                                <div class="dropdown chat-actions lh-1">
                                                    <a aria-label="anchor" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fe fe-more-vertical fs-18"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="operators/conversationdelete/${data.MessageSent.unique_id}"><i class="ri-chat-delete-line align-middle me-2 fs-18"></i>Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="chat-msg text-truncate fs-13 text-default">${data.MessageSent.message}</span>
                                                ${messageDeliveryStatus}
                                            </div>
                                        </div>
                                    </div>
                                `
                                newMessageLiElement.onclick = () => {
                                    sideMenuOpenClickFunction(newMessageLiElement);
                                }
                                document.querySelector("#chat-msg-scroll").insertBefore(newMessageLiElement,
                                    document.querySelector("#chat-msg-scroll > li"));

                            }

                            // To Scroll Down the Conversation
                            document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)

                            textarea.style.height = '49px'

                            // After message sent show the message in the top
                            document.querySelectorAll("#chat-msg-scroll > li").forEach((operatorsChat) => {
                                if (operatorsChat.getAttribute('data-id') == document.querySelector(
                                        "#operator-conversation").getAttribute('operator-id')) {
                                    operatorsChat.querySelector(".chat-msg").classList.remove("font-weight-bold")
                                    operatorsChat.querySelector(".chat-msg").innerHTML = sanitizeMessage(data.comment);
                                    operatorsChat.querySelector(".chat-time").innerText = new Date().toLocaleTimeString()
                                    if (operatorsChat.querySelector(".chat-msg").parentNode.querySelector(
                                        '.d-inline-flex')) {
                                        operatorsChat.querySelector(".chat-msg").parentNode.querySelector('.d-inline-flex')
                                            .remove()
                                    }
                                    if (document.querySelector("#messageStatusDiv")) {
                                        document.querySelector("#messageStatusDiv").remove()
                                    }
                                    // To remove the Make as unread button
                                    if (operatorsChat.querySelector(".markAsUnreadBtn")) {
                                        operatorsChat.querySelector(".markAsUnreadBtn").parentNode.remove()
                                    }
                                    const messageStatus = document.createElement("div");
                                    messageStatus.id = "messageStatusDiv"
                                    // operatorsChat.querySelector(".chat-msg").parentNode.appendChild(messageStatus)
                                    document.querySelector("#chat-msg-scroll").insertBefore(operatorsChat, document.querySelector("#chat-msg-scroll > li"));
                                }
                            })

                            // To Scroll Down the Conversation
                            operatorConversation.scrollBy(0, operatorConversation.scrollHeight)

                            if(data.status == 'Solved'){
                                document.getElementById('agentSendMessage').value = 'Solved';
                                document.getElementById('agentSendMessage').innerHTML = `{{ lang('Submit as Solved') }}`;
                            }else{
                                document.getElementById('agentSendMessage').value = 'Inprogress';
                                document.getElementById('agentSendMessage').innerHTML = `{{ lang('Submit as Inprogress') }}`;
                            }
                            toastr.success(response?.success);
                        }

                        if (response?.error) {
                            toastr.error(response.error);
                        }

                    },
                    error: function(response) {
                        if (response.responseJSON) {
                            toastr.error("The message field cannot be null")
                        }
                    }
                });
            }

            document.querySelector("#auto-expand").textContent = ''

            setTimeout(() => {
                afterMessageSend = true
            }, 500);
        }

        // Enter Message Send Function
        document.getElementById('auto-expand').addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                if (!document.querySelector("#agentSendMessage").classList.contains("d-none")) {
                    document.getElementById('agentSendMessage').click();
                }
            }
        });

        // Operator typing indaction
        var debounceTimeout2;
        var afterMessageSend = true

        document.querySelector("#auto-expand").oninput = (ele) => {
            let agentSendMessageBtn = document.querySelector("#agentSendMessage")

            // For the message Send Btn Disabled and Enabled
            if (ele.target.textContent && ticketEditPerm) {
                agentSendMessageBtn.disabled = false
                agentSendMessageBtn.classList.remove('disabled')
            } else {
                agentSendMessageBtn.disabled = true
                agentSendMessageBtn.classList.add('disabled')
            }

            let envatoId = true

            if (document.querySelector("#envato_id")) {
                envatoId = document.querySelector("#envato_id").getAttribute("readonly") == 'readonly'
            }

            clearTimeout(debounceTimeout2);
            debounceTimeout2 = setTimeout(function() {
                if (afterMessageSend) {
                    let data = {
                        message: null,
                        username: autoUserInfo.name,
                        id: autoID,
                        customerId: document.querySelector("#operator-conversation").getAttribute(
                            "operator-id"),
                        typingMessage: document.querySelector("#auto-expand").textContent,
                        agentInfo: JSON.stringify(autoUserInfo)
                    }

                    $.ajax({
                        type: "post",
                        url: SITEURL + "/livechat/broadcast-message-typing",
                        data: data,
                        success: function(data) {

                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                }
            }, 500);
        }

        var debounceTimeout;
        var debounceTimeout3;
        var debounceTimeout4;
        var pastMessage = ""

        var ticketNotificationType = "{{ setting('notificationType') }}"
        var enableswitchfonotify = "{{ setting('notificationsSounds') }}";
        var newTicketCreateWebNot = "{{ setting('newTicketCreateWebNot') }}";
        var newTicketCreateSound = "{{ asset('/uploads/livechatsounds/' . setting('newTicketCreateSound')) }}";
        var ticketNewMessaegeWebNot = "{{ setting('ticketNewMessaegeWebNot') }}";
        var ticketNewMessageCreateSound = "{{ asset('/uploads/livechatsounds/' . setting('ticketNewMessageCreateSound')) }}";
        window.newTicketSoundCurrentAudio = "";
        let ticketIntervalId;

        const ticketNotifyVibrate = () => {
            if ('vibrate' in navigator) {
                try {
                    window.navigator.vibrate([300, 100, 300]);
                } catch (error) {
                    console.error('Vibration API error:', error);
                }
            }
        }

        const ticketNotifyPlaying = (ticketId,notifysound) => {
            // Stop the current audio if it exists
            if (newTicketSoundCurrentAudio) {
                newTicketSoundCurrentAudio.pause();
                newTicketSoundCurrentAudio.currentTime = 0;
            }

            // Create a new audio element
            let audioElement = document.createElement('audio');
            audioElement.id = "audioPlayer";
            audioElement.innerHTML = `<source src="${notifysound}" allow="autoplay">`;

            // Play the new audio
            if (audioElement.paused) {
                audioElement.play();
                ticketNotifyVibrate();
            }

            // Set the new audio as the current audio
            newTicketSoundCurrentAudio = audioElement;
            newTicketSoundCurrentAudio.__proto__.cusumerId = ticketId;

            if (ticketNotificationType == "Loop") {
                const clickEventHandler = () => {
                    newTicketSoundCurrentAudio.pause();
                    clearInterval(ticketIntervalId);
                    document.querySelector(`#operator-conversation-Info`).removeEventListener('click', clickEventHandler);
                    ticketIntervalId = null
                };
                if (document.querySelector(`#operator-conversation-Info`)) {
                    document.querySelector(`#operator-conversation-Info`).addEventListener('click', clickEventHandler);
                }

                function checkAndPlaySound() {
                    if (audioElement.paused) {
                        audioElement.play();
                        ticketNotifyVibrate();
                    }
                }

                if (!ticketIntervalId) {
                    ticketIntervalId = setInterval(checkAndPlaySound, 1000);
                }
            }
        }


        function sanitizeMessage(message) {
            const tempElement = document.createElement('div');
            tempElement.innerHTML = message;
            let plainText = tempElement.textContent || tempElement.innerText || '';
            plainText = plainText.replace(/&nbsp;/g, ' ');
            return plainText;
        }

        function ticketdatappending(socket){

            if(document.querySelector('.text-center.mt-5.p-1.bg-warning-transparent.text-default')){
                document.querySelector('.text-center.mt-5.p-1.bg-warning-transparent.text-default').remove();
            }

            // sidebar data need create
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

            function humandifrformatTime(inputTime) {
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

            const sidebardata = document.createElement("li");
            sidebardata.className = "checkforactive";
            sidebardata.setAttribute("data-id", socket.ticketmessage.ticketfulldata.id);
            sidebardata.innerHTML =
            `
                <div class="d-flex align-items-start position-relative">
                    <div class="me-2 lh-1 checkingpreventclick">
                        <span class="avatar brround" style="background-color: ${socket.ticketmessage.ticketfulldata.cust.profile_bg_color}">
                            <span
                                class="new-chat-user-letter">${socket.ticketmessage.ticketfulldata.cust.username.slice(0,1).toUpperCase()}
                            </span>
                        </span>
                        ${tickettypelocalstore == 'trashedticketslocalstore' ?
                            (ticketDeletePerm || ticketRestorePerm ?
                                `<input aria-label="anchor" aria-expanded="false" type="checkbox" name="customer_checkbox[]" class="checkall ticket-select-check live-chat-checkbox all-tickets-check form-check-input" value="${socket.ticketmessage.ticketencryptedId}"/>`
                                :
                                ''
                            )
                            :
                            (ticketDeletePerm ?
                                `<input aria-label="anchor" aria-expanded="false" type="checkbox" name="customer_checkbox[]" class="checkall ticket-select-check live-chat-checkbox all-tickets-check form-check-input" value="${socket.ticketmessage.ticketencryptedId}"/>`
                                :
                                ''
                            )
                        }
                    </div>
                    <div class="flex-fill">
                        <div class="mb-0 d-flex align-items-start justify-content-between">
                            <div>
                                <div class="font-weight-semibold">
                                    <a href="javascript:void(0);" class="customer-name-change">${socket.ticketmessage.ticketfulldata.cust.username}</a>
                                    <span class="badge badge-sm badge-success">#${socket.ticketmessage.ticketfulldata.ticket_id}</span>
                                    <span class="badge badge-sm badge-danger-light overduestautsupdate${socket.ticketmessage.ticketfulldata.id} ${socket.ticketmessage.ticketfulldata.overduestatus == 'Overdue' ? '' : 'd-none'}">{{ lang('Overdue') }}</span>
                                </div>
                                <div class="font-weight-semibold">
                                    <a href="javascript:void(0);" >${socket.ticketmessage.ticketfulldata.subject}</a>
                                </div>
                            </div>
                            <div class="float-end text-muted fw-normal fs-12 chat-time" data-initial-24time="${dateTime(socket.ticketmessage.created_at)}">
                                ${humandifrformatTime(socket.ticketmessage.created_at)}
                            </div>
                            <div class="dropdown chat-actions lh-1">
                                <a aria-label="anchor" href="javascript:void(0);"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fe fe-more-vertical fs-18"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    ${tickettypelocalstore == 'trashedticketslocalstore' ?
                                        (ticketDeletePerm || ticketRestorePerm ?
                                            `<li>
                                                <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="slectTicket(event, ${socket.ticketmessage.ticketfulldata.id})" data-id="${socket.ticketmessage.ticketfulldata.id}">
                                                    <i class="ri-mail-check-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Select') }}
                                                </a>
                                            </li>`
                                            :
                                            ''
                                        )
                                        :
                                        (ticketDeletePerm ?
                                            `<li>
                                                <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="slectTicket(event, ${socket.ticketmessage.ticketfulldata.id})" data-id="${socket.ticketmessage.ticketfulldata.id}">
                                                    <i class="ri-mail-check-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Select') }}
                                                </a>
                                            </li>`
                                            :
                                            ''
                                        )
                                    }
                                    ${socket.ticketmessage.comment == 'Ticket has been deleted' && ticketRestorePerm ?
                                        `<li>
                                            <a class="dropdown-item" restoreRouteLink="${socket.ticketmessage.ticketencryptedId}">
                                                <i class="ri-refresh-line align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Restore') }}
                                            </a>
                                        </li>
                                        `
                                        :
                                        `<li>
                                            <a data-id="${socket.ticketmessage.ticketfulldata.id}" class="dropdown-item markAsUnreadBtn markAsUnReadSubmit" href="javascript:void(0);">
                                                <i class="ri-mail-unread-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>
                                                {{ lang('Mark As Unread') }}
                                            </a>
                                        </li>
                                        `
                                    }
                                    ${ticketDeletePerm ?
                                        `
                                        <li>
                                            <a class="dropdown-item" deleteRouteLink="${socket.ticketmessage.ticketencryptedId}" data-id="${socket.ticketmessage.ticketfulldata.id}" data-tickettype="${socket.ticketmessage.comment == 'Ticket has been deleted' ? 'trashed' : 'normal'}">
                                                <i class="ri-delete-bin-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Delete') }}
                                            </a>
                                        </li>
                                        ` : ''
                                    }

                                </ul>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="chat-msg text-truncate fs-13 text-default custrecentmessage" data-id="100">${sanitizeMessage(socket.ticketmessage.ticketfulldata.message)}</span>
                            ${socket.ticketmessage.ticketfulldata.myassignuser_id && socket.ticketmessage.ticketfulldata.selfassignuser_id ?
                                `<span class="avatar brround avatar-sm avatar-image-sm" style="background-image: url(${socket.ticketmessage.assigneduserprofile})" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="${socket.ticketmessage.ticketfulldata.selfassign.name}" reddy="" data-bs-original-title="" title=""></span>` : ''
                            }
                        </div>
                    </div>
                </div>
            `;

            sidebardata.onclick = () => {
                if(event.target.classList.contains('fe-more-vertical') || event.target.classList.contains('dropdown-item')){
                    deletingticfunction();
                    restoringticfunction();

                    $('.markAsReadSubmit').on('click', function (ele){
                        markAsReadSubmit(ele);
                    });

                    $('.markAsUnReadSubmit').on('click', function (ele){
                        markAsUnReadSubmit(ele);
                    });
                }else{
                    sideMenuOpenClickFunction(sidebardata);
                }
            }

            document.querySelector("#chat-msg-scroll").insertBefore(sidebardata, document.querySelector("#chat-msg-scroll > li"));
        }

        function ticketdataremoving(deletecustId){
            var activecustId = document.querySelector(`.checkforactive.active`)?.getAttribute("data-id");
            // var deletecustId = socket?.ticketmessage?.ticketfulldata?.id;
            document.querySelector(`.checkforactive[data-id="${deletecustId}"]`)?.remove()
            if(activecustId == deletecustId){
                localStorage.removeItem(tickettypelocalstore);
                document.querySelector(`#operator-conversation-Info[data-id="${deletecustId}"]`)?.remove()
                document.querySelector(`#operator-conversation[operator-id="${deletecustId}"]`)?.remove()
                document.querySelector(".chat-footer")?.classList.add("d-none")
            }
            if(document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")){
                sideMenuOpenClickFunction(document.querySelector(`.checkforactive[data-id="${document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")}"]`))
            }else{
                if(!document.querySelector('.text-center.mt-5.p-1.bg-warning-transparent.text-default')){
                    let emptyconver = document.createElement("div");
                    emptyconver.className = "text-center mt-5 px-2 py-4 bg-warning-transparent text-default mx-auto w-90 rounded-2";
                    emptyconver.innerHTML = `<span>{{ lang('As of now, there are no chat discussions in progress') }}</span>`;
                    document.querySelector("#chat-msg-scroll").append(emptyconver);
                    document.querySelector(".no-articles").classList.remove("d-none");
                }
            }
        }

        Echo.channel('liveChat').listen('ChatMessageEvent', (socket) => {
            // Conversation Messages
            let liveChatConversation = document.querySelector("#operator-conversation")

            if(socket && socket?.messageType == 'texttospeachsettingUpdated'){
                if(socket?.custdata.enabledtexttospeach == 'on' || socket?.custdata.enabledtextranslation == 'on'){
                    document.querySelectorAll('.msg-sent-time').forEach((element) => {
                        const nextAnchor = element.nextElementSibling;
                        const nextDropdown = nextAnchor?.nextElementSibling;

                        if(nextAnchor?.classList.contains('msg-send-dropdown') && nextDropdown?.classList.contains('dropdown-menu')){
                            nextAnchor.remove();
                            nextDropdown.remove();
                        }

                        const dropdownHTML = `
                        <a aria-label="anchor" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class='float-end msg-send-dropdown'>
                            <i class="fe fe-more-vertical fs-18"></i>
                        </a>
                        <ul class="dropdown-menu">
                            ${socket?.custdata.enabledtexttospeach == 'on' ?
                                `<li>
                                    <a class="dropdown-item" onclick="texttospeachbtn()">
                                        <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Text To Speach') }}
                                    </a>
                                </li>` : ''
                            }
                            ${socket?.custdata.enabledtextranslation == 'on' ?
                                `<li>
                                    <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="langtranslatebtn()">
                                        <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Language Translate') }}
                                    </a>
                                </li>` : ''
                            }
                        </ul>
                        `;

                        element.insertAdjacentHTML('afterend', dropdownHTML);
                    });
                }else{
                    document.querySelectorAll('.msg-send-dropdown').forEach((element)=>{
                        element.nextElementSibling.remove();
                        element.remove();
                    })
                }
            }

            if(socket.ticketmessagetype == 'ticketcreate' && ((tickettypelocalstore == 'myticketslocalstore' && socket.ticketmessage.selfassignuser_id == autoID) || (tickettypelocalstore == 'unassignedticketslocalstore' && socket.ticketmessage.selfassignuser_id == null && socket.ticketmessage.myassignuser_id == null) || (tickettypelocalstore == 'allticketslocalstore'))){

                if(parseInt(enableswitchfonotify) && parseInt(newTicketCreateWebNot)){
                    ticketNotifyPlaying(socket.ticketmessage.id,newTicketCreateSound);
                }

                if(document.querySelector('.text-center.mt-5.p-1.bg-warning-transparent.text-default')){
                    document.querySelector('.text-center.mt-5.p-1.bg-warning-transparent.text-default').remove();
                }

                // sidebar data need create and message also need to create
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

                function humandifrformatTime(inputTime) {
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

                if (socket.ticketmessage.selfassignuser_id && socket.ticketmessage.selfassign.image) {
                    var baseUrl = "{{ route('getprofile.url', ['imagePath' => ':imagePath', 'storage_disk' => ':storage_disk']) }}";

                    let assignuserprofile = baseUrl.replace(':imagePath', encodeURIComponent(socket.ticketmessage.selfassign.image)).replace(':storage_disk', encodeURIComponent(socket.ticketmessage.selfassign.storage_disk) ?? 'public');

                } else {
                    var assignuserprofile = "{{ asset('/uploads/profile/user-profile.png') }}";
                }
                const sidebardata = document.createElement("li");
                sidebardata.className = "checkforactive";
                sidebardata.setAttribute("data-id", socket.ticketmessage.id);
                sidebardata.innerHTML =
                `
                    <div class="d-flex align-items-start position-relative">
                        <div class="me-2 lh-1 checkingpreventclick">
                            <span class="avatar brround" style="background-color: ${socket.ticketmessage.cust.profile_bg_color}">
                                <span
                                    class="new-chat-user-letter">${socket.ticketmessage.cust.username.slice(0,1).toUpperCase()}
                                </span>
                            </span>
                            ${tickettypelocalstore == 'trashedticketslocalstore' ?
                                (ticketDeletePerm || ticketRestorePerm ?
                                    `<input aria-label="anchor" aria-expanded="false" type="checkbox" name="customer_checkbox[]" class="checkall ticket-select-check live-chat-checkbox all-tickets-check form-check-input" value="${socket.ticketmessage.encrypted_id}"/>`
                                    :
                                    ''
                                )
                                :
                                (ticketDeletePerm ?
                                    `<input aria-label="anchor" aria-expanded="false" type="checkbox" name="customer_checkbox[]" class="checkall ticket-select-check live-chat-checkbox all-tickets-check form-check-input" value="${socket.ticketmessage.encrypted_id}"/>`
                                    :
                                    ''
                                )
                            }
                        </div>
                        <div class="flex-fill">
                            <div class="mb-0 d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="font-weight-semibold">
                                        <a href="javascript:void(0);" class="customer-name-change">${socket.ticketmessage.cust.username}</a>
                                        <span class="badge badge-sm badge-success">#${socket.ticketmessage.ticket_id}</span>
                                        <span class="badge badge-sm badge-danger-light overduestautsupdate${socket.ticketmessage.id} ${socket.ticketmessage.overduestatus == 'Overdue' ? '' : 'd-none'}">{{ lang('Overdue') }}</span>
                                    </div>
                                    <div class="font-weight-semibold">
                                        <a href="javascript:void(0);" >${socket.ticketmessage.subject}</a>
                                    </div>
                                </div>
                                <div class="float-end text-muted fw-normal fs-12 chat-time" data-initial-24time="${dateTime(socket.ticketmessage.created_at)}">
                                    ${humandifrformatTime(socket.ticketmessage.created_at)}
                                </div>
                                <div class="dropdown chat-actions lh-1">
                                    <a aria-label="anchor" href="javascript:void(0);"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fe fe-more-vertical fs-18"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        ${tickettypelocalstore == 'trashedticketslocalstore' ?
                                            (ticketDeletePerm || ticketRestorePerm ?
                                                `<li>
                                                    <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="slectTicket(event, ${socket.ticketmessage.id})" data-id="${socket.ticketmessage.id}">
                                                        <i class="ri-mail-check-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Select') }}
                                                    </a>
                                                </li>`
                                                :
                                                ''
                                            )
                                            :
                                            (ticketDeletePerm ?
                                                `<li>
                                                    <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="slectTicket(event, ${socket.ticketmessage.id})" data-id="${socket.ticketmessage.id}">
                                                        <i class="ri-mail-check-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Select') }}
                                                    </a>
                                                </li>`
                                                :
                                                ''
                                            )
                                        }
                                        ${socket.ticketmessage.deleted_at == null && !ticketRestorePerm ?
                                            `<li>
                                                <a data-id="${socket.ticketmessage.id}" class="dropdown-item markAsUnreadBtn markAsUnReadSubmit" href="javascript:void(0);">
                                                    <i class="ri-mail-unread-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>
                                                    {{ lang('Mark As Unread') }}
                                                </a>
                                            </li>
                                            `
                                            :
                                            `<li>
                                                <a class="dropdown-item" restoreRouteLink="${socket.ticketmessage.encrypted_id}">
                                                    <i class="ri-refresh-line align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Restore') }}
                                                </a>
                                            </li>
                                            `
                                        }
                                        ${ticketDeletePerm ?
                                            `
                                            <li>
                                                <a class="dropdown-item" deleteRouteLink="${socket.ticketmessage.id}" data-id="${socket.ticketmessage.id}" data-tickettype="${socket.ticketmessage.deleted_at ? 'trashed' : 'normal'}">
                                                    <i class="ri-delete-bin-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Delete') }}
                                                </a>
                                            </li>
                                            ` : ''
                                        }

                                    </ul>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="chat-msg text-truncate fs-13 text-default custrecentmessage" data-id="100">${socket.ticketmessage.message}</span>
                                ${socket.ticketmessage.myassignuser_id && socket.ticketmessage.selfassignuser_id ?
                                    `<span class="avatar brround avatar-sm avatar-image-sm" style="background-image: url(${assignuserprofile})" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="${socket.ticketmessage.selfassign.name}" reddy="" data-bs-original-title="" title=""></span>` : ''
                                }
                            </div>
                        </div>
                    </div>
                `;

                sidebardata.onclick = () => {
                    if(event.target.classList.contains('fe-more-vertical') || event.target.classList.contains('dropdown-item')){
                        deletingticfunction();
                        restoringticfunction();

                        $('.markAsReadSubmit').on('click', function (ele){
                            markAsReadSubmit(ele);
                        });

                        $('.markAsUnReadSubmit').on('click', function (ele){
                            markAsUnReadSubmit(ele);
                        });
                    }else{
                        sideMenuOpenClickFunction(sidebardata);
                    }
                }

                document.querySelector("#chat-msg-scroll").insertBefore(sidebardata, document.querySelector("#chat-msg-scroll > li"));

                // message adding
                let agentLi = document.createElement("li");
                agentLi.className = "chat-item-start"
                agentLi.innerHTML = `
                    <div class="chat-list-inner">
                        <div class="chat-user-profile">
                            <span class="avatar avatar-md brround" style="background-image: url(${socket.ticketmessage.users.profileurl})">
                            </span>
                        </div>
                        <div class="ms-3">
                            <span class="chatting-user-info">
                                ${socket.ticketmessage.user_id == '741741741' ? '{{ setting('bot_name') }}' : (!socket.ticketmessage.user_id ? '~' : (socket.ticketmessage.users.id == autoUserInfo.id ? 'You' : socket.agentInfo.name))}
                                <span class="msg-sent-time">
                                    ${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}
                                </span>
                                ${texttospeachenabled == 'on' || texttranslateenabled == 'on' ?
                                    `<a aria-label="anchor" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class='float-end msg-send-dropdown'>
                                        <i class="fe fe-more-vertical fs-18"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        ${texttospeachenabled == 'on' ?
                                            `<li>
                                                <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="texttospeachbtn()">
                                                    <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Text To Speach') }}
                                                </a>
                                            </li>` : ''}
                                        ${texttranslateenabled == 'on' ?
                                            `<li>
                                                <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="langtranslatebtn()">
                                                    <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Language Translate') }}
                                                </a>
                                            </li>` : ''}
                                    </ul>`
                                    : ''
                                }
                            </span>
                            <div class="main-chat-msg">
                                <div style="white-space: pre-line;" class="text-start"><p class="mb-0 text-break" >${socket.ticketmessage.message}</p></div>
                            </div>
                        </div>
                    </div>
                `

                liveChatConversation.appendChild(agentLi)

                // To Scroll Down the Conversation
                document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)

            }

            // For the Messages Add
            if((socket.ticketid == liveChatConversation?.getAttribute("operator-id") || socket.ticketcustid == liveChatConversation?.getAttribute("operator-id")) && socket.ticketmessage && socket.ticketmessagetype == 'ticketreply'){

                if(parseInt(enableswitchfonotify) && parseInt(ticketNewMessaegeWebNot)){
                    ticketNotifyPlaying(socket.ticketid,ticketNewMessageCreateSound);
                }

                let custMessage = document.createElement("li");
                custMessage.className = "chat-item-start"
                custMessage.innerHTML = `
                    <div class="chat-list-inner">
                        <div class="chat-user-profile">
                            <span class="avatar avatar-md brround" style="background-color: ${socket.ticketmessage.profile_bg_color}">
                                <span class="new-chat-user-letter">${socket?.ticketusername?.slice(0,1).toUpperCase()}</span>
                            </span>
                        </div>
                        <div class="ms-3">
                            <span class="chatting-user-info">
                                <span class="chatnameperson">${socket.ticketusername}</span> <span class="msg-sent-time">${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}</span>
                                ${texttospeachenabled == 'on' || texttranslateenabled == 'on' ?
                                    `<a aria-label="anchor" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class='float-end msg-send-dropdown'>
                                        <i class="fe fe-more-vertical fs-18"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        ${texttospeachenabled == 'on' ?
                                            `<li>
                                                <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="texttospeachbtn()">
                                                    <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Text To Speach') }}
                                                </a>
                                            </li>` : ''}
                                        ${texttranslateenabled == 'on' ?
                                            `<li>
                                                <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="langtranslatebtn()">
                                                    <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Language Translate') }}
                                                </a>
                                            </li>` : ''}
                                    </ul>`
                                    : ''
                                }
                            </span>
                            <div class="main-chat-msg">
                                <div style="white-space: pre-line;" class="text-start"><p class="mb-0 text-break" >${socket.ticketmessage.comment}</p></div>
                                ${socket?.ticketmessage?.image_url && socket?.ticketmessage?.image_url.length > 0 ?
                                    `<div class="comment-images mt-2">` +
                                        socket.ticketmessage?.image_url.map(image =>
                                        `<img src="${image}" alt="${image}" class="img-fluid me-2" style="width: 50px; height: 50px;">`
                                    ).join('') +
                                    `</div>`
                                    : ''
                                }
                            </div>
                        </div>
                    </div>
                `

                liveChatConversation.appendChild(custMessage)

                // To Scroll Down the Conversation
                document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)
            }

            // ticket closing with the reply at that time append the sidebar data
            if(socket && socket.ticketmessagetype == 'agentticketreply' && socket?.ticketmessage?.ticketclosingreply == 'ticketclosingreply' && tickettypelocalstore == 'myclosedticketslocalstore' && !document.querySelector(`.checkforactive[data-id="${socket.ticketid}"]`)){
                ticketdatappending(socket);
            }

            // For the Agent Message Component
            if((socket.ticketid == liveChatConversation?.getAttribute("operator-id") || socket.ticketcustid == liveChatConversation?.getAttribute("operator-id")) && socket.ticketmessage && socket.ticketmessagetype == 'agentticketreply'){
                let agentLi = document.createElement("li");
                agentLi.className = "chat-item-start"
                agentLi.innerHTML = `
                    <div class="chat-list-inner">
                        <div class="chat-user-profile">
                            <span class="avatar avatar-md brround" style="background-image: url(${socket.engageUser})">
                            </span>
                        </div>
                        <div class="ms-3">
                            <span class="chatting-user-info">
                                ${socket.ticketmessage.user_id == '741741741' ? '{{ setting('bot_name') }}' : (!socket.ticketmessage.user_id ? '~' : (socket.agentInfo.id == autoUserInfo.id ? 'You' : socket.agentInfo.name))}
                                <span class="msg-sent-time">
                                    ${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}
                                </span>
                                ${texttospeachenabled == 'on' || texttranslateenabled == 'on' ?
                                    `<a aria-label="anchor" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class='float-end msg-send-dropdown'>
                                        <i class="fe fe-more-vertical fs-18"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        ${texttospeachenabled == 'on' ?
                                            `<li>
                                                <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="texttospeachbtn()">
                                                    <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Text To Speach') }}
                                                </a>
                                            </li>` : ''}
                                        ${texttranslateenabled == 'on' ?
                                            `<li>
                                                <a aria-label="anchor" class="dropdown-item" href="javascript:void(0);" onclick="langtranslatebtn()">
                                                    <i class="ri-volume-up-fill align-middle me-2 fs-17 text-muted chat-info-optionicon d-inline-block"></i>{{ lang('Language Translate') }}
                                                </a>
                                            </li>` : ''}
                                    </ul>`
                                    : ''
                                }
                            </span>
                            <div class="main-chat-msg">
                                <div style="white-space: pre-line;" class="text-start"><p class="mb-0 text-break" >${socket.ticketmessage.comment}</p></div>
                                ${socket.ticketmessage.image_url && socket.ticketmessage.image_url.length > 0 ?
                                    `<div class="comment-images mt-2">` +
                                        socket.ticketmessage.image_url.map(image =>
                                        `<img onclick="AllFileViewer(this)" imageSrc="${image}" src="${image}" alt="${image}" class="img-fluid me-2" style="width: 50px; height: 50px;">`
                                    ).join('') +
                                    `</div>`
                                    : ''
                                }
                            </div>
                        </div>
                    </div>
                `

                liveChatConversation.appendChild(agentLi)

                // To Scroll Down the Conversation
                document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)
            }

            let timenow = new Date();
            let timehours = now.getHours();
            let timeminutes = now.getMinutes();
            let timeperiod = hours >= 12 ? "PM" : "AM";
            timehours = timehours % 12 || 12;
            timeminutes = timeminutes < 10 ? '0' + timeminutes : timeminutes;
            let presenttime = `${timehours}:${timeminutes} ${timeperiod}`;

            // To Update the Message in the Side Bar
            if(document.querySelector(`.checkforactive[data-id='${socket.ticketid}']`) && socket.ticketmessage && (socket.ticketmessagetype == 'ticketreply' || socket.ticketmessagetype == 'agentticketreply')){
                document.querySelector(`.checkforactive[data-id='${socket.ticketid}'] .chat-msg`).innerHTML = sanitizeMessage(socket.ticketmessage.comment);
                document.querySelector(`.checkforactive[data-id='${socket.ticketid}'] .chat-time`).innerText = `${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}`;
                if (!document.querySelector(`.checkforactive[data-id='${socket.ticketid}']`).classList.contains("active")) {
                    // To add the font bold in sidebar
                    document.querySelector(`.checkforactive[data-id='${socket.ticketid}'] .chat-msg`).classList.add("font-weight-bold")
                }
                clearTimeout(debounceTimeout4);
                pastMessage.__proto__.ticketid = ""

                // For the update unread index number
                if (!document.querySelector(`.checkforactive[data-id='${socket.ticketid}']`).classList.contains("active")) {
                    let unReadIndexNumberVar = document.querySelector(`.checkforactive[data-id='${socket.ticketid}'] .unReadIndexNumber`)
                    if (unReadIndexNumberVar) {
                        unReadIndexNumberVar.innerText = parseInt(unReadIndexNumberVar.innerText) + 1
                    } else {
                        var chatMsgSpan = document.querySelector(`.checkforactive[data-id='${socket.ticketid}'] .chat-msg`);
                        var newSpan = document.createElement('span');
                        newSpan.className =
                            'ms-auto me-2 badge bg-success-transparent rounded-circle unReadIndexNumber';
                        newSpan.textContent = '1';
                        chatMsgSpan.parentNode.insertBefore(newSpan, chatMsgSpan.nextSibling);
                    }
                }

                // To add the Top in the Sidebar
                document.querySelector("#chat-msg-scroll").insertBefore(document.querySelector(`.checkforactive[data-id='${socket.ticketid}']`), document.querySelector("#chat-msg-scroll > li"))
            }

            // deleted ticket is directly append to the trashed ticket sidebar
            if(socket && socket.ticketmessagetype == 'tickethistory' && socket?.ticketmessage?.ticketdeletingaction == 'ticketdeletingaction' && tickettypelocalstore == 'trashedticketslocalstore' && !document.querySelector(`.checkforactive[data-id="${socket.ticketid}"]`) && socket?.ticketmessage?.user_id == autoID){
                ticketdatappending(socket);
            }

            // deleted the ticket then remove it in another opened page
            if(socket && socket.ticketmessagetype == 'tickethistory' && socket?.ticketmessage?.ticketdeletingaction == 'ticketdeletingaction' && ((tickettypelocalstore == 'myticketslocalstore' && socket?.ticketmessage?.ticketfulldata?.selfassignuser_id == autoID) || (tickettypelocalstore == 'unassignedticketslocalstore' && socket?.ticketmessage?.ticketfulldata?.selfassignuser_id == null && socket?.ticketmessage?.ticketfulldata?.myassignuser_id == null) || (tickettypelocalstore == 'allticketslocalstore')) && socket?.ticketmessage?.user_id == autoID){
                ticketdataremoving(socket?.ticketmessage?.ticketfulldata?.id);
            }

            // restoring the ticket data
            if(socket && socket.ticketmessagetype == 'tickethistory' && socket?.ticketmessage?.ticketrestoringaction == 'ticketrestoringaction' && ((tickettypelocalstore == 'myticketslocalstore' && socket?.ticketmessage?.ticketfulldata?.selfassignuser_id == autoID) || (tickettypelocalstore == 'unassignedticketslocalstore' && socket?.ticketmessage?.ticketfulldata?.selfassignuser_id == null && socket?.ticketmessage?.ticketfulldata?.myassignuser_id == null) || (tickettypelocalstore == 'allticketslocalstore')) && !document.querySelector(`.checkforactive[data-id="${socket.ticketid}"]`) && socket?.ticketmessage?.user_id == autoID){
                ticketdatappending(socket);
            }

            if(socket && socket.ticketmessagetype == 'tickethistory' && socket?.ticketmessage?.ticketrestoringaction == 'ticketrestoringaction' && tickettypelocalstore == 'trashedticketslocalstore' && socket?.ticketmessage?.user_id == autoID){
                ticketdataremoving(socket?.ticketmessage?.ticketfulldata?.id);
            }

            if(socket && socket.ticketmessagetype == 'tickethistory' && socket?.ticketmessage?.ticketpermdeletingaction == 'ticketpermdeletingaction' && tickettypelocalstore == 'trashedticketslocalstore' && socket?.ticketmessage?.user_id == autoID){
                ticketdataremoving(socket?.ticketmessage?.ticket_id);
            }

            // ticket history adding and append the ticket inoformation data
            if(document.querySelector(`.checkforactive[data-id='${socket.ticketid}']`) && document.querySelector(`#operator-conversation[operator-id="${socket.ticketid}"]`) && socket.ticketmessagetype == 'tickethistory'){
                let commentMessage = document.createElement("li");
                commentMessage.className = "chat-join-notify"
                commentMessage.innerHTML = `
                    <span>${socket.ticketmessage.comment} by ${socket.ticketmessage.comment == 'Bot Responded to this ticket' ? '{{ setting('bot_name') }}' : socket.ticketusername.replace(autoUserInfo.name,'You')} at ${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}</span>
                `

                liveChatConversation.appendChild(commentMessage)

                if(socket.ticketmessage.comment == 'Ticket category changed'){
                    document.querySelector('#ticketinfofethc').innerHTML = socket.ticketmessage.ticketinfo;
                }

                if(socket.ticketmessage.comment == 'Ticket priority changed'){
                    document.querySelector('#ticketinfofethc').innerHTML = socket.ticketmessage.ticketinfo;
                }

                if(socket.ticketmessage.comment == 'Ticket status changed to Solved' || socket.ticketmessage.comment == 'This ticket is auto closed'){
                    document.querySelector('#ticketinfofethc').innerHTML = socket.ticketmessage.ticketinfo;
                    document.getElementById('agentSendMessage').value = 'Solved';
                    document.getElementById('agentSendMessage').innerHTML = `{{ lang('Submit as Solved') }}`;
                }

                if(socket.ticketmessage.comment == 'Ticket status changed to Inprogress'){
                    document.querySelector('#ticketinfofethc').innerHTML = socket.ticketmessage.ticketinfo;
                    document.getElementById('agentSendMessage').value = 'Inprogress';
                    document.getElementById('agentSendMessage').innerHTML = `{{ lang('Submit as Inprogress') }}`;
                }

                if(socket.ticketmessage.comment.includes("Ticket has been assigned to")){
                    document.querySelector('#ticketinfofethc').innerHTML = socket.ticketmessage.ticketinfo;

                    const element = document.querySelector(`.checkforactive[data-id='${socket.ticketid}']`)?.querySelector('.avatar.brround.avatar-sm.avatar-image-sm');
                    if (element) {
                        element.style.backgroundImage = `url(${socket.ticketmessage.assigneruserprofile})`;
                        element.setAttribute('data-bs-title', socket.ticketmessage.assignerusername);

                        const tooltip = bootstrap.Tooltip.getInstance(element);
                        if (tooltip) {
                            tooltip.dispose();
                        }
                        new bootstrap.Tooltip(element);
                    }
                }

                if(socket.ticketmessage.comment == 'Ticket has been unassigned'){
                    document.querySelector('#ticketinfofethc').innerHTML = socket.ticketmessage.ticketinfo;
                    document.querySelector(`.checkforactive[data-id='${socket.ticketid}']`)?.querySelector('.avatar.brround.avatar-sm.avatar-image-sm').remove();
                }

                if(socket.ticketmessage.comment == 'Ticket note created'){
                    document.querySelector('#ticketinfofethc').innerHTML = socket.ticketmessage.ticketinfo;
                }

                if(socket.ticketmessage.comment == 'This ticket has been auto overdue'){
                    document.querySelectorAll(`.overduestautsupdate${socket.ticketid}`).forEach((ele) => {
                        ele.classList.remove('d-none');
                    })
                }

                return
            }
        });
    </script>

    <script type="text/javascript">
        function responsivechat() {

            /* NEW JS FOR LIVE CHAT RESPONSIVE */
            document.querySelectorAll(".checkforactive").forEach((ele) => {
                ele.addEventListener("click", () => {
                    document.querySelector(".main-chart-wrapper").classList.add("responsive-chat-open")
                })
            })

            document.querySelectorAll(".responsive-userinfo-open").forEach((ele) => {
                ele.addEventListener("click", () => {
                    document.querySelector(".main-chat-user-details").classList.add("open");
                });
            });

            document.querySelector(".responsive-chat-close")?.addEventListener("click", () => {
                document.querySelector(".main-chart-wrapper").classList.remove("responsive-chat-open")
            })

            document.querySelector(".responsive-chat-close2")?.addEventListener("click",() =>{
                document.querySelector(".main-chat-user-details").classList.remove("open")
            })

            document.querySelector(".chat-info")?.addEventListener("click", () => {
                document.querySelector(".main-chat-user-details").classList.remove("open")
            })
            document.querySelector(".chat-content")?.addEventListener("click", () => {
                document.querySelector(".main-chat-user-details").classList.remove("open")
            })
            /* NEW JS FOR LIVE CHAT RESPONSIVE */
        }
    </script>

    <script>
        let voices = [];
        let arabicVoice = null;
        let defaultVoice = null;

        function loadVoices() {
            voices = speechSynthesis.getVoices();
        }

        async function translateText(text, targetLang) {
            const response = await fetch('{{ route('texttospeech.lang.translate.back') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    text: text,
                    target_lang: targetLang
                })
            });

            if (!response.ok) {
                throw new Error('Translation failed');
            }

            const data = await response.json();
            return data.translatedText;
        }

        async function speakText() {
            const textInput = document.getElementById('texttospeachcontent').value;
            const selectedLang = document.getElementById('lang-select').value;
            try {
                const translatedText = await translateText(textInput, selectedLang);
                const utterance = new SpeechSynthesisUtterance(translatedText);
                const matchingVoice = voices.find(voice => voice.lang.startsWith(selectedLang));

                if (matchingVoice) {
                    utterance.voice = matchingVoice;
                    utterance.lang = selectedLang;
                } else {
                    alert(`No voice is available for the selected language: ${selectedLang}. The browser will use a default voice.`);
                }

                speechSynthesis.cancel();
                speechSynthesis.speak(utterance);
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function stop() {
            speechSynthesis.cancel();
        }

        function pause() {
            speechSynthesis.pause();
        }

        function resume() {
            speechSynthesis.resume();
        }

        window.addEventListener('load', () => {
            loadVoices();
            speechSynthesis.onvoiceschanged = loadVoices;
        });
        window.addEventListener('beforeunload', () => {
            if (speechSynthesis.speaking) {
                stop();
            }
        });
    </script>

@endsection
