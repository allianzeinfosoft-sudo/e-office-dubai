@php

    $currentPath = trim(request()->path(), '/');
    $routePath = isset($item['route']) ? trim(parse_url($item['route'], PHP_URL_PATH), '/') : '';
    $isActive = $currentPath === $routePath ||  (isset($item['submenu']) && collect($item['submenu'])->pluck('route')->map(fn($route) => trim(parse_url($route, PHP_URL_PATH), '/'))->contains($currentPath));

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
