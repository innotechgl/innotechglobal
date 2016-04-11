$('upr_photo_form').addEvent('click', upr_photo_form);
function upr_photo_form(event) {
    var el;
    var el_remove;
    switch ($(event.target).get('id')) {
        case "add":
            el = new Element('input', {
                'type': 'file',
                'name': 'photo[]',
                'size': 40
            });
            el_remove = new Element('button', {
                'type': 'button',
                'id': 'remove',
                'text': ' - '
            });
            el.inject('upr_photo_form');
            el_remove.inject(el, 'after');
            break;
        case "remove":
            el = $(event.target).getPrevious('input');
            $(el).destroy();
            $(event.target).destroy();
            break;
    }
}