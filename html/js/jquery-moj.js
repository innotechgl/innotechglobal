$( document ).ready(function() {

$('button').fadeIn(3300).removeClass('hidden');

$("button").click(function(){

        $(".vezba").slideToggle("fast");
        });

// .empty() -> brise sadrzaj ali ne i strukturu
// .remove() -> brise sadrzaj i strukturu!
//$("p").remove(); == .empty();
/*
$('selector').addClass('className');
$('selector').removeClass('className');
*/
});
