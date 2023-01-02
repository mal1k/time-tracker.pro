<!-- footer area start -->
<footer>
    <div class="footer-area footer-demo-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="footer-left-area">
                        <div class="footer-logo">
                            <div class="header-logo">
                                <img class="img-fluid" src="{{ asset('uploads/dark_logo.png') }}" height="100" alt="">
                            </div>
                            <div class="footer-content">
                                <p>{{ $theme_info->footer_description }}</p>
                                <div class="footer-lang-select">
                                    <select name="lang" class="form-select" id="lang">
                                        @foreach (App\Models\Language::where('status',1)->get() as $lang)
                                        <option {{ app()->getLocale() == $lang->name ? 'selected' : '' }} value="{{ $lang->name }}">{{ $lang->data }}</option> 
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="agent-social-links">
                                <nav>
                                    <ul>
                                        @foreach ($theme_info->social as $value)
                                        <li><a href="{{ $value->link }}"><span class="iconify" data-icon="{{ $value->icon }}" data-inline="false"></span></a></li>  
                                        @endforeach
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="footer-menu">
                        {{ footer_menu('footer_left') }}
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="footer-menu">
                        {{ footer_menu('footer_right') }}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="footer-newsletter">
                        <div class="footer-menu-title">
                            <h4>{{ __('Newsletter') }}</h4>
                        </div>
                        <div class="footer-content">
                            <p>{{ $theme_info->newsletter_address }}</p>
                        </div>
                        <div class="footer-newsletter-input">
                            <form action="{{ route('newsletter') }}" id="newsletter" method="post">
                                @csrf
                                <input type="email" name="email" placeholder="{{ __('Enter Your Email Address') }}" id="subscribe_email">
                                <button type="submit" class="basicbtn">{{ __('Subscribe') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom-area footer-demo-1">
        <div class="footer-bottom-content text-center">
            <span>{{ __('Copyright ©') }} {{ date('Y') }} {{ env('APP_NAME') }}</span>
        </div>
    </div>
</footer>
<!-- footer area end -->