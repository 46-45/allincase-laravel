<!--aside open-->
<aside class="app-sidebar">
    <div class="app-sidebar__logo">
        <a class="header-brand" href="{{url('admin')}}">
            {{--Logo--}}
            @if ($title->image == null)
                <img src="{{asset('uploads/logo/logo/logo-white.png')}}" class="header-brand-img dark-logo" alt="logo">
            @else
                <img src="{{ route('getImage.url', ['imagePath' => 'uploads*logo*logo*'.$title->image, 'storage_disk' => $title->image_storage_disk ?? 'public']) }}" class="header-brand-img dark-logo" alt="logo">
            @endif

            {{--Dark-Logo--}}
            @if ($title->image1 == null)
                <img src="{{asset('uploads/logo/darklogo/logo.png')}}" class="header-brand-img desktop-lgo" alt="dark-logo">
            @else
                <img src="{{ route('getImage.url', ['imagePath' => 'uploads*logo*darklogo*'.$title->image1, 'storage_disk' => $title->image1_storage_disk ?? 'public']) }}" class="header-brand-img desktop-lgo" alt="dark-logo">
            @endif

            {{--Mobile-Logo--}}
            @if ($title->image2 == null)
                <img src="{{asset('uploads/logo/icon/icon.png')}}" class="header-brand-img mobile-logo" alt="mobile-logo">
            @else
                <img src="{{ route('getImage.url', ['imagePath' => 'uploads*logo*icon*'.$title->image2, 'storage_disk' => $title->image2_storage_disk ?? 'public']) }}" class="header-brand-img mobile-logo" alt="mobile-logo">
            @endif

            {{--Mobile-Dark-Logo--}}
            @if ($title->image3 == null)
                <img src="{{asset('uploads/logo/darkicon/icon-white.png')}}" class="header-brand-img darkmobile-logo" alt="mobile-dark-logo">
            @else
                <img src="{{ route('getImage.url', ['imagePath' => 'uploads*logo*darkicon*'.$title->image3, 'storage_disk' => $title->image3_storage_disk ?? 'public']) }}" class="header-brand-img darkmobile-logo" alt="mobile-dark-logo">
            @endif
        </a>
    </div>
    <div class="app-sidebar3">
        <div class="app-sidebar__user">
            <div class="dropdown user-pro-body text-center">
                <div class="user-pic">
                    @if (Auth::user()->image == null)

                    <img src="{{asset('uploads/profile/user-profile.png')}}" class="avatar-xxl rounded-circle mb-1"
                        alt="default">
                    @else
                    <img src="{{ route('getprofile.url', ['imagePath' =>Auth::user()->image,'storage_disk'=>Auth::user()->storage_disk ?? 'public']) }}" class="avatar-xxl rounded-circle mb-1"
                        alt="{{Auth::user()->image}}">
                    @endif

                </div>
                <div class="user-info">
                    <h5 class=" mb-2">{{Auth::user()->name}}</h5>
                    @if(!empty(Auth::user()->getRoleNames()[0]))
                        <span class="text-muted app-sidebar__user-name text-sm">{{ Auth::user()->getRoleNames()[0]}}</span>
                    @endif
                    @php
                        use App\Models\Employeerating;
                        if(Auth::check() && Auth::user()->id){
                            $avgrating1 = Employeerating::where('user_id', Auth::id())->where('rating', '1')->count();
                            $avgrating2 = Employeerating::where('user_id', Auth::id())->where('rating', '2')->count();
                            $avgrating3 = Employeerating::where('user_id', Auth::id())->where('rating', '3')->count();
                            $avgrating4 = Employeerating::where('user_id', Auth::id())->where('rating', '4')->count();
                            $avgrating5 = Employeerating::where('user_id', Auth::id())->where('rating', '5')->count();

                            $avgr = ((5*$avgrating5) + (4*$avgrating4) + (3*$avgrating3) + (2*$avgrating2) + (1*$avgrating1));
                            $avggr = ($avgrating1 + $avgrating2 + $avgrating3 + $avgrating4 + $avgrating5);

                            if($avggr == 0){
                                $avggr = 1;
                                $avg1 = $avgr/$avggr;
                            }else{
                                $avg1 = $avgr/$avggr;
                            }
                        }
                    @endphp
                    <div class="allprofilerating pt-1" data-rating="{{$avg1}}"></div>
                </div>
            </div>
        </div>
        <ul class="side-menu custom-ul">

            <li class="slide">
                <a class="side-menu__item" href="{{url('admin/')}}">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z" />
                    </svg>
                    <span class="side-menu__label">{{lang('Dashboard', 'menu')}}</span>
                </a>
            </li>
             <!-- LiveChat -->
             @can('Livechat Access')
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368">
                            <path d="M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/>
                        </svg>
                        <span class="side-menu__label">{{lang('Live Chat', 'menu')}}</span><i
                            class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu custom-ul">

                        @can('Customer Chat Process')
                            <li><a href="{{url('/admin/livechat')}}" class="slide-item">{{lang('New Chats', 'menu')}}</a></li>
                            <li><a href="{{url('/admin/myopened')}}" class="slide-item">{{lang('My Opened Chats', 'menu')}}</a></li>
                            <li><a href="{{url('/admin/solvedchats')}}" class="slide-item">{{lang('Solved Chats', 'menu')}}</a></li>
                        @endcan
                        @can('Operators')
                            <li><a href="{{url('/admin/operators')}}" class="slide-item">{{lang('Operators Chat', 'menu')}}</a></li>
                        @endcan
                        @can('Chat Flows')
                            <li><a href="{{url('/admin/chat-responses')}}" class="slide-item">{{lang('Chat Flows', 'menu')}}</a></li>
                        @endcan
                        @can('Livechat Settings')
                            <li><a href="{{url('/admin/livechat-settings')}}" class="slide-item">{{lang('Live Chat Settings', 'menu')}}</a></li>
                        @endcan
                        @can('Livechat Appearance')
                            <li><a href="{{url('/admin/livechatappearance')}}" class="slide-item">{{lang('Live Chat Appearance', 'menu')}}</a></li>
                        @endcan
                        @can('Livechat Integrations')
                            <li><a href="{{ route('admin.integrations') }}" class="slide-item">{{lang('Live Chat Integrations', 'menu')}}</a></li>
                        @endcan
                        @can('Livechat Ratings')
                            <li><a href="{{url('/admin/livechat-allratings')}}" class="slide-item">{{lang('Live Chat Ratings', 'menu')}}</a></li>
                        @endcan

                    </ul>
                </li>
            @endcan

            <!--- Employee menu tickets --->
            @can('Ticket Access')
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M22 10V6c0-1.11-.9-2-2-2H4c-1.1 0-1.99.89-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-2-1.46c-1.19.69-2 1.99-2 3.46s.81 2.77 2 3.46V18H4v-2.54c1.19-.69 2-1.99 2-3.46 0-1.48-.8-2.77-1.99-3.46L4 6h16v2.54zM11 15h2v2h-2zm0-4h2v2h-2zm0-4h2v2h-2z"/></svg>
                        <span class="side-menu__label">{{lang('Tickets', 'menu')}}</span><i class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu custom-ul">
                        @can('Ticket Create')
                            <li><a href="javascript:void(0);"  class="slide-item" id="adminticketcreateforcust">{{lang('Create Ticket', 'menu')}}</a></li>
                        @endcan
                        @can('All Tickets')
                            <li><a href="{{ route('admin.alltickets', ['ticketdata' => 'alltickets'])}}" class="slide-item">{{lang('All Tickets', 'menu')}}</a></li>
                        @endcan
                        @can('Unassigned Tickets')
                            <li><a href="{{ route('admin.unassignedtickets', ['ticketdata' => 'unassignedtickets'])}}" class="slide-item">{{lang('Unassigned Tickets', 'menu')}}</a></li>
                        @endcan
                        @can('My Tickets')
                            <li><a href="{{ route('admin.mytickets', ['ticketdata' => 'mytickets'])}}" class="slide-item">{{lang('My Tickets', 'menu')}}</a></li>
                        @endcan
                        @can('Solved')
                            <li><a href="{{ route('admin.myclosedtickets', ['ticketdata' => 'myclosedtickets'])}}" class="slide-item">{{lang('My Solved Tickets', 'menu')}}</a></li>
                        @endcan
                        @can('Trashed')
                            <li><a href="{{ route('admin.trashedtickets', ['ticketdata' => 'tickettrashed'])}}" class="slide-item">{{lang('Trashed', 'menu')}}</a></li>
                        @endcan
                        @can('Ticket Setting Access')
                            <li><a href="{{url('/admin/ticketsetting')}}" class="slide-item">{{lang('Ticket Setting', 'menu')}}</a></li>
                        @endcan

                    </ul>
                </li>
            @endcan
           <!--- Employee menu tickets --->


            <!--- End LiveChat -->
            <li class="slide">
                <a class="side-menu__item" href="{{url('/admin/profile')}}">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 9c2.7 0 5.8 1.29 6 2v1H6v-.99c.2-.72 3.3-2.01 6-2.01m0-11C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 9c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                    </svg>
                    <span class="side-menu__label">{{lang('Profile', 'menu')}}</span>
                </a>
            </li>


            @can('Categories Access')
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                            width="24px" fill="#000000">
                            <path d="M0 0h24v24H0V0z" fill="none"></path>
                            <path
                                d="M12 2l-5.5 9h11L12 2zm0 3.84L13.93 9h-3.87L12 5.84zM17.5 13c-2.49 0-4.5 2.01-4.5 4.5s2.01 4.5 4.5 4.5 4.5-2.01 4.5-4.5-2.01-4.5-4.5-4.5zm0 7c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5zM3 21.5h8v-8H3v8zm2-6h4v4H5v-4z">
                            </path>
                        </svg>
                        <span class="side-menu__label">{{lang('Categories', 'menu')}}</span><i
                            class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu custom-ul">
                        @can('Category Access')

                        <li><a href="{{url('/admin/categories')}}" class="slide-item">{{lang('Main Categories',
                                'menu')}}</a></li>
                        @endcan

                        @can('Subcategory Access')

                        <li><a href="{{url('/admin/subcategories')}}" class="slide-item">{{lang('SubCategory', 'menu')}}</a>
                        </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('Knowledge Access')
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                            height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                            <g>
                                <rect fill="none" height="24" width="24" />
                            </g>
                            <g>
                                <g />
                                <g>
                                    <path
                                        d="M17,19.22H5V7h7V5H5C3.9,5,3,5.9,3,7v12c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2v-7h-2V19.22z" />
                                    <path d="M19,2h-2v3h-3c0.01,0.01,0,2,0,2h3v2.99c0.01,0.01,2,0,2,0V7h3V5h-3V2z" />
                                    <rect height="2" width="8" x="7" y="9" />
                                    <polygon points="7,12 7,14 15,14 15,12 12,12" />
                                    <rect height="2" width="8" x="7" y="15" />
                                </g>
                            </g>
                        </svg>
                        <span class="side-menu__label">{{lang('Knowledge', 'menu')}}</span><i
                            class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu custom-ul">

                        @can('Article Access')

                        <li><a href="{{url('/admin/article')}}" class="slide-item">{{lang('Articles', 'menu')}}</a></li>
                        @endcan

                    </ul>
                </li>
            @endcan

            @can('Managerole Access')
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                            height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                            <g>
                                <path d="M0,0h24v24H0V0z" fill="none" />
                            </g>
                            <g>
                                <g>
                                    <path
                                        d="M4,18v-0.65c0-0.34,0.16-0.66,0.41-0.81C6.1,15.53,8.03,15,10,15c0.03,0,0.05,0,0.08,0.01c0.1-0.7,0.3-1.37,0.59-1.98 C10.45,13.01,10.23,13,10,13c-2.42,0-4.68,0.67-6.61,1.82C2.51,15.34,2,16.32,2,17.35V20h9.26c-0.42-0.6-0.75-1.28-0.97-2H4z" />
                                    <path
                                        d="M10,12c2.21,0,4-1.79,4-4s-1.79-4-4-4C7.79,4,6,5.79,6,8S7.79,12,10,12z M10,6c1.1,0,2,0.9,2,2s-0.9,2-2,2 c-1.1,0-2-0.9-2-2S8.9,6,10,6z" />
                                    <path
                                        d="M20.75,16c0-0.22-0.03-0.42-0.06-0.63l1.14-1.01l-1-1.73l-1.45,0.49c-0.32-0.27-0.68-0.48-1.08-0.63L18,11h-2l-0.3,1.49 c-0.4,0.15-0.76,0.36-1.08,0.63l-1.45-0.49l-1,1.73l1.14,1.01c-0.03,0.21-0.06,0.41-0.06,0.63s0.03,0.42,0.06,0.63l-1.14,1.01 l1,1.73l1.45-0.49c0.32,0.27,0.68,0.48,1.08,0.63L16,21h2l0.3-1.49c0.4-0.15,0.76-0.36,1.08-0.63l1.45,0.49l1-1.73l-1.14-1.01 C20.72,16.42,20.75,16.22,20.75,16z M17,18c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S18.1,18,17,18z" />
                                </g>
                            </g>
                        </svg>
                        <span class="side-menu__label">{{lang('Manage Roles', 'menu')}}</span><i
                            class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu custom-ul">
                        @can('Roles & Permission Access')

                        <li><a href="{{url('/admin/role')}}" class="slide-item">{{lang('Roles & Permissions', 'menu')}}</a>
                        </li>
                        @endcan
                        @can('Employee Create')

                        <li><a href="{{url('/admin/employee/create')}}" class="slide-item">{{lang('Create Employee',
                                'menu')}}</a></li>
                        @endcan
                        @can('Employee Access')

                        <li><a href="{{url('/admin/employee')}}" class="slide-item">{{lang('Employees List', 'menu')}}</a>
                        </li>
                        @endcan

                    </ul>
                </li>
            @endcan
            @can('Landing Page Access')
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                            width="24px" fill="#000000">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path
                                d="M3 17v2h6v-2H3zM3 5v2h10V5H3zm10 16v-2h8v-2h-8v-2h-2v6h2zM7 9v2H3v2h4v2h2V9H7zm14 4v-2H11v2h10zm-6-4h2V7h4V5h-4V3h-2v6z" />
                        </svg>
                        <span class="side-menu__label">{{lang('Landing Page Settings', 'menu')}}</span><i
                            class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu custom-ul">
                        @can('Banner Access')

                        <li><a href="{{url('/admin/bannersetting')}}" class="slide-item">{{lang('Banner', 'menu')}}</a></li>
                        @endcan
                        @can('Feature Box Access')

                        <li><a href="{{url('/admin/feature-box')}}" class="slide-item">{{lang('Feature Box', 'menu')}}</a>
                        </li>
                        @endcan
                        @can('Call To Action Access')

                        <li><a href="{{url('/admin/call-to-action')}}" class="slide-item">{{lang('Call To Action',
                                'menu')}}</a></li>
                        @endcan
                        @can('Testimonial Access')

                        <li><a href="{{url('/admin/testimonial')}}" class="slide-item">{{lang('Testimonial', 'menu')}}</a>
                        </li>
                        @endcan
                        @can('Main FAQ Access')
                        <li class="sub-slide"> <a class="sub-side-menu__item" data-bs-toggle="sub-slide"
                                href="javascript:void(0);"><span class="sub-side-menu__label">{{lang('Main FAQ’s', 'menu')}}</span><i
                                    class="sub-angle fa fa-angle-right"></i></a>
                            <ul class="sub-slide-menu">
                                @can('FAQs Access')
                                <li><a class="sub-slide-item" href="{{url('/admin/faq')}}">{{lang('FAQ’s', 'menu')}}</a></li>
                                @endcan
                                @can('FAQ Category Access')
                                <li><a class="sub-slide-item" href="{{route('faqsub.index')}}">{{lang('Faq Category', 'menu')}}</a></li>
                                @endcan

                            </ul>
                        </li>
                        @endcan

                    </ul>
                </li>
            @endcan
            @can('Customers Access')
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24px"
                            fill="#000000">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path
                                d="M9 13.75c-2.34 0-7 1.17-7 3.5V19h14v-1.75c0-2.33-4.66-3.5-7-3.5zM4.34 17c.84-.58 2.87-1.25 4.66-1.25s3.82.67 4.66 1.25H4.34zM9 12c1.93 0 3.5-1.57 3.5-3.5S10.93 5 9 5 5.5 6.57 5.5 8.5 7.07 12 9 12zm0-5c.83 0 1.5.67 1.5 1.5S9.83 10 9 10s-1.5-.67-1.5-1.5S8.17 7 9 7zm7.04 6.81c1.16.84 1.96 1.96 1.96 3.44V19h4v-1.75c0-2.02-3.5-3.17-5.96-3.44zM15 12c1.93 0 3.5-1.57 3.5-3.5S16.93 5 15 5c-.54 0-1.04.13-1.5.35.63.89 1 1.98 1 3.15s-.37 2.26-1 3.15c.46.22.96.35 1.5.35z" />
                        </svg>
                        <span class="side-menu__label">{{lang('Customers', 'menu')}}</span><i
                            class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu custom-ul">
                        @can('Customer List Access')
                            <li>
                                <a href="{{url('/admin/customer')}}" class="slide-item">{{lang('Customers List', 'menu')}}</a>
                            </li>
                        @endcan
                        @can('Visitors Access')
                            <li>
                                <a href="{{url('/admin/livevisitorsview')}}" class="slide-item">{{lang('Visitors', 'menu')}}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('Email Campaigns Access')
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24px"
                            fill="#000000">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path
                                d="M9 13.75c-2.34 0-7 1.17-7 3.5V19h14v-1.75c0-2.33-4.66-3.5-7-3.5zM4.34 17c.84-.58 2.87-1.25 4.66-1.25s3.82.67 4.66 1.25H4.34zM9 12c1.93 0 3.5-1.57 3.5-3.5S10.93 5 9 5 5.5 6.57 5.5 8.5 7.07 12 9 12zm0-5c.83 0 1.5.67 1.5 1.5S9.83 10 9 10s-1.5-.67-1.5-1.5S8.17 7 9 7zm7.04 6.81c1.16.84 1.96 1.96 1.96 3.44V19h4v-1.75c0-2.02-3.5-3.17-5.96-3.44zM15 12c1.93 0 3.5-1.57 3.5-3.5S16.93 5 15 5c-.54 0-1.04.13-1.5.35.63.89 1 1.98 1 3.15s-.37 2.26-1 3.15c.46.22.96.35 1.5.35z" />
                        </svg>
                        <span class="side-menu__label">{{ lang('Email Campaigns', 'menu') }}</span><i class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu custom-ul">
                        @can('All Email Campaigns Access')
                            <li>
                                <a href="{{url('/admin/emailCampaigns')}}" class="slide-item">{{lang('All Campaigns', 'menu')}}</a>
                            </li>
                        @endcan
                        @can('Users List Access')
                            <li>
                                <a href="{{url('/admin/emailCampaigns/users')}}" class="slide-item">{{lang('Users List', 'menu')}}</a>
                            </li>
                        @endcan
                        @can('Templates Access')
                            <li>
                                <a href="{{url('/admin/emailCampaigns/templates')}}" class="slide-item">{{lang('Templates', 'menu')}}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('Canned Response Access')

                <li class="slide">
                    <a class="side-menu__item" href="{{route('admin.cannedmessages')}}">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                            height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                            <g>
                                <rect fill="none" height="24" width="24" />
                            </g>
                            <g>
                                <g>
                                    <polygon points="16.6,10.88 15.18,9.46 10.94,13.71 8.82,11.58 7.4,13 10.94,16.54" />
                                    <path
                                        d="M19,4H5C3.89,4,3,4.9,3,6v12c0,1.1,0.89,2,2,2h14c1.1,0,2-0.9,2-2V6C21,4.9,20.11,4,19,4z M19,18H5V8h14V18z" />
                                </g>
                            </g>
                        </svg>
                        <span class="side-menu__label">{{lang('Canned Response', 'menu')}}</span>
                    </a>
                </li>
            @endcan

            @can('Envato Access')
                @if(setting('ENVATO_ON') == 'on')

                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <svg class="sidemenu_icon" style="enable-background:new 0 0 512 512;"
                            version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="_x38_5-envato">
                                <g>
                                    <g>
                                        <g>
                                            <path
                                                d="M401.225,19.381c-17.059-8.406-103.613,1.196-166.01,61.218      c-98.304,98.418-95.947,228.089-95.947,228.089s-3.248,13.335-17.086-6.011c-30.305-38.727-14.438-127.817-12.651-140.23      c2.508-17.494-8.615-17.999-13.243-12.229c-109.514,152.46-10.616,277.288,54.136,316.912      c75.817,46.386,225.358,46.354,284.922-85.231C509.547,218.042,422.609,29.875,401.225,19.381L401.225,19.381z M401.225,19.381" />
                                        </g>
                                    </g>
                                </g>
                            </g>
                            <g id="Layer_1" />
                        </svg>
                        <span class="side-menu__label">{{lang('Envato', 'menu')}}</span><i
                            class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu custom-ul">

                        <li>
                            <a href="{{route('settings.envatosetting')}}" class="slide-item">{{lang('Envato Setting',
                                'menu')}}</a>
                        </li>
                        @can('Envato API Token Access')

                        <li>
                            <a href="{{route('admin.envatoapitoken')}}" class="slide-item">{{lang('Envato API Token',
                                'menu')}}</a>
                        </li>
                        @endcan
                        @can('Envato License Details Access')

                        <li>
                            <a href="{{route('admin.envatolicensesearch')}}" class="slide-item">{{lang('Envato License Verification', 'menu')}}</a>
                        </li>
                        @endcan

                    </ul>
                </li>
                @endif
            @endcan
            @can('App Info Access')

            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="#">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24px"
                        fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                    </svg>
                    <span class="side-menu__label">{{lang('App Info', 'menu')}}</span><i
                        class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu custom-ul">
                    @can('App Purchase Code Access')

                    <li>
                        <a href="{{route('admin.licenseinfo')}}" class="slide-item">{{lang('App Purchase Code',
                            'menu')}}</a>
                    </li>
                    @endcan

                </ul>
            </li>
            @endcan

            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="#">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#000000">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z" />
                    </svg>
                    <span class="side-menu__label">{{lang('Notifications', 'menu')}}</span><i
                        class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu custom-ul">
                    <li><a href="{{route('notificationpage')}}" class="slide-item smark-all">{{lang('All Notifications',
                            'menu')}}</a></li>

                </ul>
            </li>
            @can('Custom Pages Access')

                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                            width="24px" fill="#000000">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path
                                d="M11.99 18.54l-7.37-5.73L3 14.07l9 7 9-7-1.63-1.27zM12 16l7.36-5.73L21 9l-9-7-9 7 1.63 1.27L12 16zm0-11.47L17.74 9 12 13.47 6.26 9 12 4.53z" />
                        </svg>
                        <span class="side-menu__label">{{lang('Custom Pages', 'menu')}}</span><i
                            class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu custom-ul">
                        @can('Pages Access')

                        <li><a href="{{url('/admin/pages')}}" class="slide-item">{{lang('Pages', 'menu')}}</a></li>
                        @endcan
                        @can('404 Error Page Access')

                        <li><a href="{{url('/admin/error404')}}" class="slide-item">{{lang('404 Error Page', 'menu')}}</a>
                        </li>
                        @endcan
                        @can('Under Maintenance Page Access')

                        <li><a href="{{url('/admin/maintenancepage')}}" class="slide-item">{{lang('Under Maintenance Page',
                                'menu')}}</a></li>
                        @endcan

                    </ul>
                </li>
            @endcan

            @can('Holidays Access')
                <li class="slide">
                    <a class="side-menu__item" href="{{url('/admin/holidays')}}">

                        <svg class="sidemenu_icon" style="enable-background:new 0 0 512 512;"  xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-calendar2-check" viewBox="0 0 16 16">
                            <path d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z"/>
                            <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4z"/>
                        </svg>
                        <span class="side-menu__label">{{lang('Holidays', 'menu')}}</span>
                    </a>
                </li>
            @endcan

            @can('Business Hours Access')
                <li class="slide">
                    <a class="side-menu__item"  href="{{route('admin.bussinesshour.index')}}">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                        <span class="side-menu__label">{{lang('Business Hours', 'menu')}}</span>
                    </a>
                </li>
            @endcan
            @can('Languages Access')
                <li class="slide">
                    <a class="side-menu__item"  href="{{route('admin.languages.index')}}">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="20" width="20"><path d="m9.917 18.333 3.791-10.021h1.73l3.812 10.021H17.5l-.875-2.562h-4.063l-.895 2.562Zm3.166-4.021h2.979l-1.458-4.104h-.062Zm-9.729 1.521-1.187-1.187 4.208-4.208q-.792-.876-1.385-1.813-.594-.937-1.011-1.979h1.729q.396.75.813 1.354.417.604 1.021 1.271.937-1 1.531-2.052.594-1.052.989-2.24H.833V3.312h5.834V1.646h1.666v1.666h5.834v1.667h-2.438q-.417 1.479-1.156 2.875-.74 1.396-1.844 2.625l1.979 2.042-.625 1.708-2.562-2.562Z"/></svg>
                        <span class="side-menu__label">{{lang('Languages', 'menu')}}</span>
                    </a>
                </li>
            @endcan

            @can('App Setting Access')

                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                            width="24px" fill="#000000">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path
                                d="M19.43 12.98c.04-.32.07-.64.07-.98 0-.34-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.09-.16-.26-.25-.44-.25-.06 0-.12.01-.17.03l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.06-.02-.12-.03-.18-.03-.17 0-.34.09-.43.25l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98 0 .33.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.09.16.26.25.44.25.06 0 .12-.01.17-.03l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.06.02.12.03.18.03.17 0 .34-.09.43-.25l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zm-1.98-1.71c.04.31.05.52.05.73 0 .21-.02.43-.05.73l-.14 1.13.89.7 1.08.84-.7 1.21-1.27-.51-1.04-.42-.9.68c-.43.32-.84.56-1.25.73l-1.06.43-.16 1.13-.2 1.35h-1.4l-.19-1.35-.16-1.13-1.06-.43c-.43-.18-.83-.41-1.23-.71l-.91-.7-1.06.43-1.27.51-.7-1.21 1.08-.84.89-.7-.14-1.13c-.03-.31-.05-.54-.05-.74s.02-.43.05-.73l.14-1.13-.89-.7-1.08-.84.7-1.21 1.27.51 1.04.42.9-.68c.43-.32.84-.56 1.25-.73l1.06-.43.16-1.13.2-1.35h1.39l.19 1.35.16 1.13 1.06.43c.43.18.83.41 1.23.71l.91.7 1.06-.43 1.27-.51.7 1.21-1.07.85-.89.7.14 1.13zM12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z" />
                        </svg>
                        <span class="side-menu__label">{{lang('App Setting', 'menu')}}</span><i
                            class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu custom-ul">
                        @can('General Setting Access')

                        <li><a href="{{url('/admin/general')}}" class="slide-item">{{lang('General Setting', 'menu')}}</a>
                        </li>
                        @endcan

                        @can('SEO Access')

                        <li><a href="{{url('/admin/seo')}}" class="slide-item">{{lang('SEO', 'menu')}}</a></li>
                        @endcan
                        @can('Google Analytics Access')

                        <li><a href="{{url('/admin/googleanalytics')}}" class="slide-item">{{lang('Google Analytics',
                                'menu')}}</a></li>
                        @endcan
                        @can('Custom JS & CSS Access')

                        <li><a href="{{url('/admin/customcssjssetting')}}" class="slide-item">{{lang('Custom CSS & JS',
                                'menu')}}</a></li>
                        @endcan
                        @can('Captcha Setting Access')

                        <li><a href="{{url('/admin/captcha')}}" class="slide-item">{{lang('Captcha Setting', 'menu')}}</a>
                        </li>
                        @endcan
                        @can('Email Setting Access')

                        <li><a href="{{url('/admin/emailsetting')}}" class="slide-item">{{lang('Email Setting',
                                'menu')}}</a></li>
                        @endcan
                        @can('SecruitySetting Access')

                        <li><a href="{{url('/admin/securitysetting')}}" class="slide-item">{{lang('Security Setting',
                                'menu')}}</a></li>
                        @endcan
                        @can('IpBlock Access')

                        <li><a href="{{route('ipblocklist')}}" class="slide-item">{{lang('IP List', 'menu')}}</a></li>
                        @endcan
                        @can('Bot Response Setting Access')

                            <li><a href="{{route('admin.botresponsetting')}}" class="slide-item">{{lang('Bot Response Setting', 'menu')}}</a></li>
                        @endcan

                    </ul>
                </li>
            @endcan
            @can('Announcements Access')

                <li class="slide">
                    <a class="side-menu__item" href="{{url('/admin/announcement')}}">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                            height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                            <g>
                                <rect fill="none" height="24" width="24" />
                            </g>
                            <path d="M18,11c0,0.67,0,1.33,0,2c1.2,0,2.76,0,4,0c0-0.67,0-1.33,0-2C20.76,11,19.2,11,18,11z" />
                            <path
                                d="M16,17.61c0.96,0.71,2.21,1.65,3.2,2.39c0.4-0.53,0.8-1.07,1.2-1.6c-0.99-0.74-2.24-1.68-3.2-2.4 C16.8,16.54,16.4,17.08,16,17.61z" />
                            <path
                                d="M20.4,5.6C20,5.07,19.6,4.53,19.2,4c-0.99,0.74-2.24,1.68-3.2,2.4c0.4,0.53,0.8,1.07,1.2,1.6 C18.16,7.28,19.41,6.35,20.4,5.6z" />
                            <path
                                d="M4,9c-1.1,0-2,0.9-2,2v2c0,1.1,0.9,2,2,2h1v4h2v-4h1l5,3V6L8,9H4z M9.03,10.71L11,9.53v4.94l-1.97-1.18L8.55,13H8H4v-2h4 h0.55L9.03,10.71z" />
                            <path d="M15.5,12c0-1.33-0.58-2.53-1.5-3.35v6.69C14.92,14.53,15.5,13.33,15.5,12z" />
                        </svg>
                        <span class="side-menu__label">{{lang('Announcements', 'menu')}}</span>
                    </a>
                </li>
            @endcan
            @can('Email Template Access')

                <li class="slide">
                    <a class="side-menu__item" href="{{url('/admin/emailtemplates')}}">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                            width="24px" fill="#000000">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path
                                d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z" />
                        </svg>
                        <span class="side-menu__label">{{lang('Email Templates', 'menu')}}</span>
                    </a>
                </li>
            @endcan

            @can('Addon Access')
                <li class="slide">
                    <a class="side-menu__item" href="{{url('/admin/addons')}}">

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-plus sidemenu_icon" viewBox="0 0 16 16" width="24px" fill="#000000">
                            <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9V5.5z"/>
                            <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                          </svg>

                        <span class="side-menu__label">{{lang('Addons', 'menu')}}</span>
                    </a>
                </li>
            @endcan

            @can('Reports Access')

                <li class="slide">
                    <a class="side-menu__item" href="{{url('/admin/reports')}}">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M7 17h2v-5H7Zm8 0h2V7h-2Zm-4 0h2v-3h-2Zm0-5h2v-2h-2Zm-6 9q-.825 0-1.413-.587Q3 19.825 3 19V5q0-.825.587-1.413Q4.175 3 5 3h14q.825 0 1.413.587Q21 4.175 21 5v14q0 .825-.587 1.413Q19.825 21 19 21Zm0-2h14V5H5v14ZM5 5v14V5Z"/></svg>
                        <span class="side-menu__label">{{lang('Reports', 'menu')}}</span>
                    </a>
                </li>
            @endcan

            @can('Domain Transfer Access')
                <li class="slide">
                    <a class="side-menu__item" href="{{url('/admin/domaintransfer')}}">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M838-65 720-183v89h-80v-226h226v80h-90l118 118-56 57ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 20-2 40t-6 40h-82q5-20 7.5-40t2.5-40q0-20-2.5-40t-7.5-40H654q3 20 4.5 40t1.5 40q0 20-1.5 40t-4.5 40h-80q3-20 4.5-40t1.5-40q0-20-1.5-40t-4.5-40H386q-3 20-4.5 40t-1.5 40q0 20 1.5 40t4.5 40h134v80H404q12 43 31 82.5t45 75.5q20 0 40-2.5t40-4.5v82q-20 2-40 4.5T480-80ZM170-400h136q-3-20-4.5-40t-1.5-40q0-20 1.5-40t4.5-40H170q-5 20-7.5 40t-2.5 40q0 20 2.5 40t7.5 40Zm34-240h118q9-37 22.5-72.5T376-782q-55 18-99 54.5T204-640Zm172 462q-18-34-31.5-69.5T322-320H204q29 51 73 87.5t99 54.5Zm28-462h152q-12-43-31-82.5T480-798q-26 36-45 75.5T404-640Zm234 0h118q-29-51-73-87.5T584-782q18 34 31.5 69.5T638-640Z"/></svg>
                        <span class="side-menu__label">{{lang('Domain Transfer', 'menu')}}</span>
                    </a>
                </li>
            @endcan


            <li class="slide">
                <a class="side-menu__item sprukoclearcache" href="javascript:void(0);">
                    <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M11 11h2V4q0-.425-.287-.713Q12.425 3 12 3t-.712.287Q11 3.575 11 4Zm-6 4h14v-2H5Zm-1.45 6H6v-2q0-.425.287-.712Q6.575 18 7 18t.713.288Q8 18.575 8 19v2h3v-2q0-.425.288-.712Q11.575 18 12 18t.713.288Q13 18.575 13 19v2h3v-2q0-.425.288-.712Q16.575 18 17 18t.712.288Q18 18.575 18 19v2h2.45l-1-4H4.55l-1 4Zm16.9 2H3.55q-.975 0-1.575-.775t-.35-1.725L3 15v-2q0-.825.587-1.413Q4.175 11 5 11h4V4q0-1.25.875-2.125T12 1q1.25 0 2.125.875T15 4v7h4q.825 0 1.413.587Q21 12.175 21 13v2l1.375 5.5q.325.95-.287 1.725-.613.775-1.638.775ZM19 13H5h14Zm-6-2h-2 2Z"/></svg>
                    <span class="side-menu__label">{{lang('Clear Cache', 'menu')}}</span>
                </a>
            </li>

            @if(Auth::user()->dashboard == 'Admin')
                <li class="slide">
                    <a class="side-menu__item" target="_blank" href="https://support.spruko.com/">
                        <svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-headset" viewBox="0 0 16 16">
                            <path d="M8 1a5 5 0 0 0-5 5v1h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a6 6 0 1 1 12 0v6a2.5 2.5 0 0 1-2.5 2.5H9.366a1 1 0 0 1-.866.5h-1a1 1 0 1 1 0-2h1a1 1 0 0 1 .866.5H11.5A1.5 1.5 0 0 0 13 12h-1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h1V6a5 5 0 0 0-5-5z"/>
                        </svg>
                        <span class="side-menu__label">{{lang('Contact Support', 'menu')}}</span>
                    </a>
                </li>
            @endif
        </ul>

    </div>
</aside>
<!--aside closed-->
