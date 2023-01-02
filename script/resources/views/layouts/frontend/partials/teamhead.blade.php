<ul class="nav nav-pills">
  <li class="nav-item border border-right-0">
    <a class="nav-link {{ Request::is('user/team/member*') ? 'active' : ''  }} rounded-0" href="{{ route('user.team.member') }}">{{ __('Members') }}</a>
  </li>
  <li class="nav-item border">
      <a class="nav-link {{ Request::is('user/team/group*') ? 'active' : ''  }} rounded-0" href="{{ route('user.team.group') }}">{{ __('Groups') }}</a>
  </li>
  <li class="nav-item border">
    <a class="nav-link {{ Request::is('user/team/collaboration*') ? 'active' : ''  }} rounded-0" href="{{ route('user.team.collaboration') }}">{{ __('Collaboration') }}</a>
  </li>   
</ul>