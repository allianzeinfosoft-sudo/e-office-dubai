<!-- Menu -->
<?php
use App\helpers\CustomHelper;
$badges = [
    'Survey' => auth()->check() ? CustomHelper::SurveyNotification() : 0,
    'Survey`s' => auth()->check() ? CustomHelper::SurveyNotification() : 0,
    'PAR' => auth()->check() ? CustomHelper::ParNotification() : 0,
    'PAR`s' => auth()->check() ? CustomHelper::ParNotification() : 0,
    'SAR' => auth()->check() ? CustomHelper::SarNotification() : 0,
    'SAR`s' => auth()->check() ? CustomHelper::SarNotification() : 0,
    'Feedback' => auth()->check() ? CustomHelper::FeedbackNotification() : 0,
    'Company Policies' => auth()->check() ? CustomHelper::PolicyNotification() : 0,
    'Ticket Raising' => auth()->check() ? CustomHelper::TicketNotification() : 0,
    'Training Test' => auth()->check() ? CustomHelper::TrainingTestNotification() : 0,
    'Training' => auth()->check() ? CustomHelper::TrainingTestNotification() : 0,

];
?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">
            <span class="logo-white">
                <img class="w-100" src="{{asset('assets/img/icons/logo-white.png') }} ">
            </span>
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
                    {{-- @if(isset($item['badge']))
                        <div class="badge bg-label-primary rounded-pill ms-auto">{{ $item['badge'] }}</div>
                    @endif --}}
                    {{-- Check for dynamic badge --}}
                   @if(isset($badges[$item['title']]) && $badges[$item['title']] > 0)
                        <div class="badge bg-label-danger rounded-pill ms-auto">
                            new {{ $badges[$item['title']] }}
                        </div>
                    @elseif(isset($item['badge']))
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


