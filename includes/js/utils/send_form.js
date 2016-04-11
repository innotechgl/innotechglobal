var send_form_class = new Class({
    Implements: Options,
    options: {
        link: '',
        form: '',
        success_text: 'You have succesfully posted form!'
    },
    initialize: function (options) {
        this.form = options.form;
        this.link = options.link;
        this.success_text = options.success_text;
        this.events();
    },
    events: function () {
        $(this.form).addEvent('submit', this.submit.bind(this));
    },
    submit: function (event) {
        event.stop();
        $(this.form).set('action', this.link);
        $(this.form).set('send', {
            onComplete: function (response) {
                alert(this.success_text);
            }.bind(this)
        });
        $(this.form).send();
    }
});