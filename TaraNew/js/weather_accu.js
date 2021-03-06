var oapBootstrapVer = "2013-06-24-11-28", _gaq = _gaq || [];
function pgfxLoadBlankBroadcasterImage() {
    return !1
}
var lifestyleIndexToDFP = {
    golf: 69749050,
    driving: 69749170,
    "air-travel": 69749290,
    "sun-sand": 69749410,
    fishing: 69749530,
    sailing: 69749650,
    ski: 69749770,
    "school-day": 69749890,
    allergies: 69750010,
    running: 69750130,
    astronomy: 69750250,
    hunting: 69750610,
    "lawn-garden": 69750730,
    biking: 69750850,
    hiking: 69750970,
    diy: 69751090,
    "outdoor-entertaining": 69751210,
    "snow-day": 69751330,
    "cold-flu": 69751570,
    "home-energy": 69751690,
    events: 69751810,
    "hair-day": 69751930,
    asthma: 69752050,
    migraine: 69752170,
    arthritis: 69752410,
    sinus: 69752650
};
(function () {
    function z() {
        f = window.jQuery.noConflict(!0);
        G()
    }

    function G() {
        J();
        f(document).ready(function (b) {
            if (-1 != document.domain.indexOf("accuweather") || b(".aw-widget-legal").length) {
                b.each(e.css, function (a, c) {
                    var e = "aw-widget-css-" + a;
                    b("#" + e).length || b("<link>", {
                        id: e,
                        rel: "stylesheet",
                        type: "text/css",
                        href: c
                    }).appendTo("head")
                });
                var c = f(e.current.selector).add(e.threeday.selector), a = b();
                c.each(function () {
                    this.awInit || a.length || (a = b(this), this.awType = -1 != a.data("uid").indexOf("awcc") ? "current" : "threeday",
                        this.awInit = !0)
                });
                a.html('<span class="message">Loading Widget\u2026</span>');
                c = a.data();
                c.css && !b("#aw-widget-client-css").length && b("<link>", {
                    id: "aw-widget-client-css",
                    rel: "stylesheet",
                    type: "text/css",
                    href: c.css
                }).appendTo("head");
                c.css = null;
                f.getJSON(e[a.get(0).awType].url + "?callback=?", c, function (c) {
                    c.style && b('<style type="text/css">' + c.style + "</style>").appendTo("head");
                    a.html(c.html);
                    x(f, a)
                })
            } else alert("It seems the widget code is incomplete. Please visit http://www.accuweather.com/en/free-weather-widgets to set up your free weather widget.")
        })
    }

    function y(b) {
        b.html('<span class="message">Loading Widget\u2026</span>');
        b.get(0).awInit = !1;
        var c = b.data();
        c.widget = null;
        var a;
        f(e.current.selector).add(e.threeday.selector).each(function () {
            this.awInit && f(this).data("uid").substr(4) == b.data("uid").substr(4) && (a = f(this), a.data("locationkey", b.data("locationkey")), a.data("unit", b.data("unit")), -1 != a.data("uid").indexOf("awcc") ? (this.awType = "current", a.awType = "current") : (this.awType = "threeday", a.awType = "threeday"))
        });
        b.get(0).awInit = !0;
        f.getJSON(e[b.get(0).awType].url +
            "?callback=?", c, function (a) {
            b.html(a.html);
            x(f, b)
        });
        a && (c = a.data(), c.widget = null, f.getJSON(e[a.awType].url + "?callback=?", c, function (b) {
            a.html(b.html);
            x(f, a)
        }))
    }

    function J() {
        (function (b, c) {
            var a;
            b.throttle = a = function (a, e, m, g) {
                function l() {
                    function b() {
                        C = +new Date;
                        m.apply(n, u)
                    }

                    function l() {
                        v = c
                    }

                    var n = this, f = +new Date - C, u = arguments;
                    g && !v && b();
                    v && clearTimeout(v);
                    g === c && f > a ? b() : !0 !== e && (v = setTimeout(g ? l : b, g === c ? a - f : a))
                }

                var v, C = 0;
                "boolean" !== typeof e && (g = m, m = e, e = c);
                b.guid && (l.guid = m.guid = m.guid || b.guid++);
                return l
            };
            b.debounce = function (b, e, f) {
                return f === c ? a(b, e, !1) : a(b, f, !1 !== e)
            }
        })(f);
        (function (b) {
            b.autocomplete = function (c, a) {
                function f() {
                    t = {data: {}, length: 0}
                }

                function k() {
                    var a = document.getElementById(q.attr("id")).value;
                    d(a, "search")
                }

                function m(a) {
                    var c = b("li", p);
                    c && (r += a, 0 > r ? r = 0 : r >= c.size() && (r = c.size() - 1), c.removeClass("aw-ac-hover"), b(c[r]).addClass("aw-ac-hover"), c[r] && c[r].scrollIntoView && c[r].scrollIntoView(!1))
                }

                function g() {
                    w && clearTimeout(w);
                    p.hide()
                }

                function l(c, d, K) {
                    if (d) {
                        q.removeClass(a.loadingClass);
                        p.html("");
                        if ("search" == K) {
                            if (!d.length) {
                                p.html('<div class="aw-no-results">No Matching Results Found</div>');
                                p.show();
                                return
                            }
                            if (1 == d.length) {
                                c = p.closest(e.threeday.selector);
                                c.data("locationkey", d[0][1]);
                                c.data("useip", "false");
                                y(c);
                                return
                            }
                        } else if (!x || 0 == d.length)return g();
                        p.html(C(d));
                        p.find("a").each(function (a) {
                            b(this).click(function () {
                                var a = b(this).closest(e.threeday.selector);
                                a.data("locationkey", b(this).data("key"));
                                a.data("useip", "false");
                                y(a)
                            })
                        });
                        p.show()
                    } else g()
                }

                function v(a) {
                    if (!a)return null;
                    for (var b = [], c = 0; c < a.length; c++) {
                        var d = [], g = "";
                        a[c] && (d = a[c], g = [d.LocalizedName], "US" == d.Country.ID || "CA" == d.Country.ID || "AU" == d.Country.ID ? g.push(", " + d.AdministrativeArea.ID) : "GB" == d.Country.ID ? void 0 !== d.SupplementalAdminAreas && d.SupplementalAdminAreas.length ? g.push(", " + d.SupplementalAdminAreas[0].LocalizedName) : g.push(", " + d.AdministrativeArea.ID) : d.AdministrativeArea.LocalizedName != d.LocalizedName && g.push(", " + d.AdministrativeArea.LocalizedName), "US" == d.Country.ID ? g.push(" (USA)") : g.push(" (" +
                            d.Country.LocalizedName + ")"), g = g.join(""), d = [], d.push(g), d.push(a[c].Key), b.push(d))
                    }
                    return b
                }

                function C(b) {
                    var d = b.length;
                    0 < a.maxItemsToShow && a.maxItemsToShow < d && (d = a.maxItemsToShow);
                    for (var c = [], g = 0; g < d; g++)c.push('<li class="aw-location"><a data-key="' + b[g][1] + '">' + b[g][0] + "</a></li>");
                    return c ? "<ul>" + c.join("") + "</ul>" : ""
                }

                function d(d, c) {
                    a.matchCase || (d = d.toLowerCase());
                    a.cacheLength && n(d);
                    "string" == typeof a.url && 0 < a.url.length ? b.ajax({
                        type: "GET", url: h(d, c), cache: !0, dataType: "jsonp", success: function (a) {
                            a =
                                v(a);
                            u(d, a);
                            l(d, a, c)
                        }
                    }) : q.removeClass(a.loadingClass)
                }

                function h(b, d) {
                    "search" != d && (d = "autocomplete");
                    var c = a.url + d + "?q=" + encodeURI(b) + "&apiKey=" + a.api_key, g;
                    for (g in a.extraParams)c += "&" + g + "=" + encodeURI(a.extraParams[g]);
                    return c
                }

                function n(b) {
                    if (!b)return null;
                    if (t.data[b])return t.data[b];
                    if (a.matchSubset)for (var d = b.length - 1; d >= a.minChars; d--) {
                        var c = b.substr(0, d);
                        if (c = t.data[c]) {
                            for (var d = [], g = 0; g < c.length; g++) {
                                var e = c[g], l;
                                l = e[0];
                                var q = b;
                                a.matchCase || (l = l.toLowerCase());
                                l = l.indexOf(q);
                                l = -1 ==
                                l ? !1 : 0 == l || a.matchContains;
                                l && (d[d.length] = e)
                            }
                            return d
                        }
                    }
                    return null
                }

                function H(b, d) {
                    d && q.removeClass(a.loadingClass);
                    for (var c = d ? d.length : 0, g = null, l = 0; l < c; l++) {
                        var e = d[l];
                        if (e[0].toLowerCase() == b.toLowerCase()) {
                            g = document.createElement("li");
                            g.innerHTML = a.formatItem ? a.formatItem(e, l, c) : e[0];
                            g.selectValue = e[0];
                            var h = null;
                            if (1 < e.length)for (var h = [], f = 1; f < e.length; f++)h[h.length] = e[f];
                            g.extra = h
                        }
                    }
                    a.onFindValue && setTimeout(function () {
                        a.onFindValue(g)
                    }, 1)
                }

                function u(b, d) {
                    d && b && a.cacheLength && (!t.length ||
                    t.length > a.cacheLength ? (f(), t.length++) : t[b] || t.length++, t.data[b] = d)
                }

                var q = c.attr("autocomplete", "off");
                a.inputClass && q.addClass(a.inputClass);
                var p = b(".aw-autocomplete"), L = b(".aw-submit-button");
                c.autocompleter = this;
                var w = null, I = "", r = -1, t = {}, x = !1, D = null;
                f();
                if (null != a.data) {
                    var E = "", A = {}, F = [];
                    "string" != typeof a.url && (a.cacheLength = 1);
                    for (var B = 0; B < a.data.length; B++)F = "string" == typeof a.data[B] ? [a.data[B]] : a.data[B], 0 < F[0].length && (E = F[0].substring(0, 1).toLowerCase(), A[E] || (A[E] = []), A[E].push(F));
                    for (var z in A)a.cacheLength++, u(z, A[z])
                }
                L.click(function () {
                    k()
                });
                q.bind("keydown", function (c) {
                    D = c.keyCode;
                    switch (c.keyCode) {
                        case 38:
                            c.preventDefault();
                            m(-1);
                            break;
                        case 40:
                            c.preventDefault();
                            m(1);
                            break;
                        case 9:
                            c.preventDefault();
                            break;
                        case 13:
                            var g = b("li", p);
                            c.preventDefault();
                            if (b(g).hasClass("aw-ac-hover")) {
                                c = p.closest(e.threeday.selector);
                                c.data("locationkey", b(g[r]).find("a").data("key"));
                                c.data("useip", "false");
                                y(c);
                                break
                            } else k();
                            break;
                        default:
                            r = -1, w && clearTimeout(w), w = setTimeout(function () {
                                if (46 ==
                                    D || 8 < D && 32 > D)p.hide(); else {
                                    var b = document.getElementById(q.attr("id")).value;
                                    b != I && (I = b, b.length >= a.minChars ? (q.addClass(a.loadingClass), d(b, "autocomplete")) : (q.removeClass(a.loadingClass), p.hide()))
                                }
                            }, a.delay)
                    }
                }).bind("focus", function () {
                    x = !0
                }).bind("blur", function () {
                    x = !1;
                    w && clearTimeout(w);
                    w = setTimeout(g, 200)
                });
                g();
                this.flushCache = function () {
                    f()
                };
                this.setExtraParams = function (b) {
                    a.extraParams = b
                };
                this.findValue = function () {
                    var d = document.getElementById(q.attr("id")).value;
                    a.matchCase || (d = d.toLowerCase());
                    var c = a.cacheLength ? n(d) : null;
                    c ? H(d, c) : "string" == typeof a.url && 0 < a.url.length ? b.ajax({
                        type: "GET",
                        url: h(d, "autocomplete"),
                        cache: !0,
                        dataType: "jsonp",
                        success: function (a) {
                            a = v(a);
                            u(d, a);
                            l(d, a, "autocomplete")
                        }
                    }) : H(d, null)
                }
            };
            b.fn.autocomplete = function (c, a, e) {
                a = a || {};
                a.url = c;
                a.data = "object" == typeof e && e.constructor == Array ? e : null;
                a.inputClass = a.inputClass || "ac_input";
                a.resultsClass = a.resultsClass || "ac_results";
                a.lineSeparator = a.lineSeparator || "\n";
                a.cellSeparator = a.cellSeparator || "|";
                a.minChars = a.minChars ||
                    1;
                a.delay = a.delay || 400;
                a.matchCase = a.matchCase || 0;
                a.matchSubset = a.matchSubset || 1;
                a.matchContains = a.matchContains || 0;
                a.cacheLength = a.cacheLength || 1;
                a.mustMatch = a.mustMatch || 0;
                a.extraParams = a.extraParams || {};
                a.loadingClass = a.loadingClass || "ac_loading";
                a.selectFirst = a.selectFirst || !1;
                a.selectOnly = a.selectOnly || !1;
                a.maxItemsToShow = a.maxItemsToShow || -1;
                a.autoFill = a.autoFill || !1;
                a.width = parseInt(a.width, 10) || 0;
                new b.autocomplete(b(this), a);
                return b(this)
            };
            b.fn.autocompleteArray = function (b, a) {
                return this.autocomplete(null,
                    a, b)
            };
            b.fn.indexOf = function (b) {
                for (var a = 0; a < this.length; a++)if (this[a] == b)return a;
                return -1
            }
        })(f)
    }

    function x(b, c) {
        if ("undefined" != typeof awxOapIE8)for (var a = [{
            o: ".aw-widget-36hour .tbg-su, .aw-widget-36hour .bg-su, .aw-widget-current .tbg-su, .aw-widget-current .bg-su",
            c: "background",
            s: "#6cb2fc"
        }, {
            o: ".aw-widget-36hour .tbg-c, .aw-widget-36hour .bg-c, .aw-widget-current .tbg-c, .aw-widget-current .bg-c",
            c: "background",
            s: "#d0dae8"
        }, {
            o: ".aw-widget-36hour .tbg-f, .aw-widget-36hour .bg-f, .aw-widget-current .tbg-f, .aw-widget-current .bg-f",
            c: "background", s: "#dee4ef"
        }, {
            o: ".aw-widget-36hour .tbg-t, .aw-widget-36hour .bg-t, .aw-widget-current .tbg-t, .aw-widget-current .bg-t",
            c: "background",
            s: "#90bbe5"
        }, {
            o: ".aw-widget-36hour .tbg-r, .aw-widget-36hour .bg-r, .aw-widget-current .tbg-r, .aw-widget-current .bg-r",
            c: "background",
            s: "#7789b2"
        }, {
            o: ".aw-widget-36hour .tbg-cl, .aw-widget-36hour .bg-cl, .aw-widget-current .tbg-cl, .aw-widget-current .bg-cl",
            c: "background",
            s: "#141414"
        }, {
            o: ".aw-widget-36hour .tbg-s, .aw-widget-36hour .bg-s, .aw-widget-current .tbg-s, .aw-widget-current .bg-s",
            c: "background", s: "#141414"
        }, {
            o: "div.aw-widget-36hour-inner div.aw-widget-content div.aw-header div.aw-search button.aw-submit-button",
            c: "border",
            s: "none !important"
        }], f = 0; f < a.length; f++)b(a[f].o).css(a[f].c, a[f].s);
        window.accuweather = window.accuweather || {};
        window.accuweather.widgets = window.accuweather.widgets || {
                register: function (a, c) {
                    var e = b(a);
                    e.length && (window.accuweather.widgets[e.get(0)] = c)
                }, get: function (a) {
                    a = b(a);
                    if (a.length)return window.accuweather.widgets[a.get(0)]
                }
            };
        window.accuweather.widgets.Current =
            function (a) {
                var c = this;
                this.el = b(a);
                this.breakpoints = [86, 106, 115, 216, 280, 350, 479];
                this.inner = this.el.find(".aw-widget-current-inner");
                this.inow = this.el.find(".aw-current-weather .aw-icon");
                this.clickUrl = this.el.find(".aw-widget-current-inner a").first();
                -1 == this.clickUrl.attr("href").toString().toLowerCase().indexOf("accuweather.com") && this.clickUrl.removeAttr("target");
                this.reload = function () {
                    y(a)
                };
                this.resize();
                this.toggle = this.el.find(".aw-toggle");
                this.toggle.click(function () {
                    c.inner.addClass("with-get");
                    return !1
                });
                window.accuweather.widgets.register(this.el.get(0), this)
            };
        window.accuweather.widgets.Current.prototype.resize = function () {
            for (var a = this.el.outerWidth(), b = [], c = !1, f = 0; f < this.breakpoints.length; f++) {
                var d = this.breakpoints[f];
                a < d && (b.push("lt-" + d), c = !0);
                a == d && b.push("eq-" + d)
            }
            c || (b = ["gte-" + this.breakpoints[this.breakpoints.length - 1]]);
            this.el.attr("class", e.current.baseClass + " " + b.join(" "));
            c = this.inow.data("icon");
            f = "l";
            216 > a && (f = "m");
            115 > a && (f = "t");
            this.inow.attr("class", "aw-icon aw-icon-" +
                c + "-" + f);
            this.el.find(".debug .width").html(a);
            this.el.find(".debug .bpclasses").html(b.join(" "));
            this.el.hide();
            this.el.show()
        };
        window.accuweather.widgets.ThirtySixHour = function (a) {
            function e(a) {
                return awxWidgetInfo[d].wxInfo.ut ? "1" == awxWidgetInfo[d].wxInfo.ut ? 9 * a / 5 + 32 : a : a
            }

            function f(a, b) {
                for (var c in b)switch (c) {
                    case "hi":
                    case "lo":
                        awxWidgetInfo[d].keyValuePairs[a + c] = 5 * Math.round(e(parseInt(b[c])) / 5);
                        break;
                    case "wx":
                        awxWidgetInfo[d].keyValuePairs[a + c] = parseInt(b[c]);
                        break;
                    default:
                        awxWidgetInfo[d].keyValuePairs[a +
                        c] = b[c]
                }
            }

            this.el = b(a);
            this.breakpoints = [320, 480, 540, 624, 632, 780, 860, 950];
            this.i36 = this.el.find(".aw-36-hours .aw-icon");
            this.inow = this.el.find(".aw-current-weather .aw-icon");
            this.search = this.el.find(".aw-search");
            this.reload = function () {
                y(a)
            };
            this.moveTimeBubble = function () {
                setTimeout(function () {
                    var a = b(".aw-widget-36hour").find(".aw-time-bubble"), c = a.find("span"), d = a.find("i"), g = a.find("b"), a = a.attr("data-minutes") / 60, e = b(".aw-six-hours-inner ul li:first").width(), g = g.width(), a = Math.max(5, e * a), a =
                        Math.min(g - 7, a), g = a + 6;
                    c.css("left", a + "px");
                    d.css("left", g + "px")
                }, 100)
            };
            c.find(".aw-temp-unit").find("li a").each(function (a) {
                b(this).click(function () {
                    b(this).closest("li").hasClass("current") || (b(this).hasClass("aw-unit-f") ? c.data("unit", "f") : c.data("unit", "c"), c.data("useip", "false"), y(c))
                })
            });
            var m = this.el.find(".aw-autocomplete");
            this.el.find(".aw-search input").bind({
                focus: function () {
                    b(this).closest(".aw-search").addClass("aw-focus");
                    b(this).val("");
                    var a = b(this).position().top + b(this).height() +
                        16;
                    b(".aw-emergency-header").length && (a += b(".aw-emergency-header").height() + 10);
                    m.css({width: b(this).width(), top: a, left: b(this).position().left})
                }, blur: function () {
                    b(this).closest(".aw-search").removeClass("aw-focus")
                }
            }).autocomplete("http://api.accuweather.com/locations/v1/cities/", {
                el: this.el.get(0),
                api_key: "651aa630aeac48e8b15f9072cfa524bc",
                autoFill: 1,
                inputClass: "aw-input",
                resultsClass: "aw-autocomplete",
                minChars: 1,
                cacheLength: 0,
                matchSubset: 1,
                matchCase: 0,
                matchContains: 1,
                maxItemsToShow: 8,
                mustMatch: 0
            });
            var d = this.el.data("uid");
            awxWidgetInfo[d].keyValuePairs = {
                zip: awxWidgetInfo[d].userInfo.zip,
                city: awxWidgetInfo[d].userInfo.city,
                state: awxWidgetInfo[d].userInfo.state,
                country: awxWidgetInfo[d].userInfo.country,
                partner: awxWidgetInfo[d].userInfo.sessionPartner,
                metro: awxWidgetInfo[d].userInfo.metro,
                dma: awxWidgetInfo[d].userInfo.dma,
                lang: awxWidgetInfo[d].userInfo.lang
            };
            if (awxWidgetInfo[d].wxInfo)for (var h in awxWidgetInfo[d].wxInfo)if ("ix" != h)if ("cu" == h)for (var n in awxWidgetInfo[d].wxInfo[h])awxWidgetInfo[d].wxInfo[h][n] &&
            f("cu", awxWidgetInfo[d].wxInfo[h]); else if ("fc" == h)for (var k = 0; k < awxWidgetInfo[d].wxInfo[h].length; k++)for (n in awxWidgetInfo[d].wxInfo[h][k])awxWidgetInfo[d].wxInfo[h][k][n] && f("fc" + (k + 1), awxWidgetInfo[d].wxInfo[h][k]); else awxWidgetInfo[d].wxInfo[h] && (awxWidgetInfo[d].keyValuePairs[h] = awxWidgetInfo[d].wxInfo[h].toString());
            h = [];
            h.push('<iframe frameborder="0" height="250" width="300" marginheight="0" marginwidth="0" scrolling="no" src="');
            h.push("http://ad.doubleclick.net/N6581/adi/accuwx.products/oap/");
            h.push(document.domain.split(".").join("-") + ";");
            n = [];
            for (var u in awxWidgetInfo[d].keyValuePairs)awxWidgetInfo[d].keyValuePairs[u] && (n.push(u), n.push("="), n.push(awxWidgetInfo[d].keyValuePairs[u]), n.push(";"));
            h.push(n.join("").toLowerCase().split(" ").join("_"));
            h.push("sz=300x250;ord=" + 1E18 * Math.random());
            h.push('"></iframe>');
            b("#aw-ad-container").html(h.join(""));
            this.resize();
            window.accuweather.widgets.register(this.el.get(0), this)
        };
        window.accuweather.widgets.ThirtySixHour.prototype.resize =
            function () {
                for (var a = this.el.outerWidth(), c = [], f = !1, k = 0; k < this.breakpoints.length; k++) {
                    var d = this.breakpoints[k];
                    a < d && (c.push("lt-" + d), f = !0);
                    a == d && c.push("eq-" + d)
                }
                f || (c = ["gte-" + this.breakpoints[this.breakpoints.length - 1]]);
                this.el.attr("class", e.threeday.baseClass + " " + c.join(" "));
                this.i36.each(function () {
                    var c = b(this), d = c.data("icon"), e = "m";
                    950 > a && (e = "s");
                    c.attr("class", "aw-icon aw-icon-" + d + "-" + e)
                });
                c = this.inow.data("icon");
                this.inow.attr("class", "aw-icon aw-icon-" + c + "-" + (480 > a ? "m" : "xl"));
                this.moveTimeBubble();
                $table = b(".vid-headline-box");
                $table.find("td.logo").css("width", $table.outerHeight(!0) + "px")
            };
        var k = "current" == c.get(0).awType ? new window.accuweather.widgets.Current(c) : new window.accuweather.widgets.ThirtySixHour(c);
        b(window).resize(b.throttle(200, function () {
            k.resize()
        }));
        "current" == c.get(0).awType ? window.accuweatherWidgetCurrentReady && window.accuweatherWidgetCurrentReady(c.get(0), k) : window.accuweatherWidgetThirtySixHourReady && window.accuweatherWidgetThirtySixHourReady(c.get(0), k);
        var m = c.data("uid") ?
            c.data("uid") : "aw" + (new Date).getTime(), f = c.data("lifestyle") ? "/" + c.data("lifestyle") : "", a = c.get(0).awType + f;
        "" != f && (f = lifestyleIndexToDFP[c.data("lifestyle")], c.append('<img src="http://pubads.g.doubleclick.net/activity;dc_iu=/6581/DFPAudiencePixel;ord=' + 1E13 * (Math.random() + "") + ";dc_seg=" + f + '?" width="1" height="1" border="0" />'));
        try {
            (function (a, b, c, e, d, f, k) {
                a.GoogleAnalyticsObject = d;
                a[d] = a[d] || function () {
                        (a[d].q = a[d].q || []).push(arguments)
                    };
                a[d].l = 1 * new Date;
                f = b.createElement(c);
                k = b.getElementsByTagName(c)[0];
                f.async = 1;
                f.src = e;
                k.parentNode.insertBefore(f, k)
            })(window, document, "script", "//www.google-analytics.com/analytics.js", "ga_awxoap"), ga_awxoap("create", "UA-31945348-1", "auto", {name: "awxoapTracker"}), ga_awxoap("awxoapTracker.set", "page", "/oap-weather-widgets/" + a), ga_awxoap("awxoapTracker.send", "pageview", {
                dimension1: "OAP_Weather_Widgets",
                dimension2: document.domain ? document.domain.split(".").join("-") : "OAP_NoDomain",
                dimension3: awxWidgetInfo[m].userInfo.partner,
                dimension4: "OAP_Widget_" + a,
                dimension5: awxWidgetInfo[m].wxInfo.mcpct
            })
        } catch (g) {
        }
        b("#" +
            m).find("a").each(function () {
            if (b(this).attr("id") && "#" != b(this).attr("href")) {
                var a = b(this).attr("href");
                b(this).attr("id");
                var e = [];
                e.push("utm_source=" + (document.domain ? document.domain.split(".").join("-") : "OAP_NoDomain"));
                e.push("utm_medium=oap_weather_widget");
                e.push("utm_term=" + b(this).attr("id"));
                e.push("utm_content=" + awxWidgetInfo[m].userInfo.partner);
                e.push("utm_campaign=" + c.get(0).awType);
                -1 != a.indexOf("?") ? b(this).attr("href", a + "&" + e.join("&")) : b(this).attr("href", a + "?" + e.join("&"))
            }
        })
    }

    document && document.URL && document.URL.indexOf("/adc/");
    var f;
    if (void 0 !== window.jQuery && window.jQuery.fn.jquery.match(/^1\.9/))f = window.jQuery, G(); else {
        var k = document.createElement("script");
        k.setAttribute("type", "text/javascript");
        k.setAttribute("src", "http://vortex.accuweather.com/adc2010/oap/javascript/jquery-1.9.1.min.js");
        k.readyState ? k.onreadystatechange = function () {
            "complete" != this.readyState && "loaded" != this.readyState || z()
        } : k.onload = z;
        (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(k)
    }
    var e =
    {current: {}, threeday: {}};
    "undefined" != typeof oap3dayConfigDev ? (e.css = oap3dayConfigDev.css, e.threeday.selector = oap3dayConfigDev.threedaySelector, e.threeday.baseClass = oap3dayConfigDev.threedayBaseClass, e.threeday.url = oap3dayConfigDev.threedayUrl, e.current.selector = oap3dayConfigDev.currentSelector, e.current.baseClass = oap3dayConfigDev.currentBaseClass, e.current.url = oap3dayConfigDev.currentUrl) : (e.css = ["http://vortex.accuweather.com/adc2010/oap/stylesheets/widgets-20151019.css"], e.threeday.selector = ".aw-widget-36hour",
        e.threeday.baseClass = "aw-widget-36hour", e.threeday.url = "http://www.accuweather.com/ajax-service/oap/3day", e.current.selector = ".aw-widget-current", e.current.baseClass = "aw-widget-current", e.current.url = "http://www.accuweather.com/ajax-service/oap/current")
})();
