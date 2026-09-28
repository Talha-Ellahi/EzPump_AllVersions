{{--@php--}}
{{--    $versionData = \Illuminate\Support\Facades\DB::table('sysConfig')->first();--}}
{{--    $version = $versionData?->ATGEnable == 0 ? '1.2a (No ATG)' : '1.2b (ATG):1.2c (only ATG)';--}}
{{--@endphp--}}

{{--<footer class="text-center text-muted py-3 bg-light border-top">--}}
{{--    <small>--}}

{{--        &copy; {{ now()->year }} EZPump. All rights reserved.--}}
{{--        | Version: v{{ $version }}--}}
{{--    </small>--}}
{{--</footer>--}}
{{--<!-- Simple display -->--}}

@php
    use Illuminate\Support\Facades\DB;

    /* ========================================= */
    /* ========== GET ATG MODE ================= */
    /* ========================================= */

    /* ========================================= */
    /* ========== GET GIT TAG ================== */
    /* ========================================= */

    $tag = '1.0.0';

    try {
        $tagOutput = [];
        // Pick latest numeric tag like 1.4.2a (ignore v-prefixed/non-version tags).
        exec('git tag -l "[0-9]*" --sort=-version:refname 2>&1', $tagOutput);

        if (!empty($tagOutput[0]) && !str_contains(strtolower($tagOutput[0]), 'fatal')) {
            $tag = trim($tagOutput[0]);
        }

    } catch (\Exception $e) {
        $tag = '1.0.0';
    }

    /* ========================================= */
    /* ========== FINAL VERSION STRING ========= */
    /* ========================================= */

    $version = $tag;

@endphp

<footer class="text-center text-muted py-3 bg-light border-top">
    <small>
        &copy; {{ now()->year }} EZPump. All rights reserved.
        | Version: {{ $version }}
    </small>
</footer>


