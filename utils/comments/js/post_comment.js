var comments_class = new Class({
    initialize: function (util_id, div, rel_page) {
        this.util_id = util_id;
        this.div = div;
        this.rel_page = rel_page;
        this.rel_id = 0;
        this.parent_id = 0;
        this.comment_form = 'comment_form_' + util_id;
        this.url = '/ajax/comments/add?type=utils';
        this.name = '';
        this.mail = '';
    },
    set_rel_id: function (id) {
        this.rel_id = id;
    },
    set_parent_id: function (id) {
        this.parent_id = id;
    },
    hide_form: function () {
        $(this.comment_form).setStyles({
            'display': 'none'
        });
    },
    show_form: function (el, rel_id, parent_id) {
        // set ids
        this.rel_id = rel_id;
        this.parent_id = parent_id;
        // Make it visible
        $(this.comment_form).setStyles({
            'left': $(el).getPosition().x,
            'top': $(el).getPosition().y,
            'display': ''
        });
    },
    submit_comment: function () {
        // get comment
        this.comment = $('comment_' + this.util_id + '_comment').get('value');
        // get name
        if ($('comment_' + this.util_id + '_name') !== null) {
            this.name = $('comment_' + this.util_id + '_name').get('value');
        }
        // get mail
        if ($('comment_' + this.util_id + '_mail') !== null) {
            this.mail = $('comment_' + this.util_id + '_mail').get('value');
        }
        // Send comment!
        var comment_req = new Request({
            method: 'post',
            url: '/ajax/comments/?type=utils&task=add&rel_id=' + this.rel_id + '&rel_page=' + this.rel_page + '&parent_id=' + this.parent_id,
            onSuccess: function (responseText) {
                if (responseText == "unregistered") {
                    window.location = "/user/login";
                }
                else {
                    this.hide_form();
                    this.add_comment(responseText);
                }
            }.bind(this)
        }, this);
        comment_req.send('comment=' + this.comment + '&name=' + this.name + '&mail=' + this.mail);
    },
    add_comment: function (html) {
    }
});