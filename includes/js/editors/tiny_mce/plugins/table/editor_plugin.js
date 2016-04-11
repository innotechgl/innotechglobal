(function (b) {
    var c = b.each;

    function a(F, E, I) {
        var e, J, B, n;
        r();
        n = E.getParent(I.getStart(), "th,td");
        if (n) {
            J = D(n);
            B = G();
            n = v(J.x, J.y)
        }
        function w(L, K) {
            L = L.cloneNode(K);
            L.removeAttribute("id");
            return L
        }

        function r() {
            var K = 0;
            e = [];
            c(["thead", "tbody", "tfoot"], function (L) {
                var M = E.select(L + " tr", F);
                c(M, function (N, O) {
                    O += K;
                    c(E.select("td,th", N), function (U, P) {
                        var Q, R, S, T;
                        if (e[O]) {
                            while (e[O][P]) {
                                P++
                            }
                        }
                        S = g(U, "rowspan");
                        T = g(U, "colspan");
                        for (R = O; R < O + S; R++) {
                            if (!e[R]) {
                                e[R] = []
                            }
                            for (Q = P; Q < P + T; Q++) {
                                e[R][Q] = {part: L, real: R == O && Q == P, elm: U, rowspan: S, colspan: T}
                            }
                        }
                    })
                });
                K += M.length
            })
        }

        function v(K, M) {
            var L;
            L = e[M];
            if (L) {
                return L[K]
            }
        }

        function g(L, K) {
            return parseInt(L.getAttribute(K) || 1)
        }

        function h(K) {
            return E.hasClass(K.elm, "mceSelected") || K == n
        }

        function j() {
            var K = [];
            c(F.rows, function (L) {
                c(L.cells, function (M) {
                    if (E.hasClass(M, "mceSelected") || M == n.elm) {
                        K.push(L);
                        return false
                    }
                })
            });
            return K
        }

        function q() {
            var K = E.createRng();
            K.setStartAfter(F);
            K.setEndAfter(F);
            I.setRng(K);
            E.remove(F)
        }

        function d(K) {
            var L;
            b.walk(K, function (N) {
                var M;
                if (N.nodeType == 3) {
                    c(E.getParents(N.parentNode, null, K).reverse(), function (O) {
                        O = w(O, false);
                        if (!L) {
                            L = M = O
                        } else {
                            if (M) {
                                M.appendChild(O)
                            }
                        }
                        M = O
                    });
                    if (M) {
                        M.innerHTML = b.isIE ? "&nbsp;" : '<br _mce_bogus="1" />'
                    }
                    return false
                }
            }, "childNodes");
            K = w(K, false);
            K.rowSpan = K.colSpan = 1;
            if (L) {
                K.appendChild(L)
            } else {
                if (!b.isIE) {
                    K.innerHTML = '<br _mce_bogus="1" />'
                }
            }
            return K
        }

        function p() {
            var K = E.createRng();
            c(E.select("tr", F), function (L) {
                if (L.cells.length == 0) {
                    E.remove(L)
                }
            });
            if (E.select("tr", F).length == 0) {
                K.setStartAfter(F);
                K.setEndAfter(F);
                I.setRng(K);
                E.remove(F);
                return
            }
            c(E.select("thead,tbody,tfoot", F), function (L) {
                if (L.rows.length == 0) {
                    E.remove(L)
                }
            });
            r();
            row = e[Math.min(e.length - 1, J.y)];
            if (row) {
                I.select(row[Math.min(row.length - 1, J.x)].elm, true);
                I.collapse(true)
            }
        }

        function s(Q, O, S, P) {
            var N, L, K, M, R;
            N = e[O][Q].elm.parentNode;
            for (K = 1; K <= S; K++) {
                N = E.getNext(N, "tr");
                if (N) {
                    for (L = Q; L >= 0; L--) {
                        R = e[O + K][L].elm;
                        if (R.parentNode == N) {
                            for (M = 1; M <= P; M++) {
                                E.insertAfter(d(R), R)
                            }
                            break
                        }
                    }
                    if (L == -1) {
                        for (M = 1; M <= P; M++) {
                            N.insertBefore(d(N.cells[0]), N.cells[0])
                        }
                    }
                }
            }
        }

        function A() {
            c(e, function (K, L) {
                c(K, function (N, M) {
                    var Q, P, R, O;
                    if (h(N)) {
                        N = N.elm;
                        Q = g(N, "colspan");
                        P = g(N, "rowspan");
                        if (Q > 1 || P > 1) {
                            N.colSpan = N.rowSpan = 1;
                            for (O = 0; O < Q - 1; O++) {
                                E.insertAfter(d(N), N)
                            }
                            s(M, L, P - 1, Q)
                        }
                    }
                })
            })
        }

        function o(S, P, V) {
            var N, M, U, T, R, O, Q, K, S, L;
            if (S) {
                pos = D(S);
                N = pos.x;
                M = pos.y;
                U = N + (P - 1);
                T = M + (V - 1)
            } else {
                N = J.x;
                M = J.y;
                U = B.x;
                T = B.y
            }
            Q = v(N, M);
            K = v(U, T);
            if (Q && K && Q.part == K.part) {
                A();
                r();
                Q = v(N, M).elm;
                Q.colSpan = (U - N) + 1;
                Q.rowSpan = (T - M) + 1;
                for (O = M; O <= T; O++) {
                    for (R = N; R <= U; R++) {
                        S = e[O][R].elm;
                        if (S != Q) {
                            L = b.grep(S.childNodes);
                            c(L, function (X, W) {
                                if (X.nodeName != "BR" || W != L.length - 1) {
                                    Q.appendChild(X)
                                }
                            });
                            E.remove(S)
                        }
                    }
                }
                p()
            }
        }

        function k(N) {
            var K, P, M, O, Q, R, L, S;
            c(e, function (T, U) {
                c(T, function (W, V) {
                    if (h(W)) {
                        W = W.elm;
                        Q = W.parentNode;
                        R = w(Q, false);
                        K = U;
                        if (N) {
                            return false
                        }
                    }
                });
                if (N) {
                    return !K
                }
            });
            for (O = 0; O < e[0].length; O++) {
                P = e[K][O].elm;
                if (P != M) {
                    if (!N) {
                        rowSpan = g(P, "rowspan");
                        if (rowSpan > 1) {
                            P.rowSpan = rowSpan + 1;
                            continue
                        }
                    } else {
                        if (K > 0 && e[K - 1][O]) {
                            S = e[K - 1][O].elm;
                            rowSpan = g(S, "rowspan");
                            if (rowSpan > 1) {
                                S.rowSpan = rowSpan + 1;
                                continue
                            }
                        }
                    }
                    L = d(P);
                    L.colSpan = P.colSpan;
                    R.appendChild(L);
                    M = P
                }
            }
            if (R.hasChildNodes()) {
                if (!N) {
                    E.insertAfter(R, Q)
                } else {
                    Q.parentNode.insertBefore(R, Q)
                }
            }
        }

        function f(L) {
            var M, K;
            c(e, function (N, O) {
                c(N, function (Q, P) {
                    if (h(Q)) {
                        M = P;
                        if (L) {
                            return false
                        }
                    }
                });
                if (L) {
                    return !M
                }
            });
            c(e, function (Q, R) {
                var N = Q[M].elm, O, P;
                if (N != K) {
                    P = g(N, "colspan");
                    O = g(N, "rowspan");
                    if (P == 1) {
                        if (!L) {
                            E.insertAfter(d(N), N);
                            s(M, R, O - 1, P)
                        } else {
                            N.parentNode.insertBefore(d(N), N);
                            s(M, R, O - 1, P)
                        }
                    } else {
                        N.colSpan++
                    }
                    K = N
                }
            })
        }

        function m() {
            var K = [];
            c(e, function (L, M) {
                c(L, function (O, N) {
                    if (h(O) && b.inArray(K, N) === -1) {
                        c(e, function (R) {
                            var P = R[N].elm, Q;
                            Q = g(P, "colspan");
                            if (Q > 1) {
                                P.colSpan = Q - 1
                            } else {
                                E.remove(P)
                            }
                        });
                        K.push(N)
                    }
                })
            });
            p()
        }

        function l() {
            var L;

            function K(O) {
                var N, P, M;
                N = E.getNext(O, "tr");
                c(O.cells, function (Q) {
                    var R = g(Q, "rowspan");
                    if (R > 1) {
                        Q.rowSpan = R - 1;
                        P = D(Q);
                        s(P.x, P.y, 1, 1)
                    }
                });
                P = D(O.cells[0]);
                c(e[P.y], function (Q) {
                    var R;
                    Q = Q.elm;
                    if (Q != M) {
                        R = g(Q, "rowspan");
                        if (R <= 1) {
                            E.remove(Q)
                        } else {
                            Q.rowSpan = R - 1
                        }
                        M = Q
                    }
                })
            }

            L = j();
            c(L.reverse(), function (M) {
                K(M)
            });
            p()
        }

        function C() {
            var K = j();
            E.remove(K);
            p();
            return K
        }

        function H() {
            var K = j();
            c(K, function (M, L) {
                K[L] = w(M, true)
            });
            return K
        }

        function z(M, L) {
            var N = j(), K = N[L ? 0 : N.length - 1], O = K.cells.length;
            c(e, function (Q) {
                var P;
                O = 0;
                c(Q, function (S, R) {
                    if (S.real) {
                        O += S.colspan
                    }
                    if (S.elm.parentNode == K) {
                        P = 1
                    }
                });
                if (P) {
                    return false
                }
            });
            if (!L) {
                M.reverse()
            }
            c(M, function (R) {
                var Q = R.cells.length, P;
                for (i = 0; i < Q; i++) {
                    P = R.cells[i];
                    P.colSpan = P.rowSpan = 1
                }
                for (i = Q; i < O; i++) {
                    R.appendChild(d(R.cells[Q - 1]))
                }
                for (i = O; i < Q; i++) {
                    E.remove(R.cells[i])
                }
                if (L) {
                    K.parentNode.insertBefore(R, K)
                } else {
                    E.insertAfter(R, K)
                }
            })
        }

        function D(K) {
            var L;
            c(e, function (M, N) {
                c(M, function (P, O) {
                    if (P.elm == K) {
                        L = {x: O, y: N};
                        return false
                    }
                });
                return !L
            });
            return L
        }

        function u(K) {
            J = D(K)
        }

        function G() {
            var M, L, K;
            L = K = 0;
            c(e, function (N, O) {
                c(N, function (Q, P) {
                    var S, R;
                    if (h(Q)) {
                        Q = e[O][P];
                        if (P > L) {
                            L = P
                        }
                        if (O > K) {
                            K = O
                        }
                        if (Q.real) {
                            S = Q.colspan - 1;
                            R = Q.rowspan - 1;
                            if (S) {
                                if (P + S > L) {
                                    L = P + S
                                }
                            }
                            if (R) {
                                if (O + R > K) {
                                    K = O + R
                                }
                            }
                        }
                    }
                })
            });
            return {x: L, y: K}
        }

        function t(Q) {
            var N, M, S, R, L, K, O, P;
            B = D(Q);
            if (J && B) {
                N = Math.min(J.x, B.x);
                M = Math.min(J.y, B.y);
                S = Math.max(J.x, B.x);
                R = Math.max(J.y, B.y);
                L = S;
                K = R;
                for (y = M; y <= K; y++) {
                    Q = e[y][N];
                    if (!Q.real) {
                        if (N - (Q.colspan - 1) < N) {
                            N -= Q.colspan - 1
                        }
                    }
                }
                for (x = N; x <= L; x++) {
                    Q = e[M][x];
                    if (!Q.real) {
                        if (M - (Q.rowspan - 1) < M) {
                            M -= Q.rowspan - 1
                        }
                    }
                }
                for (y = M; y <= R; y++) {
                    for (x = N; x <= S; x++) {
                        Q = e[y][x];
                        if (Q.real) {
                            O = Q.colspan - 1;
                            P = Q.rowspan - 1;
                            if (O) {
                                if (x + O > L) {
                                    L = x + O
                                }
                            }
                            if (P) {
                                if (y + P > K) {
                                    K = y + P
                                }
                            }
                        }
                    }
                }
                E.removeClass(E.select("td.mceSelected,th.mceSelected"), "mceSelected");
                for (y = M; y <= K; y++) {
                    for (x = N; x <= L; x++) {
                        E.addClass(e[y][x].elm, "mceSelected")
                    }
                }
            }
        }

        b.extend(this, {
            deleteTable: q,
            split: A,
            merge: o,
            insertRow: k,
            insertCol: f,
            deleteCols: m,
            deleteRows: l,
            cutRows: C,
            copyRows: H,
            pasteRows: z,
            getPos: D,
            setStartCell: u,
            setEndCell: t
        })
    }

    b.create("tinymce.plugins.TablePlugin", {
        init: function (e, f) {
            var d, j;

            function h(m) {
                var l = e.selection, k = e.dom.getParent(m || l.getNode(), "table");
                if (k) {
                    return new a(k, e.dom, l)
                }
            }

            function g() {
                e.getBody().style.webkitUserSelect = "";
                e.dom.removeClass(e.dom.select("td.mceSelected,th.mceSelected"), "mceSelected")
            }

            c([["table", "table.desc", "mceInsertTable", true], ["delete_table", "table.del", "mceTableDelete"], ["delete_col", "table.delete_col_desc", "mceTableDeleteCol"], ["delete_row", "table.delete_row_desc", "mceTableDeleteRow"], ["col_after", "table.col_after_desc", "mceTableInsertColAfter"], ["col_before", "table.col_before_desc", "mceTableInsertColBefore"], ["row_after", "table.row_after_desc", "mceTableInsertRowAfter"], ["row_before", "table.row_before_desc", "mceTableInsertRowBefore"], ["row_props", "table.row_desc", "mceTableRowProps", true], ["cell_props", "table.cell_desc", "mceTableCellProps", true], ["split_cells", "table.split_cells_desc", "mceTableSplitCells", true], ["merge_cells", "table.merge_cells_desc", "mceTableMergeCells", true]], function (k) {
                e.addButton(k[0], {title: k[1], cmd: k[2], ui: k[3]})
            });
            if (!b.isIE) {
                e.onClick.add(function (k, l) {
                    l = l.target;
                    if (l.nodeName === "TABLE") {
                        k.selection.select(l)
                    }
                })
            }
            e.onNodeChange.add(function (l, k, o) {
                var m;
                o = l.selection.getStart();
                m = l.dom.getParent(o, "td,th,caption");
                k.setActive("table", o.nodeName === "TABLE" || !!m);
                if (m && m.nodeName === "CAPTION") {
                    m = 0
                }
                k.setDisabled("delete_table", !m);
                k.setDisabled("delete_col", !m);
                k.setDisabled("delete_table", !m);
                k.setDisabled("delete_row", !m);
                k.setDisabled("col_after", !m);
                k.setDisabled("col_before", !m);
                k.setDisabled("row_after", !m);
                k.setDisabled("row_before", !m);
                k.setDisabled("row_props", !m);
                k.setDisabled("cell_props", !m);
                k.setDisabled("split_cells", !m);
                k.setDisabled("merge_cells", !m)
            });
            e.onInit.add(function (l) {
                var k, o, p = l.dom, m;
                d = l.windowManager;
                l.onMouseDown.add(function (q, r) {
                    if (r.button != 2) {
                        g();
                        o = p.getParent(r.target, "td,th");
                        k = p.getParent(o, "table")
                    }
                });
                p.bind(l.getDoc(), "mouseover", function (t) {
                    var r, q, s = t.target;
                    if (o && (m || s != o) && (s.nodeName == "TD" || s.nodeName == "TH")) {
                        q = p.getParent(s, "table");
                        if (q == k) {
                            if (!m) {
                                m = h(q);
                                m.setStartCell(o);
                                l.getBody().style.webkitUserSelect = "none"
                            }
                            m.setEndCell(s)
                        }
                        r = l.selection.getSel();
                        if (r.removeAllRanges) {
                            r.removeAllRanges()
                        } else {
                            r.empty()
                        }
                        t.preventDefault()
                    }
                });
                l.onMouseUp.add(function (z, A) {
                    var r, t = z.selection, B, C = t.getSel(), q, u, s, w;
                    if (o) {
                        if (m) {
                            z.getBody().style.webkitUserSelect = ""
                        }
                        function v(D, F) {
                            var E = new b.dom.TreeWalker(D, D);
                            do {
                                if (D.nodeType == 3 && b.trim(D.nodeValue).length != 0) {
                                    if (F) {
                                        r.setStart(D, 0)
                                    } else {
                                        r.setEnd(D, D.nodeValue.length)
                                    }
                                    return
                                }
                                if (D.nodeName == "BR") {
                                    if (F) {
                                        r.setStartBefore(D)
                                    } else {
                                        r.setEndBefore(D)
                                    }
                                    return
                                }
                            } while (D = (F ? E.next() : E.prev()))
                        }

                        B = p.select("td.mceSelected,th.mceSelected");
                        if (B.length > 0) {
                            r = p.createRng();
                            u = B[0];
                            w = B[B.length - 1];
                            v(u, 1);
                            q = new b.dom.TreeWalker(u, p.getParent(B[0], "table"));
                            do {
                                if (u.nodeName == "TD" || u.nodeName == "TH") {
                                    if (!p.hasClass(u, "mceSelected")) {
                                        break
                                    }
                                    s = u
                                }
                            } while (u = q.next());
                            v(s);
                            t.setRng(r)
                        }
                        z.nodeChanged();
                        o = m = k = null
                    }
                });
                l.onKeyUp.add(function (q, r) {
                    g()
                });
                if (l && l.plugins.contextmenu) {
                    l.plugins.contextmenu.onContextMenu.add(function (s, q, u) {
                        var v, t = l.selection, r = t.getNode() || l.getBody();
                        if (l.dom.getParent(u, "td") || l.dom.getParent(u, "th") || l.dom.select("td.mceSelected,th.mceSelected").length) {
                            q.removeAll();
                            if (r.nodeName == "A" && !l.dom.getAttrib(r, "name")) {
                                q.add({
                                    title: "advanced.link_desc",
                                    icon: "link",
                                    cmd: l.plugins.advlink ? "mceAdvLink" : "mceLink",
                                    ui: true
                                });
                                q.add({title: "advanced.unlink_desc", icon: "unlink", cmd: "UnLink"});
                                q.addSeparator()
                            }
                            if (r.nodeName == "IMG" && r.className.indexOf("mceItem") == -1) {
                                q.add({
                                    title: "advanced.image_desc",
                                    icon: "image",
                                    cmd: l.plugins.advimage ? "mceAdvImage" : "mceImage",
                                    ui: true
                                });
                                q.addSeparator()
                            }
                            q.add({
                                title: "table.desc",
                                icon: "table",
                                cmd: "mceInsertTable",
                                value: {action: "insert"}
                            });
                            q.add({title: "table.props_desc", icon: "table_props", cmd: "mceInsertTable"});
                            q.add({title: "table.del", icon: "delete_table", cmd: "mceTableDelete"});
                            q.addSeparator();
                            v = q.addMenu({title: "table.cell"});
                            v.add({title: "table.cell_desc", icon: "cell_props", cmd: "mceTableCellProps"});
                            v.add({title: "table.split_cells_desc", icon: "split_cells", cmd: "mceTableSplitCells"});
                            v.add({title: "table.merge_cells_desc", icon: "merge_cells", cmd: "mceTableMergeCells"});
                            v = q.addMenu({title: "table.row"});
                            v.add({title: "table.row_desc", icon: "row_props", cmd: "mceTableRowProps"});
                            v.add({title: "table.row_before_desc", icon: "row_before", cmd: "mceTableInsertRowBefore"});
                            v.add({title: "table.row_after_desc", icon: "row_after", cmd: "mceTableInsertRowAfter"});
                            v.add({title: "table.delete_row_desc", icon: "delete_row", cmd: "mceTableDeleteRow"});
                            v.addSeparator();
                            v.add({title: "table.cut_row_desc", icon: "cut", cmd: "mceTableCutRow"});
                            v.add({title: "table.copy_row_desc", icon: "copy", cmd: "mceTableCopyRow"});
                            v.add({
                                title: "table.paste_row_before_desc",
                                icon: "paste",
                                cmd: "mceTablePasteRowBefore"
                            }).setDisabled(!j);
                            v.add({
                                title: "table.paste_row_after_desc",
                                icon: "paste",
                                cmd: "mceTablePasteRowAfter"
                            }).setDisabled(!j);
                            v = q.addMenu({title: "table.col"});
                            v.add({title: "table.col_before_desc", icon: "col_before", cmd: "mceTableInsertColBefore"});
                            v.add({title: "table.col_after_desc", icon: "col_after", cmd: "mceTableInsertColAfter"});
                            v.add({title: "table.delete_col_desc", icon: "delete_col", cmd: "mceTableDeleteCol"})
                        } else {
                            q.add({title: "table.desc", icon: "table", cmd: "mceInsertTable"})
                        }
                    })
                }
                if (!b.isIE) {
                    function n() {
                        var q;
                        for (q = l.getBody().lastChild; q && q.nodeType == 3 && !q.nodeValue.length; q = q.previousSibling) {
                        }
                        if (q && q.nodeName == "TABLE") {
                            l.dom.add(l.getBody(), "p", null, '<br mce_bogus="1" />')
                        }
                    }

                    if (b.isGecko) {
                        l.onKeyDown.add(function (r, t) {
                            var q, s, u = r.dom;
                            if (t.keyCode == 37 || t.keyCode == 38) {
                                q = r.selection.getRng();
                                s = u.getParent(q.startContainer, "table");
                                if (s && r.getBody().firstChild == s) {
                                    if (isAtStart(q, s)) {
                                        q = u.createRng();
                                        q.setStartBefore(s);
                                        q.setEndBefore(s);
                                        r.selection.setRng(q);
                                        t.preventDefault()
                                    }
                                }
                            }
                        })
                    }
                    l.onKeyUp.add(n);
                    l.onSetContent.add(n);
                    l.onVisualAid.add(n);
                    l.onPreProcess.add(function (q, s) {
                        var r = s.node.lastChild;
                        if (r && r.childNodes.length == 1 && r.firstChild.nodeName == "BR") {
                            q.dom.remove(r)
                        }
                    });
                    n()
                }
            });
            c({
                mceTableSplitCells: function (k) {
                    k.split()
                }, mceTableMergeCells: function (l) {
                    var m, n, k;
                    k = e.dom.getParent(e.selection.getNode(), "th,td");
                    if (k) {
                        m = k.rowSpan;
                        n = k.colSpan
                    }
                    if (!e.dom.select("td.mceSelected,th.mceSelected").length) {
                        d.open({
                            url: f + "/merge_cells.htm",
                            width: 240 + parseInt(e.getLang("table.merge_cells_delta_width", 0)),
                            height: 110 + parseInt(e.getLang("table.merge_cells_delta_height", 0)),
                            inline: 1
                        }, {
                            rows: m, cols: n, onaction: function (o) {
                                l.merge(k, o.cols, o.rows)
                            }, plugin_url: f
                        })
                    } else {
                        l.merge()
                    }
                }, mceTableInsertRowBefore: function (k) {
                    k.insertRow(true)
                }, mceTableInsertRowAfter: function (k) {
                    k.insertRow()
                }, mceTableInsertColBefore: function (k) {
                    k.insertCol(true)
                }, mceTableInsertColAfter: function (k) {
                    k.insertCol()
                }, mceTableDeleteCol: function (k) {
                    k.deleteCols()
                }, mceTableDeleteRow: function (k) {
                    k.deleteRows()
                }, mceTableCutRow: function (k) {
                    j = k.cutRows()
                }, mceTableCopyRow: function (k) {
                    j = k.copyRows()
                }, mceTablePasteRowBefore: function (k) {
                    k.pasteRows(j, true)
                }, mceTablePasteRowAfter: function (k) {
                    k.pasteRows(j)
                }, mceTableDelete: function (k) {
                    k.deleteTable()
                }
            }, function (l, k) {
                e.addCommand(k, function () {
                    var m = h();
                    if (m) {
                        l(m);
                        e.execCommand("mceRepaint");
                        g()
                    }
                })
            });
            c({
                mceInsertTable: function (k) {
                    d.open({
                        url: f + "/table.htm",
                        width: 400 + parseInt(e.getLang("table.table_delta_width", 0)),
                        height: 320 + parseInt(e.getLang("table.table_delta_height", 0)),
                        inline: 1
                    }, {plugin_url: f, action: k ? k.action : 0})
                }, mceTableRowProps: function () {
                    d.open({
                        url: f + "/row.htm",
                        width: 400 + parseInt(e.getLang("table.rowprops_delta_width", 0)),
                        height: 295 + parseInt(e.getLang("table.rowprops_delta_height", 0)),
                        inline: 1
                    }, {plugin_url: f})
                }, mceTableCellProps: function () {
                    d.open({
                        url: f + "/cell.htm",
                        width: 400 + parseInt(e.getLang("table.cellprops_delta_width", 0)),
                        height: 295 + parseInt(e.getLang("table.cellprops_delta_height", 0)),
                        inline: 1
                    }, {plugin_url: f})
                }
            }, function (l, k) {
                e.addCommand(k, function (m, n) {
                    l(n)
                })
            })
        }
    });
    b.PluginManager.add("table", b.plugins.TablePlugin)
})(tinymce);