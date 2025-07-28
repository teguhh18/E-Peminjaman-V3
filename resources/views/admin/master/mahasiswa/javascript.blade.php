 <div id="tempat-modal"></div>

 @push('js')
     <script>
         $(document).ready(function() {
             $('.select2').select2();
         });
         $(window).resize(function() {
             $('.select2').css('width', "100%");
         });
     </script>
 @endpush
