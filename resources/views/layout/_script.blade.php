<script>
    var sidebarState = localStorage.getItem('sidebarState');
    // document.querySelector('.left-sidebar.open-sub').style.width = '220px';
    if (sidebarState === 'open') {
        document.getElementById('left-sidebar').classList.add('open-sub');
        document.getElementById('main-wraper').classList.add('menu-expanded');
        // document.getElementById('shortcut-section').classList.add('menu-expanded');
    }

    // Horizontal VS Vertical
    let isHorizontal = window.localStorage.getItem('isHorizontal');
    if (isHorizontal == 'true') {
        document.getElementById('left-sidebar').classList.add('horizontal-menu');
        document.getElementById('orientationName').innerHTML = 'Vertical';
        document.getElementById('main-wraper').style.marginLeft = '0px';
    }

    // Light VS Dark Theme
    let isLightTheme = window.localStorage.getItem('isLightTheme');
    if (isLightTheme == 'true') {
        document.body.classList.add('light-nav');
        document.getElementById('themeNameText').innerHTML = 'Dark Nav';
    }
</script>

<script src="{{ asset('plugins/jquery-3.6.0.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@include('layout.partial._session_message')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script src="{{ asset('plugins/print_this/printThis.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="//cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('plugins/bootstrap-dropdown.js') }}"></script>
<script src="{{ asset('plugins/TableTools.min.js') }}"></script>
<script src="{{ asset('plugins/jeditable.jquery.js') }}"></script>
<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/SimpleCalculadorajQuery.js') }}" defer></script>
@if (isset($custom_modal) && $custom_modal === true)
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
{{-- <script src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/datatables.min.js"></script> --}}
<script src="{{ asset('plugins/data-table/print.datatable.min.js') }}"></script>

<script src="{{ asset('js/number-bdt-formater.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>

<script>
    window.SCALE_API = "{{ config('company.scale_api') ?? 'http://localhost:8888' }}"
    toastr.options = {
        "positionClass": "toast-top-center"
    }

    $(document).on('click', '#logout_option', function(e) {
        e.preventDefault();
        $.confirm({
            'title': 'Logout Confirmation',
            'content': 'Are you sure, you want to logout?',
            'buttons': {
                'Yes': {
                    'btnClass': 'yes btn-primary',
                    'action': function() {
                        $('#logout_form').submit();
                    }
                },
                'No': {
                    'btnClass': 'no btn-danger',
                    'action': function() {
                        //
                    }
                }
            }
        });
    });

    $(document).on('click', '.display tbody tr', function() {

        var data = $(this).data('active_disabled');

        if (data == undefined) {

            $('.display tbody tr').removeClass('active_tr');
            $(this).addClass('active_tr');
        }
    });

    $(document).on('click', '.selectable tbody tr', function() {

        var data = $(this).data('active_disabled');

        if (data == undefined) {

            $('.selectable tbody tr').removeClass('active_tr');
            $(this).addClass('active_tr');
        }
    });

    $(document).on('click', '#hard_reload', function() {

        window.location.reload(true);
    });

    $(document).on('click', '.close-btn', function(e) {
        e.preventDefault();
    });

    $(document).on('select2:open', () => {

        document.querySelector('.select2-search__field').focus();
    });
</script>

<script>
    /* Get the documentElement (<html>) to display the page in fullscreen */
    var elem = document.getElementById("dashboard-8")

    function openFullscreen() {
        $('.addFullScrintBtn').addClass('d-hide');
        $('.exitFullScrintBtn').removeClass('d-hide');

        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) {
            /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) {
            /* IE11 */
            elem.msRequestFullscreen();
        }
    };

    function closeFullscreen() {
        $('.addFullScrintBtn').removeClass('d-hide');
        $('.exitFullScrintBtn').addClass('d-hide');
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            /* Safari */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            /* IE11 */
            document.msExitFullscreen();
        }
    };
</script>

<script>
    $(window).on('load', function() {
        var preLoder = $(".preloader");
        preLoder.fadeOut(300);
    });

    $(document).ready(function() {
        // Modal Customize (Draggable)
        // $('.modal-dialog').draggable();

        // Right-sidebar
        (function rightSidebar() {
            const powerButton = document.getElementById('powerButton');
            const rightSidebar = document.getElementById('rightSidebar');
            const closeRightSidebar = document.getElementById('closeRightSidebar');
            const hiddenDivFullWidth = document.getElementById('hiddenDivFullWidth');

            powerButton?.addEventListener('click', (e) => {
                e.preventDefault();
                if (rightSidebar.style.right === '0px') {
                    rightSidebar.style.right = '-100%';
                } else {
                    rightSidebar.style.right = '0px';
                }
            });

            closeRightSidebar?.addEventListener('click', (e) => {
                rightSidebar.style.right = '-100%';
            });
        })();

        // Tooltip script
        (function toolTip() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover'
                });
            });
        })();

        // Selec2 script
        $(document).on('select2:open', 'select', function() {

            if ($('body').hasClass('modal-open')) {

                $('.select2-dropdown').addClass('select2-in-modal');
            } else {

                $('.select2-dropdown').removeClass('select2-in-modal');
            }
        });

        // CkEditor
        window.editors = {};
        document.querySelectorAll('.ckEditor').forEach((node, index) => {
            ClassicEditor
                .create(node, {})
                .then(newEditor => {
                    newEditor.editing.view.change(writer => {
                        var height = node.getAttribute('data-height');
                        writer.setStyle('min-height', height + 'px', newEditor.editing.view.document.getRoot());
                    });
                    window.editors[index] = newEditor
                });
        });
    });

    $(document).on('hidden.bs.modal', '.modal', function() {
        
        if ($('.modal.show').length > 0) {

            $('body').addClass('modal-open');
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mapael/2.2.0/js/jquery.mapael.min.js"></script>
<script src="https://www.jqueryscript.net/demo/jQuery-Interactive-Vector-Map-Plugin-Mapael/js/maps/world_countries.js"></script>
<script>
    $(".container").mapael({
        map: {
            name: "world_countries"
        }
    });

    if ($('#categorisedAssets').length) {
        var data = [
            ['Category 1', 20],
            ['Category 2', 30],
            ['Category 3', 15],
            ['Category 4', 35]
        ];

        // Create the chart
        Highcharts.chart('categorisedAssets', {
            chart: {
                type: 'pie',
                height: 510
            },
            title: {
                text: ''
            },
            plotOptions: {
                pie: {
                    innerSize: '70%' // Adjust the innerSize to control the size of the hole
                }
            },
            series: [{
                name: 'Categories',
                data: data
            }]
        });
    }
</script>
