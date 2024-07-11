<nav class="customer-nav">
    <div class="logo-name">
        <div class="logo-image">
            <img src="{{ asset('assets/Images/Store Logo.JPG') }}">
        </div>
        <span class="logo_name">P.O.S</span>
    </div>
    <div class="menu-items">
        <ul class="nav-links">
            <li><a href="/Dashboard" title="Dashboard">
                <i class="uil uil-apps" title="Dashboard"></i>
                <span class="link-name">Dahsboard</span>
            </a></li>
            <li><a href="/Sales" title="Sales">
                <i class="uil uil-money-withdrawal" title="Sales"></i>
                <span class="link-name">Sales</span>
            </a></li>
            <li><a href="/Inventory" title="Inventory">
                <i class="uil uil-box" title="Inventory"></i>
                <span class="link-name">Inventory</span>
            </a></li>
            <li><a href="#" title="Invoice">
                <i class="uil uil-invoice" title="Invoice"></i>
                <span class="link-name">Invoice</span>
            </a></li>
            <li><a href="#" title="Reports">
                <i class="uil uil-graph-bar" title="Reports"></i>
                <span class="link-name">Reports</span>
            </a></li>
            <li><a href="/Users" title="Users">
                <i class="uil uil-user" title="Users"></i>
                <span class="link-name">Users</span>
            </a>
            </li>
            <li><a href="/Settings" title="Settings">
                <i class="uil uil-setting" title="Settings"></i>
                <span class="link-name">Settings</span>
            </a>
            </li>
        </ul>
        
        <ul class="logout-mode">
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="uil uil-signout"></i>
                <span class="link-name">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>

            <li class="mode">
                <a>
                    <i class="uil uil-moon"></i>
                <span class="link-name">Dark Mode</span>
            </a>

            <div class="mode-toggle">
              <span class="switch"></span>
            </div>
        </li>
        </ul>
    </div>
</nav>