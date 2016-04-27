<script type="text/javascript">
$(function () {

var menu_ul = $('.right-menu-ul > li > ul'),
menu_a = $('.right-menu-ul > li > a');

menu_ul.hide();

menu_a.click(function (e) {
e.preventDefault();
if (!$(this).hasClass('active')) {
menu_a.removeClass('active');
menu_ul.filter(':visible').slideUp('normal');
$(this).addClass('active').next().stop(true, true).slideDown('normal');
} else {
$(this).removeClass('active');
$(this).next().stop(true, true).slideUp('normal');
}
});

});
</script>
<script type="text/javascript">

    $(function () {
        $('#right-menu-ul li a').click(function () {
            $('#right-menu-ul li').removeClass();
            $($(this).attr('href')).addClass('active');
        });
    });

</script>
<script>
    function cycleImages(){
        var $active = $('#cycler .active');
        var $next = ($active.next().length > 0) ? $active.next() : $('#cycler img:first');
        $next.css('z-index',2);//move the next image up the pile
        $active.fadeOut(1500,function(){//fade out the top image
            $active.css('z-index',1).show().removeClass('active');//reset the z-index and unhide the image
            $next.css('z-index',3).addClass('active');//make the next image the top one
        });
    }

    $(document).ready(function(){
// run every 7s
        setInterval('cycleImages()', 7000);
    })
</script>