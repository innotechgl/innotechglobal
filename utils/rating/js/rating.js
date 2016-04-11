// JavaScript Document
function rate_it(el, id, page, rate) {
    load_ajax(el, '/ajax/rating/rate_it/' + id + '/' + page + '/' + rate + '?type=utils');
}