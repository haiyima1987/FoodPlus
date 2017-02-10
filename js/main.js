// task module: error box visibility
var errorBox = (function () {
    var $errorBox = $(".errorMsg");
    var $text = $errorBox.find("h3");
    var $btnErrorClose = $(".errorMsg i");

    events.on('formIncomplete', showErrorBox);
    $btnErrorClose.on('click', _closeErrorBox);

    function showErrorBox(msg) {
        $text.html(msg);
        events.emit('centerBox', $errorBox);
        $errorBox.fadeIn(200);
    }

    function _closeErrorBox() {
        $errorBox.fadeOut(200);
    }
})();

// task module: center any box element
var centerBox = (function () {
    events.on('centerBox', centerBox);

    function centerBox(box) {
        box.css("position", "absolute");
        box.css("top", Math.max(0, ($(window).height() - box.outerHeight()) / 3 + $(window).scrollTop()) + "px");
        box.css("left", Math.max(0, ($(window).width() - box.outerWidth()) / 2 + $(window).scrollLeft()) + "px");
        box.css("z-index", 5);
    }
})();

var showMenu = (function () {
    var $btnMenu = $(".btnMenu");
    var $menu = $("nav");
    var $menuShown = false;
    var $timeLap = 200;

    $btnMenu.on('click', toggleMenu);

    function toggleMenu() {
        if ($menuShown) {
            $menu.fadeOut($timeLap);
        } else {
            $menu.fadeIn($timeLap);
        }
        $menuShown = !$menuShown;
    }
})();

var scrollUp = (function () {
    var $offset = 250;
    var $duration = 300;
    var $btnUp = $('.btnUp');
    var $page = $('html, body');

    $btnUp.on('click', scrollToTop);
    $(window).on('scroll', showScrollBtn);

    function showScrollBtn() {
        if ($(this).scrollTop() > $offset) {
            $btnUp.fadeIn($duration);
        } else {
            $btnUp.fadeOut($duration);
        }
    }

    function scrollToTop(event) {
        event.preventDefault();
        $page.animate({scrollTop: 0}, $duration);
    }
})();

var showPayBox = (function () {
    var $payBox = $(".payBox");
    var $btnShowPayBox = $("#btnPayBoxShow");
    var $btnPayBoxClose = $("#btnPayBoxClose");

    $btnShowPayBox.on('click', showPayBox);
    $btnPayBoxClose.on('click', hidePayBox);

    function showPayBox() {
        $payBox.fadeIn(200);
        events.emit('centerBox', $payBox);
    }

    function hidePayBox() {
        $payBox.fadeOut(200);
    }
})();

var subscribe = (function () {
    var $btnSub = $("#btnSubscribe");
    var $emailSub = $("#emailSub");

    $btnSub.on('click', saveEmail);

    function saveEmail() {
        var $email = $emailSub.val();

        if (!$email) {
            events.emit('formIncomplete', "Fill in your email please");
        }else{

        }
    }
})();

// var buyTicket = (function () {
//     var $btnPayment = $("#btnPayment");
//     var $postLocation = "index.php";
//
//     $btnPayment.on('click', postTicketPayment);
//
//     function postTicketPayment() {
//         // $.post(
//         //     $postLocation,
//         //     {payment: true},
//         //     function (res, status) {
//         //         // // $(window).hide().html(res).show();
//         //         // $pageContainer.hide().html(res).show();
//         //         // var $rejectBox = $(".reject");
//         //         // events.emit('centerBox', $rejectBox);
//         //     }
//         // )
//     }
// })();