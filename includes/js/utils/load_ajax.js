function load_ajax(el, url_link) {
    var myHTMLRequest = new Request.HTML({
        url: url_link,
        update: el
    }).post();
}