<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <i class="fas fa-graduation-cap brand-icon"></i>
        <span class="brand-text font-weight-bold">إدارة الكورسات</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>لوحة التحكم</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('courses.index') }}" class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>الكورسات</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>الطلاب</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('training-courses.index') }}" class="nav-link {{ request()->routeIs('training-courses.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>الدورات التدريبية</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('enrollments.create') }}" class="nav-link {{ request()->routeIs('enrollments.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-plus"></i>
                        <p>تسجيل طالب في دورة</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
