<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="#7367F0" />
                  <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                  <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="#7367F0" />
                </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bold">{{ config('app.name') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1 text-uppercase">
    @foreach(config('menu') as $item)
    @php
        // Closure to check permission
        $checkPermission = function($permission) {
            if (!$permission) return true;
            if (is_array($permission)) {
                foreach ($permission as $perm) {
                    if (auth()->user()?->can($perm)) return true;
                }
                return false;
            }
            return auth()->user()?->can($permission);
        };

        // Check main item permission
        $hasPermission = $checkPermission($item['permission'] ?? null);
        
        // Filter submenu based on permission
        if (isset($item['submenu'])) {
            $item['submenu'] = collect($item['submenu'])
            ->filter(fn($sub) => $checkPermission($sub['permission'] ?? null))
            ->values()
            ->toArray();
        }
    @endphp

    @if(isset($item['header']) && $hasPermission)
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ $item['header'] }}</span>
        </li>
    @elseif($hasPermission)
        @php
            $currentPath = trim(request()->path(), '/');
            $routePath = isset($item['route']) ? trim(parse_url($item['route'], PHP_URL_PATH), '/') : '';
            $isActive = $currentPath === $routePath;

            // Check if any submenu route matches the current path
            if (!empty($item['submenu'])) {
                foreach ($item['submenu'] as $sub) {
                    $subPath = isset($sub['route']) ? trim(parse_url($sub['route'], PHP_URL_PATH), '/') : '';
                    if ($currentPath === $subPath) {
                        $isActive = true;
                        break;
                    }
                }
            }

            // Optional custom pattern matching
            if (isset($item['isActive'])) {
                foreach ($item['isActive'] as $pattern) {
                    if (Str::is($pattern, $currentPath)) {
                        $isActive = true;
                        break;
                    }
                }
            }
        @endphp

        @if(!isset($item['submenu']) || count($item['submenu']) > 0)
            <li class="menu-item {{ $isActive ? 'active open' : '' }}">
                <a href="{{ isset($item['route']) ? url($item['route']) : '#' }}"
                   class="menu-link {{ !empty($item['submenu']) ? 'menu-toggle' : '' }}">
                    <i class="menu-icon tf-icons {{ $item['icon'] ?? '' }}"></i>
                    <div data-i18n="{{ $item['title'] }}">{{ $item['title'] }}</div>
                    @if(isset($item['badge']))
                        <div class="badge bg-label-primary rounded-pill ms-auto">{{ $item['badge'] }}</div>
                    @endif
                </a>

                @if(!empty($item['submenu']))
                    <ul class="menu-sub {{ $isActive ? 'active' : '' }}">
                        @foreach($item['submenu'] as $submenu)
                            @include('partials.menu-item', ['item' => $submenu])
                        @endforeach
                    </ul>
                @endif
            </li>
        @endif
    @endif
@endforeach

</ul>

</aside>


