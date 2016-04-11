/* classes */
var ZebraTables = new Class({
    //initialization
    initialize: function (table_class) {

        //add table shading
        $$('table.' + table_class + ' tr').each(function (el, i) {

            //do regular shading
            var _class = i % 2 ? 'even' : 'odd';
            el.addClass(_class);
            //do mouseover
            el.addEvent('mouseenter', function () {
                if (!el.hasClass('highlight')) {
                    el.addClass('mo').removeClass(_class);
                }
            });
            //do mouseout
            el.addEvent('mouseleave', function () {
                if (!el.hasClass('highlight')) {
                    el.removeClass('mo').addClass(_class);
                }
            });
            //do click
            el.addEvent('click', function () {
                //click off
                if (el.hasClass('highlight')) {
                    el.removeClass('highlight').addClass(_class);
                }
                //click on
                else {
                    el.removeClass(_class).removeClass('mo').addClass('highlight');
                }
            });
        });
    }
});