<!-- jQuery -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script src="{{ asset('TemplatePixel/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('TemplatePixel/js/pixeladmin.min.js') }}"></script>

<script src="{{ asset('TemplatePixel/js/app.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-chained/1.0.1/jquery.chained.min.js"
    integrity="sha512-rcWQG55udn0NOSHKgu3DO5jb34nLcwC+iL1Qq6sq04Sj7uW27vmYENyvWm8I9oqtLoAE01KzcUO6THujRpi/Kg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('#datatables').dataTable();
    $('#datatables2').dataTable();
</script>

{{-- instajs scan QR --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/instascan/1.0.0/instascan.min.js"></script>

<!-- sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

{{-- Full Calendar --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
@stack('scripts')
@stack('js')



</body>

</html>
