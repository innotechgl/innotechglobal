/* SEARCH classes */
var search_class = new Class({
    initialize: function (div, parent_element, search_element) {
        this.div = div;
        this.parent_element = parent_element;
        this.search_element = search_element;
        // get elements
        this.elements = $(this.div).getElements(this.search_element);
    },
    search_it: function (search_text) {
        found = [];
        var myregexp = new RegExp(search_text.toUpperCase(), 'gi');
        this.elements.each(function (item) {
            var matched = myregexp.exec($(item).get('text').toUpperCase());
            $(item).set('opacity', .3);
            if (matched != null) {
                //console.log(matched);
                found.push(item);
                $(item).set('opacity', 1);
            }
        });
        //$('found').set('html','Found: '+found.length);
    }
});