var like_class = new Class({
    add: function (el, type, rel_id, rel_page) {
        var addLikeDislike = new Request({
            method: 'get',
            url: '/ajax/like/',
            onSuccess: function (responseText) {
                if (responseText == "unregistered") {
                    window.location = "/user/login";
                }
                else {
                    $(el).set('text', type + ' (' + responseText + ')')
                }
            }
        });
        addLikeDislike.send('type=utils&task=' + type + '&rel_id=' + rel_id + '&rel_page=' + rel_page);
    }
});
var like = new like_class();