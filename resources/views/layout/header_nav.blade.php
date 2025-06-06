<header class="navbar sticky-top navbar-expand-lg shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand logo" href="/">
            <img src="{{asset('img/logo.png')}}" alt="" width="35px">
        </a>
        <a class="navbar-brand link-light" href="/">QaraTMS</a>
        <div class="collapse navbar-collapse">
            {{-- DO NOT DISABE UL because form will be at start --}}
            <div class="navbar-nav nav-pills me-auto mb-2 mb-lg-0">
                @if(Route::currentRouteName() == 'repository_show_page')
                    <a href="{{route("project_show_page", $project->id)}}" class="nav-link text-white">
                        <i class="bi bi-kanban-fill"></i>&nbsp;{{__('Dashboard')}}
                    </a>
                    <a href="{{route("repository_list_page", $project->id)}}" class="nav-link text-white">
                        <i class="bi bi-server"></i>&nbsp;{{__('Repositories')}}
                    </a>
                    <a href="{{route("test_plan_list_page", $project->id)}}" class="nav-link text-white">
                        <i class="bi bi-journals"></i>&nbsp;{{__('Test Plans')}}
                    </a>
                    <a href="{{route("test_run_list_page", $project->id)}}" class="nav-link text-white">
                        <i class="bi bi-play-circle"></i>&nbsp;{{__('Test Runs')}}
                    </a>
                    <hr>
                    <a href="{{route("project_documents_list_page", $project->id)}}" class="nav-link text-white">
                        <i class="bi bi-file-text-fill"></i>&nbsp;{{__('Documents')}}
                    </a>
                    <a href="{{route("project_list_page")}}" class="nav-link text-white">
                        <i class="bi bi-diagram-3-fill"></i>&nbsp;{{__('Projects')}}
                    </a>
                    <a href="{{route('users_list_page')}}" class="nav-link text-white">
                        <i class="bi bi-people-fill"></i>&nbsp;{{__('Users')}}
                    </a>
                @endif
            </div>
            <div class="d-flex justify-content-between align-items-center logout_block">
                @if(Route::currentRouteName() == 'repository_show_page')
                    <a href="{{route('logout')}}" class="nav-link text-white" style="padding-left: 10px;">
                        <i class="bi bi-box-arrow-in-left"></i>&nbsp;<b>{{__('Logout')}}</b>
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>
