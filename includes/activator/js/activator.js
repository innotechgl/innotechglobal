var activator_class = new Class({
    initialize: function (id_cont, default_link) {
        this.id_cont = id_cont;           // Set id cont
        this.default_link = default_link; // Set default link
        // Start events
        this.events();
    },
    events: function () {
        // Add click event
        $(this.id_cont).addEvent('click', this.clickEvent.bind(this));
    },
    clickEvent: function (e) {
        //e.stop();
        if ($(e.target).get('type') == 'checkbox' && $(e.target).hasClass('activator')) {
            var link = this.default_link + '?id=' + $(e.target).get('value') + '&active=' + $(e.target).get('checked');
            this.callAjax(link);
        }
    },
    callAjax: function (link) {
        var myRequest = new Request({
            method: 'get',
            url: link,
            onComplete: function (responseText) {
            }
        }, this).send();
    }
});