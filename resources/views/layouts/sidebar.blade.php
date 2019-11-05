<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar Menu -->

    @not_role('consultatore')
    <ul class="sidebar-menu" data-widget="tree">      
      

        
      {{-- Distretti --}}

        <li class="treeview @if (in_array('distretti',Request::segments())) active @endif">
          <a href="#"><i class="fa fa-object-group"></i> <span>Distretti</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('distretti.index') }}">Elenco</a></li>
            @not_role_and(['cacciatore','admin_ro'])
              <li><a href="{{ route('distretti.create') }}">Nuovo</a></li>
            @endnot_role_and
          </ul>
        </li>

      {{-- Utg --}}
        <li class="treeview @if (in_array('utg',Request::segments())) active @endif">
          <a href="#"><i class="fa fa-houzz"></i> <span>Ug</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('utg.index') }}">Elenco</a></li>
            @not_role_and(['cacciatore','admin_ro'])
            <li><a href="{{ route('utg.create') }}">Nuovo</a></li>
            @endnot_role_and
          </ul>
        </li>

        {{-- Zone --}}
      <li class="treeview @if (in_array('zone',Request::segments())) active @endif">
        <a href="#"><i class="fa fa-map-signs"></i> <span>Quadranti</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ route('zone.index') }}">Elenco</a></li>
           @not_role_and(['cacciatore','admin_ro'])
          <li><a href="{{ route('zone.create') }}">Nuovo</a></li>
          @endnot_role_and
        </ul>
      </li>

      @not_role('cartografo')
        {{-- Squadre --}}
        <li class="treeview @if (in_array('squadre',Request::segments())) active @endif">
          <a href="#"><i class="fa fa-users"></i> <span>Squadre</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('squadre.index') }}">Elenco</a></li>
            @not_role_and(['cacciatore','admin_ro'])
            <li><a href="{{ route('squadre.create') }}">Nuova</a></li>
            @endnot_role_and
          </ul>
        </li>
      @endnot_role

      @role_or(['admin', 'admin_ro'])
        {{-- Cacciatori --}}
        <li class="treeview @if (in_array('cacciatori',Request::segments())) active @endif">
          <a href="#"><i class="fa fa-bullseye"></i> <span>Cacciatori</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('cacciatori.index') }}">Elenco</a></li>
            @not_role('admin_ro')
            <li><a href="{{ route('cacciatori.create') }}">Nuovo</a></li>
            @endnot_role
          </ul>
        </li>

        {{-- Utenti --}}
        <li class="treeview @if (in_array('utenti',Request::segments())) active @endif">
          <a href="#"><i class="fa fa-user"></i> <span>Utenti</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('utenti.index') }}">Elenco</a></li>
            @not_role('admin_ro')
            <li><a href="{{ route('utenti.create') }}">Nuovo</a></li>
            @endnot_role
          </ul>
        </li>
      @endrole_or

      

      @role_or(['admin', 'admin_ro'])
      {{-- Referenti --}}
      <li class="treeview @if (in_array('referenti',Request::segments())) active @endif">
        <a href="#"><i class="fa fa-send-o"></i> <span>Referenti</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ route('referenti.index') }}">Elenco</a></li>
          @not_role('admin_ro')
          <li><a href="{{ route('referenti.create') }}">Nuovo</a></li>
          @endnot_role
        </ul>
      </li>
      @endrole

      @not_role('cartografo')
        {{-- Azioni --}}
        <li class="treeview @if (in_array('azioni',Request::segments())) active @endif">
          <a href="#"><i class="fa fa-tasks"></i> <span>Attivita</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('azioni.index') }}">Elenco</a></li>
            @not_role('admin_ro')
            <li><a href="{{ route('azioni.create') }}">Nuova</a></li>
            @endnot_role
          </ul>
        </li>
      @endnot_role


      {{-- DOCUMENTI ELENCO --}}

      {{-- @if(Auth::user()->hasRole('associazione'))
      <li class="treeview @if (in_array('documenti',Request::segments())) active @endif">
        <a href="#"><i class="fa fa-folder-open-o"></i> <span>Documenti</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ route('documenti.index') }}">Elenco</a></li>
        </ul>
      </li>
      @endif
      
      @if(Auth::user()->hasRole('admin'))
        <li class="header">&nbsp;</li>
        <li><a href="{{ route('utenti') }}"><i class="fa fa-navicon text-aqua"></i> <span>Elenco admin</span></a></li>
        <li><a href="{{ route('register') }}"><i class="fa fa-plus-square text-aqua"></i> <span>Registra nuovo admin</span></a></li>

      @endif --}}
      

    </ul>
    <!-- /.sidebar-menu -->
    @endnot_role

    
  </section>
  <!-- /.sidebar -->
</aside>