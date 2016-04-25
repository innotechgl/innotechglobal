<script type="text/javascript">
$(function () {

var menu_ul = $('.menu > li > ul'),
menu_a = $('.menu > li > a');

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
        $('#menu li a').click(function () {
            $('#menu li').removeClass();
            $($(this).attr('href')).addClass('active');
        });
    });

</script>