<!-- header area end -->
<header>
    <div class="header-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-2">
                    <div class="header-logo">
                        <a href="{{ url('/') }}"><img class="img-fluid" src="{{ asset('uploads/logo.png') }}" alt="{{ env('APP_NAME') }}"></a>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="header-btn f-right">
                        @if(!empty($theme_info->top_left_link) && !empty($theme_info->top_left_text))
                        <a href="{{ url($theme_info->top_left_link) }}" class="days_trail">{{ $theme_info->top_left_text }}</a>
                        @endif
                        @if(!empty($theme_info->top_right_link) && !empty($theme_info->top_right_text))
                        <a href="{{ url($theme_info->top_right_link) }}">
                            <span class="iconify" data-icon="ic:sharp-login" data-inline="false"></span> {{ $theme_info->top_right_text }}
                        </a>
                        @endif
                    </div>
                    <div class="header-menu f-right">
                        <div class="mobile-menu">
                            <a class="toggle f-right" href="#" role="button" aria-controls="hc-nav-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                                  </svg>
                            </a>
                        </div>
                        <nav id="main-nav">
                            <ul>
                                {{ header_menu('header') }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header area end -->
