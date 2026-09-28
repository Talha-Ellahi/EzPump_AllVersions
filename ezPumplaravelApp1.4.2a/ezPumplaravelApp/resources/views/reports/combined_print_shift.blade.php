@include('reports.print_headers')
<style>
    @media print {
        .no-print,
        .no-print * {
            display: none !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
        }

        /* Remove page breaks for hidden content */
        .no-print ~ .pagebreak,
        .no-print .pagebreak {
            display: none !important;
        }
    }

</style>
@include('tank.shift_print')
    <div class=pagebreak></div>

@include('reports.print_shift')
