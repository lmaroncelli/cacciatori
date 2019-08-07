<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar Menu -->


    {{-- ASSOCIAZIONE --}}
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
            <li><a href="{{ route('distretti.create') }}">Nuovo</a></li>
          </ul>
        </li>

      {{-- Utg --}}
        <li class="treeview @if (in_array('utg',Request::segments())) active @endif">
          <a href="#"><i class="fa fa-houzz"></i> <span>Utg</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('utg.index') }}">Elenco</a></li>
            <li><a href="{{ route('utg.create') }}">Nuovo</a></li>
          </ul>
        </li>

        {{-- Zone --}}
      <li class="treeview @if (in_array('zone',Request::segments())) active @endif">
        <a href="#"><i class="fa fa-map-signs"></i> <span>Zone/Particelle</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ route('zone.index') }}">Elenco</a></li>
          <li><a href="{{ route('zone.create') }}">Nuovo</a></li>
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
            <li><a href="{{ route('squadre.create') }}">Nuova</a></li>
          </ul>
        </li>
      @endnot_role

      @role('admin')
        {{-- Cacciatori --}}
        <li class="treeview @if (in_array('cacciatori',Request::segments())) active @endif">
          <a href="#"><i class="fa fa-bullseye"></i> <span>Cacciatori</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('cacciatori.index') }}">Elenco</a></li>
            <li><a href="{{ route('cacciatori.create') }}">Nuovo</a></li>
          </ul>
        </li>
      @endrole

      

      @role('admin')
      {{-- Province --}}
      {{-- <li class="treeview @if (in_array('province',Request::segments())) active @endif">
        <a href="#"><i class="fa fa-envelope-o"></i> <span>Province</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ route('province.index') }}">Elenco</a></li>
          <li><a href="{{ route('province.create') }}">Nuova</a></li>

        </ul>
      </li> --}}

      {{-- Comuni --}}
      {{-- <li class="treeview @if (in_array('comuni',Request::segments())) active @endif">
        <a href="#"><i class="fa fa-envelope-o"></i> <span>Comuni</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ route('comuni.index') }}">Elenco</a></li>
          <li><a href="{{ route('comuni.create') }}">Nuova</a></li>

        </ul>
      </li> --}}
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
            <li><a href="{{ route('azioni.create') }}">Nuova</a></li>

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


    
  </section>
  <!-- /.sidebar -->
</aside>