var item_mover_class = new Class({
    initialize: function (el, elCont, item_class, numToShow, stopped, period) {
        this.tempStop = stopped;
        this.el = el;
        this.elCont = elCont;
        this.items = $$('.' + item_class);
        this.activeElement = 0;
        this.numToShow = numToShow;
        this.period = period;
        this.fxEl = new Fx.Morph(elCont, {
            duration: 300,
            transition: Fx.Transitions.Sine.easeOut
        });
        // Setup
        this.setUp();
        // Set events
        this.events();
        if (this.tempStop == false) {
            this.startIt();
        }
    },
    setUp: function () {
        var height = 0;
        for (var i = 0; i < this.numToShow; i++) {
            if (this.items[i] !== undefined) {
                height += $(this.items[i]).getSize().y;
            }
        }
        // Set new size for cont element
        $(this.el).setStyle('height', height);
    },
    events: function () {
        $(this.el).addEvent('mouseover', this.stopIt.bind(this));
        $(this.el).addEvent('mouseout', this.startIt.bind(this));
    },
    stopIt: function (event) {
        clearInterval(this.p);
        this.tempStop = true;
    },
    startIt: function (event) {
        this.tempStop = false;
        this.p = this.moveNext.periodical(this.period, this);
    },
    moveNext: function () {
        if (this.tempStop == false) {
            ++this.activeElement;
            if (this.activeElement > this.items.length - 1) {
                this.activeElement = 0;
            }
            this.moveIt();
        }
    },
    moveIt: function () {
        var pos = -($(this.items[this.activeElement]).getPosition(this.elCont).y);
        this.fxEl.cancel();
        this.fxEl.start({
            'top': pos
        });
    }
});
