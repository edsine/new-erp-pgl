@php
    $users=\Auth::user();
    //$profile=asset(Storage::url('uploads/avatar/'));
    $profile=\App\Models\Utility::get_file('uploads/avatar/');
    $languages=\App\Models\Utility::languages();
    $lang = isset($users->lang)?$users->lang:'en';
    $setting = \App\Models\Utility::colorset();
    $mode_setting = \App\Models\Utility::mode_layout();


    $unseenCounter=App\Models\ChMessage::where('to_id', Auth::user()->id)->where('seen', 0)->count();
@endphp
<style>
    .locked-content {
    filter: blur(5px);  /* Apply blur to make content unreadable */
    color: #d3d3d3;     /* Light grey to indicate locked content */
    pointer-events: none; /* Prevent clicking or interaction */
    user-select: none;    /* Disable text selection */
    cursor: pointer;     /* Make it clear that the content is clickable */
}

.locked-contentt {
    filter: blur(20px);  /* Apply blur to make content unreadable */
    color: #d3d3d3;     /* Light grey to indicate locked content */
    pointer-events: none; /* Prevent clicking or interaction */
    user-select: none;    /* Disable text selection */
    cursor: pointer;     /* Make it clear that the content is clickable */
}

.locked-file {
    cursor: not-allowed; /* Optional: Change cursor to indicate it's locked */
}

/* Optional: Add a "locked" overlay to make it look more like a lock */
.locked-file2:before {
    content: 'ðŸ”’'; /* Lock icon */
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: 30px;
    color: #d3d3d3;
}

.lock-input {
    display: none; /* Initially hide the lock input and button */
}


.real-content {
    display: none; /* Initially hide the real content */
}
/* Badge styling */
.badge {
    display: inline-block;
    padding: 0.5rem 0.75rem;
    font-size: 1rem;
    font-weight: 600;
    line-height: 1;
    text-align: center;
    vertical-align: middle;
    border-radius: 50rem;
    user-select: none;
}

.badge-soft-danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.text-danger {
    color: #dc3545 !important;
}

.rounded-pill {
    border-radius: 50rem !important;
}

.float-end {
    float: right !important;
}

/* Additional custom styles for badge */
.badge-soft-danger.text-danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.badge-soft-danger.text-danger:hover {
    background-color: rgba(220, 53, 69, 0.2);
}
</style>
{{--<header class="dash-header  {{(isset($mode_setting['cust_theme_bg']) && $mode_setting['cust_theme_bg'] == 'on')?'transprent-bg':''}}">--}}

@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <header class="dash-header transprent-bg">
        @else
            <header class="dash-header">
                @endif

    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            <ul class="list-unstyled">
                <li class="dash-h-item mob-hamburger">
                    <a href="#!" class="dash-head-link" id="mobile-collapse">
                        <div class="hamburger hamburger--arrowturn">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="dropdown dash-h-item drp-company">
                    <a
                        class="dash-head-link dropdown-toggle arrow-none me-0"
                        data-bs-toggle="dropdown"
                        href="#"
                        role="button"
                        aria-haspopup="false"
                        aria-expanded="false"
                    >
                        <span class="theme-avtar">
                             <img src="{{ !empty(\Auth::user()->avatar) ? $profile . \Auth::user()->avatar :  $profile.'avatar.png'}}" class="img-fluid rounded-circle">
                        </span>
                        <span class="hide-mob ms-2">{{__('Hi, ')}}{{\Auth::user()->name }} !</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">

                        <!-- <a href="{{ route('change.mode') }}" class="dropdown-item">
                            <i class="ti ti-circle-plus"></i>
                            <span>{{(Auth::user()->mode == 'light') ? __('Dark Mode') : __('Light Mode')}}</span>
                        </a> -->

                        <a href="{{route('profile')}}" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span>{{__('Profile')}}</span>
                        </a>

                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item">
                            <i class="ti ti-power"></i>
                            <span>{{__('Logout')}}</span>
                        </a>
                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                            {{ csrf_field() }}
                        </form>

                    </div>
                </li>

            </ul>
        </div>
        <?php
            $notifications = getUnreadNotifications();
             ?>
        <div class="ms-auto">
            <ul class="list-unstyled">
                <li class="dropdown dash-h-item drp-notification">
                    <a title="File Archive Notifications" class="dash-head-link arrow-none me-0" href="{{ route('documents.by.files.shared') }}" aria-haspopup="false"
                       aria-expanded="false">
                        <i class="ti ti-bell"></i>
                        <span class="bg-danger dash-h-badge message-toggle-msg  message-counter custom_messanger_counter beep"> {{ $notifications->count() }}<span
                                class="sr-only"></span></span>
                    </a>

                </li>
               
                @if( \Auth::user()->type !='client' && \Auth::user()->type !='super admin' )
                        <li class="dropdown dash-h-item drp-notification">
                            <a class="dash-head-link arrow-none me-0" href="{{ url('chats') }}" aria-haspopup="false"
                               aria-expanded="false">
                                <i class="ti ti-brand-hipchat"></i>
                                <span class="bg-danger dash-h-badge message-toggle-msg  message-counter custom_messanger_counter beep"> {{ $unseenCounter }}<span
                                        class="sr-only"></span></span>
                            </a>

                        </li>
                    @endif

                    <li class="dropdown dash-h-item drp-language">
                    <a
                        class="dash-head-link dropdown-toggle arrow-none me-0"
                        data-bs-toggle="dropdown"
                        href="#"
                        role="button"
                        aria-haspopup="false"
                        aria-expanded="false"
                    >
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text hide-mob">{{Str::upper(isset($lang)?$lang:'en')}}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">

                        @foreach($languages as $language)
                            <a href="{{route('change.language',$language)}}" class="dropdown-item @if($language == $lang) text-danger @endif">
                                <span>{{Str::upper($language)}}</span>
                            </a>
                        @endforeach
                        <h></h>

                                <a class="dropdown-item text-primary" href="{{route('manage.language',[isset($lang)?$lang:'en'])}}">{{ __('Manage Language ') }}</a>

                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
