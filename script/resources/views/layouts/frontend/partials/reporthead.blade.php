<ul class="nav nav-pills">
    <li class=" border">
      <a href="" class="text-muted nav-link disabled">{{ __('Report') }}</a>
    </li>
    <li class="nav-item border border-right-0">
      <a class="nav-link {{ Request::is('user/report/summary*') ? 'active' : ''  }} rounded-0" href="{{ route('user.report.summary') }}">{{ __('Summary') }}</a>
    </li>
    <li class="nav-item border border-right-0">
      <a class="nav-link {{ Request::is('user/report/detailed*') ? 'active' : ''  }} rounded-0" href="{{ route('user.report.detailed') }}">{{ __('Detailed') }}</a>
    </li>
    <li class="nav-item border border-right-0">
      <a class="nav-link {{ Request::is('user/report/weekly*') ? 'active' : ''  }} rounded-0" href="{{ route('user.report.weekly') }}">{{ __('Weekly') }}</a>
    </li>
    <li class="nav-item border">
      <a class="nav-link {{ Request::is('user/report/shared*') ? 'active' : ''  }} rounded-0" href="{{ route('user.report.shared') }}">{{ __('Shared') }}</a>
    </li>
</ul>