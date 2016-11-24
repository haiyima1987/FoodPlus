/**
 * Created by Haiyi on 11/24/2016.
 */
$(document).ready(function () {

    // task 1 toggle selected img
    var $spotAvail = $(".available");

    var toggleAvailableClass = function () {
        var $interested = $(".interested");
        $interested.removeClass("interested");
        $interested.addClass("available");
        $interested.attr('src', 'img/tent_available.png');

        $(this).removeClass("available");
        $(this).addClass("interested");
        $(this).attr('src', 'img/tent_interested.png');
    };

    $spotAvail.on('click', toggleAvailableClass);

    // task 2 add companion fields
    var $btnAdd = $("#btnAdd");
    var $companionFieldset = $("#companionFieldset");
    var $count = 3;

    var addOneInputField = function () {
        if ($count < 3) {
            var $field = $('<div><div class="form-group"><label for="account">Account: </label>' +
                '<input type="text" class="form-control companionAccount" required></div>' +
                '<div class="form-group"><label for="name">Name: </label>' +
                '<input type="text" class="form-control companionName" required></div></div>');

            $companionFieldset.append($field);
            $count++;
        }
        else {
            showErrorBox("You can maximum have 3 companions");
        }
    };

    $btnAdd.on('click', addOneInputField);

    // task 3 remove companion fields
    var $btnRemove = $("#btnRemove");

    var removeField = function () {
        $companionFieldset.children().last().remove();
        $count--;
    };

    $btnRemove.on('click', removeField);

    // task 4 send post request to database
    var $btnSubmit = $("#btnSubmit");
    var $btnClose = $(".errorMsg i");
    var $postLocation = "include/camping.php";
    var $errorBox = $(".errorMsg");
    var $fadeInTime = 200;

    // get data from form and site map
    var getRequiredData = function () {

        var $data = {};
        var $companionAccountArray = [];
        var $companionNameArray = [];

        $data["reserve_account"] = $("#reserveAccount").val();
        $data["reserve_name"] = $("#reserveName").val();

        var $companionAccounts = $(".companionAccount");
        var $companionNames = $(".companionName");

        $companionAccounts.each(function () {
            $companionAccountArray.push($(this).val());
        });
        $companionNames.each(function () {
            $companionNameArray.push($(this).val());
        });

        for (var i = 0; i < $companionAccounts.length; i++) {
            var $accountNum = "account" + (i + 1);
            $data[$accountNum] = $companionAccountArray[i];
            var $accountName = "name" + (i + 1);
            $data[$accountName] = $companionNameArray[i];
        }

        $data["tent_num"] = $(".interested").attr('alt');
        return $data;
    };

    // show error box
    var showErrorBox = function (msg) {
        $errorBox.fadeIn($fadeInTime);
        $errorBox.find("h3").html(msg);
        centerErrorBox();
    };

    // center the error box
    var centerErrorBox = function () {
        $errorBox.css("position", "absolute");
        $errorBox.css("top", Math.max(0, ($(window).height() - $errorBox.outerHeight()) / 2 + $(window).scrollTop()) + "px");
        $errorBox.css("left", Math.max(0, ($(window).width() - $errorBox.outerWidth()) / 2 + $(window).scrollLeft()) + "px");
        $errorBox.css("z-index", 5);
    };

    // post data to database
    var postReservationInfo = function () {
        var $data = getRequiredData();
        console.log($data);
        var $pageContainer = $("#pageContainer");
        var $incompleteMsg = "";

        if ($data["tent_num"] == undefined) {
            $incompleteMsg = "Please select your spot";
        } else {
            var $count = 0;
            for (var key in $data) {
                if ($data[key] === "") {
                    $incompleteMsg = "Please fill in blank fields";
                    break;
                } else if ($count % 2 == 0 && isNaN($data[key])) {
                    $incompleteMsg = "Account numbers should contain only numbers";
                    break;
                }
                $count++;
            }
        }

        if ($incompleteMsg != "") {
            showErrorBox($incompleteMsg);
        } else {
            $.post(
                $postLocation,
                getRequiredData(),
                function (data, status) {
                    $pageContainer.hide().html(data).show();
                }
            )
        }
    };

    var closeErrorBox = function () {
        $errorBox.hide();
    };

    $btnSubmit.on('click', postReservationInfo);
    $btnClose.on('click', closeErrorBox);
});