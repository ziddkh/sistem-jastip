<x-app>
    <x-page-heading>
        <x-page-title>Laporan Harian Jastip</x-page-title>
        <div class="row mb-3">
            <div class="col-12">
                <a
                    class="btn btn-danger"
                    id="btn-export-pdf"
                    href="{{ route('laporan-jastip.exportDailyPdf') }}"
                >
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div id="js-packages-partial-target">
                    @if (!empty($cachedPackages))
                        {!! $cachedPackages !!}
                    @endif
                </div>
            </div>
        </div>
    </x-page-heading>
    <x-slot:script>
        <script>
            const IS_CACHED = {{ !empty($cachedPackages) ? 'true' : 'false' }};
            const DAILY_REPORT_JASTIP_URL = '{{ route('laporan-jastip.getDailyData') }}';
        </script>
        <script id="placeholder-template" type="text/template">
      @include('pages.report-jastip._placeholder')
    </script>
        <script>
            const PLACEHOLDER_ELEMENT = document.getElementById('placeholder-template').innerHTML;
        </script>
        <script
            type="module"
            src="{{ asset('js/daily-report-jastip.js') }}"
        ></script>
    </x-slot:script>
</x-app>
