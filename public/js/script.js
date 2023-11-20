// Second left menu submenu click functions
// Left Second menu toggle
const navState = !!JSON.parse(localStorage.getItem('toggle'))
document.addEventListener('DOMContentLoaded', () => {
    // toggleSections(navState);
    // toggleBarForSecondNav();
    settingsNav();
    // hideAllMainNav();
    addActiveClassToMain();
    // secondLeftAllMenus();
    secondLeftMenuParent();
    rightSidebar();
    hideSecondNavElements();
    handleSmallScreenForNavigation();
    // handleHeaderTabMenus();

});

// function handleHeaderTabMenus() {
//     // Set active class
//     let headerTabMenus = document.querySelectorAll('.header_tab_menu ul li');
//     headerTabMenus[0].children[0].classList.add('active');
//     let bodyWrappers = document.querySelectorAll('.tab-body-content');
//     bodyWrappers[0].classList.add("active");
// }

function handleSmallScreenForNavigation() {
    if (screen.width < 768) {
        let navigation = document.querySelector('.navigation');
        navigation.style.right = "-270px";
        mainNavigationToggle();
    }
}

function secondLeftMenuParent() {
    let secondLeftMenuParent = document.querySelectorAll(".second_left .sub_nav .dp_sub_left_parent");
    // Second Left Sub Menu
    for (let a = 0; a < secondLeftMenuParent.length; a++) {
        if (secondLeftMenuParent[a]?.children[1]?.classList.contains('d-block')) {
            secondLeftMenuParent[a].children[0].lastElementChild.style.transform = 'rotate(90deg)';
        };

        secondLeftMenuParent[a].addEventListener("click", (e) => {
            for (let b = 0; b < e.target.parentElement.children.length; b++) {
                if (e.target.parentElement.children[b].classList.contains('dp_sub_left_menu')) {
                    if (e.target.parentElement.children[b].classList.contains('d-none')) {
                        e.target.parentElement.children[b].classList.remove('d-none');
                        e.target.parentElement.children[b].classList.add('d-block');
                        if ((secondLeftMenuParent[a].children[0].lastElementChild.style.transform) == 'rotate(0deg)') {
                            secondLeftMenuParent[a].children[0].lastElementChild.style.transform = 'rotate(90deg)';

                        } else if ((secondLeftMenuParent[a].children[0].lastElementChild.style.transform) == 'rotate(90deg)') {
                            secondLeftMenuParent[a].children[0].lastElementChild.style.transform = 'rotate(0deg)';
                        } else {
                            secondLeftMenuParent[a].children[0].lastElementChild.style.transform = 'rotate(90deg)';
                        }
                    } else {
                        e.target.parentElement.children[b].classList.add('d-none');
                        e.target.parentElement.children[b].classList.remove('d-block');
                        if ((secondLeftMenuParent[a].children[0].lastElementChild.style.transform) == 'rotate(0deg)') {
                            secondLeftMenuParent[a].children[0].lastElementChild.style.transform = 'rotate(90deg)';
                        } else if ((secondLeftMenuParent[a].children[0].lastElementChild.style.transform) == 'rotate(90deg)') {
                            secondLeftMenuParent[a].children[0].lastElementChild.style.transform = 'rotate(0deg)';
                        } else {
                            secondLeftMenuParent[a].children[0].lastElementChild.style.transform = 'rotate(90deg)';
                        }
                    }
                }
            }
        });
    }
}

function secondLeftAllMenus() {
    let secondLeftAllMenus = document.querySelectorAll('.second_left .sub_nav>ul li a');
    document.querySelector('.sub_nav>ul').style.display = "block";
    // Icon value from first letter
    for (let d = 0; d < secondLeftAllMenus.length; d++) {
        let menuText = secondLeftAllMenus[d];
    }
}

// function rightSidebar() {
//     const powerButton = document.getElementById('powerButton');
//     const rightSidebar = document.getElementById('rightSidebar');
//     const closeRightSidebar = document.getElementById('closeRightSidebar');
//     const hiddenDivFullWidth = document.getElementById('hiddenDivFullWidth');

//     powerButton?.addEventListener('click', (e) => {
//         e.preventDefault();
//         if (rightSidebar.style.right == '-100%') {
//             rightSidebar.style.right = '0px';
//         } else {
//             rightSidebar.style.right = '-100%';
//         }
//     });

//     closeRightSidebar?.addEventListener('click', (e) => {
//         rightSidebar.style.right = '-100%';
//     });
// }

function hideSecondNavElements() {
    const hideSecondNavElements = document.getElementsByClassName('hideSecondNav');
    for (let i = 0; i < hideSecondNavElements.length; i++) {
        hideSecondNavElements[i].addEventListener('click', (e) => {
            // e.preventDefault();
            hideSecondNav();
            const link = document.getAttribute('href');
            window.location.href = link;
        });
    }
}

function hideSecondNav() {
    let toggleBarForSecondNav = document.querySelector('.second_nav_toggler');
    // const navState = !!JSON.parse(localStorage.getItem('toggle'))
    const nextValue = false;
    if (toggleBarForSecondNav.style.left == '230px') {
        toggleBarForSecondNav.style.transform = "rotate(0deg)";
    } else {
        let allSubNavs = document.querySelectorAll('.sub_nav>ul');
        let ifSubMenuExists = 0;
        for (let a = 1; a < allSubNavs.length; a++) {
            allSubNavs[a].classList.contains('d-block') ? ifSubMenuExists++ : '';
        }
        ifSubMenuExists === 0 ? allSubNavs[1].classList.add('d-block') : "";
        toggleBarForSecondNav.style.transform = "rotate(0deg)";
    }
    localStorage.setItem('toggle', nextValue);
    toggleSections(nextValue); style.left = '-201px';
}

function toggleSecondNav() {
    let body = document.querySelector('body');
    let toggleBarForSecondNav = document.querySelector('.second_nav_toggler');
    const navState = !!JSON.parse(localStorage.getItem('toggle'))
    const nextValue = !navState;
    if (toggleBarForSecondNav.style.left == '230px') {
        toggleBarForSecondNav.style.transform = "rotate(0deg)";
    } else {
        let allSubNavs = document.querySelectorAll('.sub_nav>ul');
        let ifSubMenuExists = 0;
        for (let a = 1; a < allSubNavs.length; a++) {
            allSubNavs[a].classList.contains('d-block') ? ifSubMenuExists++ : '';
        }
        ifSubMenuExists === 0 ? allSubNavs[1].classList.add('d-block') : "";
        toggleBarForSecondNav.style.transform = "rotate(0deg)";
    }
    localStorage.setItem('toggle', nextValue);
    toggleSections(nextValue); style.left = '-201px';
}

function hideAllMainNav() {
    let allMainSubNavs = document.querySelectorAll('.second_left .sub_nav ul');
    allMainSubNavs[0].style.display = "block";
    for (let e = 1; e < allMainSubNavs.length; e++) {
        allMainSubNavs[e].style.display = "none";
    }
}

function addActiveClassToMain() {
    let allMainNavs = document.querySelectorAll('.main__nav ul li');
    for (let c = 0; c < allMainNavs.length; c++) {
        allMainNavs[c]?.addEventListener('click', e => {
            // e.preventDefault();
            let targetDataMenu = e.target.parentElement.parentElement.getAttribute('data-menu');
            let toggleBarForSecondNav = document.querySelector('.second_nav_toggler');
            let secondLeftMenu = document.querySelector('#primary_nav .second_left .sub_nav');
            let headerNav = document.querySelector('.navigation');
            let primaryNav = document.querySelector('#primary_nav');
            let mainwraper = document.querySelector('#main-wraper');
            let bodywraper = document.querySelector('#body-wraper');
            let mainNavId = allMainNavs[c].getAttribute('data-menu');
            let theActiveMenu = document.getElementById(mainNavId);
            if (targetDataMenu != null) {
                removeActiveFromAllMainNav();
                e.target.parentElement.classList.add('active');
                hideAllMainNav();

                theActiveMenu.style.display = "block";
                toggleSections(true);
                toggleBarForSecondNav.style.left = "230px";
                headerNav.style.paddingLeft = "0px";
                secondLeftMenu.style.left = '41px';
                // primaryNav.style.width = "250px";
                mainwraper.style.marginLeft = "221px";
                bodywraper.style.marginRight = "221px";
                localStorage.setItem('toggle', 'true');
            } else {
                localStorage.setItem('toggle', 'false');
            }
        });
    }
}

function removeActiveFromAllMainNav() {
    // remove laravel d-block class to active other menus on click
    let allMainSubNavs = document.querySelectorAll('.second_left .sub_nav ul');
    for (let e = 1; e < allMainSubNavs.length; e++) {
        if (allMainSubNavs[e].classList.contains('d-block')) {
            allMainSubNavs[e].classList.remove('d-block');
        }
    }
    let allMainNavs = document.querySelectorAll('.main__nav ul li');
    for (let d = 0; d < allMainNavs.length; d++) {
        allMainNavs[d].children[0].classList.remove('active');
    }
}

function settingsNav() {
    const settingsNav = document.getElementById('settingsNav');
    settingsNav?.addEventListener('click', function () {
        settingsNav.classList.remove('active');
    })

    const settingsNavClose = document.getElementById('settingsNavClose');
    settingsNavClose?.addEventListener('click', function () {
        if (settingsNav.classList.contains('active')) {
            settingsNav.classList.remove('active');
        } else {
            settingsNav.classList.add('active');
        }
    })
}

function toggleBarForSecondNav() {

    let toggleBarForSecondNav = document.querySelector('.second_nav_toggler');
    if (navState === true) {
        toggleBarForSecondNav.style.transform = "rotate(0deg)";
    } else {
        toggleBarForSecondNav.style.transform = "rotate(0deg)";
    }

    toggleBarForSecondNav?.addEventListener('click', (e) => {

        const navState = !!JSON.parse(localStorage.getItem('toggle'))
        const nextValue = !navState;
        if (toggleBarForSecondNav.style.left == '230px') {
            toggleBarForSecondNav.style.transform = "rotate(0deg)";
        } else {
            let allSubNavs = document.querySelectorAll('.sub_nav>ul');
            let ifSubMenuExists = 0;
            for (let a = 1; a < allSubNavs.length; a++) {
                allSubNavs[a].classList.contains('d-block') ? ifSubMenuExists++ : '';
            }
            ifSubMenuExists === 0 ? allSubNavs[1].classList.add('d-block') : "";
            toggleBarForSecondNav.style.transform = "rotate(0deg)";
        }
        localStorage.setItem('toggle', nextValue);
        toggleSections(nextValue);
    })
}

function toggleSections(navState) {
    let toggleBarForSecondNav = document.querySelector('.second_nav_toggler');
    let secondLeftMenu = document.querySelector('#primary_nav .second_left .sub_nav');
    let headerNav = document.querySelector('.navigation');
    let headerDiv = document.querySelector('#header');
    let primaryNav = document.querySelector('#primary_nav');
    let mainwraper = document.querySelector('#main-wraper');
    let bodywraper = document.querySelector('#body-wraper');

    // ToggleBar position change
    if (navState) {
        toggleBarForSecondNav.style.left = "230px";
        // Header navigation position change
        headerNav.style.paddingLeft = "0px";
        // Second left menu position change
        secondLeftMenu.style.left = '41px';
        // Primary Nav Position Change
        // primaryNav.style.width = "25px";
        mainwraper.style.marginLeft = "221px";
        // mainwraper.style.width = "calc(100% - 221px)";
        // bodywraper.style.marginRight = "221px";
        headerNav.style.width = "calc(100% - 221px)"
    } else {
        toggleBarForSecondNav.style.left = "50px";
        headerNav.style.paddingLeft = "0px";
        headerNav.style.width = "100%";

        secondLeftMenu.style.left = '-201px';
        mainwraper.style.marginLeft = "41px";
        // bodywraper.style.marginRight = "41px";
        headerNav.style.width = "calc(100% - 41px)"
        if(screen.width < 992) {
            mainwraper.style.width = "calc(100% - 41px)";

        }
    }
}


function mainNavigationToggle() {
    let toggleBarForNavigationHeader1 = document.querySelector('.toggle_for_right_navigation');
    let toggleBarForNavigationHeader2 = document.querySelector('#left_bar_toggle');
    let navigation = document.querySelector('.navigation');

    toggleBarForNavigationHeader1?.addEventListener('click', () => {
        navigation.style.right = "0px";
    })

    toggleBarForNavigationHeader2?.addEventListener('click', () => {
        navigation.style.right = "-270px";
    })
}

// Header Tab Functions
// let headerTabMenuList = document.querySelector('.header_tab_menu ul');
// headerTabMenuList?.addEventListener('click', (e) => {
//     // Set active tab
//     if (e.target.hasAttribute('href')) {
//         let headerTabMenus = document.querySelectorAll('.header_tab_menu ul li');
//         for (let a = 0; a < headerTabMenus.length; a++) {
//             headerTabMenus[a].children[0].classList.remove('active');
//         }
//         e.target.classList.add('active');
//         let menuTabId = e.target.getAttribute('data-main-content');
//         menuTabId -= 1;
//         // display body tab
//         let bodyWrappers = document.querySelectorAll('.tab-body-content');
//         for (let b = 0; b < bodyWrappers.length; b++) {
//             bodyWrappers[b].classList.remove('active');
//         }
//         bodyWrappers[menuTabId].classList.add('active');
//     }
// });

// Theme Change
let themeOption = document.getElementById('choose_theme');
themeOption?.addEventListener('click', (e) => {
    if (e.target.classList.contains('color')) {
        let color = e.target.getAttribute('data-theme');
        let body = document.querySelector('body');
        body.classList.remove('light-theme');
        body.classList.remove('dark-theme');
        body.classList.remove('blue-theme');
        body.classList.add(color + '-theme');
        localStorage.setItem('color_theme', color);
    }
});


if($('.dashboard-nav-list').length) {
    var scrollerB = document.querySelector('.dashboard-nav-list');
    var leftArrowB = document.getElementById('leftArrowB');
    var directionB = 0;
    var activeB = false;
    var maxB = 10;
    var VxB = 0;
    var xB = 0.0;
    var prevTimeB = 0;
    var fB = 0.2;
    var prevScrollB = 0;
    function physicsB(time) {
        var diffTimeB = time - prevTimeB;
        if (!activeB) {
            diffTimeB = 80;
            activeB = true;
        }
        prevTimeB = time;

        VxB = (directionB * maxB * fB + VxB * (1-fB)) * (diffTimeB / 20);

        xB += VxB;
        var thisScrollB = scrollerB.scrollLeft;
        var nextScrollB = Math.floor(thisScrollB + VxB);

        if (Math.abs(VxB) > 0.5 && nextScrollB !== prevScrollB) {
            scrollerB.scrollLeft = nextScrollB;
            requestAnimationFrame(physicsB);
        } else {
            VxB = 0;
            activeB = false;
        }
        prevScrollB = nextScrollB;
    }
    leftArrowB.addEventListener('mousedown', function () {
        directionB = -1;
        if (!activeB) {
            requestAnimationFrame(physicsB);
        }
    });
    leftArrowB.addEventListener('mouseup', function () {
        directionB = 0;
    });
    rightArrowB.addEventListener('mousedown', function () {
        directionB = 1;
        if (!activeB) {
            requestAnimationFrame(physicsB);
        }
    });
    rightArrowB.addEventListener('mouseup', function(event){
        directionB = 0;
    });
    $(scrollerB).on('scroll', function() {
        if($(this).scrollLeft() < 1) {
            $(leftArrowB).prop('disabled', true);
        } else {
            $(leftArrowB).prop('disabled', false);
        }
        if($(this).scrollLeft() + 30 + $(this).outerWidth() >= $(this)[0].scrollWidth) {
            $(rightArrowB).prop('disabled', true);
        } else {
            $(rightArrowB).prop('disabled', false);
        }
    });
}

$('.menu-theme').on('click', function(){
    $('body').toggleClass('light-nav');
    if($('body').hasClass('light-nav')) {
        $(this).find('span:last-child').text('Dark Nav');
    } else {
        $(this).find('span:last-child').text('Light Nav');
    }
});
if($('body').hasClass('light-nav')) {
    $('.menu-theme').find('span:last-child').text('Dark Nav');
} else {
    $('.menu-theme').find('span:last-child').text('Light Nav');
}
