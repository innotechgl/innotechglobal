// JavaScript Document
var widgets_class = new Class({
    initialize: function () {
        this.positions = $$('.widgets_toolbar');
        this.options_visible = false;
    },
    /* SHOW HIDE OPTIONS BLOCK*/
    toggle: function () {
        if (this.options_visible == true) {
            this.hideOptions();
        }
        else {
            this.showOptions();
        }
    },
    showOptions: function () {
        // Show
        this.positions.each(function (item) {
            $(item).setStyle('display', '');
        });
    },
    hideOptions: function () {
        // Hide
        this.positions.each(function (item) {
            $(item).setStyle('display', 'none');
        });
    },
    /* SHOW FORMS */
    showFormNew: function (elWhere, positionName) {
        // Load ajax
        load_ajax('/ajax/widgets/show_new_widget_form/index.php.html?position_name=' + positionName, 'widgetForm');
        $('widgetForm').setStyles({'left': $(elWhere).getPosition().x, 'top': $(elWhere).getPosition().y})
    }
});