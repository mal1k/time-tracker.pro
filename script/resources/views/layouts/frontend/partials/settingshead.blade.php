<ul class="nav nav-pills">
    <li class="nav-item border border-right-0">
      <a class="nav-link {{ Request::is('user/settings/index*') ? 'active' : ''  }} rounded-0" href="{{ route('user.settings.index') }}">{{ __('Settings') }}</a>
    </li>
    <li class="nav-item border border-right-0">
        <a class="nav-link {{ Request::is('user/settings/alerts*') ? 'active' : ''  }} rounded-0" href="{{ route('user.settings.alerts') }}">{{ __('Alerts') }}</a>
    </li>
    <li class="nav-item border border-right-0">
        <a class="nav-link {{ Request::is('user/settings/accounts*') ? 'active' : ''  }} rounded-0" href="{{ route('user.settings.accounts') }}">{{ __('Accounts') }}</a>
    </li>
    <li class="nav-item border border-right-0">
        <a class="nav-link {{ Request::is('user/settings/authentication*') ? 'active' : ''  }} rounded-0" href="{{ route('user.settings.authentication') }}">{{ __('Authentication') }}</a>
    </li>
    <li class="nav-item border border-right-0">
        <a class="nav-link {{ Request::is('user/settings/customfields*') ? 'active' : ''  }} rounded-0" href="{{ route('user.settings.customfields') }}">{{ __('Custom Fields') }}</a>
    </li>
    <li class="nav-item border">
        <a class="nav-link {{ Request::is('user/settings/import*') ? 'active' : ''  }} rounded-0" href="{{ route('user.settings.import') }}">{{ __('Import') }}</a>
    </li>
</ul>