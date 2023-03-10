/*
 * HC Off-canvas Nav
 * ===================
 * Version: 4.2.5
 * Author: Some Web Media
 * Author URL: https://github.com/somewebmedia/
 * Plugin URL: https://github.com/somewebmedia/hc-offcanvas-nav
 * Description: jQuery plugin for creating off-canvas multi-level navigations
 * License: MIT
 */
"use strict";
function _typeof(e) {
    return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
        return typeof e
    } : function(e) {
        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
    })(e)
}
!function(le, se) {
    var re = se.document,
        de = le(se),
        ve = le(re.getElementsByTagName("html")[0]),
        pe = le(re),
        fe = !1;
    try {
        var e = Object.defineProperty({}, "passive", {
            get: function() {
                fe = {
                    passive: !1
                }
            }
        });
        se.addEventListener("testPassive", null, e),
        se.removeEventListener("testPassive", null, e)
    } catch (e) {}
    var i,
        ue = (/iPad|iPhone|iPod/.test(navigator.userAgent) || !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform)) && !se.MSStream,
        he = "ontouchstart" in se || navigator.maxTouchPoints || se.DocumentTouch && re instanceof DocumentTouch,
        me = function(e) {
            return !isNaN(parseFloat(e)) && isFinite(e)
        },
        ge = function(e) {
            return "auto" === e ? e : me(e) ? e + "px" : e
        },
        be = function(e) {
            return e.stopPropagation()
        },
        ye = function(e) {
            return e.preventDefault()
        },
        xe = function(n) {
            return function(e) {
                e.preventDefault(),
                e.stopPropagation(),
                "function" == typeof n && n()
            }
        },
        ke = function(e) {
            var n = ["Webkit", "Moz", "Ms", "O"],
                t = (re.body || re.documentElement).style,
                a = e.charAt(0).toUpperCase() + e.slice(1);
            if (void 0 !== t[e])
                return e;
            for (var o = 0; o < n.length; o++)
                if (void 0 !== t[n[o] + a])
                    return n[o] + a;
            return !1
        },
        Ce = function(e, n, t) {
            var a = t.children(),
                o = a.length,
                i = -1 < n ? Math.max(0, Math.min(n - 1, o)) : Math.max(0, Math.min(o + n + 1, o));
            0 === i ? t.prepend(e) : a.eq(i - 1).after(e)
        },
        Oe = function(e) {
            return -1 !== ["left", "right"].indexOf(e) ? "x" : "y"
        },
        we = (i = ke("transform"), function(e, n, t) {
            if (i)
                if (!1 === n)
                    e.css(i, "");
                else if ("x" === Oe(t)) {
                    var a = "left" === t ? n : 0 - n;
                    e.css(i, "translate3d(".concat(a, "px,0,0)"))
                } else {
                    var o = "top" === t ? n : 0 - n;
                    e.css(i, "translate3d(0,".concat(o, "px,0)"))
                }
            else
                e.css(t, n)
        }),
        n = function(e, n, t) {
            console.warn("%cHC Off-canvas Nav:%c " + t + "%c '" + e + "'%c is now deprecated and will be removed in the future. Use%c '" + n + "'%c option instead. See details about plugin usage at https://github.com/somewebmedia/hc-offcanvas-nav.", "color: #fa253b", "color: default", "color: #5595c6", "color: default", "color: #5595c6", "color: default")
        },
        Te = 0;
    le.fn.extend({
        hcOffcanvasNav: function() {
            var e = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : {};
            if (!this.length)
                return this;
            var ne = this,
                te = le(re.body);
            void 0 !== e.maxWidth && (n("maxWidth", "disableAt", "option"), e.disableAt = e.maxWidth);
            var ae = le.extend({}, {
                    width: 280,
                    height: "auto",
                    disableAt: !1,
                    pushContent: !1,
                    swipeGestures: !0,
                    expanded: !1,
                    position: "left",
                    levelOpen: "overlap",
                    levelSpacing: 40,
                    levelTitles: !0,
                    closeOpenLevels: !0,
                    closeActiveLevel: !1,
                    navTitle: null,
                    navClass: "",
                    disableBody: !0,
                    closeOnClick: !0,
                    customToggle: null,
                    bodyInsert: "prepend",
                    keepClasses: !0,
                    removeOriginalNav: !1,
                    rtl: !1,
                    insertClose: !0,
                    insertBack: !0,
                    levelTitleAsBack: !0,
                    labelClose: "Close",
                    labelBack: "Back"
                }, e),
                oe = [],
                ie = "nav-open",
                ce = function(e) {
                    if (!oe.length)
                        return !1;
                    var n = !1;
                    "string" == typeof e && (e = [e]);
                    for (var t = e.length, a = 0; a < t; a++)
                        -1 !== oe.indexOf(e[a]) && (n = !0);
                    return n
                };
            return this.each(function() {
                var n = le(this);
                if (n.find("ul").addBack("ul").length) {
                    var e,
                        o,
                        i,
                        c,
                        a,
                        t,
                        r,
                        l,
                        y = "hc-nav-".concat(++Te),
                        s = (e = "hc-offcanvas-".concat(Te, "-style"), o = le('<style id="'.concat(e, '">')).appendTo(le("head")), i = {}, c = {}, a = function(e) {
                            return ";" !== e.substr(-1) && (e += ";" !== e.substr(-1) ? ";" : ""), e
                        }, {
                            reset: function() {
                                i = {},
                                c = {}
                            },
                            add: function(e, n, t) {
                                e = e.trim(),
                                n = n.trim(),
                                t ? (t = t.trim(), c[t] = c[t] || {}, c[t][e] = a(n)) : i[e] = a(n)
                            },
                            remove: function(e, n) {
                                e = e.trim(),
                                n ? (n = n.trim(), void 0 !== c[n][e] && delete c[n][e]) : void 0 !== i[e] && delete i[e]
                            },
                            insert: function() {
                                var e = "";
                                for (var n in c) {
                                    for (var t in e += "@media screen and (".concat(n, ") {\n"), c[n])
                                        e += "".concat(t, " { ").concat(c[n][t], " }\n");
                                    e += "}\n"
                                }
                                for (var a in i)
                                    e += "".concat(a, " { ").concat(i[a], " }\n");
                                o.html(e)
                            }
                        }),
                        d = "keydown.hc-offcanvas-nav",
                        v = le('<nav role="navigation">').on("click", be),
                        p = le('<div class="nav-container">').appendTo(v),
                        f = null,
                        u = null,
                        h = {},
                        m = !1,
                        g = !1,
                        b = null,
                        x = 0,
                        k = 0,
                        C = 0,
                        O = null,
                        w = {},
                        T = [],
                        M = !1,
                        S = [],
                        E = null,
                        L = null,
                        B = !1,
                        A = !1;
                    n.addClass("hc-nav-original ".concat(y)),
                    ae.customToggle ? f = le(ae.customToggle).addClass("hc-nav-trigger ".concat(y)).on("click", R) : (f = le('<a href="#" aria-label="Open Menu" class="hc-nav-trigger '.concat(y, '"><span></span></a>')).on("click", R), n.after(f)),
                    f.attr("role", "button").attr("aria-controls", y).on("keydown", function(e) {
                        "Enter" !== e.key && 13 !== e.keyCode || setTimeout(function() {
                            N(0, 0)
                        }, 0)
                    });
                    var N = function(e, n, t) {
                            if ("number" == typeof n && ("number" == typeof e || S.length)) {
                                var a = '[tabindex=0], a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select',
                                    o = p.find(".nav-wrapper").filter("[data-level=".concat(n, "]")).filter(function() {
                                        return "number" != typeof t || le(this).is("[data-index=".concat(t, "]"))
                                    }).children(".nav-content").children("ul").children("li").children(":not(.nav-wrapper)").find(a).addBack(a).filter(":not([tabindex=-1])");
                                if (o.length) {
                                    var i = o.first(),
                                        c = o.last();
                                    "number" == typeof e ? o.eq(e).focus() : (S[S.length - 1].focus(), S.pop()),
                                    pe.off(d),
                                    pe.on(d, function(e) {
                                        ("Tab" === e.key || 9 === e.keyCode) && (e.shiftKey ? re.activeElement === i[0] && (e.preventDefault(), c.focus()) : re.activeElement === c[0] && (e.preventDefault(), i.focus()))
                                    })
                                }
                            }
                        },
                        P = function() {
                            pe.off(d),
                            setTimeout(function() {
                                f.focus()
                            }, r)
                        },
                        D = function() {
                            var e;
                            p.css("transition", "none"),
                            k = p.outerWidth(),
                            C = p.outerHeight(),
                            s.add(".hc-offcanvas-nav.".concat(y, ".nav-position-left .nav-container"), "transform: translate3d(-".concat(k, "px, 0, 0)")),
                            s.add(".hc-offcanvas-nav.".concat(y, ".nav-position-right .nav-container"), "transform: translate3d(".concat(k, "px, 0, 0)")),
                            s.add(".hc-offcanvas-nav.".concat(y, ".nav-position-top .nav-container"), "transform: translate3d(0, -".concat(C, "px, 0)")),
                            s.add(".hc-offcanvas-nav.".concat(y, ".nav-position-bottom .nav-container"), "transform: translate3d(0, ".concat(C, "px, 0)")),
                            s.insert(),
                            p.css("transition", ""),
                            t = p.css("transition-property").split(",")[0],
                            e = p.css("transition-duration").split(",")[0],
                            r = parseFloat(e) * (/\ds$/.test(e) ? 1e3 : 1),
                            l = p.css("transition-timing-function").split(",")[0],
                            ae.pushContent && u && t && s.add(function e(n) {
                                return "string" == typeof n ? n : n.attr("id") ? "#" + n.attr("id") : n.attr("class") ? n.prop("tagName").toLowerCase() + "." + n.attr("class").replace(/\s+/g, ".") : e(n.parent()) + " " + n.prop("tagName").toLowerCase()
                            }(ae.pushContent), "transition: ".concat(t, " ").concat(r, "ms ").concat(l)),
                            s.insert()
                        },
                        j = function(e) {
                            var n = f.css("display"),
                                t = !!ae.disableAt && "max-width: ".concat(ae.disableAt - 1, "px"),
                                a = ge(ae.width),
                                o = ge(ae.height);
                            -1 !== a.indexOf("px") && (k = parseInt(a)),
                            -1 !== o.indexOf("px") && (C = parseInt(o)),
                            ce(["disableAt", "position"]) && s.reset(),
                            s.add(".hc-offcanvas-nav.".concat(y), "display: block", t),
                            s.add(".hc-nav-original.".concat(y), "display: none", t),
                            s.add(".hc-nav-trigger.".concat(y), "display: ".concat(n && "none" !== n ? n : "block"), t),
                            -1 !== ["left", "right"].indexOf(ae.position) ? s.add(".hc-offcanvas-nav.".concat(y, " .nav-container"), "width: ".concat(a)) : s.add(".hc-offcanvas-nav.".concat(y, " .nav-container"), "height: ".concat(o)),
                            s.add(".hc-offcanvas-nav.".concat(y, ".nav-position-left .nav-container"), "transform: translate3d(-".concat("auto" === a ? "100%" : a, ", 0, 0);")),
                            s.add(".hc-offcanvas-nav.".concat(y, ".nav-position-right .nav-container"), "transform: translate3d(".concat("auto" === a ? "100%" : a, ", 0, 0);")),
                            s.add(".hc-offcanvas-nav.".concat(y, ".nav-position-top .nav-container"), "transform: translate3d(0, -".concat("auto" === o ? "100%" : o, ", 0);")),
                            s.add(".hc-offcanvas-nav.".concat(y, ".nav-position-bottom .nav-container"), "transform: translate3d(0, ".concat("auto" === o ? "100%" : o, ", 0);")),
                            s.add(".hc-offcanvas-nav.".concat(y, ".nav-levels-overlap.nav-position-left li.level-open > .nav-wrapper"), "transform: translate3d(-".concat(ae.levelSpacing, "px,0,0)"), t),
                            s.add(".hc-offcanvas-nav.".concat(y, ".nav-levels-overlap.nav-position-right li.level-open > .nav-wrapper"), "transform: translate3d(".concat(ae.levelSpacing, "px,0,0)"), t),
                            s.add(".hc-offcanvas-nav.".concat(y, ".nav-levels-overlap.nav-position-top li.level-open > .nav-wrapper"), "transform: translate3d(0,-".concat(ae.levelSpacing, "px,0)"), t),
                            s.add(".hc-offcanvas-nav.".concat(y, ".nav-levels-overlap.nav-position-bottom li.level-open > .nav-wrapper"), "transform: translate3d(0,".concat(ae.levelSpacing, "px,0)"), t),
                            s.insert(),
                            (!e || e && ce("pushContent")) && ("string" == typeof ae.pushContent ? (u = le(ae.pushContent)).length || (u = null) : u = ae.pushContent instanceof jQuery ? ae.pushContent : null),
                            p.css("transition", "none");
                            var i = v.hasClass(ie),
                                c = ["hc-offcanvas-nav", ae.navClass || "", y, ae.navClass || "", "nav-levels-" + ae.levelOpen || "none", "nav-position-" + ae.position, ae.disableBody ? "disable-body" : "", ue ? "is-ios" : "", he ? "touch-device" : "", i ? ie : "", ae.rtl ? "rtl" : ""].join(" ");
                            v.off("click").attr("class", "").attr("aria-hidden", !0).attr("aria-labelledby", y).addClass(c),
                            ae.disableBody && v.on("click", J),
                            e ? D() : setTimeout(D, 0)
                        },
                        H = function() {
                            var e;
                            h = function l(e, n) {
                                var t = [];
                                return e.each(function() {
                                    var e = le(this),
                                        c = {
                                            id: n,
                                            classes: e.attr("class") || null,
                                            items: []
                                        };
                                    void 0 !== e.attr("data-nav-active") && (b = n, e.removeAttr("data-nav-active")),
                                    e.children("li").each(function() {
                                        var e = le(this),
                                            n = void 0 !== e.attr("data-nav-custom-content"),
                                            t = n ? e.children() : e.children().filter(function() {
                                                var e = le(this);
                                                return e.is(":not(ul)") && !e.find("ul").length
                                            }).add(e.contents().filter(function() {
                                                return 3 === this.nodeType && this.nodeValue.trim()
                                            })),
                                            a = n ? le() : e.find("ul"),
                                            o = a.first().add(a.first().siblings("ul")),
                                            i = null;
                                        o.length && (e.data("hc-uniqid") ? i = e.data("hc-uniqid") : (i = Math.random().toString(36).substr(2), e.data("hc-uniqid", i))),
                                        void 0 !== e.attr("data-nav-active") && (b = i, e.removeAttr("data-nav-active")),
                                        c.items.push({
                                            id: i,
                                            classes: e.attr("class") || null,
                                            content: t,
                                            custom: n,
                                            subnav: o.length ? l(o, i) : [],
                                            highlight: void 0 !== e.attr("data-nav-highlight")
                                        })
                                    }),
                                    t.push(c)
                                }), t
                            }((e = n.find("ul").addBack("ul")).first().add(e.first().siblings("ul")), null)
                        },
                        q = function(e) {
                            e && (p.empty(), w = {}),
                            function h(e, n, m, g, t, a) {
                                var b = le('<div class="nav-wrapper nav-wrapper-'.concat(m, '" data-level="').concat(m, '" data-index="').concat(t || 0, '">')).appendTo(n).on("click", be),
                                    o = le('<div class="nav-content">').appendTo(b);
                                if (g && o.prepend("<h2>".concat(g, "</h2>")), le.each(e, function(e, n) {
                                    var u = le('<ul role="menu" aria-level="'.concat(m + 1, '">')).appendTo(o);
                                    ae.keepClasses && u.addClass(n.classes),
                                    0 === e && g && u.attr("aria-label", g),
                                    n.id && u.attr("aria-labelledby", "menu-" + n.id),
                                    le.each(n.items, function(e, n) {
                                        var t = n.content;
                                        if (n.custom) {
                                            var a = le('<li class="custom-content">').append(le('<div class="nav-item nav-item-custom">').append(t.clone(!0, !0)));
                                            return ae.keepClasses && a.addClass(n.classes), void u.append(a)
                                        }
                                        var o = t.find("a").addBack("a"),
                                            i = o.length ? o.clone(!1, !0).addClass("nav-item") : le("<".concat(n.subnav.length ? 'a href="#"' : "span", ' class="nav-item">')).append(t.clone(!0, !0)).on("click", be);
                                        i.is("a") && i.attr("tabindex", "0").attr("role", "menuitem"),
                                        o.length && i.on("click", function(e) {
                                            e.stopPropagation(),
                                            (le._data(o[0], "events") || {}).click && o[0].click()
                                        }),
                                        "#" === i.attr("href") && i.on("click", function(e) {
                                            e.preventDefault()
                                        }),
                                        ae.closeOnClick && (Y() ? i.filter("a").filter('[data-nav-close!="false"]:not([disabled])').filter(function() {
                                            var e = le(this);
                                            return !n.subnav.length || e.attr("href") && "#" !== e.attr("href").charAt(0)
                                        }).on("click", J) : i.filter("a").filter('[data-nav-close!="false"]:not([disabled])').on("click", J));
                                        var c = le("<li>").append(i).appendTo(u);
                                        if (ae.keepClasses && c.addClass(n.classes), n.highlight && c.addClass("nav-highlight"), i.wrap('<div class="nav-item-wrapper">'), ae.levelSpacing && ("expand" === ae.levelOpen || !1 === ae.levelOpen || "none" === ae.levelOpen)) {
                                            var l = ae.levelSpacing * m;
                                            l && u.css("text-indent", "".concat(l, "px"))
                                        }
                                        if (n.subnav.length) {
                                            var s = m + 1,
                                                r = n.id,
                                                d = "";
                                            if (w[s] || (w[s] = 0), c.addClass("nav-parent"), Y()) {
                                                var v = w[s],
                                                    p = le('<input type="checkbox" id="'.concat(y, "-").concat(s, "-").concat(v, '" class="hc-chk" tabindex="-1">')).attr("data-level", s).attr("data-index", v).val(r).on("click", be).on("change", U).prependTo(c),
                                                    f = function(e) {
                                                        e.on("click", function() {
                                                            p.prop("checked", !p.prop("checked")).trigger("change")
                                                        }).on("keydown", function(e) {
                                                            "Enter" !== e.key && 13 !== e.keyCode || (M = !0, S.push(le(this)))
                                                        }).attr("aria-controls", "menu-" + r).attr("aria-haspopup", "overlap" === ae.levelOpen).attr("aria-expanded", !1)
                                                    };
                                                -1 !== T.indexOf(r) && (b.addClass("sub-level-open").on("click", function() {
                                                    return ee(s, v)
                                                }), c.addClass("level-open"), p.prop("checked", !0)),
                                                d = !0 === ae.levelTitles ? t.text().trim() : "",
                                                i.attr("href") && "#" !== i.attr("href") ? f(le('<a href="#" class="nav-next" aria-label="'.concat(d, ' Submenu" role="menuitem" tabindex="0"><span>')).on("click", xe()).insertAfter(i)) : (le('<span class="nav-next"><span>').appendTo(i), f(i))
                                            } else
                                                i.attr("aria-expanded", !0);
                                            w[s]++,
                                            h(n.subnav, c, s, d, w[s] - 1, g)
                                        }
                                    })
                                }), m && void 0 !== t && !1 !== ae.insertBack && "overlap" === ae.levelOpen) {
                                    var i = o.children("ul"),
                                        c = ae.levelTitleAsBack && a || ae.labelBack || "",
                                        l = le('<li class="nav-back"><a href="#" role="menuitem" tabindex="0">'.concat(c, "<span></span></a></li>")),
                                        s = function() {
                                            return ee(m, t)
                                        };
                                    l.children("a").on("click", xe(s)).on("keydown", function(e) {
                                        "Enter" !== e.key && 13 !== e.keyCode || (M = !0)
                                    }).wrap('<div class="nav-item-wrapper">'),
                                    !0 === ae.insertBack ? i.first().prepend(l) : me(ae.insertBack) && Ce(l, ae.insertBack, i)
                                }
                                if (0 === m && !1 !== ae.insertClose) {
                                    var r = o.children("ul"),
                                        d = le('<li class="nav-close"><a href="#" role="menuitem" tabindex="0">'.concat(ae.labelClose || "", "<span></span></a></li>"));
                                    d.children("a").on("click", xe(J)).on("keydown", function(e) {
                                        "Enter" !== e.key && 13 !== e.keyCode || P()
                                    }).wrap('<div class="nav-item-wrapper">'),
                                    !0 === ae.insertClose ? r.first().prepend(d) : me(ae.insertClose) && Ce(d, ae.insertClose, r.first().add(r.first().siblings("ul")))
                                }
                            }(h, p, 0, ae.navTitle)
                        },
                        I = function(n) {
                            return function(e) {
                                "left" != ae.position && "right" != ae.position || (E = e.touches[0].clientX, L = e.touches[0].clientY, "doc" == n ? A || (re.addEventListener("touchmove", _, fe), re.addEventListener("touchend", F, fe)) : (A = !0, p[0].addEventListener("touchmove", $, fe), p[0].addEventListener("touchend", G, fe)))
                            }
                        },
                        W = function(e, n) {
                            se.addEventListener("touchmove", ye, fe),
                            v.css("visibility", "visible"),
                            p.css(ke("transition"), "none"),
                            we(p, e, ae.position),
                            u && (u.css(ke("transition"), "none"), we(u, n, ae.position))
                        },
                        X = function(e) {
                            var n = !(1 < arguments.length && void 0 !== arguments[1]) || arguments[1],
                                t = 2 < arguments.length && void 0 !== arguments[2] && arguments[2],
                                a = 3 < arguments.length && void 0 !== arguments[3] && arguments[3];
                            se.removeEventListener("touchmove", ye, fe),
                            p.css(ke("transition"), ""),
                            we(p, t, ae.position),
                            u && (u.css(ke("transition"), ""), we(u, a, ae.position)),
                            "open" == e ? V() : (J(), n ? setTimeout(function() {
                                v.css("visibility", "")
                            }, r) : v.css("visibility", ""))
                        },
                        _ = function(e) {
                            var n = 0 - (E - e.touches[0].clientX),
                                t = "overlap" === ae.levelOpen ? K() * ae.levelSpacing : 0,
                                a = k + t;
                            n = "left" == ae.position ? Math.min(Math.max(n, 0), a) : Math.abs(Math.min(Math.max(n, -a), 0)),
                            ("left" == ae.position && E < 20 || "right" == ae.position && E > pe.width() - 20) && (B = !0, W(0 - (k - n), Math.abs(n)))
                        },
                        F = function e(n) {
                            if (re.removeEventListener("touchmove", _), re.removeEventListener("touchend", e), B) {
                                var t = n.changedTouches[n.changedTouches.length - 1],
                                    a = 0 - (E - t.clientX),
                                    o = "overlap" === ae.levelOpen ? K() * ae.levelSpacing : 0,
                                    i = k + o;
                                (a = "left" == ae.position ? Math.min(Math.max(a, 0), i) : Math.abs(Math.min(Math.max(a, -i), 0))) ? X(70 < a ? "open" : "close") : X("close", !1),
                                L = E = null,
                                B = !1
                            }
                        },
                        $ = function(e) {
                            var n = 0 - (E - e.touches[0].clientX),
                                t = 0 - (L - e.touches[0].clientY);
                            if (!(Math.abs(n) < Math.abs(t))) {
                                var a = "overlap" === ae.levelOpen ? K() * ae.levelSpacing : 0,
                                    o = k + a;
                                n = "left" == ae.position ? Math.min(Math.max(n, -o), 0) : Math.min(Math.max(n, 0), o),
                                ("left" == ae.position && n < 0 || "right" == ae.position && 0 < n) && (B = !0, W(-Math.abs(n) + a, o - Math.abs(n)))
                            }
                        },
                        G = function e(n) {
                            if (p[0].removeEventListener("touchmove", $), p[0].removeEventListener("touchend", e), A = !1, B) {
                                var t = n.changedTouches[n.changedTouches.length - 1],
                                    a = 0 - (E - t.clientX),
                                    o = "overlap" === ae.levelOpen ? K() * ae.levelSpacing : 0,
                                    i = k + o;
                                (a = "left" == ae.position ? Math.abs(Math.min(Math.max(a, -i), 0)) : Math.abs(Math.min(Math.max(a, 0), i))) == i ? X("close", !1) : 50 < a ? X("close") : X("open", !0, o, i),
                                L = E = null,
                                B = !1
                            }
                        };
                    j(),
                    H(),
                    q(),
                    !0 === ae.removeOriginalNav && n.remove(),
                    "prepend" === ae.bodyInsert ? te.prepend(v) : "append" === ae.bodyInsert && te.append(v),
                    !0 === ae.expanded && (g = !0, V()),
                    ae.swipeGestures && (p[0].addEventListener("touchstart", I("nav"), fe), re.addEventListener("touchstart", I("doc"), fe)),
                    pe.on("keydown", function(e) {
                        if (z() && ("Escape" === e.key || 27 === e.keyCode)) {
                            var n = K();
                            0 === n ? (J(), P()) : (ee(n), N(null, n - 1))
                        }
                    });
                    var Q = function(e, n, t) {
                        var a = le("#".concat(y, "-").concat(e, "-").concat(n));
                        if (a.length) {
                            var o = a.val(),
                                i = a.parent("li"),
                                c = i.closest(".nav-wrapper");
                            if (a.prop("checked", !1), c.removeClass("sub-level-open"), i.removeClass("level-open"), i.children(".nav-item-wrapper").children("[aria-controls]").attr("aria-expanded", !1), -1 !== T.indexOf(o) && T.splice(T.indexOf(o), 1), t && "overlap" === ae.levelOpen && (c.off("click").on("click", be), we(p, (e - 1) * ae.levelSpacing, ae.position), u)) {
                                var l = "x" === Oe(ae.position) ? k : C;
                                we(u, l + (e - 1) * ae.levelSpacing, ae.position)
                            }
                        }
                    };
                    ne.getSettings = function() {
                        return Object.assign({}, ae)
                    },
                    ne.isOpen = z,
                    ne.open = V,
                    ne.close = J,
                    ne.update = function(e, n) {
                        if (oe = [], "object" === _typeof(e)) {
                            for (var t in e)
                                ae[t] !== e[t] && oe.push(t);
                            ae = le.extend({}, ae, e)
                        }
                        if (!0 === e || !0 === n) {
                            if (ae.removeOriginalNav)
                                return void console.warn("%c! HC Offcanvas Nav:%c Can't update because original navigation has been removed. Disable `removeOriginalNav` option.", "color: #fa253b", "color: default");
                            j(!0),
                            H(),
                            q(!0)
                        } else
                            j(!0),
                            q(!0)
                    }
                } else
                    console.error("%c! HC Offcanvas Nav:%c Menu must contain <ul> element.", "color: #fa253b", "color: default");
                function U() {
                    var e = le(this),
                        n = e.data("level"),
                        t = e.data("index");
                    e.prop("checked") ? Z(n, t) : ee(n, t)
                }
                function Y() {
                    return !1 !== ae.levelOpen && "none" !== ae.levelOpen
                }
                function z() {
                    return m
                }
                function K() {
                    return T.length ? p.find(".hc-chk").filter("[value=".concat(T[T.length - 1], "]")).data("level") : 0
                }
                function V(e, n) {
                    if ((!z() || void 0 !== n) && (function() {
                        if (!z()) {
                            if (m = !0, v.css("visibility", "visible").attr("aria-hidden", !1).addClass(ie), f.addClass("toggle-open"), "expand" === ae.levelOpen && O && clearTimeout(O), ae.disableBody && (x = de.scrollTop() || ve.scrollTop() || te.scrollTop(), re.documentElement.scrollHeight > re.documentElement.clientHeight && ve.addClass("hc-nav-yscroll"), te.addClass("hc-nav-open"), x && te.css("top", -x)), u) {
                                var e = "x" === Oe(ae.position) ? k : C;
                                we(u, e, ae.position)
                            }
                            if (g)
                                return g = !1;
                            setTimeout(function() {
                                ne.trigger("open.$", le.extend({}, ae))
                            }, r)
                        }
                    }(), Y())) {
                        var t;
                        if ("number" != typeof e && !me(e) || "number" != typeof n && !me(n))
                            b ? (t = p.find(".hc-chk").filter("[value=".concat(b, "]")), !ae.closeActiveLevel && ae.closeOpenLevels || (b = null)) : !1 === ae.closeOpenLevels && (t = p.find(".hc-chk").filter(":checked").last());
                        else if (!(t = le("#".concat(y, "-").concat(e, "-").concat(n))).length)
                            return void console.warn("HC Offcanvas Nav: level ".concat(e, " doesn't have index ").concat(n));
                        if (t && t.length) {
                            var a = [];
                            e = t.data("level"),
                            n = t.data("index"),
                            1 < e && (t.parents(".nav-wrapper").each(function() {
                                var e = le(this),
                                    n = e.data("level");
                                0 < n && a.push({
                                    level: n,
                                    index: e.data("index")
                                })
                            }), a = a.reverse()),
                            a.push({
                                level: e,
                                index: n
                            });
                            for (var o = 0; o < a.length; o++)
                                Z(a[o].level, a[o].index, !1)
                        }
                    }
                }
                function J() {
                    if (z()) {
                        if (m = !1, u && we(u, !1), v.removeClass(ie).attr("aria-hidden", !0), p.removeAttr("style"), f.removeClass("toggle-open"), "expand" === ae.levelOpen && -1 !== ["top", "bottom"].indexOf(ae.position) ? ee(0) : Y() && (O = setTimeout(function() {
                            ee(0)
                        }, "expand" === ae.levelOpen ? r : 0)), ae.disableBody && (te.removeClass("hc-nav-open"), ve.removeClass("hc-nav-yscroll"), x)) {
                            if (te.css("top", "").scrollTop(x), ve.scrollTop(x), "bottom" === ae.position) {
                                var e = x;
                                setTimeout(function() {
                                    te.scrollTop(e),
                                    ve.scrollTop(e)
                                }, 0)
                            }
                            x = 0
                        }
                        setTimeout(function() {
                            v.css("visibility", ""),
                            ne.trigger("close.$", le.extend({}, ae)),
                            ne.trigger("close.once", le.extend({}, ae)).off("close.once")
                        }, r)
                    }
                }
                function R(e) {
                    e.preventDefault(),
                    e.stopPropagation(),
                    m ? J() : V()
                }
                function Z(e, n) {
                    var t = !(2 < arguments.length && void 0 !== arguments[2]) || arguments[2],
                        a = le("#".concat(y, "-").concat(e, "-").concat(n)),
                        o = a.val(),
                        i = a.parent("li"),
                        c = i.closest(".nav-wrapper"),
                        l = i.children(".nav-wrapper");
                    if (!1 === t && l.css("transition", "none"), a.prop("checked", !0), c.addClass("sub-level-open"), i.addClass("level-open"), i.children(".nav-item-wrapper").children("[aria-controls]").attr("aria-expanded", !0), !1 === t && setTimeout(function() {
                        l.css("transition", "")
                    }, r), -1 === T.indexOf(o) && T.push(o), "overlap" === ae.levelOpen && (c.on("click", function() {
                        return ee(e, n)
                    }), we(p, e * ae.levelSpacing, ae.position), u)) {
                        var s = "x" === Oe(ae.position) ? k : C;
                        we(u, s + e * ae.levelSpacing, ae.position)
                    }
                    ne.trigger("open.level", [e, n]),
                    M && (N(0, e, n), M = !1)
                }
                function ee(e, n) {
                    for (var t = e; t <= Object.keys(w).length; t++)
                        if (t == e && void 0 !== n)
                            Q(e, n, !0);
                        else if (0 !== e || ae.closeOpenLevels)
                            for (var a = 0; a < w[t]; a++)
                                Q(t, a, t == e);
                    ne.trigger("close.level", [e - 1, T.length ? p.find(".hc-chk").filter("[value=".concat(T[T.length - 1], "]")).data("index") : 0]),
                    M && (N(null, e - 1), M = !1)
                }
            })
        }
    })
}(jQuery, "undefined" != typeof window ? window : this);
