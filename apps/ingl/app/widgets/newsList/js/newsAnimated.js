var funcNewsList = function (widgetId, interval) {
    me = this;
    this.widgetId = widgetId;
    this.interval = interval;
    this.items = $('#' + widgetId + ' .news .news-item');
    this.active = 0;
    this.init = function () {
        var h = 0;
        for (var i = 0; i < this.items.length; i++) {
            $('.news-items-pags').append('<a class="pag"></a>');
        }
        $($('.news-items-pags a.pag')[0]).addClass('active');
        // Move them right
        jQuery.each(me.items, function () {
            $(this).css('left', $('#' + widgetId + ' .news').outerWidth());
            if ($(this).height() > h) {
                h = $(this).height();
            }
        });
        $('#news').height((h + 80))
        me.showNext();
        // Start timer
        setInterval(me.showNext, me.interval);
    }
    this.showNext = function () {
        var previous = me.active - 1;
        if (previous > -1) {
            //console.log('move ME!',-$('#'+widgetId+' #news').width());
            $(me.items[previous]).animate({
                'left': -($('#' + widgetId + ' .news').outerWidth() + 15)
            }, 500, 'linear', function () {
                $(this).css('left', $('#' + widgetId + ' .news').outerWidth());
            });
        }
        if (me.active >= me.items.length) {
            me.active = 0;
        }
        $(me.items[me.active]).animate({
            'left': 0
        }, 500);
        for (var i = 0; i < $('.news-items-pags a.pag').length; i++) {
            $('.news-items-pags a.pag').removeClass('active');
        }
        $($('.news-items-pags a.pag')[me.active]).addClass('active');
        ++me.active;
    }
}