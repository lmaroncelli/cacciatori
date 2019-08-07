<!-- Main Header -->
<header class="main-header">

  <!-- Logo -->
  <a href="{{ url('home') }}" class="logo" data-toggle="tooltip" title="Gestione Piano Venatorio">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini">P<b>V</b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg">Piano <b>Venatorio</b></span>
  </a>

  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    
    @auth
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span>{{ Auth::user()->name}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
              </li>
            </ul>
          </li>
          <li class="ruolo">
              <span class="label">{{ Auth::user()->ruolo}}</span>
          </li>
        </ul>
      </div>
    @endauth

  </nav>
</header>