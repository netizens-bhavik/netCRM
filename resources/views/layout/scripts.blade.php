<script src="{{ url('layout/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ url('layout/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ url('layout/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ url('layout/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
<script src="{{ url('layout/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ url('layout/assets/vendor/libs/hammer/hammer.js') }}"></script>

<script src="{{ url('layout/assets/vendor/js/menu.js') }}"></script>

<!-- endbuild -->
{{-- select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Vendors JS -->

<!-- Main JS -->
<script src="{{ url('layout/assets/js/main.js') }}"></script>
<script>
    $(document).ready(function() {
        $('select').select2({ width: '100%' });
    });
</script>
@yield('scripts')
