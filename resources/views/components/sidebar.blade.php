<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('home') }}">CWE {{ env('APP_NAME') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('home') }}">CWE</a>
        </div>
        <ul class="sidebar-menu">

            <li class="nav-item  ">
                <a href="{{ route('home') }}" class="nav-link"><i class="fas fa-gauge"></i><span>Dashboard</span></a>
            </li>

            <li class="nav-item ">
                <a href="{{ route('users.index') }}" class="nav-link "><i class="fas fa-reguler fa-user"></i>
                    <span>Users</span></a>
            </li>

            <li class="nav-item">
                <a href="{{ route('companies.show', 1) }}" class="nav-link">
                    <i class="fas fa-building"></i>
                    <span>Company</span>
                </a>
            </li>

            
            <li class="nav-item">
                <a href="{{ route('departments.index') }}" class="nav-link">
                    <i class="fas fa-table-list"></i>
                    <span>Department</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('attendances.index') }}" class="nav-link">
                    <i class="fas fa-columns"></i>
                    <span>Attendances</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('permissions.index') }}" class="nav-link">
                    <i class="fas fa-note-sticky"></i>
                    <span>Permissions</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('broadcasts.index') }}" class="nav-link">
                    <i class="fas fa-podcast"></i>
                    <span>Broadcast</span>
                </a>
            </li>
    </aside>
</div>
