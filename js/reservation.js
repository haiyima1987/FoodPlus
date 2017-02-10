/**
 * Created by Haiyi on 11/24/2016.
 */
// task module: toggle selected img
var spotReservation = (function () {
    var $spotAvail = $(".available");
    var name = "name";

    $spotAvail.on('click', _toggleAvailableClass);

    function _toggleAvailableClass() {
        var $interested = $(".interested");
        $interested.removeClass("interested").addClass("available").attr('src', 'img/tent_available.png');
        $(this).removeClass("available").addClass("interested").attr('src', 'img/tent_interested.png');
    }
})();

// // task module: error box visibility
// var errorBox = (function () {
//     var $errorBox = $(".errorMsg");
//     var $text = $errorBox.find("h3");
//     var $btnErrorClose = $(".errorMsg i");
//
//     events.on('formIncomplete', showErrorBox);
//     $btnErrorClose.on('click', _closeErrorBox);
//
//     function showErrorBox(msg) {
//         $text.html(msg);
//         events.emit('centerBox', $errorBox);
//         $errorBox.fadeIn(200);
//     }
//
//     function _closeErrorBox() {
//         $errorBox.fadeOut(200);
//     }
// })();
//
// // task module: center any box element
// var centerBox = (function () {
//     events.on('centerBox', centerBox);
//
//     function centerBox(box) {
//         box.css("position", "absolute");
//         box.css("top", Math.max(0, ($(window).height() - box.outerHeight()) / 3 + $(window).scrollTop()) + "px");
//         box.css("left", Math.max(0, ($(window).width() - box.outerWidth()) / 2 + $(window).scrollLeft()) + "px");
//         box.css("z-index", 5);
//     }
// })();

// task module: reject box visibility
var rejectBox = (function () {
    var $rejectBox = $(".reject");
    var $btnRejectClose = $(".reject i");

    $btnRejectClose.on('click', _closeRejectBox);

    function _closeRejectBox() {
        $rejectBox.fadeOut(200);
    }
})();


// task module: add companion fields
var companionAdding = (function () {
    var $btnAdd = $("#btnAdd");
    var $btnRemove = $("#btnRemove");
    var $companionFieldset = $("#companionFieldset");
    var $field = $("#inputField").html();
    var $count = 3;

    $btnAdd.on('click', _addOneInputField);
    $btnRemove.on('click', _removeOneInputField);

    function _addOneInputField() {
        if ($count === 6) {
            events.emit('formIncomplete', "You can maximum have 6 companions");
        } else {
            $companionFieldset.append($field);
            $count++;
        }
    }

    function _removeOneInputField() {
        if ($count === 0) {
            events.emit('formIncomplete', "Minimum 1 person");
        } else {
            $companionFieldset.children().last().remove();
            $count--;
        }
    }
})();

// task module: retrieve data from the page
var dataRetrieving = function () {
    var $companionAccounts = $(".companionAccount");
    var $companionNames = $(".companionName");
    var $data = {};
    var $companionAccountArray = [];
    var $companionNameArray = [];

    $companionAccounts.each(function () {
        $companionAccountArray.push($(this).val());
    });
    $companionNames.each(function () {
        $companionNameArray.push($(this).val());
    });

    $data["reserve_account"] = $("#reserveAccount").val();
    $data["reserve_name"] = $("#reserveName").val();

    for (var i = 0; i < $companionAccounts.length; i++) {
        $data["account" + (i + 1)] = $companionAccountArray[i];
        $data["name" + (i + 1)] = $companionNameArray[i];
    }

    $data["spot_id"] = $(".interested").attr('alt');

    function getData() {
        return $data;
    }

    return {
        getData: getData
    }
};

// task module: evaluate data and generate a message
var dataEvaluating = function () {
    var $data = dataRetrieving().getData();
    var $incompleteMsg = "";
    console.log($data);

    if ($data["spot_id"] === undefined) {
        $incompleteMsg = "Please select your spot";
    } else {
        var $count = 0;
        for (var key in $data) {
            if ($data[key] === "") {
                $incompleteMsg = "Please fill in blank fields";
                break;
            } else if ($count % 2 === 0 && isNaN($data[key])) {
                $incompleteMsg = "Account numbers should contain only numbers";
                break;
            }
            $count++;
        }
    }

    function getResult() {
        if ($incompleteMsg === "") {
            return $data;
        } else {
            return $incompleteMsg;
        }
    }

    return {
        getResult: getResult
    }
};

// task module: send post request
// var postRequest = (function () {
//     var $pageContainer = $("#pageContainer");
//     var $btnSubmit = $("#btnSubmit");
//     var $postLocation = "include/camping.php";
//
//     $btnSubmit.on('click', _postReservation);
//
//     function _postReservation() {
//
//         var data = dataEvaluating().getResult();
//         if ($.type(data) === 'string') {
//             events.emit('formIncomplete', data);
//         } else {
//             $.post(
//                 $postLocation,
//                 data,
//                 function (res, status) {
//                     // $(window).hide().html(res).show();
//                     $pageContainer.hide().html(res).show();
//                     var $rejectBox = $(".reject");
//                     events.emit('centerBox', $rejectBox);
//                 }
//             )
//         }
//     }
// })();

// task module: get reservation price
var reservationPrice = (function () {
    var $personCountInput = $("#personCount");
    var $subtotal = $("#subtotal");
    var $total = $("#total");

    $personCountInput.on('change', updatePrice);

    function updatePrice() {
        var $personCount = $personCountInput.val();

        var price = ($personCount - 1) * 20;
        var subtotal = price + 30;
        $subtotal.html("Subtotal: € " + subtotal);
        $total.html("Total: € " + (subtotal + 50));
    }

    function getPersonCount() {
        var $personCount = $personCountInput.val();
        return {
            person_count: $personCount
        }
    }

    return {
        getPersonCount: getPersonCount
    }
})();

var getDataV1 = function () {
    var data = {};
    var $personCount = $("#personCount").val();
    var $ticket = $("#ticket").val();

    if (!($ticket === undefined))
        data['ticket'] = $ticket;

    data["spot_id"] = $(".interested").attr('alt');
    data["person_count"] = $personCount;

    function getData() {
        if (data["spot_id"] === undefined)
            return "Please select your spot";
        else
            return data;
    }

    return {
        getData: getData
    }
};

// task module: send post request version 1
var postRequestV1 = (function () {
    var $pageContainer = $("#pageContainer");
    var $btnSubmit = $("#btnSubmit");
    var $postLocation = "include/camping.v1.php";

    $btnSubmit.on('click', _postReservation);

    function _postReservation() {
        var data = getDataV1().getData();
        if ($.type(data) === 'string')
            events.emit('formIncomplete', data);
        else {
            $.post(
                $postLocation,
                data,
                function (res, status) {
                    // $(window).hide().html(res).show();
                    $pageContainer.hide().html(res).show();
                    console.log('done');
                    var $rejectBox = $(".reject");
                    events.emit('centerBox', $rejectBox);
                }
            )
        }
    }
})();