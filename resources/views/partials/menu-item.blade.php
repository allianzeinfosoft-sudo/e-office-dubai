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

    $filterMenuRecursive = function($item) use (&$filterMenuRecursive, $checkPermission) {
        if (!$checkPermission($item['permission'] ?? null)) {
            return null;
        }

        if (isset($item['submenu'])) {
            $item['submenu'] = collect($item['submenu'])
                ->map(fn($sub) => $filterMenuRecursive($sub))
                ->filter()
                ->values()
                ->toArray();

            // If submenu becomes empty, and this is just a wrapper, skip it
            if (empty($item['submenu']) && (!isset($item['route']) || $item['route'] === '#' )) {
                return null;
            }
        }

        return $item;
    };

    $item = $filterMenuRecursive($item);
@endphp

@if($item)
    @php
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
                if (\Illuminate\Support\Str::is($pattern, $currentPath)) {
                    $isActive = true;
                    break;
                }
            }
        }
    @endphp

    <li class="menu-item {{ $isActive ? 'active open' : '' }}">
        <a href="{{ isset($item['route']) ? url($item['route']) : '#' }}"
            class="menu-link {{ isset($item['submenu']) ? 'menu-toggle' : '' }}">
                <i class="menu-icon tf-icons {{ $item['icon'] ?? '' }}"></i>

                <div data-i18n="{{ $item['title'] }}">
                    {{ $item['title'] }}
                </div>

                {{-- 🔥 Dynamic badge (main + submenu) --}}
                @if(isset($badges) && isset($badges[$item['title']]) && $badges[$item['title']] > 0)
                    <div class="badge bg-label-danger rounded-pill ms-auto">
                        new {{ $badges[$item['title']] }}
                    </div>

                {{-- Static badge fallback --}}
                @elseif(isset($item['badge']))
                    <div class="badge bg-label-primary rounded-pill ms-auto">
                        {{ $item['badge'] }}
                    </div>
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
