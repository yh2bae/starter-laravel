  <!-- Search -->
  <div class="navbar-nav align-items-center">
      <div class="nav-item navbar-search-wrapper mb-0">
          <a class="nav-item nav-link search-toggler fw-normal px-0" href="javascript:void(0);">
              <i class="mdi mdi-magnify mdi-24px scaleX-n1-rtl"></i>
              <span class="d-none d-md-inline-block text-muted">Search (Ctrl+/)</span>
          </a>
      </div>
  </div>
  <!-- /Search -->

  <ul class="navbar-nav flex-row align-items-center ms-auto">
      <!-- Style Switcher -->
      <li class="nav-item dropdown-style-switcher dropdown me-1 me-xl-0">
          <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
              href="javascript:void(0);" data-bs-toggle="dropdown">
              <i class="mdi mdi-24px"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
              <li>
                  <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                      <span class="align-middle"><i class="mdi mdi-weather-sunny me-2"></i>Light</span>
                  </a>
              </li>
              <li>
                  <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                      <span class="align-middle"><i class="mdi mdi-weather-night me-2"></i>Dark</span>
                  </a>
              </li>
              <li>
                  <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                      <span class="align-middle"><i class="mdi mdi-monitor me-2"></i>System</span>
                  </a>
              </li>
          </ul>
      </li>
      <!-- / Style Switcher-->


      <!-- User -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
          <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
              <div class="avatar avatar-online">
                  <img src="../../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
              </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
              <li>
                  <a class="dropdown-item" href="pages-account-settings-account.html">
                      <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                              <div class="avatar avatar-online">
                                  <img src="../../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                              </div>
                          </div>
                          <div class="flex-grow-1">
                              <span class="fw-medium d-block">
                                  {{ Auth::user()->name }}
                              </span>
                              <small class="text-muted">
                                  Last Login: {{ \Carbon\Carbon::parse(Auth::user()->last_login_at)->diffForHumans() }}
                              </small>

                          </div>
                      </div>
                  </a>
              </li>
              <li>
                  <div class="dropdown-divider"></div>
              </li>
              <li>
                  <a class="dropdown-item" href="{{ route('profile.index') }}">
                      <i class="mdi mdi-account-outline me-2"></i>
                      <span class="align-middle">My Profile</span>
                  </a>
              </li>
              <li>
                  <button class="dropdown-item" id="logout">
                      <i class="mdi mdi-logout me-2"></i>
                      <span class="align-middle">Log Out</span>
                  </button>
              </li>
          </ul>
      </li>
      <!--/ User -->
  </ul>
  </div>

  <!-- Search Small Screens -->
  <div class="navbar-search-wrapper search-input-wrapper d-none">
      <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..."
          aria-label="Search..." />
      <i class="mdi mdi-close search-toggler cursor-pointer"></i>
  </div>
