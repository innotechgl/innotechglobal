var report_class = new Class({
    initialize: function (util_id, div, rel_page) {
        this.util_id = util_id;
        this.div = div;
        this.rel_page = rel_page;
        this.rel_id = 0;
        this.parent_id = 0;
        this.report_form = 'report_form_' + util_id;
        this.url = '/ajax/report/add?type=utils';
    },
    set_rel_id: function (id) {
        this.rel_id = id;
    },
    set_parent_id: function (id) {
        this.parent_id = id;
    },
    hide_form: function () {
        $(this.report_form).setStyles({
            'display': 'none'
        });
    },
    show_form: function (el, rel_id, parent_id) {
        // set ids
        this.rel_id = rel_id;
        this.parent_id = parent_id;
        // Make it visible
        $(this.report_form).setStyles({
            'left': $(el).getPosition().x,
            'top': $(el).getPosition().y,
            'display': ''
        });
    },
    submit_report: function () {
        // get comment
        this.comment = $('report_' + this.util_id + '_comment').get('value');
        this.type = $('report_' + this.util_id + '_type').get('value');
        // Send comment!
        var report_req = new Request({
            method: 'post',
            url: '/ajax/report/?type=utils&task=add&rel_id=' + this.rel_id + '&rel_page=' + this.rel_page + '&parent_id=' + this.parent_id,
            onSuccess: function (responseText) {
                if (responseText == "unregistered") {
                    window.location = "/user/login";
                }
                else {
                    alert(responseText);
                }
            }
        }, this);
        report_req.send('comment=' + this.comment + '&type_of_spam=' + this.type);
    }
});