<ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item {{ (null !== $active && $active == 'dashboard')?'active':'' }}">
      <a href="{{ url('/') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Analytics">Beranda</div>
      </a>
    </li>

    <!-- Layouts -->
    @if(Auth::user()->role->id == 2)
    <li class="menu-item {{ (null !== $active && $active == 'materi')?'active':'' }}">
        <a href="{{ url('/guru/materi') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-book"></i>
          <div data-i18n="Analytics">Materi</div>
        </a>
    </li>
    @endif
    @if(Auth::user()->role->id == 3)
    <li class="menu-item {{ (null !== $active && $active == 'materi')?'active':'' }}">
        <a href="{{ url('/siswa/materi') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-book"></i>
          <div data-i18n="Analytics">Materi</div>
        </a>
    </li>
    @endif
    @if(Auth::user()->role->id == 1)
    {{-- JUST FOR ADMIN --}}
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Master Data</span>
    </li>
    <li class="menu-item {{ (null !== $active && $active == 'master_guru')?'active':'' }}">
        <a href="{{ url('/master-data/guru') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="Account Settings">Guru</div>
        </a>
    </li>
    <li class="menu-item {{ (null !== $active && $active == 'master_kelas')?'active':'' }}">
        <a href="{{ url('/master-data/kelas') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="Account Settings">Kelas & Siswa</div>
        </a>
    </li>
    <li class="menu-item {{ (null !== $active && $active == 'master_matkul')?'active':'' }}">
      <a href="{{ url('/master-data/matkul') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-book"></i>
          <div data-i18n="Account Settings">Matkul / Matpel</div>
      </a>
  </li>
    @endif
  </ul>