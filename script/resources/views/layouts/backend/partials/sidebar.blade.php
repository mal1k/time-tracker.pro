<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="">{{ config()->get('app.name') }}</a>
    </div>
    <ul class="sidebar-menu">
        @if (Auth::user()->role_id == 1)
        @can('dashboard.index') 
        <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> <span>{{ __('Dashboard')}}</span></a>
        </li>
        @endcan
        @can('plan.index') 
        <li class="menu-header">{{ __('Order Management') }}</li>
        <li class="nav-item dropdown {{ Request::is('admin/plan*') ? 'show active' : '' }}">
          <a href="{{ route('admin.plan.index') }}" class="nav-link"><i class="fas fa-tags"></i> <span>{{ __('Plans') }}</span></a>
        </li>
        @endcan
        @can('order.index') 
        <li class="nav-item dropdown {{ Request::is('admin/order*') ? 'show active' : '' }}">
          <a href="{{ route('admin.order.index') }}" class="nav-link"><i class="fas fa-cubes"></i> <span>{{ __('Orders') }}</span></a>
        </li>
        @endcan
        @can('report') 
        <li class="{{ Request::is('admin/report') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('admin.report.index') }}"><i class="fas fa-clipboard-list"></i> <span>{{ __('Reports') }}</span></a>
        </li>
        @endcan
        @can('website_settings.index')
        <li class="menu-header">{{ __('Website Management') }}</li>
        <li class="nav-item dropdown {{ Request::is('admin/feature*') ? 'show active' : '' }}">
          <a href="{{ route('admin.feature.index') }}" class="nav-link"><i class="fas fa-columns"></i><span>{{ ('Features Section') }}</span></a>
        </li>
        <li class="{{ Request::is('admin/theme/settings') ? 'active' : '' }}">
            <a href="{{ route('admin.theme.settings') }}" class="nav-link"><i class="fas fa-tools"></i><span>{{ __('Theme Settings') }}</span></a>
        </li>
        <li class="nav-item dropdown {{ Request::is('admin/header*') ? 'show active' : '' }}">
          <a href="{{ route('admin.header.index') }}" class="nav-link"><i class="fas fa-sliders-h"></i> <span>{{ ('Header Section') }}</span></a>
        </li>
        <li class="nav-item dropdown {{ Request::is('admin/about*') ? 'show active' : '' }}">
          <a href="{{ route('admin.about.index') }}" class="nav-link"><i class="far fa-address-card"></i> <span>{{ ('About Section') }}</span></a>
        </li>
        <li class="nav-item dropdown {{ Request::is('admin/analytic*') ? 'show active' : '' }}">
          <a href="{{ route('admin.analytic.index') }}" class="nav-link"><i class="fas fa-chart-bar"></i> <span>{{ ('Analytic Section') }}</span></a>
        </li>
        @endcan
        @can('page.index') 
        <li class="nav-item dropdown {{ Request::is('admin/page*') ? 'show active' : '' }}">
          <a href="{{ route('admin.page.index') }}" class="nav-link"><i class="far fa-file-alt"></i> <span>{{ __('Pages') }}</span></a>
        </li>
        @endcan
        @can('blog.index') 
        <li class="nav-item dropdown {{ Request::is('admin/blog*') ? 'show active' : '' }}">
          <a href="{{ route('admin.blog.index') }}" class="nav-link"><i class="fab fa-blogger-b"></i> <span>{{ __('Blogs') }}</span></a>
        </li>
        @endcan
        
        @can('user.index') 
        <li class="menu-header">{{ __('Customer Management') }}</li>
        <li class="nav-item dropdown {{ Request::is('admin/user*') ? 'show active' : '' }}">
          <a href="{{ route('admin.user.index') }}" class="nav-link"><i class="fas fa-users"></i> <span>{{ ('Users') }}</span></a>
        </li>
        @endcan 
        @can('support.index') 
        <li class="menu-header">{{ __('Support Management') }}</li>
        <li class="nav-item dropdown {{ Request::is('admin/support*') ? 'show active' : '' }}">
          <a href="{{ route('admin.support.index') }}" class="nav-link"><i class="fas fa-headset"></i> <span>{{ __('Support') }}</span></a>
        </li>
        @endcan
        <li class="menu-header">{{ __('Options & Settings') }}</li>
        @can('getway.index') 
        <li class="nav-item dropdown {{ Request::is('admin/paymentgateway*') ? 'show active' : '' }}">
          <a href="{{ route('admin.paymentgateway.index') }}" class="nav-link"><i class="fab fa-cc-visa"></i> <span>{{ ('Payment getaways') }}</span></a>
        </li>
        @endcan
        @can('role.list') 
        <li class="nav-item dropdown {{ Request::is('admin/role*') ? 'show active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-shield-alt"></i><span>{{ __('Admin and roles') }}</span></a>
          <ul class="dropdown-menu">
            @can('role.list')
            <li><a class="nav-link" href="{{ route('admin.role.index') }}">{{ __('Roles') }}</a></li>
            @endcan
            @can('admin.list')
            <li><a class="nav-link" href="{{ route('admin.admin.index') }}">{{ __('Admins') }}</a></li>
            @endcan
          </ul>
        </li>
        @endcan
        @can('system.settings','seo.settings','menu',) 
        <li class="nav-item dropdown {{ Request::is('admin/setting*') ? 'show active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cogs"></i> <span>{{ __('Settings') }}</span></a>
          <ul class="dropdown-menu">
            @can('language.index')
            <li class="{{ Request::is('admin/language') ? 'active' : '' }}">
              <a href="{{ route('admin.language.index') }}" class="nav-link"><span>{{ __('Languages') }}</span></a>
            </li>
            @endcan
            @can('system.settings')
            <li class="{{ Request::is('admin/setting/env') ? 'active' : '' }}"><a class="nav-link" href="{{ url('/admin/setting/env') }}">{{ __('System Environment') }}</a></li>
            @endcan
            @can('seo.settings')
            <li><a class="nav-link" href="{{ url('/admin/setting/seo') }}">{{ __('SEO Settings') }}</a></li>
            @endcan
            @can('menu')
            <li><a class="nav-link" href="{{ route('admin.menu.index') }}">{{ __('Menu Settings') }}</a></li>
            @endcan
          </ul>
        </li>
        @endcan
        @can('option') 
        <li class="nav-item dropdown {{ Request::is('admin/option*') ? 'show active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cog"></i> <span>{{ __('Site Options') }}</span></a>
          <ul class="dropdown-menu">
            <li><a class="nav-link" href="{{ route('admin.option.edit', 'cron_option') }}">{{ __('Cron Option') }}</a></li>
            <li><a class="nav-link" href="{{ route('admin.option.edit', 'all') }}">{{ __('Site Option') }}</a></li>
          </ul>
        </li>
        @endcan
        @elseif(Auth::user()->role_id == 2)
        <li class="nav-item dropdown">
          <li class="nav-item {{ Request::is('user/dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.dashboard') }}"><i class="fas fa-tachometer-alt"></i> <span>{{ __('Dashboard') }}</span></a>
          </li>
        </li>
        <li class="{{ Request::is('user/time-tracker') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('user.time.tracker') }}"><i class="fas fa-clock"></i> <span>{{ __('Time Tracker') }}</span></a>
        </li>
        <li class="menu-header">{{ __('Analyze') }}</li>
        <li class="{{ Request::is('user/calender') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('user.calender.index') }}"><i class="fas fa-calendar-alt"></i><span>{{ __('Calender') }}</span></a>
        </li>
        <li class="{{ Request::is('user/report') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('user.report.index') }}"><i class="fas fa-university"></i> <span>{{ __('Reports') }}</span></a>
        </li>
        <li class="menu-header">{{ __('Manage') }}</li>
        <li class="{{ Request::is('user/project*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('user.project.index') }}"><i class="fas fa-file-alt"></i><span>{{ __('Projects') }}</span></a>
        </li>
        <li class="{{ Request::is('user/task*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('user.task.index') }}"><i class="fas fa-file-alt"></i><span>{{ __('Tasks') }}</span></a>
        </li>
        <li class="{{ Request::is('user/team*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('user.team.member') }}"><i class="fas fa-users"></i><span>{{ __('Team') }}</span></a>
        </li>
        <li class="{{ Request::is('user/support') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('user.support.index') }}"><i class="fas fa-headset"></i><span>{{ __('Support') }}</span></a>
        </li>
        <li class="nav-item {{ Request::is('user/plan/subscription') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('user.plan.subscribe') }}"><i class="fas fa-tachometer-alt"></i> <span>{{ __('Plans') }}</span></a>
        </li>
      @endif
    </ul> 
  </aside>
</div>