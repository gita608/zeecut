<!-- partial:partials/_sidebar.html -->
<nav class="sidebar">
  <div class="sidebar-header">
    @if(Route::has('admin.dashboard'))
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
      {{ env('APP_NAME') }}
    </a>
    @endif
    <div class="sidebar-toggler not-active">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  <div class="sidebar-body">
    <ul class="nav">
      <li class="nav-item nav-category">Menu</li>

      @if(Route::has('admin.dashboard'))
      <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
          <i class="link-icon" data-feather="home"></i> <!-- Changed to home for dashboard -->
          <span class="link-title">Dashboard</span>
        </a>
      </li>
      @endif

      <li class="nav-item d-none">
        <a class="nav-link" data-bs-toggle="collapse" href="#emails" role="button" aria-expanded="false"
          aria-controls="emails">
          <i class="link-icon" data-feather="mail"></i>
          <span class="link-title">Users</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="emails">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="#" class="nav-link">Inbox</a>
            </li>
            <li class="nav-item">
              <a href="pages/email/read.html" class="nav-link">Read</a>
            </li>
            <li class="nav-item">
              <a href="pages/email/compose.html" class="nav-link">Compose</a>
            </li>
          </ul>
        </div>
      </li>

      @if(Route::has('banner.index'))
      <li class="nav-item">
        <a href="{{ route('banner.index') }}" class="nav-link">
          <i class="link-icon" data-feather="image"></i>
          <span class="link-title">Banner</span>
        </a>
      </li>
      @endif

      @if(Route::has('category.index'))
      <li class="nav-item">
        <a href="{{ route('category.index') }}" class="nav-link">
          <i class="link-icon" data-feather="layers"></i> <!-- Changed to layers for category hierarchy -->
          <span class="link-title">Category</span>
        </a>
      </li>
      @endif

      @if(Route::has('product.index'))
      <li class="nav-item">
        <a href="{{ route('product.index') }}" class="nav-link">
          <i class="link-icon" data-feather="shopping-bag"></i> <!-- Changed to shopping-bag for products -->
          <span class="link-title">Product</span>
        </a>
      </li>
      @endif

      @if(Route::has('user.index'))
      <li class="nav-item">
        <a href="{{ route('user.index') }}" class="nav-link">
          <i class="link-icon" data-feather="users"></i>
          <span class="link-title">Users</span>
        </a>
      </li>
      @endif

      @if(Route::has('payLater.index'))
      <li class="nav-item">
        <a href="{{ route('payLater.index') }}" class="nav-link">
          <i class="link-icon" data-feather="dollar-sign"></i> <!-- Changed to dollar-sign for payments -->
          <span class="link-title">Pay Later</span>
        </a>
      </li>
      @endif

      <li class="nav-item">
        <a href="{{ route('orders') }}" class="nav-link">
          <i class="link-icon" data-feather="package"></i> <!-- Changed to package for orders -->
          <span class="link-title">Orders</span>
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('payments.index') }}" class="nav-link">
          <i class="link-icon" data-feather="credit-card"></i>
          <span class="link-title">Payment</span>
        </a>
      </li>

      @if(Route::has('offer.index'))
      <li class="nav-item">
        <a href="{{route('stock.index')}}" class="nav-link">
          <i class="link-icon" data-feather="database"></i> <!-- Changed to database for stock/inventory -->
          <span class="link-title">Stock</span>
        </a>
      </li>
      @endif

      @if(Route::has('offer.index'))
      <li class="nav-item">
        <a href="{{ route('offer.index') }}" class="nav-link">
          <i class="link-icon" data-feather="percent"></i> <!-- Changed to percent for discounts/offers -->
          <span class="link-title">Offers</span>
        </a>
      </li>
      @endif

      @if(Route::has('pincode.index'))
      <li class="nav-item">
        <a href="{{ route('pincode.index') }}" class="nav-link">
          <i class="link-icon" data-feather="map-pin"></i>
          <span class="link-title">Pincode</span>
        </a>
      </li>
      @endif

      @if(Route::has('setting.index'))
      <li class="nav-item">
        <a href="{{ route('setting.index') }}" class="nav-link">
          <i class="link-icon" data-feather="settings"></i>
          <span class="link-title">Settings</span>
        </a>
      </li>
      @endif
    </ul>
  </div>
</nav>