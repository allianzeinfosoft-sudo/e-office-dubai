@php
    $currentPath = trim(request()->path(), '/');
    $routePath = isset($item['route']) ? trim(parse_url($item['route'], PHP_URL_PATH), '/') : '';

    // Check if current path matches the route directly
    $isActive = $currentPath === $routePath;

    // Check if submenu routes match the current path
    if (isset($item['submenu'])) {
        $submenuRoutes = collect($item['submenu'])->pluck('route')
            ->map(fn($route) => trim(parse_url($route, PHP_URL_PATH), '/'));

        if ($submenuRoutes->contains($currentPath)) {
            $isActive = true;
        }
    }

    // Additional logic to check against the `isActive` array
    if (isset($item['isActive'])) {
        foreach ($item['isActive'] as $pattern) {
            if (Str::is($pattern, $currentPath)) {
                $isActive = true;
                break;
            }
        }
    }
@endphp

<li class="menu-item {{ $isActive ? 'active open' : '' }}">
    <a href="{{ isset($item['route']) ? url($item['route']) : '#' }}" class="menu-link {{ isset($item['submenu']) ? 'menu-toggle' : '' }}">
        <div data-i18n="{{ $item['title'] }}">{{ $item['title'] }}</div>
        @if(isset($item['badge']))
            <div class="badge bg-label-primary rounded-pill ms-auto">{{ $item['badge'] }}</div>
        @endif
    </a>

    @if(isset($item['submenu']))
        <ul class="menu-sub {{ $isActive ? 'active' : '' }}">
            @foreach($item['submenu'] as $submenu)
                @include('partials.menu-item', ['item' => $submenu])
            @endforeach
        </ul>
    @endif
</li>
