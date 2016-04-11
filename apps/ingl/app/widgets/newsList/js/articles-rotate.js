var funcArticlesRotate = function (widgetId, interval) {
    me = this;
    this.widgetId = widgetId;
    this.interval = interval;
    this.items = $('#' + widgetId + ' .articles-items .articles-part');
    this.h = $($('#' + me.widgetId + ' .articles-items .articles-part')[0]).height();
    this.active = 0;
    this.init = function () {
        me.showNext();
        // Start timer
        setInterval(me.showNext, me.interval);
    }
    this.showNext = function () {
        console.log('show-next', $($('#' + me.widgetId + ' .articles-items .articles-part')[0]).height());
        var p;
        var previous = me.active - 1;
        if (previous > -1) {
            p = 'translate3d(0,-' + (me.h) + 'px,0)';
            $(me.items[previous]).css({
                '-webkit-transform': p, 'opacity': 0,
                '-moz-transform': p, 'opacity': 0,
                '-ms-transform': p, 'opacity': 0,
                '-o-transform': p, 'opacity': 0,
                'transform': p, 'opacity': 0
            }).one('webkitTransitionEnd oTransitionEnd transitionend msTransitionEnd mozTransitionEnd', function () {
                $(this).css({
                    '-webkit-transform': 'translate3d(0,' + (me.h) + 'px,0)',
                    '-ms-transform': 'translate3d(0,' + (me.h) + 'px,0)',
                    '-moz-transform': 'translate3d(0,' + (me.h) + 'px,0)',
                    '-o-transform': 'translate3d(0,' + (me.h) + 'px,0)',
                    'transform': 'translate3d(0,' + (me.h) + 'px,0)'
                });
            });
        }
        if (me.active >= me.items.length) {
            me.active = 0;
        }
        p = 'translate3d(0,0,0)';
        $(me.items[me.active]).css({
            '-webkit-transform': p, 'opacity': 1,
            'transform': p, 'opacity': 1,
            '-moz-transform': p, 'opacity': 1,
            '-o-transform': p, 'opacity': 1,
            '-ms-transform': p, 'opacity': 1
        });
        ++me.active;
    }
}