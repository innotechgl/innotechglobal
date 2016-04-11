var sScroll = function (divID) {
    var me = this;
    this.divID = divID;
    this.items = $('#' + me.divID + ' .item-container .item');
    this.active = 0;
    this.init = function () {
        var int = setInterval(me.move, 4000);
    }
    this.move = function () {
        ++me.active;
        if (me.active > (me.items.length - 1)) {
            me.active = 0;
        }
        var top = me.active * $(me.items[0]).height();
        $('#' + me.divID + ' .item-container').css({
            '-webkit-transform': 'translate3d(0, -' + top + 'px,0)',
            '-moz-transform': 'translate3d(0, -' + top + 'px,0)',
            '-ms-transform': 'translate3d(0, -' + top + 'px,0)',
            '-o-transform': 'translate3d(0, -' + top + 'px,0)',
            'transform': 'translate3d(0, -' + top + 'px,0)',
        });
    }
}