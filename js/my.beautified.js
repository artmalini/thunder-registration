/*********
********
************ change shortcod content dynamically
********
***********/
jQuery(document).ready(function() {
    /*!
 * jQuery Upload File Plugin
 * version: 1.9
 * @requires jQuery v1.5 or later & form plugin
 * Copyright (c) 2013 Ravishanker Kusuma
 * http://hayageek.com/
 */
    (function(a) {
        a.fn.uploadFile = function(b) {
            var c = a.extend({
                url: "",
                method: "POST",
                enctype: "multipart/form-data",
                formData: null,
                returnType: null,
                allowedTypes: "*",
                fileName: "thunder_file",
                multiple: false,
                autoSubmit: true,
                showCancel: false,
                showAbort: false,
                showDone: false,
                showStatusAfterSuccess: true,
                buttonCss: false,
                buttonClass: false,
                onSubmit: function(e) {},
                onSuccess: function(f, e) {},
                onError: function(f, e) {},
                uploadButtonClass: "ajax-file-upload"
            }, b);
            var d = "ajax-file-upload-" + a(this).attr("id");
            this.formGroup = d;
            a(this).click(function() {
                a.fn.uploadFile.createAjaxForm(this, d, c);
            });
            this.startUpload = function() {
                a("." + this.formGroup).each(function(f, e) {
                    a(this).submit();
                });
            };
            a(this).addClass(c.uploadButtonClass);
            return this;
        };
        a.fn.uploadFile.createAjaxForm = function(g, l, o) {
            var d = a("<form style='display:none;' class='" + l + "' method='" + o.method + "' action='" + o.url + "' enctype='" + o.enctype + "'></form>");
            var c = "<input type='file' name='" + o.fileName + "'/>";
            if (o.multiple) {
                if (o.fileName.indexOf("[]") != o.fileName.length - 2) {
                    o.fileName += "[]";
                }
                c = "<input type='file' name='" + o.fileName + "' multiple/>";
            }
            var h = a(c).appendTo(d);
            var k = a("<div class='ajax-file-upload-statusbar'></div>");
            var b = a("").appendTo(k);
            var n = a("<div class='ajax-file-upload-progress'>").appendTo(k).hide();
            var j = a("<div class='ajax-file-upload-bar'></div>").appendTo(n);
            var f = a("").appendTo(k).hide();
            var m = a("<div class='ajax-file-upload-red'>Cancel</div>").appendTo(k).hide();
            var e = a("").appendTo(k).hide();
            a(h).change(function() {
                var v = o.allowedTypes.toLowerCase().split(",");
                var r = "";
                var q = [];
                if (this.files) {
                    for (i = 0; i < this.files.length; i++) {
                        var t = this.files[i].name;
                        q.push(t);
                        var u = t.split(".").pop().toLowerCase();
                        if (o.allowedTypes != "*" && jQuery.inArray(u, v) < 0) {
                            alert("File type is not allowed. Allowed only: " + o.allowedTypes);
                            a(d).remove();
                            return;
                        }
                        r += t;
                        if (this.files.length != 0) {
                            r += "";
                        }
                    }
                } else {
                    var t = a(this).val();
                    q.push(t);
                    var u = t.split(".").pop().toLowerCase();
                    if (o.allowedTypes != "*" && jQuery.inArray(u, v) < 0) {
                        alert("File type is not allowed. Allowed only: " + o.allowedTypes);
                        a(d).remove();
                        return;
                    }
                    r = t;
                }
                a("body").append(d);
                a(g).after(k);
                a(b).html(r);
                var s = null;
                var p = {
                    forceSync: false,
                    data: o.formData,
                    dataType: o.returnType,
                    beforeSend: function(x, w) {
                        o.onSubmit.call(this, q);
                        a(n).show();
                        a(m).hide();
                        a(e).hide();
                        if (o.showAbort) {
                            a(f).show();
                            a(f).click(function() {
                                x.abort();
                            });
                        }
                    },
                    uploadProgress: function(A, w, z, y) {
                        var x = y + "%";
                        a(j).width(x);
                    },
                    success: function(x, w, y) {
                        a(f).hide();
                        o.onSuccess.call(this, q, x, y);
                        if (o.showStatusAfterSuccess) {
                            if (o.showDone) {
                                a(e).show();
                                a(e).click(function() {
                                    a(k).hide("slow");
                                });
                            } else {
                                a(e).hide();
                            }
                            a(j).width("100%");
                        } else {
                            a(k).hide("slow");
                        }
                        a(d).remove();
                    },
                    error: function(y, w, x) {
                        if (y.statusText == "abort") {
                            a(k).hide("slow");
                        } else {
                            o.onError.call(this, q, w, x);
                            a(n).hide();
                            a(k).append("<font color='red'>ERROR: " + x + "</font>");
                        }
                        a(f).hide();
                        a(d).remove();
                    }
                };
                if (o.autoSubmit) {
                    a(d).ajaxSubmit(p);
                } else {
                    if (o.showCancel) {
                        a(m).show();
                        a(m).click(function() {
                            a(d).remove();
                            a(k).remove();
                        });
                    }
                    a(d).ajaxForm(p);
                }
            });
            a(h).click();
        };
        if (a.fn.ajaxForm == undefined) {
            
            (function(g) {
                var d = {};
                d.fileapi = g("<input type='file'/>").get(0).files !== undefined;
                d.formdata = window.FormData !== undefined;
                var f = !!g.fn.prop;
                g.fn.attr2 = function() {
                    if (!f) {
                        return this.attr.apply(this, arguments);
                    }
                    var h = this.prop.apply(this, arguments);
                    if (h && h.jquery || typeof h === "string") {
                        return h;
                    }
                    return this.attr.apply(this, arguments);
                };
                g.fn.ajaxSubmit = function(m) {
                    if (!this.length) {
                        e("ajaxSubmit: skipping submit process - no element selected");
                        return this;
                    }
                    var l, E, o, r = this;
                    if (typeof m == "function") {
                        m = {
                            success: m
                        };
                    } else {
                        if (m === undefined) {
                            m = {};
                        }
                    }
                    l = m.type || this.attr2("method");
                    E = m.url || this.attr2("action");
                    o = typeof E === "string" ? g.trim(E) : "";
                    o = o || window.location.href || "";
                    if (o) {
                        o = (o.match(/^([^#]+)/) || [])[1];
                    }
                    m = g.extend(true, {
                        url: o,
                        success: g.ajaxSettings.success,
                        type: l || g.ajaxSettings.type,
                        iframeSrc: /^https/i.test(window.location.href || "") ? "javascript:false" : "about:blank"
                    }, m);
                    var w = {};
                    this.trigger("form-pre-serialize", [ this, m, w ]);
                    if (w.veto) {
                        e("ajaxSubmit: submit vetoed via form-pre-serialize trigger");
                        return this;
                    }
                    if (m.beforeSerialize && m.beforeSerialize(this, m) === false) {
                        e("ajaxSubmit: submit aborted via beforeSerialize callback");
                        return this;
                    }
                    var p = m.traditional;
                    if (p === undefined) {
                        p = g.ajaxSettings.traditional;
                    }
                    var u = [];
                    var G, H = this.formToArray(m.semantic, u);
                    if (m.data) {
                        m.extraData = m.data;
                        G = g.param(m.data, p);
                    }
                    if (m.beforeSubmit && m.beforeSubmit(H, this, m) === false) {
                        e("ajaxSubmit: submit aborted via beforeSubmit callback");
                        return this;
                    }
                    this.trigger("form-submit-validate", [ H, this, m, w ]);
                    if (w.veto) {
                        e("ajaxSubmit: submit vetoed via form-submit-validate trigger");
                        return this;
                    }
                    var A = g.param(H, p);
                    if (G) {
                        A = A ? A + "&" + G : G;
                    }
                    if (m.type.toUpperCase() == "GET") {
                        m.url += (m.url.indexOf("?") >= 0 ? "&" : "?") + A;
                        m.data = null;
                    } else {
                        m.data = A;
                    }
                    var J = [];
                    if (m.resetForm) {
                        J.push(function() {
                            r.resetForm();
                        });
                    }
                    if (m.clearForm) {
                        J.push(function() {
                            r.clearForm(m.includeHidden);
                        });
                    }
                    if (!m.dataType && m.target) {
                        var n = m.success || function() {};
                        J.push(function(q) {
                            var k = m.replaceTarget ? "replaceWith" : "html";
                            g(m.target)[k](q).each(n, arguments);
                        });
                    } else {
                        if (m.success) {
                            J.push(m.success);
                        }
                    }
                    m.success = function(M, q, N) {
                        var L = m.context || this;
                        for (var K = 0, k = J.length; K < k; K++) {
                            J[K].apply(L, [ M, q, N || r, r ]);
                        }
                    };
                    if (m.error) {
                        var B = m.error;
                        m.error = function(L, k, q) {
                            var K = m.context || this;
                            B.apply(K, [ L, k, q, r ]);
                        };
                    }
                    if (m.complete) {
                        var j = m.complete;
                        m.complete = function(K, k) {
                            var q = m.context || this;
                            j.apply(q, [ K, k, r ]);
                        };
                    }
                    var F = g('input[type=file]:enabled:not([value=""])', this);
                    var s = F.length > 0;
                    var D = "multipart/form-data";
                    var z = r.attr("enctype") == D || r.attr("encoding") == D;
                    var y = d.fileapi && d.formdata;
                    e("fileAPI :" + y);
                    var t = (s || z) && !y;
                    var x;
                    if (m.iframe !== false && (m.iframe || t)) {
                        if (m.closeKeepAlive) {
                            g.get(m.closeKeepAlive, function() {
                                x = I(H);
                            });
                        } else {
                            x = I(H);
                        }
                    } else {
                        if ((s || z) && y) {
                            x = v(H);
                        } else {
                            x = g.ajax(m);
                        }
                    }
                    r.removeData("jqxhr").data("jqxhr", x);
                    for (var C = 0; C < u.length; C++) {
                        u[C] = null;
                    }
                    this.trigger("form-submit-notify", [ this, m ]);
                    return this;
                    function h(M) {
                        var N = g.param(M, m.traditional).split("&");
                        var q = N.length;
                        var k = [];
                        var L, K;
                        for (L = 0; L < q; L++) {
                            N[L] = N[L].replace(/\+/g, " ");
                            K = N[L].split("=");
                            k.push([ decodeURIComponent(K[0]), decodeURIComponent(K[1]) ]);
                        }
                        return k;
                    }
                    function v(q) {
                        var k = new FormData();
                        for (var K = 0; K < q.length; K++) {
                            k.append(q[K].name, q[K].value);
                        }
                        if (m.extraData) {
                            var N = h(m.extraData);
                            for (K = 0; K < N.length; K++) {
                                if (N[K]) {
                                    k.append(N[K][0], N[K][1]);
                                }
                            }
                        }
                        m.data = null;
                        var M = g.extend(true, {}, g.ajaxSettings, m, {
                            contentType: false,
                            processData: false,
                            cache: false,
                            type: l || "POST"
                        });
                        if (m.uploadProgress) {
                            M.xhr = function() {
                                var O = g.ajaxSettings.xhr();
                                if (O.upload) {
                                    O.upload.addEventListener("progress", function(S) {
                                        var R = 0;
                                        var P = S.loaded || S.position;
                                        var Q = S.total;
                                        if (S.lengthComputable) {
                                            R = Math.ceil(P / Q * 100);
                                        }
                                        m.uploadProgress(S, P, Q, R);
                                    }, false);
                                }
                                return O;
                            };
                        }
                        M.data = null;
                        var L = M.beforeSend;
                        M.beforeSend = function(P, O) {
                            O.data = k;
                            if (L) {
                                L.call(this, P, O);
                            }
                        };
                        return g.ajax(M);
                    }
                    function I(ah) {
                        var N = r[0], M, ad, X, af, aa, P, S, Q, R, ab, ae, V;
                        var ak = g.Deferred();
                        ak.abort = function(al) {
                            Q.abort(al);
                        };
                        if (ah) {
                            for (ad = 0; ad < u.length; ad++) {
                                M = g(u[ad]);
                                if (f) {
                                    M.prop("disabled", false);
                                } else {
                                    M.removeAttr("disabled");
                                }
                            }
                        }
                        X = g.extend(true, {}, g.ajaxSettings, m);
                        X.context = X.context || X;
                        aa = "jqFormIO" + new Date().getTime();
                        if (X.iframeTarget) {
                            P = g(X.iframeTarget);
                            ab = P.attr2("name");
                            if (!ab) {
                                P.attr2("name", aa);
                            } else {
                                aa = ab;
                            }
                        } else {
                            P = g('<iframe name="' + aa + '" src="' + X.iframeSrc + '" />');
                            P.css({
                                position: "absolute",
                                top: "-1000px",
                                left: "-1000px"
                            });
                        }
                        S = P[0];
                        Q = {
                            aborted: 0,
                            responseText: null,
                            responseXML: null,
                            status: 0,
                            statusText: "n/a",
                            getAllResponseHeaders: function() {},
                            getResponseHeader: function() {},
                            setRequestHeader: function() {},
                            abort: function(al) {
                                var am = al === "timeout" ? "timeout" : "aborted";
                                e("aborting upload... " + am);
                                this.aborted = 1;
                                try {
                                    if (S.contentWindow.document.execCommand) {
                                        S.contentWindow.document.execCommand("Stop");
                                    }
                                } catch (an) {}
                                P.attr("src", X.iframeSrc);
                                Q.error = am;
                                if (X.error) {
                                    X.error.call(X.context, Q, am, al);
                                }
                                if (af) {
                                    g.event.trigger("ajaxError", [ Q, X, am ]);
                                }
                                if (X.complete) {
                                    X.complete.call(X.context, Q, am);
                                }
                            }
                        };
                        af = X.global;
                        if (af && 0 === g.active++) {
                            g.event.trigger("ajaxStart");
                        }
                        if (af) {
                            g.event.trigger("ajaxSend", [ Q, X ]);
                        }
                        if (X.beforeSend && X.beforeSend.call(X.context, Q, X) === false) {
                            if (X.global) {
                                g.active--;
                            }
                            ak.reject();
                            return ak;
                        }
                        if (Q.aborted) {
                            ak.reject();
                            return ak;
                        }
                        R = N.clk;
                        if (R) {
                            ab = R.name;
                            if (ab && !R.disabled) {
                                X.extraData = X.extraData || {};
                                X.extraData[ab] = R.value;
                                if (R.type == "image") {
                                    X.extraData[ab + ".x"] = N.clk_x;
                                    X.extraData[ab + ".y"] = N.clk_y;
                                }
                            }
                        }
                        var W = 1;
                        var T = 2;
                        function U(an) {
                            var am = null;
                            try {
                                if (an.contentWindow) {
                                    am = an.contentWindow.document;
                                }
                            } catch (al) {
                                e("cannot get iframe.contentWindow document: " + al);
                            }
                            if (am) {
                                return am;
                            }
                            try {
                                am = an.contentDocument ? an.contentDocument : an.document;
                            } catch (al) {
                                e("cannot get iframe.contentDocument: " + al);
                                am = an.document;
                            }
                            return am;
                        }
                        var L = g("meta[name=csrf-token]").attr("content");
                        var K = g("meta[name=csrf-param]").attr("content");
                        if (K && L) {
                            X.extraData = X.extraData || {};
                            X.extraData[K] = L;
                        }
                        function ac() {
                            var an = r.attr2("target"), al = r.attr2("action");
                            N.setAttribute("target", aa);
                            if (!l) {
                                N.setAttribute("method", "POST");
                            }
                            if (al != X.url) {
                                N.setAttribute("action", X.url);
                            }
                            if (!X.skipEncodingOverride && (!l || /post/i.test(l))) {
                                r.attr({
                                    encoding: "multipart/form-data",
                                    enctype: "multipart/form-data"
                                });
                            }
                            if (X.timeout) {
                                V = setTimeout(function() {
                                    ae = true;
                                    Z(W);
                                }, X.timeout);
                            }
                            function ao() {
                                try {
                                    var at = U(S).readyState;
                                    e("state = " + at);
                                    if (at && at.toLowerCase() == "uninitialized") {
                                        setTimeout(ao, 50);
                                    }
                                } catch (au) {
                                    e("Server abort: ", au, " (", au.name, ")");
                                    Z(T);
                                    if (V) {
                                        clearTimeout(V);
                                    }
                                    V = undefined;
                                }
                            }
                            var am = [];
                            try {
                                if (X.extraData) {
                                    for (var ar in X.extraData) {
                                        if (X.extraData.hasOwnProperty(ar)) {
                                            if (g.isPlainObject(X.extraData[ar]) && X.extraData[ar].hasOwnProperty("name") && X.extraData[ar].hasOwnProperty("value")) {
                                                am.push(g('<input type="hidden" name="' + X.extraData[ar].name + '">').val(X.extraData[ar].value).appendTo(N)[0]);
                                            } else {
                                                am.push(g('<input type="hidden" name="' + ar + '">').val(X.extraData[ar]).appendTo(N)[0]);
                                            }
                                        }
                                    }
                                }
                                if (!X.iframeTarget) {
                                    P.appendTo("body");
                                    if (S.attachEvent) {
                                        S.attachEvent("onload", Z);
                                    } else {
                                        S.addEventListener("load", Z, false);
                                    }
                                }
                                setTimeout(ao, 15);
                                try {
                                    N.submit();
                                } catch (ap) {
                                    var aq = document.createElement("form").submit;
                                    aq.apply(N);
                                }
                            } finally {
                                N.setAttribute("action", al);
                                if (an) {
                                    N.setAttribute("target", an);
                                } else {
                                    r.removeAttr("target");
                                }
                                g(am).remove();
                            }
                        }
                        if (X.forceSync) {
                            ac();
                        } else {
                            setTimeout(ac, 10);
                        }
                        var ai, aj, ag = 50, O;
                        function Z(ar) {
                            if (Q.aborted || O) {
                                return;
                            }
                            aj = U(S);
                            if (!aj) {
                                e("cannot access response document");
                                ar = T;
                            }
                            if (ar === W && Q) {
                                Q.abort("timeout");
                                ak.reject(Q, "timeout");
                                return;
                            } else {
                                if (ar == T && Q) {
                                    Q.abort("server abort");
                                    ak.reject(Q, "error", "server abort");
                                    return;
                                }
                            }
                            if (!aj || aj.location.href == X.iframeSrc) {
                                if (!ae) {
                                    return;
                                }
                            }
                            if (S.detachEvent) {
                                S.detachEvent("onload", Z);
                            } else {
                                S.removeEventListener("load", Z, false);
                            }
                            var ap = "success", au;
                            try {
                                if (ae) {
                                    throw "timeout";
                                }
                                var ao = X.dataType == "xml" || aj.XMLDocument || g.isXMLDoc(aj);
                                e("isXml=" + ao);
                                if (!ao && window.opera && (aj.body === null || !aj.body.innerHTML)) {
                                    if (--ag) {
                                        e("requeing onLoad callback, DOM not available");
                                        setTimeout(Z, 250);
                                        return;
                                    }
                                }
                                var av = aj.body ? aj.body : aj.documentElement;
                                Q.responseText = av ? av.innerHTML : null;
                                Q.responseXML = aj.XMLDocument ? aj.XMLDocument : aj;
                                if (ao) {
                                    X.dataType = "xml";
                                }
                                Q.getResponseHeader = function(ay) {
                                    var ax = {
                                        "content-type": X.dataType
                                    };
                                    return ax[ay.toLowerCase()];
                                };
                                if (av) {
                                    Q.status = Number(av.getAttribute("status")) || Q.status;
                                    Q.statusText = av.getAttribute("statusText") || Q.statusText;
                                }
                                var al = (X.dataType || "").toLowerCase();
                                var at = /(json|script|text)/.test(al);
                                if (at || X.textarea) {
                                    var aq = aj.getElementsByTagName("textarea")[0];
                                    if (aq) {
                                        Q.responseText = aq.value;
                                        Q.status = Number(aq.getAttribute("status")) || Q.status;
                                        Q.statusText = aq.getAttribute("statusText") || Q.statusText;
                                    } else {
                                        if (at) {
                                            var am = aj.getElementsByTagName("pre")[0];
                                            var aw = aj.getElementsByTagName("body")[0];
                                            if (am) {
                                                Q.responseText = am.textContent ? am.textContent : am.innerText;
                                            } else {
                                                if (aw) {
                                                    Q.responseText = aw.textContent ? aw.textContent : aw.innerText;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if (al == "xml" && !Q.responseXML && Q.responseText) {
                                        Q.responseXML = Y(Q.responseText);
                                    }
                                }
                                try {
                                    ai = k(Q, al, X);
                                } catch (an) {
                                    ap = "parsererror";
                                    Q.error = au = an || ap;
                                }
                            } catch (an) {
                                e("error caught: ", an);
                                ap = "error";
                                Q.error = au = an || ap;
                            }
                            if (Q.aborted) {
                                e("upload aborted");
                                ap = null;
                            }
                            if (Q.status) {
                                ap = Q.status >= 200 && Q.status < 300 || Q.status === 304 ? "success" : "error";
                            }
                            if (ap === "success") {
                                if (X.success) {
                                    X.success.call(X.context, ai, "success", Q);
                                }
                                ak.resolve(Q.responseText, "success", Q);
                                if (af) {
                                    g.event.trigger("ajaxSuccess", [ Q, X ]);
                                }
                            } else {
                                if (ap) {
                                    if (au === undefined) {
                                        au = Q.statusText;
                                    }
                                    if (X.error) {
                                        X.error.call(X.context, Q, ap, au);
                                    }
                                    ak.reject(Q, "error", au);
                                    if (af) {
                                        g.event.trigger("ajaxError", [ Q, X, au ]);
                                    }
                                }
                            }
                            if (af) {
                                g.event.trigger("ajaxComplete", [ Q, X ]);
                            }
                            if (af && !--g.active) {
                                g.event.trigger("ajaxStop");
                            }
                            if (X.complete) {
                                X.complete.call(X.context, Q, ap);
                            }
                            O = true;
                            if (X.timeout) {
                                clearTimeout(V);
                            }
                            setTimeout(function() {
                                if (!X.iframeTarget) {
                                    P.remove();
                                }
                                Q.responseXML = null;
                            }, 100);
                        }
                        var Y = g.parseXML || function(al, am) {
                            if (window.ActiveXObject) {
                                am = new ActiveXObject("Microsoft.XMLDOM");
                                am.async = "false";
                                am.loadXML(al);
                            } else {
                                am = new DOMParser().parseFromString(al, "text/xml");
                            }
                            return am && am.documentElement && am.documentElement.nodeName != "parsererror" ? am : null;
                        };
                        var q = g.parseJSON || function(al) {
                            return window["eval"]("(" + al + ")");
                        };
                        var k = function(aq, ao, an) {
                            var am = aq.getResponseHeader("content-type") || "", al = ao === "xml" || !ao && am.indexOf("xml") >= 0, ap = al ? aq.responseXML : aq.responseText;
                            if (al && ap.documentElement.nodeName === "parsererror") {
                                if (g.error) {
                                    g.error("parsererror");
                                }
                            }
                            if (an && an.dataFilter) {
                                ap = an.dataFilter(ap, ao);
                            }
                            if (typeof ap === "string") {
                                if (ao === "json" || !ao && am.indexOf("json") >= 0) {
                                    ap = q(ap);
                                } else {
                                    if (ao === "script" || !ao && am.indexOf("javascript") >= 0) {
                                        g.globalEval(ap);
                                    }
                                }
                            }
                            return ap;
                        };
                        return ak;
                    }
                };
                g.fn.ajaxForm = function(h) {
                    h = h || {};
                    h.delegation = h.delegation && g.isFunction(g.fn.on);
                    if (!h.delegation && this.length === 0) {
                        var j = {
                            s: this.selector,
                            c: this.context
                        };
                        if (!g.isReady && j.s) {
                            e("DOM not ready, queuing ajaxForm");
                            g(function() {
                                g(j.s, j.c).ajaxForm(h);
                            });
                            return this;
                        }
                        e("terminating; zero elements found by selector" + (g.isReady ? "" : " (DOM not ready)"));
                        return this;
                    }
                    if (h.delegation) {
                        g(document).off("submit.form-plugin", this.selector, c).off("click.form-plugin", this.selector, b).on("submit.form-plugin", this.selector, h, c).on("click.form-plugin", this.selector, h, b);
                        return this;
                    }
                    return this.ajaxFormUnbind().bind("submit.form-plugin", h, c).bind("click.form-plugin", h, b);
                };
                function c(j) {
                    var h = j.data;
                    if (!j.isDefaultPrevented()) {
                        j.preventDefault();
                        g(this).ajaxSubmit(h);
                    }
                }
                function b(m) {
                    var l = m.target;
                    var j = g(l);
                    if (!j.is("[type=submit],[type=image]")) {
                        var h = j.closest("[type=submit]");
                        if (h.length === 0) {
                            return;
                        }
                        l = h[0];
                    }
                    var k = this;
                    k.clk = l;
                    if (l.type == "image") {
                        if (m.offsetX !== undefined) {
                            k.clk_x = m.offsetX;
                            k.clk_y = m.offsetY;
                        } else {
                            if (typeof g.fn.offset == "function") {
                                var n = j.offset();
                                k.clk_x = m.pageX - n.left;
                                k.clk_y = m.pageY - n.top;
                            } else {
                                k.clk_x = m.pageX - l.offsetLeft;
                                k.clk_y = m.pageY - l.offsetTop;
                            }
                        }
                    }
                    setTimeout(function() {
                        k.clk = k.clk_x = k.clk_y = null;
                    }, 100);
                }
                g.fn.ajaxFormUnbind = function() {
                    return this.unbind("submit.form-plugin click.form-plugin");
                };
                g.fn.formToArray = function(y, h) {
                    var x = [];
                    if (this.length === 0) {
                        return x;
                    }
                    var m = this[0];
                    var q = y ? m.getElementsByTagName("*") : m.elements;
                    if (!q) {
                        return x;
                    }
                    var s, r, p, z, o, u, l;
                    for (s = 0, u = q.length; s < u; s++) {
                        o = q[s];
                        p = o.name;
                        if (!p || o.disabled) {
                            continue;
                        }
                        if (y && m.clk && o.type == "image") {
                            if (m.clk == o) {
                                x.push({
                                    name: p,
                                    value: g(o).val(),
                                    type: o.type
                                });
                                x.push({
                                    name: p + ".x",
                                    value: m.clk_x
                                }, {
                                    name: p + ".y",
                                    value: m.clk_y
                                });
                            }
                            continue;
                        }
                        z = g.fieldValue(o, true);
                        if (z && z.constructor == Array) {
                            if (h) {
                                h.push(o);
                            }
                            for (r = 0, l = z.length; r < l; r++) {
                                x.push({
                                    name: p,
                                    value: z[r]
                                });
                            }
                        } else {
                            if (d.fileapi && o.type == "file") {
                                if (h) {
                                    h.push(o);
                                }
                                var k = o.files;
                                if (k.length) {
                                    for (r = 0; r < k.length; r++) {
                                        x.push({
                                            name: p,
                                            value: k[r],
                                            type: o.type
                                        });
                                    }
                                } else {
                                    x.push({
                                        name: p,
                                        value: "",
                                        type: o.type
                                    });
                                }
                            } else {
                                if (z !== null && typeof z != "undefined") {
                                    if (h) {
                                        h.push(o);
                                    }
                                    x.push({
                                        name: p,
                                        value: z,
                                        type: o.type,
                                        required: o.required
                                    });
                                }
                            }
                        }
                    }
                    if (!y && m.clk) {
                        var t = g(m.clk), w = t[0];
                        p = w.name;
                        if (p && !w.disabled && w.type == "image") {
                            x.push({
                                name: p,
                                value: t.val()
                            });
                            x.push({
                                name: p + ".x",
                                value: m.clk_x
                            }, {
                                name: p + ".y",
                                value: m.clk_y
                            });
                        }
                    }
                    return x;
                };
                g.fn.formSerialize = function(h) {
                    return g.param(this.formToArray(h));
                };
                g.fn.fieldSerialize = function(j) {
                    var h = [];
                    this.each(function() {
                        var o = this.name;
                        if (!o) {
                            return;
                        }
                        var l = g.fieldValue(this, j);
                        if (l && l.constructor == Array) {
                            for (var m = 0, k = l.length; m < k; m++) {
                                h.push({
                                    name: o,
                                    value: l[m]
                                });
                            }
                        } else {
                            if (l !== null && typeof l != "undefined") {
                                h.push({
                                    name: this.name,
                                    value: l
                                });
                            }
                        }
                    });
                    return g.param(h);
                };
                g.fn.fieldValue = function(n) {
                    for (var m = [], k = 0, h = this.length; k < h; k++) {
                        var l = this[k];
                        var j = g.fieldValue(l, n);
                        if (j === null || typeof j == "undefined" || j.constructor == Array && !j.length) {
                            continue;
                        }
                        if (j.constructor == Array) {
                            g.merge(m, j);
                        } else {
                            m.push(j);
                        }
                    }
                    return m;
                };
                g.fieldValue = function(h, p) {
                    var k = h.name, w = h.type, x = h.tagName.toLowerCase();
                    if (p === undefined) {
                        p = true;
                    }
                    if (p && (!k || h.disabled || w == "reset" || w == "button" || (w == "checkbox" || w == "radio") && !h.checked || (w == "submit" || w == "image") && h.form && h.form.clk != h || x == "select" && h.selectedIndex == -1)) {
                        return null;
                    }
                    if (x == "select") {
                        var q = h.selectedIndex;
                        if (q < 0) {
                            return null;
                        }
                        var s = [], j = h.options;
                        var m = w == "select-one";
                        var r = m ? q + 1 : j.length;
                        for (var l = m ? q : 0; l < r; l++) {
                            var o = j[l];
                            if (o.selected) {
                                var u = o.value;
                                if (!u) {
                                    u = o.attributes && o.attributes.value && !o.attributes.value.specified ? o.text : o.value;
                                }
                                if (m) {
                                    return u;
                                }
                                s.push(u);
                            }
                        }
                        return s;
                    }
                    return g(h).val();
                };
                g.fn.clearForm = function(h) {
                    return this.each(function() {
                        g("input,select,textarea", this).clearFields(h);
                    });
                };
                g.fn.clearFields = g.fn.clearInputs = function(h) {
                    var j = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;
                    return this.each(function() {
                        var l = this.type, k = this.tagName.toLowerCase();
                        if (j.test(l) || k == "textarea") {
                            this.value = "";
                        } else {
                            if (l == "checkbox" || l == "radio") {
                                this.checked = false;
                            } else {
                                if (k == "select") {
                                    this.selectedIndex = -1;
                                } else {
                                    if (l == "file") {
                                        if (/MSIE/.test(navigator.userAgent)) {
                                            g(this).replaceWith(g(this).clone(true));
                                        } else {
                                            g(this).val("");
                                        }
                                    } else {
                                        if (h) {
                                            if (h === true && /hidden/.test(l) || typeof h == "string" && g(this).is(h)) {
                                                this.value = "";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    });
                };
                g.fn.resetForm = function() {
                    return this.each(function() {
                        if (typeof this.reset == "function" || typeof this.reset == "object" && !this.reset.nodeType) {
                            this.reset();
                        }
                    });
                };
                g.fn.enable = function(h) {
                    if (h === undefined) {
                        h = true;
                    }
                    return this.each(function() {
                        this.disabled = !h;
                    });
                };
                g.fn.selected = function(h) {
                    if (h === undefined) {
                        h = true;
                    }
                    return this.each(function() {
                        var j = this.type;
                        if (j == "checkbox" || j == "radio") {
                            this.checked = h;
                        } else {
                            if (this.tagName.toLowerCase() == "option") {
                                var k = g(this).parent("select");
                                if (h && k[0] && k[0].type == "select-one") {
                                    k.find("option").selected(false);
                                }
                                this.selected = h;
                            }
                        }
                    });
                };
                g.fn.ajaxSubmit.debug = false;
                function e() {
                    if (!g.fn.ajaxSubmit.debug) {
                        return;
                    }
                    var h = "[jquery.form] " + Array.prototype.join.call(arguments, "");
                    if (window.console && window.console.log) {
                        window.console.log(h);
                    } else {
                        if (window.opera && window.opera.postError) {
                            window.opera.postError(h);
                        }
                    }
                }
            })(typeof jQuery != "undefined" ? jQuery : window.Zepto);
        }
    })(jQuery);












    

    /*!
 * jQuery Upload File Plugin
 * version: 4.0.10
 * @requires jQuery v1.5 or later & form plugin
 * Copyright (c) 2013 Ravishanker Kusuma
 * http://hayageek.com/
 */
    !function(e) {
        void 0 == e.fn.ajaxForm && e.getScript(("https:" == document.location.protocol ? "https://" : "http://") + "malsup.github.io/jquery.form.js");
        var a = {};
        a.fileapi = void 0 !== e("<input type='file'/>").get(0).files, a.formdata = void 0 !== window.FormData, 
        e.fn.uploadFile = function(t) {
            function r() {
                S || (S = !0, function e() {
                    if (w.sequential || (w.sequentialCount = 99999), 0 == x.length && 0 == D.length) w.afterUploadAll && w.afterUploadAll(C), 
                    S = !1; else {
                        if (D.length < w.sequentialCount) {
                            var a = x.shift();
                            void 0 != a && (D.push(a), a.removeClass(C.formGroup), a.submit());
                        }
                        window.setTimeout(e, 100);
                    }
                }());
            }
            function o(a, t, r) {
                r.on("dragenter", function(a) {
                    a.stopPropagation(), a.preventDefault(), e(this).addClass(t.dragDropHoverClass);
                }), r.on("dragover", function(a) {
                    a.stopPropagation(), a.preventDefault();
                    var r = e(this);
                    r.hasClass(t.dragDropContainerClass) && !r.hasClass(t.dragDropHoverClass) && r.addClass(t.dragDropHoverClass);
                }), r.on("drop", function(r) {
                    r.preventDefault(), e(this).removeClass(t.dragDropHoverClass), a.errorLog.html("");
                    var o = r.originalEvent.dataTransfer.files;
                    return !t.multiple && o.length > 1 ? void (t.showError && e("<div class='" + t.errorClass + "'>" + t.multiDragErrorStr + "</div>").appendTo(a.errorLog)) : void (0 != t.onSelect(o) && l(t, a, o));
                }), r.on("dragleave", function(a) {
                    e(this).removeClass(t.dragDropHoverClass);
                }), e(document).on("dragenter", function(e) {
                    e.stopPropagation(), e.preventDefault();
                }), e(document).on("dragover", function(a) {
                    a.stopPropagation(), a.preventDefault();
                    var r = e(this);
                    r.hasClass(t.dragDropContainerClass) || r.removeClass(t.dragDropHoverClass);
                }), e(document).on("drop", function(a) {
                    a.stopPropagation(), a.preventDefault(), e(this).removeClass(t.dragDropHoverClass);
                });
            }
            function s(e) {
                var a = "", t = e / 1024;
                if (parseInt(t) > 1024) {
                    var r = t / 1024;
                    a = r.toFixed(2) + " MB";
                } else a = t.toFixed(2) + " KB";
                return a;
            }
            function i(a) {
                var t = [];
                t = "string" == jQuery.type(a) ? a.split("&") : e.param(a).split("&");
                var r, o, s = t.length, i = [];
                for (r = 0; s > r; r++) t[r] = t[r].replace(/\+/g, " "), o = t[r].split("="), i.push([ decodeURIComponent(o[0]), decodeURIComponent(o[1]) ]);
                return i;
            }
            function l(a, t, r) {
                for (var o = 0; o < r.length; o++) if (n(t, a, r[o].name)) if (a.allowDuplicates || !d(t, r[o].name)) if (-1 != a.maxFileSize && r[o].size > a.maxFileSize) a.showError && e("<div class='" + a.errorClass + "'><b>" + r[o].name + "</b> " + a.sizeErrorStr + s(a.maxFileSize) + "</div>").appendTo(t.errorLog); else if (-1 != a.maxFileCount && t.selectedFiles >= a.maxFileCount) a.showError && e("<div class='" + a.errorClass + "'><b>" + r[o].name + "</b> " + a.maxFileCountErrorStr + a.maxFileCount + "</div>").appendTo(t.errorLog); else {
                    t.selectedFiles++, t.existingFileNames.push(r[o].name);
                    var l = a, p = new FormData(), u = a.fileName.replace("[]", "");
                    p.append(u, r[o]);
                    var c = a.formData;
                    if (c) for (var h = i(c), f = 0; f < h.length; f++) h[f] && p.append(h[f][0], h[f][1]);
                    l.fileData = p;
                    var w = new m(t, a), g = "";
                    g = a.showFileCounter ? t.fileCounter + a.fileCounterStyle + r[o].name : r[o].name, 
                    a.showFileSize && (g += " (" + s(r[o].size) + ")"), w.filename.html(g);
                    var C = e("<form style='display:block; position:absolute;left: 150px;' class='" + t.formGroup + "' method='" + a.method + "' action='" + a.url + "' enctype='" + a.enctype + "'></form>");
                    C.appendTo("body");
                    var b = [];
                    b.push(r[o].name), v(C, l, w, b, t, r[o]), t.fileCounter++;
                } else a.showError && e("<div class='" + a.errorClass + "'><b>" + r[o].name + "</b> " + a.duplicateErrorStr + "</div>").appendTo(t.errorLog); else a.showError && e("<div class='" + a.errorClass + "'><b>" + r[o].name + "</b> " + a.extErrorStr + a.allowedTypes + "</div>").appendTo(t.errorLog);
            }
            function n(e, a, t) {
                var r = a.allowedTypes.toLowerCase().split(/[\s,]+/g), o = t.split(".").pop().toLowerCase();
                return "*" != a.allowedTypes && jQuery.inArray(o, r) < 0 ? !1 : !0;
            }
            function d(e, a) {
                var t = !1;
                if (e.existingFileNames.length) for (var r = 0; r < e.existingFileNames.length; r++) (e.existingFileNames[r] == a || w.duplicateStrict && e.existingFileNames[r].toLowerCase() == a.toLowerCase()) && (t = !0);
                return t;
            }
            function p(e, a) {
                if (e.existingFileNames.length) for (var t = 0; t < a.length; t++) {
                    var r = e.existingFileNames.indexOf(a[t]);
                    -1 != r && e.existingFileNames.splice(r, 1);
                }
            }
            function u(e, a) {
                if (e) {
                    a.show();
                    var t = new FileReader();
                    t.onload = function(e) {
                        a.attr("src", e.target.result);
                    }, t.readAsDataURL(e);
                }
            }
            function c(a, t) {
                if (a.showFileCounter) {
                    var r = e(t.container).find(".ajax-file-upload-filename").length;
                    t.fileCounter = r + 1, e(t.container).find(".ajax-file-upload-filename").each(function(t, o) {
                        var s = e(this).html().split(a.fileCounterStyle), i = (parseInt(s[0]) - 1, r + a.fileCounterStyle + s[1]);
                        e(this).html(i), r--;
                    });
                }
            }
            function h(t, r, o, s) {
                var i = "ajax-upload-id-" + new Date().getTime(), d = e("<form method='" + o.method + "' action='" + o.url + "' enctype='" + o.enctype + "'></form>"), p = "<input type='file' id='" + i + "' name='" + o.fileName + "' accept='" + o.acceptFiles + "'/>";
                o.multiple && (o.fileName.indexOf("[]") != o.fileName.length - 2 && (o.fileName += "[]"), 
                p = "<input type='file' id='" + i + "' name='" + o.fileName + "' accept='" + o.acceptFiles + "' multiple/>");
                var u = e(p).appendTo(d);
                u.change(function() {
                    t.errorLog.html("");
                    var i = (o.allowedTypes.toLowerCase().split(","), []);
                    if (this.files) {
                        for (g = 0; g < this.files.length; g++) i.push(this.files[g].name);
                        if (0 == o.onSelect(this.files)) return;
                    } else {
                        var p = e(this).val(), u = [];
                        if (i.push(p), !n(t, o, p)) return void (o.showError && e("<div class='" + o.errorClass + "'><b>" + p + "</b> " + o.extErrorStr + o.allowedTypes + "</div>").appendTo(t.errorLog));
                        if (u.push({
                            name: p,
                            size: "NA"
                        }), 0 == o.onSelect(u)) return;
                    }
                    if (c(o, t), s.unbind("click"), d.hide(), h(t, r, o, s), d.addClass(r), o.serialize && a.fileapi && a.formdata) {
                        d.removeClass(r);
                        var f = this.files;
                        d.remove(), l(o, t, f);
                    } else {
                        for (var w = "", g = 0; g < i.length; g++) w += o.showFileCounter ? t.fileCounter + o.fileCounterStyle + i[g] + "<br>" : i[g] + "<br>", 
                        t.fileCounter++;
                        if (-1 != o.maxFileCount && t.selectedFiles + i.length > o.maxFileCount) return void (o.showError && e("<div class='" + o.errorClass + "'><b>" + w + "</b> " + o.maxFileCountErrorStr + o.maxFileCount + "</div>").appendTo(t.errorLog));
                        t.selectedFiles += i.length;
                        var C = new m(t, o);
                        C.filename.html(w), v(d, o, C, i, t, null);
                    }
                }), o.nestedForms ? (d.css({
                    margin: 0,
                    padding: 0
                }), s.css({
                    position: "relative",
                    overflow: "hidden",
                    cursor: "default"
                }), u.css({
                    position: "absolute",
                    cursor: "pointer",
                    top: "0px",
                    width: "100%",
                    height: "100%",
                    left: "0px",
                    "z-index": "100",
                    opacity: "0.0",
                    filter: "alpha(opacity=0)",
                    "-ms-filter": "alpha(opacity=0)",
                    "-khtml-opacity": "0.0",
                    "-moz-opacity": "0.0"
                }), d.appendTo(s)) : (d.appendTo(e("body")), d.css({
                    margin: 0,
                    padding: 0,
                    display: "block",
                    position: "absolute",
                    left: "-250px"
                }), -1 != navigator.appVersion.indexOf("MSIE ") ? s.attr("for", i) : s.click(function() {
                    u.click();
                }));
            }
            function f(a, t) {
                return this.statusbar = e("<div class='ajax-file-upload-statusbar'></div>").width(t.statusBarWidth), 
                this.preview = e("<img class='ajax-file-upload-preview' />").width(t.previewWidth).height(t.previewHeight).appendTo(this.statusbar).hide(), 
                this.filename = e("<div class='ajax-file-upload-filename'></div>").appendTo(this.statusbar), 
                this.progressDiv = e("<div class='ajax-file-upload-progress'>").appendTo(this.statusbar).hide(), 
                this.progressbar = e("<div class='ajax-file-upload-bar'></div>").appendTo(this.progressDiv), 
                this.abort = e("<div>" + t.abortStr + "</div>").appendTo(this.statusbar).hide(), 
                this.cancel = e("<div>" + t.cancelStr + "</div>").appendTo(this.statusbar).hide(), 
                this.done = e("<div>" + t.doneStr + "</div>").appendTo(this.statusbar).hide(), this.download = e("<div>" + t.downloadStr + "</div>").appendTo(this.statusbar).hide(), 
                this.del = e("<div>" + t.deletelStr + "</div>").appendTo(this.statusbar).hide(), 
                this.abort.addClass("ajax-file-upload-red"), this.done.addClass("ajax-file-upload-green"), 
                this.download.addClass("ajax-file-upload-green"), this.cancel.addClass("ajax-file-upload-red"), 
                this.del.addClass("ajax-file-upload-red"), this;
            }
            function m(a, t) {
                var r = null;
                return r = t.customProgressBar ? new t.customProgressBar(a, t) : new f(a, t), r.abort.addClass(a.formGroup), 
                r.abort.addClass(t.abortButtonClass), r.cancel.addClass(a.formGroup), r.cancel.addClass(t.cancelButtonClass), 
                t.extraHTML && (r.extraHTML = e("<div class='extrahtml'>" + t.extraHTML() + "</div>").insertAfter(r.filename)), 
                "bottom" == t.uploadQueueOrder ? e(a.container).append(r.statusbar) : e(a.container).prepend(r.statusbar), 
                r;
            }
            function v(t, o, s, l, n, d) {
                var h = {
                    cache: !1,
                    contentType: !1,
                    processData: !1,
                    forceSync: !1,
                    type: o.method,
                    data: o.formData,
                    formData: o.fileData,
                    dataType: o.returnType,
                    beforeSubmit: function(a, r, d) {
                        if (0 != o.onSubmit.call(this, l)) {
                            if (o.dynamicFormData) {
                                var u = i(o.dynamicFormData());
                                if (u) for (var h = 0; h < u.length; h++) u[h] && (void 0 != o.fileData ? d.formData.append(u[h][0], u[h][1]) : d.data[u[h][0]] = u[h][1]);
                            }
                            return o.extraHTML && e(s.extraHTML).find("input,select,textarea").each(function(a, t) {
                                void 0 != o.fileData ? d.formData.append(e(this).attr("name"), e(this).val()) : d.data[e(this).attr("name")] = e(this).val();
                            }), !0;
                        }
                        return s.statusbar.append("<div class='" + o.errorClass + "'>" + o.uploadErrorStr + "</div>"), 
                        s.cancel.show(), t.remove(), s.cancel.click(function() {
                            x.splice(x.indexOf(t), 1), p(n, l), s.statusbar.remove(), o.onCancel.call(n, l, s), 
                            n.selectedFiles -= l.length, c(o, n);
                        }), !1;
                    },
                    beforeSend: function(e, t) {
                        s.progressDiv.show(), s.cancel.hide(), s.done.hide(), o.showAbort && (s.abort.show(), 
                        s.abort.click(function() {
                            p(n, l), e.abort(), n.selectedFiles -= l.length, o.onAbort.call(n, l, s);
                        })), a.formdata ? s.progressbar.width("1%") : s.progressbar.width("5%");
                    },
                    uploadProgress: function(e, a, t, r) {
                        r > 98 && (r = 98);
                        var i = r + "%";
                        r > 1 && s.progressbar.width(i), o.showProgress && (s.progressbar.html(i), s.progressbar.css("text-align", "center"));
                    },
                    success: function(a, r, i) {
                        if (s.cancel.remove(), D.pop(), "json" == o.returnType && "object" == e.type(a) && a.hasOwnProperty(o.customErrorKeyStr)) {
                            s.abort.hide();
                            var d = a[o.customErrorKeyStr];
                            return o.onError.call(this, l, 200, d, s), o.showStatusAfterError ? (s.progressDiv.hide(), 
                            s.statusbar.append("<span class='" + o.errorClass + "'>ERROR: " + d + "</span>")) : (s.statusbar.hide(), 
                            s.statusbar.remove()), n.selectedFiles -= l.length, void t.remove();
                        }
                        n.responses.push(a), s.progressbar.width("100%"), o.showProgress && (s.progressbar.html("100%"), 
                        s.progressbar.css("text-align", "center")), s.abort.hide(), o.onSuccess.call(this, l, a, i, s), 
                        o.showStatusAfterSuccess ? (o.showDone ? (s.done.show(), s.done.click(function() {
                            s.statusbar.hide("slow"), s.statusbar.remove();
                        })) : s.done.hide(), o.showDelete ? (s.del.show(), s.del.click(function() {
                            p(n, l), s.statusbar.hide().remove(), o.deleteCallback && o.deleteCallback.call(this, a, s), 
                            n.selectedFiles -= l.length, c(o, n);
                        })) : s.del.hide()) : (s.statusbar.hide("slow"), s.statusbar.remove()), o.showDownload && (s.download.show(), 
                        s.download.click(function() {
                            o.downloadCallback && o.downloadCallback(a);
                        })), t.remove();
                    },
                    error: function(e, a, r) {
                        s.cancel.remove(), D.pop(), s.abort.hide(), "abort" == e.statusText ? (s.statusbar.hide("slow").remove(), 
                        c(o, n)) : (o.onError.call(this, l, a, r, s), o.showStatusAfterError ? (s.progressDiv.hide(), 
                        s.statusbar.append("<span class='" + o.errorClass + "'>ERROR: " + r + "</span>")) : (s.statusbar.hide(), 
                        s.statusbar.remove()), n.selectedFiles -= l.length), t.remove();
                    }
                };
                o.showPreview && null != d && "image" == d.type.toLowerCase().split("/").shift() && u(d, s.preview), 
                o.autoSubmit ? (t.ajaxForm(h), x.push(t), r()) : (o.showCancel && (s.cancel.show(), 
                s.cancel.click(function() {
                    x.splice(x.indexOf(t), 1), p(n, l), t.remove(), s.statusbar.remove(), o.onCancel.call(n, l, s), 
                    n.selectedFiles -= l.length, c(o, n);
                })), t.ajaxForm(h));
            }
            var w = e.extend({
                url: "",
                method: "POST",
                enctype: "multipart/form-data",
                returnType: null,
                allowDuplicates: !0,
                duplicateStrict: !1,
                allowedTypes: "*",
                acceptFiles: "*",
                fileName: "file",
                formData: !1,
                dynamicFormData: !1,
                maxFileSize: -1,
                maxFileCount: -1,
                multiple: !0,
                dragDrop: !0,
                autoSubmit: !0,
                showCancel: !0,
                showAbort: !0,
                showDone: !1,
                showDelete: !1,
                showError: !0,
                showStatusAfterSuccess: !0,
                showStatusAfterError: !0,
                showFileCounter: !0,
                fileCounterStyle: "). ",
                showFileSize: !0,
                showProgress: !1,
                nestedForms: !0,
                showDownload: !1,
                onLoad: function(e) {},
                onSelect: function(e) {
                    return !0;
                },
                onSubmit: function(e, a) {},
                onSuccess: function(e, a, t, r) {},
                onError: function(e, a, t, r) {},
                onCancel: function(e, a) {},
                onAbort: function(e, a) {},
                downloadCallback: !1,
                deleteCallback: !1,
                afterUploadAll: !1,
                serialize: !0,
                sequential: !1,
                sequentialCount: 2,
                customProgressBar: !1,
                abortButtonClass: "ajax-file-upload-abort",
                cancelButtonClass: "ajax-file-upload-cancel",
                dragDropContainerClass: "ajax-upload-dragdrop",
                dragDropHoverClass: "state-hover",
                errorClass: "ajax-file-upload-error",
                uploadButtonClass: "ajax-file-upload",
                dragDropStr: "<span><b>Drag &amp; Drop Files</b></span>",
                uploadStr: "Upload",
                abortStr: "Abort",
                cancelStr: "Cancel",
                deletelStr: "Delete",
                doneStr: "Done",
                multiDragErrorStr: "Multiple File Drag &amp; Drop is not allowed.",
                extErrorStr: "is not allowed. Allowed extensions: ",
                duplicateErrorStr: "is not allowed. File already exists.",
                sizeErrorStr: "is not allowed. Allowed Max size: ",
                uploadErrorStr: "Upload is not allowed",
                maxFileCountErrorStr: " is not allowed. Maximum allowed files are:",
                downloadStr: "Download",
                customErrorKeyStr: "jquery-upload-file-error",
                showQueueDiv: !1,
                statusBarWidth: 400,
                dragdropWidth: 400,
                showPreview: !1,
                previewHeight: "auto",
                previewWidth: "100%",
                extraHTML: !1,
                uploadQueueOrder: "top"
            }, t);
            this.fileCounter = 1, this.selectedFiles = 0;
            var g = "ajax-file-upload-" + new Date().getTime();
            this.formGroup = g, this.errorLog = e("<div></div>"), this.responses = [], this.existingFileNames = [], 
            a.formdata || (w.dragDrop = !1), a.formdata || (w.multiple = !1), e(this).html("");
            var C = this, b = e("<div>" + w.uploadStr + "</div>");
            e(b).addClass(w.uploadButtonClass), function F() {
                if (e.fn.ajaxForm) {
                    if (w.dragDrop) {
                        var a = e('<div class="' + w.dragDropContainerClass + '" style="vertical-align:top;"></div>').width(w.dragdropWidth);
                        e(C).append(a), e(a).append(b), e(a).append(e(w.dragDropStr)), o(C, w, a);
                    } else e(C).append(b);
                    e(C).append(C.errorLog), w.showQueueDiv ? C.container = e("#" + w.showQueueDiv) : C.container = e("<div class='ajax-file-upload-container'></div>").insertAfter(e(C)), 
                    w.onLoad.call(this, C), h(C, g, w, b);
                } else window.setTimeout(F, 10);
            }(), this.startUpload = function() {
                e("form").each(function(a, t) {
                    e(this).hasClass(C.formGroup) && x.push(e(this));
                }), x.length >= 1 && r();
            }, this.getFileCount = function() {
                return C.selectedFiles;
            }, this.stopUpload = function() {
                e("." + w.abortButtonClass).each(function(a, t) {
                    e(this).hasClass(C.formGroup) && e(this).click();
                }), e("." + w.cancelButtonClass).each(function(a, t) {
                    e(this).hasClass(C.formGroup) && e(this).click();
                });
            }, this.cancelAll = function() {
                e("." + w.cancelButtonClass).each(function(a, t) {
                    e(this).hasClass(C.formGroup) && e(this).click();
                });
            }, this.update = function(a) {
                w = e.extend(w, a);
            }, this.reset = function(e) {
                C.fileCounter = 1, C.selectedFiles = 0, C.errorLog.html(""), 0 != e && C.container.html("");
            }, this.remove = function() {
                C.container.html(""), e(C).remove();
            }, this.createProgress = function(e, a, t) {
                var r = new m(this, w);
                r.progressDiv.show(), r.progressbar.width("100%");
                var o = "";
                return o = w.showFileCounter ? C.fileCounter + w.fileCounterStyle + e : e, w.showFileSize && (o += " (" + s(t) + ")"), 
                r.filename.html(o), C.fileCounter++, C.selectedFiles++, w.showPreview && (r.preview.attr("src", a), 
                r.preview.show()), w.showDownload && (r.download.show(), r.download.click(function() {
                    w.downloadCallback && w.downloadCallback.call(C, [ e ]);
                })), w.showDelete && (r.del.show(), r.del.click(function() {
                    r.statusbar.hide().remove();
                    var a = [ e ];
                    w.deleteCallback && w.deleteCallback.call(this, a, r), C.selectedFiles -= 1, c(w, C);
                })), r;
            }, this.getResponses = function() {
                return this.responses;
            };
            var x = [], D = [], S = !1;
            return this;
        };
    }(jQuery);





    /**
        load templates easily via data-template attribute
        any anchor or input with data-template
    **/
    /*  jQuery('.thunder-change','.thunder-bottom' ).on('click',function(){
        if (jQuery(this).data('template')) {
            form_data = jQuery(this).parents('.thunder').data();
            var id = jQuery('.thunder').length;
            shortcode = '[thunder id=' + id;
            jQuery.each( form_data, function(key, value) {
                shortcode = shortcode + ' ' + key + '=' + '"' + value + '"';
            });
            shortcode = shortcode + ']';
            shortcode = shortcode.replace(/(template=)"(.*?)"/, 'template="' + jQuery(this).data('template') + '"');
            if (jQuery(this).data('up_username')) {
                up_username = jQuery(this).data('up_username');
            } else {
                up_username = 0;
            }
            if (jQuery(this).data('force_redirect_uri')) {
                force_redirect_uri = jQuery(this).data('force_redirect_uri');
            } else {
                force_redirect_uri = 0;
            }
            post_id = jQuery(this).parents('.thunder').data('post_id');
            thunder_shortcode_template( 'update', jQuery(this).parents('.thunder'), shortcode, up_username, force_redirect_uri, post_id);
        }
    });*/
    /*    jQuery("a,input").live('click',function(){
        if (jQuery(this).data('template')) {
            form_data = jQuery(this).parents('.userpro').data();
            var id = jQuery('.userpro').length;
            shortcode = '[userpro id=' + id;
            jQuery.each( form_data, function(key, value) {
                shortcode = shortcode + ' ' + key + '=' + '"' + value + '"';
            });
            shortcode = shortcode + ']';
            shortcode = shortcode.replace(/(template=)"(.*?)"/, 'template="' + jQuery(this).data('template') + '"');
            if (jQuery(this).data('up_username')) {
                up_username = jQuery(this).data('up_username');
            } else {
                up_username = 0;
            }
            if (jQuery(this).data('force_redirect_uri')) {
                force_redirect_uri = jQuery(this).data('force_redirect_uri');
            } else {
                force_redirect_uri = 0;
            }
            post_id = jQuery(this).parents('.userpro').data('post_id');
            userpro_shortcode_template( 'update', jQuery(this).parents('.userpro'), shortcode, up_username, force_redirect_uri, post_id);
        }
    });*/
    jQuery(document).on("click touchstart", "input.thunder-secondary", function(e) {
        console.log("thunder-secondary");
        // form data and shortcode
        if (jQuery(this).data("tpl")) {
            container = jQuery(this).parents(".thunder");
            form_data = jQuery(this).data();
            //register  
            shortcode = "[thunder";
            /*jQuery.each( form_data, function(key, value) {
            shortcode = shortcode + ' ' + key + '=' + '"' + value + '"';
        });*/
            jQuery.each(form_data, function(key, value) {
                shortcode = shortcode + " " + key + "=" + '"' + value + '"';
            });
            shortcode = shortcode + "]";
            /* shortcode = shortcode.replace(/(template=)"(.*?)"/, 'template="' + jQuery(this).data('template') + '"');
            if (jQuery(this).data('up_username')) {
                up_username = jQuery(this).data('up_username');
            } else {
                up_username = 0;
            }*/
            /*if (jQuery(this).data('force_redirect_uri')) {
                force_redirect_uri = jQuery(this).data('force_redirect_uri');
            } else {
                force_redirect_uri = 0;
            }*/
            //post_id = jQuery(this).parents('.thunder').data('post_id');
            //post_id = 1;
            console.log(shortcode);
            str = "action=thunder_shortcode_template&tpl=" + shortcode;
            /* if (my_username) {
        str = str + '&my_username='+my_username;
    }*/
            /*if (post_id) {
        str = str + '&post_id='+post_id;
    }*/
            /*if (force_redirect_uri){
        str = str + '&force_redirect_uri=1';
    }*/
            /*  if (container.find('form').length > 0){
        var form = container.find('form');
       // userpro_init_load( form );
    }*/
            jQuery.ajax({
                url: my_data.ajaxurl,
                data: str,
                dataType: "JSON",
                type: "POST",
                error: function(xhr, status, error) {
                    // userpro_end_load( form );
                    alert(error);
                },
                success: function(data) {
                    console.log(data);
                    jQuery(container).replaceWith(data.response);
                    thunder_collapse();
                }
            });
        }
    });
    /*********
********
************ collapse and maximize field groups
********
***********/
    function thunder_collapse() {
        /*jQuery('.thunder-section').each(function(){
        if (jQuery(this).next('div.thunder-field:not(.thunder-submit)').length == 0){
            jQuery(this).hide();
        } 
    });*/
        jQuery(".thunder-collapsible-1.thunder-collapsed-1").each(function() {
            jQuery(this).nextUntil(".thunder-collapsible-1").hide();
            if (jQuery(this).find("span").length == 0) jQuery(this).prepend('<span><i class="thunder-icon-angle-right"></i></span>');
        });
        jQuery(".thunder-collapsible-1.thunder-collapsed-0").each(function() {
            jQuery(this).nextUntil(".thunder-collapsible-1").show();
            if (jQuery(this).find("span").length == 0) jQuery(this).prepend('<span><i class="thunder-icon-angle-down"></i></span>');
        });
    }
    /**
    collapse / un-collapse work
    on field sections is done here
**/
    jQuery(document).on("click", ".thunder-collapsible-1", function() {
        //console.log('colaps');
        if (jQuery(this).nextUntil(".thunder-collapsible-1").is(":hidden")) {
            jQuery(this).nextUntil(".thunder-collapsible-1").show();
            jQuery(this).removeClass("thunder-collapsed-1").addClass("thunder-collapsed-0");
            jQuery(this).find("span").html('<i class="thunder-icon-angle-down"></i>');
        } else {
            jQuery(this).nextUntil(".thunder-collapsible-1").hide();
            jQuery(this).find("span").html('<i class="thunder-icon-angle-right"></i>');
            jQuery(this).removeClass("thunder-collapsed-0").addClass("thunder-collapsed-1");
        }
    });
    /*    jQuery('.thunder-collapsible-1').on('click',function(){
        if (jQuery(this).nextUntil('div.thunder-column').is(':hidden')){
            jQuery(this).nextUntil('div.thunder-column').show();
            jQuery(this).removeClass('thunder-collapsed-1').addClass('thunder-collapsed-0');
            jQuery(this).find('span').html('<i class="thunder-icon-angle-down"></i>');
        
            if (jQuery(this).parents('.thunder').data('keep_one_section_open') == 1){
            jQuery('.thunder-collapsible-1.thunder-collapsed-0').not(this).nextUntil('div.thunder-column').hide();
            jQuery('.thunder-collapsible-1.thunder-collapsed-0').not(this).find('span').html('<i class="thunder-icon-angle-right"></i>');
            jQuery('.thunder-collapsible-1.thunder-collapsed-0').not(this).removeClass('thunder-collapsed-0').addClass('thunder-collapsed-1');
            }
        
        } else {
            jQuery(this).nextUntil('div.thunder-column').hide();
            jQuery(this).find('span').html('<i class="thunder-icon-angle-right"></i>');
            jQuery(this).removeClass('thunder-collapsed-0').addClass('thunder-collapsed-1');
        }
        userpro_overlay_center('.userpro-overlay-inner');
    });*/
    function password_strength(password) {
        var form = jQuery(".thunder").find("form");
        var meter = jQuery(".thunder").find(".thunder-field[data-key^='passwordstrength']");
        var meter_data = meter.find("span.thunder-strength-lvl").data();
        //var firstpass = form.find('input[type=password]:first').val();
        var desc = new Array();
        desc[0] = meter_data["very_weak"];
        desc[1] = meter_data["weak"];
        desc[2] = meter_data["better"];
        desc[3] = meter_data["medium"];
        desc[4] = meter_data["strong"];
        desc[5] = meter_data["strongest"];
        var score = 0;
        var low = meter_data["to_low"] ? meter_data["to_low"] : "Password too short";
        //if password bigger than 6 give 1 point
        if (password.length > 6) {
            score++;
        }
        //if password has both lower and uppercase characters give 1 point  
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) score++;
        //if password has at least one number give 1 point
        if (password.match(/\d+/)) score++;
        //if password has at least one special caracther give 1 point
        if (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) score++;
        //if password bigger than 12 give another 1 point
        if (password.length > 12) score++;
        if (password.length >= 6) {
            form.find(".password-description").html(desc[score]);
            //var def = "strength" + score;
            form.find("#password-strength").attr("class", "strength" + score);
        } else {
            form.find(".password-description").html(low);
            form.find("#password-strength").attr("class", "strength0");
        }
    }
    //Verified password when you start typing
    jQuery(document).on("keyup keydown", ".thunder input[type=password]", function() {
        var meter = jQuery(".thunder-strength-lvl");
        if (meter.length > 0) {
            password_strength(jQuery(this).val());
        }
    });
    //Remove warning message when its exists before submit
    jQuery(document).on("click tap", ".thunder input", function() {
        var elem = jQuery(this).parents(".thunder-input");
        var warn = elem.find(".thunder-warning");
        if (warn.length > 0) {
            warn.remove();
        }
    });
    //  custom radio buttons    
    jQuery(document).on("click", ".thunder input[type=radio]", function() {
        console.log("input radio");
        var radio = jQuery(this);
        var field = radio.parents(".thunder-input");
        var form = radio.closest("form");
        field.find("span").removeClass("checked");
        jQuery(this).parents("label").find("span").addClass("checked");
        if (form.find("[data-key=ava_picture]").find("input:hidden").val() == "") {
            form.find("img").attr("src", function(i, val) {
                return val.replace(/(default_avatar)(.+jpg)/gi, "default_avatar_" + radio.val().toLowerCase() + ".jpg");
            });
        }
    });
    //custom checkbox buttons  
    jQuery(document).on("change", ".thunder input[type=checkbox]", function() {
        console.log("input checkbox");
        jQuery(this).parents("label").find("span").toggleClass("checked");
    });
    // auto change avatar based on gender
    /*ui.item.find( 'input, select, textarea' ).attr( 'name', function(i,val){ 
                return val.replace(/(^[a-z]+)-/i, zone+'-');
            });*/
    /* jQuery( document ).on('change', 'input[name^="gender"]', function(){
        var form = jQuery(this).closest('form');
        if (form.find("[data-key=ava_picture]").find('input:hidden').val()==''){
            console.log('change');
            form.find('img').attr('src', function(i,val){ 
                return val.replace(/(default_avatar)(.+jpg)/gi, 'default_avatar_'+jQuery(this).val().toLowerCase() );
            });
           // form.find("[data-key=ava_picture]").find('img').attr('src', jQuery(this).parents('.thunder').data('default_avatar_'+jQuery(this).val().toLowerCase() ));
        }
    });*/
    jQuery(document).on("blur", ".thunder input", function() {
        var element = jQuery(this);
        var parent = element.parents(".thunder-input");
        var required = element.data("required");
        var requiredtxt = element.closest("form").data("required_field");
        // var ajaxcheck = element.data('ajaxcheck');
        //var original_elem = element.parents('.thunder').find('input[type=password]:first');
        var firstpass = element.parents(".thunder").find("input[type=password]:first").val();
        if (required == 1) {
            if (element.val().replace(/^\s+|\s+$/g, "").length == 0) {
                thunder_client_error(element, parent, requiredtxt);
            } else {
                thunder_client_valid(element, parent);
            }
            if (element.attr("type") == "password") {
                if (element.attr("type") == "password") {
                    if (element.val().replace(/^\s+|\s+$/g, "").length == 0) {
                        thunder_client_error(element, parent, requiredtxt);
                    } else if (element.val().length <= 5) {
                        thunder_client_error(element, parent, "password to short");
                    } else if (firstpass != element.val()) {
                        thunder_client_error(element, parent, "passwords do not match");
                    } else {
                        thunder_client_valid(element, parent);
                    }
                }
            }
        } else {
            if (element.attr("type") == "password") {
                if (element.val().replace(/^\s+|\s+$/g, "").length == 0) {
                    thunder_client_error(element, parent, requiredtxt);
                } else if (element.val().length <= 5) {
                    thunder_client_error(element, parent, "password to short");
                } else if (firstpass != element.val()) {
                    thunder_client_error(element, parent, "passwords do not match");
                } else {
                    thunder_client_valid(element, parent);
                }
            }
        }
    });
    /**
        select dropdowns live change
        validation to which fields are
        required
    **/
    jQuery(document).on("change", ".thunder select", function() {
        var element = jQuery(this);
        var parent = element.parents(".thunder-input");
        var required = element.data("required");
        var requiredtxt = element.closest("form").data("required_field");
        if (required == 1) {
            if (element.val() == 0) {
                thunder_client_error(element, parent, requiredtxt);
            } else {
                thunder_client_valid(element, parent);
            }
        }
    });
    //!!!
    //!!
    //!!! Вызываются два событи на мобильном !
    //jQuery('.thunder form:not(.thunder-search-form)').on('.lsubmit',function(e)
    jQuery(document).on("click touchstart", ".thunder-login-sub", function(e) {
        console.log("thunder-login-sub");
        e.preventDefault();
        var form = jQuery(this).closest("form");
        var requiredtxt = form.data("required_field");
        form.find("input").each(function() {
            jQuery(this).trigger("blur");
        });
        form.find("select").each(function() {
            jQuery(this).trigger("change");
        });
        form.find(".thunder-radio[data-required=1]").each(function() {
            if (jQuery(this).find("input:radio").is(":checked")) {
                console.log("checked");
                thunder_client_valid(jQuery(this).find("input:radio"), jQuery(this).parents(".thunder-input"));
            } else {
                console.log("not checked");
                thunder_client_error_irregular(jQuery(this).closest(".thunder-input"), requiredtxt);
            }
        });
        // Done
        /*else {
        
            thunder_clear_form( form );
        
        }*/
        // start load
        //thunder_init_load( form );
        // form data and shortcode
        var check = form.find(".thunder-warning");
        console.log(check);
        console.log(check.length);
        if (check.length == 0) {
            form_data = jQuery(this).closest("form").data();
            var templ = jQuery(this).closest("form").data("tpl");
            // thunder-warning error
            var shortcode = "[thunder";
            shortcode = shortcode + " tpl=" + '"' + templ + '"';
            shortcode = shortcode + "]";
            // username
            if (jQuery(this).parents(".thunder").find(".thunder-profile-img a").data("usr")) {
                usr = jQuery(this).parents(".thunder").find(".thunder-profile-img a").data("usr");
            } else {
                usr = 0;
            }
            //data: form.serialize() + "&action=thunder_process_form&form_data="+form_data['tpl']+"&tpl="+shortcode+"&usr="+usr,
            console.log("op");
            console.log(form_data);
            console.log(shortcode);
            console.log(usr);
            jQuery.ajax({
                url: my_data.ajaxurl,
                data: form.serialize() + "&action=thunder_process_form&form_data=" + form_data["tpl"] + "&tpl=" + shortcode + "&usr=" + usr,
                dataType: "JSON",
                type: "POST",
                error: function(xhr, status, error) {
                    //thunder_end_load( form );
                    alert(error);
                },
                success: function(data) {
                    //console.log(data.redirect_uri);
                    //console.log(data.error);
                    //console.log(data.custom_message);
                    //console.log(data.tpl);
                    //thunder_end_load( form );
                    form.find(".thunder-warning").remove();
                    /// server-side error 
                    if (data.error) {
                        var i = 0;
                        var key = null;
                        jQuery.each(data.error, function(key, value) {
                            i++;
                            //key = k.replace(/-[0-9]*/i, '');
                            console.log(key);
                            console.log(form.find('input[name*="' + key + '"]'));
                            element = form.find('input[name*="' + key + '"]');
                            parent = element.parents(".thunder-input");
                            parent.find(".warning-ok").remove();
                            // remove previous required valid check
                            if (element) {
                                if (i == 1) element.focus();
                                thunder_client_error(element, parent, value);
                            }
                        });
                    }
                    /// custom message 
                    if (data.custom_message) {
                        form.find(".warning-ok").remove();
                        // remove previous required valid check
                        form.parents(".thunder").find("." + templ + "-thunder-body").find(".thunder-message").remove();
                        var pos = form.parents(".thunder").find("." + templ + "-thunder-body").prepend(data.custom_message);
                        var offset = jQuery(pos).offset();
                        jQuery("html, body").animate({
                            scrollTop: offset.top,
                            scrollLeft: offset.left
                        });
                        jQuery(".thunder-message").fadeOut(9e3);
                    }
                    /// redirect after form 
                    if (data.redirect_uri) {
                        if (data.redirect_uri == "refresh") {
                            document.location.href = jQuery(location).attr("href");
                        } else {
                            document.location.href = data.redirect_uri;
                        }
                    }
                    // display template 
                    if (data.tpl) {
                        jQuery(".thunder").replaceWith(data.tpl);
                    }
                }
            });
        } else {
            jQuery(".thunder-collapsible-1").each(function() {
                jQuery(this).nextUntil(".thunder-collapsible-1").show();
                if (jQuery(this).find("span").length > 0) {
                    jQuery(this).find("span").remove();
                    jQuery(this).prepend('<span><i class="thunder-icon-angle-down"></i></span>');
                }
            });
            return false;
        }
    });
    /*jQuery('.thunder[data-template=register] input, .thunder[data-template=edit] input,  .thunder[data-template=change] input').live('blur'){
    thunder_clear_input()
}*/
    function thunder_clear_input(element) {
        element.parents(".thunder-input").find(".thunder-warning").remove();
        element.removeClass("warning");
    }
    /*********
********
************ init loading on shortcode
********
***********/
    /*function thunder_init_load(form) {
    //form.parents('.thunder').find('.thunder-message-ajax').hide();
    form.find('input[type=submit],input[type=button]').attr('disabled','disabled');
    form.parents('.thunder').find('img.thunder-loading').show().addClass('inline');
}*/
    /*********
********
************ end loading on shortcode
********
***********/
    function thunder_end_load(form) {
        form.find("input[type=submit],input[type=button]").removeAttr("disabled");
        form.find("img.thunder-loading").hide();
        form.parents(".thunder").find("img.thunder-loading").hide().removeClass("inline");
    }
    function thunder_client_error(element, parent, error) {
        //if ( element.attr('type') )   
        //parent.find('.icon-ok').remove();
        parent.find(".warning-ok").remove();
        if (parent.find(".thunder-warning").length == 0) {
            //element.addClass('warning').removeClass('ok');
            element.after('<div class="thunder-warning"><i class="thunder-icon-caret-up"></i><p>' + error + "</p></div>");
            parent.find(".thunder-warning").animate({
                top: "0px",
                opacity: "1"
            });
        }
    }
    /*********
********
************ return a valid field callback
********
***********/
    function thunder_client_valid(element, parent) {
        if (element.attr("type")) {
            if (element.attr("type") == "radio" || element.attr("type") == "checkbox") {
                parent.find(".thunder-warning").remove();
            } else {
                parent.find(".thunder-warning").remove();
                // element.removeClass('warning').addClass('ok');
                if (parent.find(".warning-ok").length == 0) {
                    if (element.val() != "") {
                        parent.append('<div class="warning-ok"><i class="thunder-warning-ok"></i></div>');
                    } else {
                        parent.find(".warning-ok").remove();
                    }
                }
            }
        } else {
            parent.find(".thunder-warning").remove();
        }
    }
    /*********
********
************ return an error to client side / radio
********
***********/
    function thunder_client_error_irregular(parent, error) {
        if (parent.find(".thunder-warning").length == 0) {
            parent.append('<div class="thunder-warning"><i class="thunder-icon-caret-up"></i><p>' + error + "</p></div>");
            parent.find(".thunder-warning").animate({
                top: "0px",
                opacity: "1"
            });
        }
    }
    jQuery(document).on("click", ".thunder-pic-upload", function() {
        var allowed = jQuery(this).data("allowed_extensions");
        var filetype = jQuery(this).data("filetype");
        jQuery(this).uploadFile({
            url: my_data.upload,
            allowedTypes: allowed,
            fileName: "thunder_file",
            dragDrop: false,
            multiple: false,
            onSubmit: function(files) {
                var statusbar = jQuery(".ajax-file-upload-statusbar:visible");
                statusbar.parents(".thunder-input").find(".red").hide();
                if (statusbar.parents(".thunder-input").find("img.default").length) {
                    statusbar.parents(".thunder-input").find("img.default").show();
                    statusbar.parents(".thunder-input").find("img.modified").remove();
                }
            },
            onSuccess: function(files, data, xhr) {
                console.log(data);
                data = jQuery.parseJSON(data);
                var statusbar = jQuery(".ajax-file-upload-statusbar:visible");
                var src = data.target_file_uri;
                if (statusbar.parents(".thunder-input").find("img.default").length) {
                    var width = statusbar.parents(".thunder-input").find("img.default").attr("width");
                    var height = statusbar.parents(".thunder-input").find("img.default").attr("height");
                } else if (statusbar.parents(".thunder-input").find("img.modified").length) {
                    var width = statusbar.parents(".thunder-input").find("img.modified").attr("width");
                    var height = statusbar.parents(".thunder-input").find("img.modified").attr("height");
                } else if (statusbar.parents(".thunder-input").find("img.avatar").length) {
                    var width = statusbar.parents(".thunder-input").find("img.avatar").attr("width");
                    var height = statusbar.parents(".thunder-input").find("img.avatar").attr("height");
                }
            }
        });
    });
});