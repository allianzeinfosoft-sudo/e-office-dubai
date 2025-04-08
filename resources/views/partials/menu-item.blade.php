@php

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


    $hasPermission = $checkPermission($item['permission'] ?? null);

    // Filter submenu if present
    if (isset($item['submenu'])) {
        $item['submenu'] = collect($item['submenu'])
            ->filter(fn($sub) => $checkPermission($sub['permission'] ?? null))
            ->toArray();
    }

    $currentPath = trim(request()->path(), '/');
    $routePath = isset($item['route']) ? trim(parse_url($item['route'], PHP_URL_PATH), '/') : '';
    $isActive = $currentPath === $routePath;

    if (!empty($item['submenu'])) {
        foreach ($item['submenu'] as $sub) {
            $subPath = isset($sub['route']) ? trim(parse_url($sub['route'], PHP_URL_PATH), '/') : '';
            if ($currentPath === $subPath) {
                $isActive = true;
                break;
            }
        }
    }

    if (isset($item['isActive'])) {
        foreach ($item['isActive'] as $pattern) {
            if (Str::is($pattern, $currentPath)) {
                $isActive = true;
                break;
            }
        }
    }
@endphp

@if($hasPermission && (!isset($item['submenu']) || count($item['submenu']) > 0))
<li class="menu-item {{ $isActive ? 'active open' : '' }}">
    <a href="{{ isset($item['route']) ? url($item['route']) : '#' }}"
       class="menu-link {{ isset($item['submenu']) ? 'menu-toggle' : '' }}">
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