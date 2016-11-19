$(document).ready(function () {
    $btn = $("#switch");
    $text = $(".heading h2");
    $back = $("#sectionMiddle");
    $greenOpaque = "rgba(69, 135, 6, 0.8)";
    $green = "rgb(69, 135, 6)";
    $orangeOpaque = "rgba(239, 62, 9, 0.8)";
    $orange = "rgb(239, 62, 9)";
    $swith = false;

    $btn.click(switchColor);

    function switchColor() {
        $swith = !$swith;
        if ($swith) {
            $back.css("background-color", $greenOpaque);
            $text.css("color", $green);
        } else {
            $back.css("background-color", $orangeOpaque);
            $text.css("color", $orange);
        }
    }
})