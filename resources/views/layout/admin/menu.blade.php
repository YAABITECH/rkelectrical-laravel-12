<nav class="navbar navbar-expand navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('admin') }}">Admin Home</a>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('home') }}">
                <i class="fa-solid fa-house-user text-white"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin') }}">
                <i class="fa-solid fa-user text-white"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="" id="theme-switcher" onclick="return false;">
                <i class="fa-solid fa-sun text-white"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.logout') }}">
                <i class="fa-solid fa-right-from-bracket text-white"></i>
              </a>
            </li>
          </ul>
        </div>
    </div>
  </nav>
  