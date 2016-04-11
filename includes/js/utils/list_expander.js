var list_expander_class = new Class({
    initialize: function (list, img_src_expand, img_src_collapse) {
        this.list = list;
        this.img_src_expand = img_src_expand;
        this.img_src_collapse = img_src_collapse;
        this.place_buttons();
        $(this.list).addEvent('click', this.event_expander.bind(this));
    },
    event_expander: function (e) {
        if ($(e.target).hasClass('expander')) {
            if ($(e.target).hasClass('closed')) {
                $(e.target).set('text', '[-]');
                this.expand($(e.target).getNext('ul'));
                $(e.target).addClass('opened')
                $(e.target).removeClass('closed')
            }
            else {
                $(e.target).set('text', '[+]');
                this.collapse($(e.target).getNext('ul'));
                $(e.target).addClass('closed')
                $(e.target).removeClass('opened')
            }
        }
    },
    place_buttons: function () {
        var expanders = $(this.list).getElements('li');
        expanders.each(function (item) {
            if ($(item).getElements('ul').length > 0) {
                // Place expand
                var el_exp = new Element('span', {
                    'class': 'expander closed',
                    'html': '[+]'
                }, this);
                el_exp.inject($(item), 'top')
            }
        });
        // Hide them
        $(this.list).getElements('ul').each(function (item) {
            $(item).setStyle('display', 'none')
        });
    },
    collapse: function (item) {
        $(item).setStyle('display', 'none');
    },
    expand: function (item) {
        $(item).setStyle('display', '');
    }
}); 