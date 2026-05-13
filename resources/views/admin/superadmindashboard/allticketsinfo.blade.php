<div class="card-header  border-0">
    <div class="card-title">{{lang('Ticket Information')}}</div>
</div>
<div class="card-body pt-2 ps-0 pe-0 pb-0">
    <div class="table-responsive tr-lastchild">
        <table class="table mb-0 table-information">
            <tbody>

                <tr>
                    <td>
                        <span class="w-50">{{lang('Ticket ID')}}</span>
                    </td>
                    <td>:</td>
                    <td>
                        <span class="font-weight-semibold">#{{ $ticket->ticket_id }}</span>
                    </td>
                </tr>
                <tr>
                    @if($ticket->purchasecode != null  )
                        @if (decrypt($ticket->purchasecode) != 'undefined')
                            <!-- Purchase Code Details -->
                            <div class="">
                                <div class="ps-0 pe-0 pb-0">
                                    <div class="">
                                        <td>
                                            <span class="w-50">{{lang('Purchase Code')}}</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            @if(!empty(Auth::user()->getRoleNames()[0]) && Auth::user()->getRoleNames()[0] == 'superadmin')

                                                <span class="bg-warning-transparent text-dark">{{decrypt($ticket->purchasecode)}}</span>
                                            @else
                                                @if(setting('purchasecode_on') == 'on')

                                                <span class="bg-warning-transparent text-dark">{{decrypt($ticket->purchasecode)}}</span>
                                                @else

                                                <span class="bg-warning-transparent text-dark">{{ Str::padLeft(Str::substr(decrypt($ticket->purchasecode), -4), Str::length(decrypt($ticket->purchasecode)), Str::padLeft('*', 1)) }}</span>
                                                @endif
                                            @endif
                                            <button class="btn btn-sm btn-dark leading-tight my-1" id="purchasecodebtn" data-id="{{ $ticket->purchasecode }}">{{lang('View Details')}}</button>
                                            @if($ticket->purchasecodesupport == 'Supported')

                                            <span class="badge btn btn-sm badge-success my-1">{{lang('Support Active')}}</span>
                                            @elseif($ticket->purchasecodesupport == 'Expired')

                                            <span class="badge btn btn-sm text-white cursor-default badge-danger my-1">{{lang('Support Expired')}}</span>
                                            @else
                                            @endif
                                        </td>

                                    </div>
                                </div>
                            </div>
                            <!-- End Purchase Code Details -->
                        @endif
                    @endif
                </tr>
                @if ($ticket->item_name != null)
                    <tr>
                        <td>
                            <span class="w-50">{{lang('Item Name')}}</span>
                        </td>
                        <td>:</td>
                        <td>
                            <span class="font-weight-semibold">{{ $ticket->item_name }}</span>
                        </td>
                    </tr>
                @endif
                <tr>
                    <td>
                        <span class="w-50">{{lang('Open Date')}}</span>
                    </td>
                    <td>:</td>
                    <td>
                        <span class="font-weight-semibold">{{ \Carbon\Carbon::parse($ticket->created_at)->timezone(timeZoneData())->format(setting('date_format'))}}</span>
                    </td>
                </tr>
                @php
                    $ccemailsend = \App\Models\CCMAILS::where('ticket_id', $ticket->id)->first();
                @endphp
                @if ($ccemailsend && $ccemailsend->ccemails)
                    <tr>
                        <td>
                            <span class="w-50">{{lang('CC Mail')}}</span>
                        </td>
                        <td>:</td>
                        <td>
                            <span class="font-weight-semibold">{{ $ccemailsend->ccemails }}</span>
                        </td>
                    </tr>
                @endif
                <tr>
                    <td>
                        <span class="w-50">{{lang('Category')}}</span>
                    </td>
                    <td>:</td>
                    <td>
                        @if($ticket->deleted_at != null || $ticket->status == 'Closed')
                            ~
                        @else
                            @if($ticket->category_id != null)
                                @if($ticket->category != null)

                                    <span class="font-weight-semibold" id="socketcatchangedata">{{ $ticket->category->name}}</span>
                                    @can('Ticket Edit')
                                        @if ($ticket->status != 'Closed')

                                            <a href="javascript:void(0)" data-id="{{$ticket->ticket_id}}" class="p-1 sprukocategory border border-primary br-7 text-white bg-primary ms-2 d-inline-flex"> <i class="feather feather-edit-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Change Category')}}"></i></a>


                                        @endif
                                    @endcan
                                @else
                                    @can('Ticket Edit')
                                        @if ($ticket->status != 'Closed')
                                        <a href="javascript:void(0)" data-id="{{$ticket->ticket_id}}" class="p-1 sprukocategory border border-primary br-7 text-white bg-primary" > <i class="feather feather-plus" data-toggle="tooltip" data-bs-placement="top" title="{{lang('Add Category')}}"></i></a>
                                        @endif
                                    @endcan
                                @endif
                            @else
                                @can('Ticket Edit')
                                    @if ($ticket->status != 'Closed')
                                        <a href="javascript:void(0)" data-id="{{$ticket->ticket_id}}" class="p-1 sprukocategory border border-primary br-7 text-white bg-primary" > <i class="feather feather-plus" data-toggle="tooltip" data-bs-placement="top" title="{{lang('Add Category')}}"></i></a>
                                    @endif
                                @endcan
                            @endif
                        @endif

                    </td>
                </tr>
                @if ($ticket->subcategory != null && $ticket->subcategoriess != null)
                    <tr>
                        <td>
                            <span class="w-50">{{lang('SubCategory')}}</span>
                        </td>
                        <td>:</td>
                        <td>
                            @if($ticket->deleted_at != null || $ticket->status == 'Closed')
                                ~
                            @else
                                <span class="font-weight-semibold">{{$ticket->subcategoriess->subcategoryname}}</span>
                            @endif
                        </td>
                    </tr>
                @endif
                @if ($ticket->item_name != null && $ticket->status != 'Closed')
                    <tr>
                        <td>
                            <span class="w-50">{{lang('Item name')}}</span>
                        </td>
                        <td>:</td>
                        <td>
                            <span class="font-weight-semibold">{{$ticket->item_name}}</span>


                        </td>
                    </tr>
                @endif
                @if($ticket->priority != null)
                    <tr>
                        <td>
                            <span class="w-50">{{lang('Priority')}}</span>
                        </td>
                        <td>:</td>
                        <td id="priorityid">
                            @if($ticket->deleted_at != null || $ticket->status == 'Closed')
                                ~
                            @else
                                @if($ticket->priority == "Low")

                                    <span class="badge badge-success-light" id="socketprioritychangedata">{{ lang($ticket->priority) }}</span>
                                    @can('Ticket Edit')
                                        @if ($ticket->status != 'Closed')
                                            <a href="javascript:void(0)" data-ticketid="{{ $ticket->id }}" data-priority="{{ $ticket->priority }}" id="changePriority" class="p-1 border border-primary br-7 text-white bg-primary ms-2">
                                                <i class="feather feather-edit-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Change priority" aria-label="Add priority"></i>
                                            </a>
                                        @endif
                                    @endcan
                                @elseif($ticket->priority == "High")

                                    <span class="badge badge-danger-light" id="socketprioritychangedata">{{ lang($ticket->priority)}}</span>
                                    @can('Ticket Edit')
                                        @if ($ticket->status != 'Closed')
                                        <a href="javascript:void(0)" data-ticketid="{{ $ticket->id }}" data-priority="{{ $ticket->priority }}" id="changePriority" class="p-1 border border-primary br-7 text-white bg-primary ms-2">
                                            <i class="feather feather-edit-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Change priority" aria-label="Add priority"></i>
                                        </a>
                                        @endif
                                    @endcan
                                @elseif($ticket->priority == "Critical")

                                    <span class="badge badge-danger-dark" id="socketprioritychangedata">{{ lang($ticket->priority)}}</span>
                                    @can('Ticket Edit')
                                        @if ($ticket->status != 'Closed')
                                            <a href="javascript:void(0)" data-ticketid="{{ $ticket->id }}" data-priority="{{ $ticket->priority }}" id="changePriority" class="p-1 border border-primary br-7 text-white bg-primary ms-2">
                                                <i class="feather feather-edit-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Change priority" aria-label="Add priority"></i>
                                            </a>
                                        @endif
                                    @endcan
                                @else

                                    <span class="badge badge-warning-light" id="socketprioritychangedata">{{ lang($ticket->priority) }}</span>
                                    @can('Ticket Edit')
                                        @if ($ticket->status != 'Closed')
                                            <a href="javascript:void(0)" data-ticketid="{{ $ticket->id }}" data-priority="{{ $ticket->priority }}" id="changePriority" class="p-1 border border-primary br-7 text-white bg-primary ms-2">
                                                <i class="feather feather-edit-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Change priority" aria-label="Add priority"></i>
                                            </a>
                                        @endif
                                    @endcan
                                @endif
                            @endif
                        </td>
                    </tr>
                @else

                    <tr>
                        <td>
                            <span class="w-50">{{lang('Priority')}}</span>
                        </td>
                        <td>:</td>
                        <td id="priorityid">
                            @can('Ticket Edit')
                                @if($ticket->deleted_at != null || $ticket->status == 'Closed')
                                    ~
                                @else
                                    @if ($ticket->status != 'Closed')
                                    <a href="javascript:void(0)" id="changePriority" data-ticketid="{{ $ticket->id }}" data-priority="nopriority" class="p-1 border border-primary br-7 text-white bg-primary">
                                        <i class="feather feather-plus" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Change priority" aria-label="Add priority"></i>
                                    </a>
                                    @endif
                                @endif
                            @else
                                ~
                            @endcan
                        </td>
                    </tr>
                @endif
                {{-- start tikcet note --}}
                <tr>
                    <td>
                        <span class="w-50">{{lang('Note')}}</span>
                    </td>
                    <td>:</td>
                    <td>
                        @can('Ticket Edit')
                            @if($ticket->deleted_at != null || $ticket->status == 'Closed')
                                ~
                            @else
                                @if ($ticket->status != 'Closed')
                                    <a href="javascript:void(0)" class="p-0  br-7 text-white position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 48 48"><path fill="#CDD6E0" d="M12.8 4.6H38c1.1 0 2 .9 2 2V46c0 1.1-.9 2-2 2H6.7c-1.1 0-2-.9-2-2V12.7l8.1-8.1z"></path><path fill="#ffffff" d="M.1 41.4V10.9L11 0h22.4c1.1 0 2 .9 2 2v39.4c0 1.1-.9 2-2 2H2.1c-1.1 0-2-.9-2-2z"></path><path fill="#CDD6E0" d="M11 8.9c0 1.1-.9 2-2 2H.1L11 0v8.9z"></path><path fill="#FFD05C" d="M15.5 8.6h13.8v2.5H15.5z"></path><path fill="#dbe0ef" d="M6.3 31.4h9.8v2.5H6.3zM6.3 23.8h22.9v2.5H6.3zM6.3 16.2h22.9v2.5H6.3z"></path><path fill="#FFD15C" d="M22.8 35.7l-2.6 6.4 6.4-2.6z"></path><path fill="#334A5E" d="M21.4 39l-1.2 3.1 3.1-1.2z"></path><path fill="#FF7058" d="M30.1 18h5.5v23h-5.5z" transform="rotate(-134.999 32.833 29.482)"></path><path fill="#40596B" d="M46.2 15l1 1c.8.8.8 2 0 2.8l-2.7 2.7-3.9-3.9 2.7-2.7c.9-.6 2.2-.6 2.9.1z"></path><path fill="#F2F2F2" d="M39.1 19.3h5.4v2.4h-5.4z" transform="rotate(-134.999 41.778 20.536)"></path></svg>
                                        <span class="badge {{ $ticket->ticketnote()->count() > 0 ? 'badge-success' : 'badge-gray' }} ticket-count-style">{{ $ticket->ticketnote()->count() }}</span>
                                    </a>

                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                                        <div class="offcanvas-header">
                                            <h5 id="offcanvasRightLabel">{{ lang('Ticket Note') }}</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fe fe-x"></i></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <form method="POST" enctype="multipart/form-data" id="note_form" name="note_form">
                                                <input type="hidden" name="ticket_id" id="ticket_id" value="{{ $ticket->id }}">
                                                @csrf
                                                @honeypot
                                                {{-- <div class="modal-body"> --}}

                                                    <div class="form-group">
                                                        <label class="form-label">{{lang('Add Note:')}}</label>
                                                        <textarea class="form-control" rows="4" name="ticketnote" id="note" required></textarea>
                                                        <span id="noteError" class="text-danger alert-message"></span>
                                                    </div>

                                                {{-- </div> --}}
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-secondary" id="btnsave"  >{{lang('Save')}}</button>
                                                </div>
                                            </form>

                                            @php $emptynote = $ticket->ticketnote()->get() @endphp
                                            @if($emptynote->isNOtEmpty())
                                                <div class="card-body  item-user">
                                                    <div id="refresh">

                                                        @foreach ($ticket->ticketnote()->latest()->get() as $note)

                                                        <div class="alert alert-light-warning ticketnote" id="ticketnote_{{$note->id}}" role="alert">
                                                            @if($note->user_id == Auth::id() || Auth::user()->getRoleNames()[0] == 'superadmin')

                                                            <a href="javascript:" class="ticketnotedelete" data-id="{{$note->id}}" onclick="deletePost(event.target)">
                                                                <i class="feather feather-x" data-id="{{$note->id}}" ></i>
                                                            </a>
                                                            @endif

                                                            <p class="m-0">{{$note->ticketnotes}}</p>
                                                            <p class="text-end mb-0"><small><i><b>{{$note->users->name}}</b> @if(!empty($note->users->getRoleNames()[0])) ({{$note->users->getRoleNames()[0]}}) @endif</i></small></p>
                                                        </div>
                                                        @endforeach

                                                    </div>
                                                </div>
                                            @else
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <div class="avatar avatar-xxl empty-block mb-4">
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="50" width="50" viewBox="0 0 48 48"><path fill="#CDD6E0" d="M12.8 4.6H38c1.1 0 2 .9 2 2V46c0 1.1-.9 2-2 2H6.7c-1.1 0-2-.9-2-2V12.7l8.1-8.1z"/><path fill="#ffffff" d="M.1 41.4V10.9L11 0h22.4c1.1 0 2 .9 2 2v39.4c0 1.1-.9 2-2 2H2.1c-1.1 0-2-.9-2-2z"/><path fill="#CDD6E0" d="M11 8.9c0 1.1-.9 2-2 2H.1L11 0v8.9z"/><path fill="#FFD05C" d="M15.5 8.6h13.8v2.5H15.5z"/><path fill="#dbe0ef" d="M6.3 31.4h9.8v2.5H6.3zM6.3 23.8h22.9v2.5H6.3zM6.3 16.2h22.9v2.5H6.3z"/><path fill="#FFD15C" d="M22.8 35.7l-2.6 6.4 6.4-2.6z"/><path fill="#334A5E" d="M21.4 39l-1.2 3.1 3.1-1.2z"/><path fill="#FF7058" d="M30.1 18h5.5v23h-5.5z" transform="rotate(-134.999 32.833 29.482)"/><path fill="#40596B" d="M46.2 15l1 1c.8.8.8 2 0 2.8l-2.7 2.7-3.9-3.9 2.7-2.7c.9-.6 2.2-.6 2.9.1z"/><path fill="#F2F2F2" d="M39.1 19.3h5.4v2.4h-5.4z" transform="rotate(-134.999 41.778 20.536)"/></svg>
                                                        </div>
                                                        <h4 class="mb-2">{{lang('Don’t have any notes yet')}}</h4>
                                                        <span class="text-muted">{{lang('Add your notes here')}}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @else
                            ~
                        @endcan
                    </td>
                </tr>
                {{-- end tikcet note --}}
                <tr>
                    <td>
                        <span class="w-50">{{lang('Status')}}</span>
                    </td>
                    <td>:</td>
                    <td>
                        @can('Ticket Edit')
                            @if($ticket->deleted_at != null)
                                <button aria-label="anchor" class="btn-reply btn-outline-primary btn shadow-none" href="javascript:void(0)" disabled>{{ lang('Solved') }}</button>
                            @else
                                @if($ticket->status == 'Suspend' || $ticket->status == 'Closed')

                                    @if(Auth::user()->can('Closed Ticket Process'))
                                        <div class="btn-group">
                                            <button aria-label="anchor" id="buttonvaluechange" class="btn-reply btn-outline-primary btn shadow-none" href="javascript:void(0)">
                                                @if($ticket->status == "Inprogress")
                                                    {{ lang($ticket->status) }}
                                                @elseif($ticket->status == "Closed")
                                                    {{ lang('Solved') }}
                                                @else
                                                    {{ lang($ticket->status) }}
                                                @endif
                                            </button>
                                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split shadow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <input type="hidden" id="changeaccordinglydropdown" value="Inprogress">
                                            <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0)" data-id="{{ encrypt($ticket->id) }}" id="directinprogress">{{ lang('Inprogress') }}</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" data-id="{{ encrypt($ticket->id) }}" id="directsolvedticinfo">{{ lang('Solved') }}</a></li>
                                            </ul>
                                        </div>
                                    @else
                                        <button aria-label="anchor" class="btn-reply btn-outline-primary btn shadow-none" href="javascript:void(0)" disabled>{{ lang('Solved') }}</button>
                                    @endif
                                @else
                                    <div class="btn-group">
                                        <button aria-label="anchor" id="buttonvaluechange" class="btn-reply btn-outline-primary btn shadow-none" href="javascript:void(0)">
                                            @if($ticket->status == "Inprogress")
                                                {{ lang($ticket->status) }}
                                            @elseif($ticket->status == "Closed")
                                                {{ lang('Solved') }}
                                            @else
                                                {{ lang($ticket->status) }}
                                            @endif
                                        </button>
                                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split shadow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <input type="hidden" id="changeaccordinglydropdown" value="Inprogress">
                                        <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0)" data-id="{{ encrypt($ticket->id) }}" id="directinprogress">{{ lang('Inprogress') }}</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)" data-id="{{ encrypt($ticket->id) }}" id="directsolvedticinfo">{{ lang('Solved') }}</a></li>
                                        </ul>
                                    </div>
                                @endif
                            @endif
                        @else
                            <button aria-label="anchor" class="btn-reply btn-outline-primary btn shadow-none" href="javascript:void(0)" disabled>{{ lang($ticket->status) }}</button>
                        @endcan
                        <span class="badge badge-sm badge-danger-light overduestautsupdate{{ $ticket->id }} {{ $ticket->overduestatus == 'Overdue' ? '' : 'd-none' }}">{{ lang('Overdue') }}</span>
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="w-50">{{lang('Assign')}}</span>
                    </td>
                    <td>:</td>
                    <td>
                        @can('Ticket Assign')
                            @if($ticket->status == 'Suspend' || $ticket->status == 'Closed' || $ticket->deleted_at != null)
                                <div class="btn-group">
                                    @if($ticket->selfassignuser_id != null)
                                        <button class="btn btn-outline-primary btn-sm" disabled>{{$ticket->selfassign->name}} (@if($ticket->selfassignuser_id != Auth::id()){{lang('Other')}}@else{{lang('You')}}@endif)</button>
                                        <button class="btn btn-outline-primary btn-sm" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Unassign')}}" aria-label="Unassign"><i class="fe fe-x"></i></button>
                                    @else
                                        <button class="btn btn-outline-primary btn-sm" disabled>{{lang('Assign')}}</button>
                                    @endif
                                </div>
                            @else
                                <div class="btn-group">
                                    @if($ticket->selfassignuser_id != null)
                                        <a class="btn btn-outline-primary btn-sm" href="javascript:void(0)" data-id="{{encrypt($ticket->id)}}" id="assigned">{{$ticket->selfassign->name}} (@if($ticket->selfassignuser_id != Auth::id()){{lang('Other')}}@else{{lang('You')}}@endif)</a>
                                        <a href="javascript:void(0)" data-id="{{encrypt($ticket->id)}}" class="btn btn-outline-primary btn-sm" id="btnremove" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Unassign')}}" aria-label="Unassign"><i class="fe fe-x"></i></a>
                                    @else
                                        <a class="btn btn-outline-primary btn-sm" href="javascript:void(0)" data-id="{{encrypt($ticket->id)}}" id="assigned">{{lang('Assign')}}</a>
                                    @endif

                                    {{-- <ul class="dropdown-menu" role="menu">
                                        <li class="dropdown-plus-title">{{lang('Assign')}} <b aria-hidden="true" class="fa fa-angle-up"></b></li>
                                        <li>
                                            <a href="javascript:void(0);" id="selfassigid" data-id="{{encrypt($ticket->id)}}">{{lang('Self Assign')}}</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" data-id="{{encrypt($ticket->id)}}" id="assigned">
                                            {{lang('Other Assign')}}
                                            </a>
                                        </li>
                                    </ul> --}}
                                </div>
                            @endif
                        @else
                            ~
                        @endcan
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
