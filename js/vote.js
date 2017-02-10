/**
 * Created by Haiyi on 12/1/2016.
 */
var voting = (function () {
    var $frame = $(".frame");
    var $votesLeft = $("#votesLeft").html();
    var $count = 0;

    $frame.on('click', toggleSelected);

    function toggleSelected() {
        var person = $(this);
        if ($votesLeft != -1) {
            if (person.hasClass("unselected")) {
                if ($count < $votesLeft) {
                    person.removeClass("unselected").addClass("selected");
                    $count++;
                } else {
                    events.emit('formIncomplete', "You can vote for maximum " + $votesLeft + " persons");
                }
            } else {
                person.removeClass("selected").addClass("unselected");
                $count--;
            }
        }
    }
})();

var warningBox = (function () {
    var $btnVote = $("#btnVote");
    var $warningBox = $("#warningBox");
    var $btnNo = $("#warningNo");

    $btnVote.on('click', showWarningBox);
    $btnNo.on('click', closeWarningBox);

    function showWarningBox() {
        $warningBox.fadeIn(200);
        events.emit('centerBox', $warningBox);
    }

    function closeWarningBox() {
        $warningBox.fadeOut(200);
    }
})();

var voteCollecting = function () {
    var $votes = $(".selected img");
    var $data = {};

    $votes.each(function () {
        $data[$(this).attr('alt')] = $(this).attr('vote');
    });

    function getVotes() {
        return $data;
    }

    return {
        getVotes: getVotes
    }
};

var postVoting = (function () {
    var $btnYes = $("#warningYes");
    var $pageContainer = $("#pageContainer");
    var $postLocation = "include/vote.php";

    $btnYes.on('click', postVotes);

    function postVotes() {
        var data = voteCollecting().getVotes();
        console.log(data);

        if ($.isEmptyObject(data)) {
            events.emit('formIncomplete', "At least one competitor has to be voted");
        } else {
            $.post(
                $postLocation,
                data,
                function (res, status) {
                    // $(window).hide().html(res).show();
                    $pageContainer.hide().html(res).show();
                    var $rejectBox = $(".reject");
                    events.emit('centerBox', $rejectBox);
                }
            )
        }
    }
})();