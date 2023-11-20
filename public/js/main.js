// $(window).on('load', function() {
//     $('.main__nav ul li').first().addClass('active');
//     $('#sidebar div').first().addClass('active');
// })

$(document).ready(function () {
    $('.main__nav ul li').on('click', function () {
        $('.main__nav ul li').removeClass('menu_active');
        $(this).addClass('menu_active');
        let menuID = $(this).data('menu')
        $('#sidebar_t div').removeClass('active');
        $('#' + menuID).addClass('active')
    })
})

// =============================================================side manu====================
// $(document).ready(function() {
//     $('.main__nav_t ul li').on('click', function() {
//         $('.main__nav_t ul li').removeClass('active');
//         $(this).addClass('active');
//         let menuID = $(this).data('menu')
//         $('#sidebar_t div').removeClass('active');
//         $('#' + menuID).addClass('active')
//     })
// })

// ===============================================================sub menu active ====================
// $(document).ready(function() {
//     $('#sidenav li').on('click', function() {
//         $('#sidenav li').removeClass('active');
//         $(this).addClass('active');
//         var sidemenu = $(this).data('submenu')
//         $('#sidenav div').removeClass('active');
//         $('#' + sidemenu).addClass('active')
//     })
// })
// ===============================================================sub menu active end ====================
// ===============================================================sub menu active ====================

$(document).ready(function () {
    $('.close-model').on('click', function () {
        $('.sub-menu').removeClass('active');
    })
})

$(document).ready(function () {
    $('.close-model').on('click', function () {
        $('.sub-menu_t').removeClass('active');
    })
})
// ===============================================================sub menu close end ====================

$(document).ready(function () {
    $('#left_bar_toggle').on('click', function () {
        $('#primary_nav').toggleClass('active');
        $('.top-main-menu').toggleClass('active');

        $('.top-menu-dropdown').on('click', function () {
            $('.top-dp-menu-one').toggleClass('first-menu-active');
        });

        if ($(window).width() < 768 && $(window).width() > 500) {
            $('.body-wraper').toggleClass('toggle_reduce_body_wraper_tab');
            $('.main-wraper').toggleClass('toggle_reduce_main_wraper_tab');
            $('.navigation').toggleClass('toggle_reduce_navigation_width_tab');
            $('#left_bar_toggle').css('margin-right', '0px!important');
        } else if ($(window).width() < 500) {
            $('.body-wraper').toggleClass('toggle_reduce_body_wraper_mobile');
            $('.main-wraper').toggleClass('toggle_reduce_main_wraper_mobile');
            $('.navigation').toggleClass('toggle_reduce_navigation_width_mobile');
            $('#left_bar_toggle').css('margin-right', '0px!important');
        }
    })
})

// ===============================================toltip==============================
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl, {
        trigger: 'hover'
    });
});



// =====================================================text editor=================================
// ClassicEditor
//     .create(document.querySelector('#editor'), {
//         // toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]
//     })
//     .then(editor => {
//         window.editor = editor;
//     })
//     .catch(err => {
//         console.error(err.stack);
//     });

// =============================================taginput======================
// $('#input-tags').selectize({
//     persist: false,
//     createOnBlur: true,
//     create: true,
//     plugins: ['remove_button'],
// });

// $('#select-state').selectize({
//     maxItems: 9999,
//     plugins: ['remove_button'],
// });

// $('#select-gear').selectize({
//     sortField: 'text',
//     plugins: ['remove_button'],
// });

// =====================================================form repetar==========
// $(document).ready(function () {
//     $('.repeater').repeater({
//         show: function () {
//             $(this).slideDown();
//         },
//         hide: function (deleteElement) {
//             if (confirm('Are you sure you want to delete this element?')) {
//                 $(this).slideUp(deleteElement);
//             }
//         },
//         ready: function (setIndexes) {

//         }
//     });
// });

// ======================================================button switch============
$(function () {
    $('div.switch-grup button').on('click', function () {
        $(this).addClass('selected').siblings().removeClass('selected');
    });
});

// =========================================rating===============
// =========================================clickeditor==================
// $(document).ready(function () {
//     console.log("CLEEDITOR INSTALLED");
//     $(".cleeditor").cleeditor();
// });

// ===============================================search and select================
$(function () {
    // $("#searchSelect").customselect();
});

//=================== Color change option ======================
$(function () {
    $('.color_change_wrapper ul li').on('click', function () {
        let cls = this.className;
        if (cls === 'red') {
            $('.color_change_wrapper ul li').removeClass('active');
            this.classList.add('active');
            $('body').removeClass();
            $('body').addClass('red-theme');
        } else if (cls === 'blue') {
            $('.color_change_wrapper ul li').removeClass('active');
            this.classList.add('active');
            $('body').removeClass();
            $('body').addClass('blue-theme');
        } else if (cls === 'dark') {
            $('.color_change_wrapper ul li').removeClass('active');
            this.classList.add('active');
            $('body').removeClass();
            $('body').addClass('dark-theme');
        } else if (cls === 'light') {
            $('.color_change_wrapper ul li').removeClass('active');
            this.classList.add('active');
            $('body').removeClass();
            $('body').addClass('light-theme');
        }
    })
});
