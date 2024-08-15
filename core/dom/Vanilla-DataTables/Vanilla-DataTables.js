/*!
 *
 * Vanilla-DataTables
 * Copyright (c) 2015-2017 Karl Saunders (http://mobius.ovh)
 * Licensed under MIT (http://www.opensource.org/licenses/mit-license.php)
 *
 * Version: 2.0.0-alpha.23
 * Enhanced by Jeedom team 01/2023
 * Added cloumn sorters
 * Better css
 * Added paging true/false option
 * Added instance and table.prototype.destroy on table._dataTable
 * Added table refresh, to call after html tr insertion/deletion
 */
(function(root, factory) {
    var plugin = "DataTable";

    if (typeof exports === "object") {
        module.exports = factory(plugin);
    } else if (typeof define === "function" && define.amd) {
        define([], factory(plugin));
    } else {
        root[plugin] = factory(plugin);
    }
})(typeof global !== 'undefined' ? global : this.window || this.global, function(plugin) {
    "use strict";
    var win = window,
        doc = document,
        body = doc.body,
        supports = body ? "classList" in body : null,
        IE = !!/(msie|trident)/i.test(navigator.userAgent);

    /**
     * Default configuration
     * @type {Object}
     */
    var defaultConfig = {
        paging: true,
        perPage: 10,
        perPageSelect: [5, 10, 15, 20, 25],

        sortable: true,
        searchable: true,

        // Pagination
        nextPrev: true,
        firstLast: false,
        prevText: "&lsaquo;",
        nextText: "&rsaquo;",
        firstText: "&laquo;",
        lastText: "&raquo;",
        ellipsisText: "&hellip;",
        truncatePager: true,
        pagerDelta: 2,

        fixedColumns: true,
        fixedHeight: false,

        header: true,
        footer: false,

        search: {
            includeHiddenColumns: false
        },

        classes: {
            top: "dt-top",
            info: "dt-info",
            input: "dt-input",
            table: "dt-table",
            bottom: "dt-bottom",
            search: "dt-search",
            sorter: "dt-sorter",
            wrapper: "dt-wrapper",
            dropdown: "dt-dropdown",
            ellipsis: "dt-ellipsis",
            selector: "dt-selector",
            container: "dt-container",
            pagination: "dt-pagination"
        },

        // Customise the display text
        labels: {
            placeholder: "{{Rechercher}}...", // The search input placeholder
            perPage: "{select} <span> {{Entrées par page}} </span>", // per-page dropdown label
            noRows: "{{Aucune entrée trouvée}}", // Message shown when there are no search results
            info: "{{Affichage}} : {start} {{à}} {end} {{de}} {rows} {{entrées}}" //
        },

        // Customise the layout
        layout: {
            top: "{select}{search}",
            bottom: "{info}{pager}",
        }
    };

    /**
     * Default extensions
     * @type {Array}
     */
    var extensions = [
        "editable",
        "exportable",
        "filterable"
    ];

    /**
     * Check is item is object
     * @return {Boolean}
     */
    var isObject = function(val) {
        return Object.prototype.toString.call(val) === "[object Object]";
    };

    /**
     * Check is item is array
     * @return {Boolean}
     */
    var isArray = function(val) {
        return Array.isArray(val);
    };

    /**
     * Check for valid JSON string
     * @param  {String}   str
     * @return {Boolean|Array|Object}
     */
    var isJson = function(str) {
        var t = !1;
        try {
            t = JSON.parse(str);
        } catch (e) {
            return !1;
        }
        return !(null === t || (!isArray(t) && !isObject(t))) && t;
    };

    var isset = function(obj, prop) {
        return obj.hasOwnProperty(prop);
    };

    /**
     * Merge objects (reccursive)
     * @param  {Object} r
     * @param  {Object} t
     * @return {Object}
     */
    var extend = function(src, props) {
        for (var prop in props) {
            if (props.hasOwnProperty(prop)) {
                var val = props[prop];
                if (val && isObject(val)) {
                    src[prop] = src[prop] || {};
                    extend(src[prop], val);
                } else {
                    src[prop] = val;
                }
            }
        }
        return src;
    };

    /**
     * Iterator helper
     * @param  {(Array|Object|Number)}   arr     Any number, object, array or array-like collection.
     * @param  {Function}         fn             Callback
     * @param  {Object}           scope          Change the value of this
     * @return {Void}
     */
    var each = function(arr, fn, scope) {
        var n;
        if (isObject(arr)) {
            for (n in arr) {
                if (Object.prototype.hasOwnProperty.call(arr, n)) {
                    fn.call(scope, arr[n], n);
                }
            }
        } else if (isArray(arr)) {
            for (n = 0; n < arr.length; n++) {
                fn.call(scope, arr[n], n);
            }
        } else {
            for (n = 0; n < arr; n++) {
                fn.call(scope, n + 1, n);
            }
        }
    };

    /**
     * Create DOM element node
     * @param  {String}   a nodeName
     * @param  {Object}   b properties and attributes
     * @return {Object}
     */
    var createElement = function(type, options) {
        var node = doc.createElement(type);
        if (options && "object" == typeof options) {
            var prop;
            for (prop in options) {
                if ("html" === prop) {
                    node.innerHTML = options[prop];
                } else {
                    if (prop in node) {
                        node[prop] = options[prop];
                    } else {
                        node.setAttribute(prop, options[prop]);
                    }
                }
            }
        }
        return node;
    };

    /**
     * Get the closest matching ancestor
     * @param  {Object}   el         The starting node.
     * @param  {Function} fn         Callback to find matching ancestor.
     * @return {Object|Boolean}      Returns the matching ancestor or false in not found.
     */
    var closest = function(el, fn) {
        return el && el !== body && (fn(el) ? el : closest(el.parentNode, fn));
    };

    /**
     * Add event listener to target
     * @param  {Object} el
     * @param  {String} e
     * @param  {Function} fn
     */
    var on = function(el, e, fn) {
        el.addEventListener(e, fn, false);
    };

    /**
     * Empty a node
     * @param  {Object} el HTMLElement
     */
    var empty = function(el) {
        if (IE) {
            while (el.hasChildNodes()) {
                el.removeChild(el.lastChild);
            }
        } else {
            el.innerHTML = "";
        }
    };

    /**
     * classList shim
     * @type {Object}
     */
    var classList = {
        add: function(s, a) {
            if (supports) {
                s.classList.add(a);
            } else {
                if (!classList.contains(s, a)) {
                    s.className = s.className.trim() + " " + a;
                }
            }
        },
        remove: function(s, a) {
            if (supports) {
                s.classList.remove(a);
            } else {
                if (classList.contains(s, a)) {
                    s.className = s.className.replace(new RegExp("(^|\\s)" + a.split(" ").join("|") + "(\\s|$)", "gi"), " ");
                }
            }
        },
        contains: function(s, a) {
            return supports ? s.classList.contains(a) : !!s.className && !!s.className.match(new RegExp("(\\s|^)" + a + "(\\s|$)"));
        },
        toggle: function(t, n, force) {
            n += "";
            var i = this.contains(t, n),
                o = i ? true !== force && "remove" : false !== force && "add";
            return o && this[o](t, n), true === force || false === force ? force : !i;
        }
    };

    /**
     * Utils
     * @type {Object}
     */
    var utils = {
        each: each,
        extend: extend,
        isObject: isObject,
        classList: classList,
        createElement: createElement
    };

    /**
     * Parse cell contents for sorting
     * @param  {String} content     The datetime string to parse
     * @param  {String} format      The format for moment to use
     * @return {String|Boolean}     Datatime string or false
     */
    var parseDate = function(content, format, cell, row) {
        var date = false;
        if (format && win.moment) {

            // moment() throws a fit if the string isn't a valid datetime string
            // so we need to supply the format to the constructor (https://momentjs.com/docs/#/parsing/string-format/)

            // .format() convert moment to timestamp, ensures we can accurately sort the column numerically

            switch (format) {
                case "ISO_8601":
                    date = moment(content, moment.ISO_8601).format()
                    break;
                case "RFC_2822":
                    date = moment(content, "ddd, DD MMM YYYY HH:mm:ss ZZ").format()
                    break;
                case "MYSQL":
                    date = moment(content, "YYYY-MM-DD hh:mm:ss").format()
                    break;
                case "UNIX":
                    date = moment(parseInt(content, 10)).unix()
                    break;
                    // User defined format using the data-format attribute or columns[n].format option
                default:
                    date = moment(content, format).format()
                    break;
            }
        } else {
            date = new Date(content).getTime();
        }

        return date;
    };

    var Cell = function(cell, index) {
        this.node = cell;
        this.content = this.originalContent = cell.innerHTML;
        this.hidden = false;
        this.index = this.node.dataIndex = index;
        this.originalContent = this.content;
    };

    Cell.prototype.setContent = function(content) {
        this.content = this.node.innerHTML = content;
    };

    var Row = function(row, index) {

        if (isArray(row)) {
            this.node = createElement("tr");

            each(row, function(val, i) {
                this.node.appendChild(createElement("td", {
                    html: val
                }));
            }, this);
        } else {
            this.node = row;
            if (index !== undefined) {
                this.isHeader = row.parentNode.nodeName === "THEAD";
            }
        }

        if (!this.isHeader && index !== undefined) {
            this.index = this.node.dataIndex = index - 1;
        }

        this.cells = [].slice.call(this.node.cells).map(function(cell, i) {
            return new Cell(cell, i, this);
        }, this);
    };

    var Table = function(table, data, dt) {
        this.node = table;

        if (typeof table === "string") {
            this.node = doc.querySelector(table);
        }

        if (data) {
            this.build(data);
        }

        this.rows = [].slice.call(this.node.rows).map(function(row, i) {
            return new Row(row, i, this);
        }, this);

        this.body = this.node.tBodies[0];

        if (!this.body) {
            this.body = createElement("tbody");
            this.node.appendChild(this.body);
        }

        if (this.rows.length) {
            if (this.rows[0].isHeader) {
                this.hasHeader = true;
                this.header = this.rows[0];
                this.head = this.header.node.parentNode;
                this.rows.shift();
                if (dt.config.sortable) {
                    each(this.header.cells, function(cell) {
                        if (cell.node.getAttribute('data-sortable') != 'false') classList.add(cell.node, dt.config.classes.sorter);
                    });
                }
            } else {

            }
        }

        if (!dt.config.header) {
            this.head.removeChild(this.header.node);
        }

        if (dt.config.footer) {
            this.hasFooter = true;
            this.footer = new Row(this.header.node.cloneNode(true));

            var foot = createElement("tfoot");
            foot.appendChild(this.footer.node);

            each(this.footer.cells, function(cell) {
                classList.remove(cell.node, dt.config.classes.sorter);
            });

            this.node.insertBefore(foot, this.body);
        }
    };

    Table.prototype = {

        build: function(data) {
            var thead = false,
                tbody = false;

            if (data.headings) {
                thead = createElement("thead");
                var tr = createElement("tr");
                each(data.headings, function(col) {
                    var td = createElement("th", {
                        html: col
                    });
                    tr.appendChild(td);
                });

                thead.appendChild(tr);
            }

            if (data.data && data.data.length) {
                tbody = createElement("tbody");
                each(data.data, function(rows) {
                    var tr = createElement("tr");
                    each(rows, function(value) {
                        var td = createElement("td", {
                            html: value
                        });
                        tr.appendChild(td);
                    });
                    tbody.appendChild(tr);
                });
            }

            if (thead) {
                if (this.node.tHead !== null) {
                    this.node.removeChild(this.node.tHead);
                }
                this.node.appendChild(thead);
            }

            if (tbody) {
                if (this.node.tBodies.length) {
                    this.node.removeChild(this.node.tBodies[0]);
                }
                this.node.appendChild(tbody);
            }
        },

        addHeader: function(data, append) {
            var that = this,
                th = createElement("thead"),
                tr = createElement("tr");

            data = data || this.rows[0].cells;

            each(data, function(cell) {
                tr.appendChild(createElement("th"));
            });

            th.appendChild(tr);

            this.head = th;
            this.header = new Row(tr, 1);
            this.hasHeader = true;

            if (append) {
                if (!this.node.contains(this.header.node)) {
                    this.node.insertBefore(th, this.body);
                }
            }
        },

        addRow: function(row, at, update) {
            if (row instanceof Row) {
                this.rows.splice(at || 0, 0, row);

                // We may have a table without a header
                if (!this.hasHeader) {
                    this.addHeader();
                }

                if (update) {
                    this.update();
                }

                return row;
            }
        },

        removeRow: function(row, update) {
            if (row instanceof Row) {
                this.rows.splice(this.rows.indexOf(row), 1);

                if (update) {
                    this.update();
                }
            }
        },

        update: function(all) {
            each(this.rows, function(row, i) {
                row.index = row.node.dataIndex = i;
            });
        }
    };

    // PAGER
    var Pager = function(instance, parent) {
        this.instance = instance;
        this.parent = parent;
    };

    Pager.prototype = {
        render: function(pages) {
            var that = this,
                dt = that.instance,
                o = dt.config;

            pages = pages || dt.totalPages;

            empty(that.parent);

            // No need for pager if we only have one page
            if (o.paging && pages > 1) {
                var c = "pager",
                    ul = createElement("ul"),
                    prev = dt.onFirstPage ? 1 : dt.currentPage - 1,
                    next = dt.onlastPage ? pages : dt.currentPage + 1;

                // first button
                if (o.firstLast) {
                    ul.appendChild(that.button(c, 1, o.firstText));
                }

                // prev button
                if (o.nextPrev) {
                    ul.appendChild(that.button(c, prev, o.prevText));
                }

                var pager = that.truncate();
                // append the links
                each(pager, function(btn) {
                    ul.appendChild(btn);
                });

                // next button
                if (o.nextPrev) {
                    ul.appendChild(that.button(c, next, o.nextText));
                }

                // first button
                if (o.firstLast) {
                    ul.appendChild(that.button(c, pages, o.lastText));
                }

                that.parent.appendChild(ul);
            }
        },

        truncate: function() {
            var that = this,
                o = that.instance.config,
                delta = o.pagerDelta * 2,
                page = that.instance.currentPage,
                left = page - o.pagerDelta,
                right = page + o.pagerDelta,
                pages = that.instance.totalPages,
                range = [],
                pager = [],
                n;

            // No need to truncate if it's disabled
            if (!o.truncatePager) {
                each(pages, function(index) {
                    pager.push(that.button(index == page ? "active" : "", index, index));
                });
            } else {
                if (page < 4 - o.pagerDelta + delta) {
                    right = 3 + delta;
                } else if (page > pages - (3 - o.pagerDelta + delta)) {
                    left = pages - (2 + delta);
                }

                // Get the links that will be visible
                for (var i = 1; i <= pages; i++) {
                    if (i == 1 || i == pages || (i >= left && i <= right)) {
                        range.push(i);
                    }
                }

                each(range, function(index) {
                    if (n) {
                        if (index - n == 2) {
                            pager.push(that.button("", n + 1, n + 1));
                        } else if (index - n != 1) {
                            // Create ellipsis node
                            pager.push(that.button(o.classes.ellipsis, 0, o.ellipsisText, true));
                        }
                    }

                    pager.push(that.button(index == page ? "active" : "", index, index));
                    n = index;
                });
            }

            return pager;
        },

        button: function(className, pageNum, content, ellipsis) {
            return createElement("li", {
                class: className,
                html: !ellipsis ? '<a href="#" data-page="' + pageNum + '">' + content + "</a>" : '<span>' + content + "</span>"
            });
        }
    };

    // ROWS
    var Rows = function(instance, select) {
        this.instance = instance;

        if (select !== undefined) {
            if (!isNaN(select)) {
                this.select = [select];
            } else if (isArray(select)) {
                this.select = select;
            }
        } else {
            this.select = instance.table.rows.map(function(row) {
                return row.index;
            });
        }
    };

    Rows.prototype = {
        init: function() {},

        count: function() {
            return this.instance.table.rows.length;
        },

        render: function(page) {

            var that = this,
                dt = that.instance;
            page = page || dt.currentPage;
            empty(dt.table.body);

            if (page < 1 || page > dt.totalPages) return;

            var head = dt.table.header,
                fragment = doc.createDocumentFragment();

            if (dt.table.hasHeader) {
                empty(head.node);
                each(head.cells, function(cell) {
                    if (!cell.hidden) {
                        head.node.appendChild(cell.node);
                    }
                });
            }

            if (dt.pages.length) {
                each(dt.pages[page - 1], function(row) {
                    empty(row.node);

                    each(row.cells, function(cell) {
                        if (!cell.hidden) {
                            row.node.appendChild(cell.node);
                        }
                    });

                    fragment.append(row.node);
                });
            }

            dt.table.body.appendChild(fragment);

            each(dt.pagers, function(pager) {
                pager.render();
            });

            dt.getInfo();

            if (dt.currentPage == 1) {
                dt.fixHeight();
            }

            dt.emit("rows.render");
        },

        paginate: function() {
            var o = this.instance.config,
                rows = this.instance.table.rows,
                dt = this.instance;

            if (dt.searching && dt.searchData) {
                rows = dt.searchData;
            }

            if (this.instance.config.paging) {
                dt.pages = rows
                    .map(function(tr, i) {
                        return i % o.perPage === 0 ? rows.slice(i, i + o.perPage) : null;
                    })
                    .filter(function(page) {
                        return page;
                    });
            } else {
                dt.pages = [rows]
            }

            dt.totalPages = dt.pages.length;

            // Current page maybe outside the range
            if (dt.currentPage > dt.totalPages) {
                dt.currentPage = dt.totalPages;
            }
        },

        add: function(row, at) {
            if (isArray(row)) {
                at = at || 0;
                if (isArray(row[0])) {
                    each(row, function(tr) {
                        tr = this.instance.table.addRow(new Row(tr, this.instance.columns().count() + 1), at);
                    }, this);
                    // only update after adding multiple rows
                    // to keep performance hit to a minimum
                    this.instance.table.update();
                } else {
                    row = this.instance.table.addRow(new Row(row, this.instance.columns().count() + 1), at, true);
                }

                this.instance.update();

                return row;
            }
        },

        remove: function(obj) {
            var row = false,
                dt = this.instance;

            if (isArray(obj)) {
                // reverse order or there'll be shit to pay
                for (var i = obj.length - 1; i >= 0; i--) {
                    dt.table.removeRow(this.get(obj[i]));
                }
                dt.table.update();
                dt.update();
            } else {
                row = this.get(obj);
                if (row) {
                    dt.table.removeRow(row, true);
                    dt.update();

                    return row;
                }
            }
        },

        cells: function() {
            var that = this,
                rows = [];

            if (this.select.length == 1) {
                this.select = this.select[0];
            }

            each(this.instance.table.rows, function(row) {
                if ((isArray(that.select) && that.select.indexOf(row.index) >= 0) || that.select == row.index) {
                    rows.push(row.cells);
                }
            });

            return rows;
        },

        get: function(row) {
            var rows = this.instance.table.rows;
            if (row instanceof Row || row instanceof Element) {
                for (var n = 0; n < rows.length; n++) {
                    if (rows[n].node === row || rows[n] === row) {
                        row = rows[n];
                        break;
                    }
                }
            } else {
                row = rows[row];
            }

            return row;
        }
    };

    // COLUMNS
    var Columns = function(instance, select) {
        this.instance = instance;

        if (select !== undefined) {
            if (!isNaN(select)) {
                this.select = [select];
            } else if (isArray(select)) {
                this.select = select;
            }
        } else {
            if (instance.table.hasheader) {
                this.select = instance.table.header.cells.map(function(cell) {
                    return cell.index;
                });
            }
        }
    };

    Columns.prototype = {
        init: function() {
            var that = this.instance,
                o = that.config;

            if (that.table.hasHeader) {
                each(that.table.header.cells, function(cell) {
                    if (cell.node.getAttribute('data-sortable') !== 'false') classList.add(cell.node, o.classes.sorter)
                });
            }

            if (o.columns) {
                var selectedColumns = [];
                var columnRenderers = [];

                each(o.columns, function(data) {
                    // convert single column selection to array
                    if (!isNaN(data.select)) {
                        data.select = [data.select];
                    }

                    if (isset(data, "render") && typeof data.render === "function") {
                        selectedColumns = selectedColumns.concat(data.select);

                        columnRenderers.push({
                            columns: data.select,
                            renderer: data.render
                        });
                    }

                    // Add the data attributes to the th elements
                    if (that.table.hasHeader) {

                        each(that.table.header.cells, function(cell) {
                            if (cell.node.getAttribute('data-sortable') !== 'false') classList.add(cell.node, o.classes.sorter)
                        });

                        each(data.select, function(column) {
                            var cell = that.table.header.cells[column];

                            if (data.type) {
                                cell.node.setAttribute("data-type", data.type);
                            }
                            if (data.format) {
                                cell.node.setAttribute("data-format", data.format);
                            }
                            if (isset(data, "sortable")) {
                                cell.node.setAttribute("data-sortable", data.sortable);

                                if (data.sortable === false) {
                                    classList.remove(cell.node, o.classes.sorter);
                                }
                            }

                            if (isset(data, "hidden")) {
                                if (data.hidden !== false) {
                                    that.columns().hide(column);
                                }
                            }

                            if (isset(data, "sort") && data.select.length === 1) {
                                that.columns().sort(data.select[0], data.sort);
                            }
                        });
                    }
                });

                if (selectedColumns.length) {
                    each(that.table.rows, function(row) {
                        each(row.cells, function(cell) {
                            if (selectedColumns.indexOf(cell.index) >= 0) {
                                each(columnRenderers, function(obj) {
                                    if (obj.columns.indexOf(cell.index) >= 0) {
                                        cell.setContent(obj.renderer.call(that, cell.content, cell, row));
                                    }
                                })
                            }
                        });
                    });
                }
            }

            if (o.fixedColumns && that.table.hasHeader) {
                this.fix(true);
            }
        },

        count: function() {
            return this.instance.table.header.cells.length;
        },

        sort: function(column, direction) {
            var dt = this.instance;

            column = column || 0;
            direction = direction || (dt.lastDirection && "asc" === dt.lastDirection ? direction = "desc" : direction = "asc");

            if (column < 0 || column > dt.table.header.cells.length - 1) {
                return false;
            }

            var node = dt.table.header.cells[column].node,
                rows = dt.table.rows;

            if (dt.searching && dt.searchData) {
                rows = dt.searchData;
            }

            // Remove class from previus column
            if (dt.lastHeading) {
                classList.remove(dt.lastHeading, dt.lastDirection);
            }

            if (dt.lastDirection) {
                classList.remove(node, dt.lastDirection);
            }

            classList.add(node, direction);

            var format, datetime;

            if (node.hasAttribute("data-type")) {
                if (node.getAttribute("data-type") === "date") {
                    format = false;
                    datetime = node.hasAttribute("data-format");
                    if (datetime) {
                        format = node.getAttribute("data-format");
                    }
                    rows = this.sortDate(rows, column, direction, format)
                } else if (node.getAttribute("data-type") === "number") {
                    rows = this.sortNumber(rows, column, direction)
                } else if (node.getAttribute("data-type") === "input") {
                    rows = this.sortInput(rows, column, direction)
                } else if (node.getAttribute("data-type") === "checkbox") {
                    rows = this.sortCheckbox(rows, column, direction)
                } else if (node.getAttribute('data-type') === 'select-text') {
                    rows = this.sortSelectText(rows, column, direction)
                } else if (node.getAttribute('data-type') === 'select-value') {
                    rows = this.sortSelectValue(rows, column, direction)
                } else if (node.getAttribute('data-type') === 'select-custom') {
                    rows = this.sortSelectCustom(rows, column, direction)
                } else if (node.getAttribute('data-type') === 'custom') {
                    rows = this.sortCustom(rows, column, direction)
                }
            } else {
                rows = this.sortDefault(rows, column, direction)

            }
            dt.table.update();
            dt.update();

            dt.lastHeading = node;
            dt.lastDirection = direction;

            dt.emit("columns.sort", direction, column, node);

            classList.remove(node, "loading");
        },
        sortDefault: function(rows, column, direction) {
            rows.sort(function(a, b) {
                //var ca = a.cells[column].content.toLowerCase().replace(/(\$|\,|\s|%)/g, "");
                //var cb = b.cells[column].content.toLowerCase().replace(/(\$|\,|\s|%)/g, "");
                var ca, cb
                if (a.cells[column].node.childNodes.length) {
                    ca = a.cells[column].node.childNodes[0].textContent.toLowerCase()
                } else {
                    ca = a.cells[column].content.toLowerCase()
                }
                if (b.cells[column].node.childNodes.length) {
                    cb = b.cells[column].node.childNodes[0].textContent.toLowerCase()
                } else {
                    cb = b.cells[column].content.toLowerCase()
                }
                ca = !isNaN(ca) ? parseInt(ca, 10) : ca;
                cb = !isNaN(cb) ? parseInt(cb, 10) : cb;
                var result = direction === "asc" ? (ca > cb ? 1 : -1) : (ca < cb ? 1 : -1)
                return result;
            });
            return rows
        },
        sortNumber: function(rows, column, direction) {
            rows.sort(function(a, b) {
                var ca = a.cells[column].content
                var cb = b.cells[column].content
                ca = !isNaN(ca) ? parseFloat(ca, 10) : 0
                cb = !isNaN(cb) ? parseFloat(cb, 10) : 0
                var result = direction === "asc" ? (ca > cb ? 1 : -1) : (ca < cb ? 1 : -1)
                return result;
            });
            return rows
        },
        sortString: function(rows, column, direction) {
            rows.sort(function(a, b) {
                var ca = a.cells[column].content.toLowerCase()
                var cb = b.cells[column].content.toLowerCase()
                var result = direction === "asc" ? (ca > cb ? 1 : -1) : (ca < cb ? 1 : -1)
                return result;
            });
            return rows
        },
        sortDate: function(rows, column, direction, format) {
            rows.sort(function(a, b) {
                var ca = parseDate(a.cells[column].content, format, a.cells[column], a);
                var cb = parseDate(b.cells[column].content, format, b.cells[column], b);
                var result = direction === "asc" ? (ca > cb ? 1 : -1) : (ca < cb ? 1 : -1)
                return result;
            });
            return rows
        },
        sortInput: function(rows, column, direction) {
            rows.sort(function(a, b) {
                var cha = a.cells[column].node.querySelector('input:not([type="checkbox"])')
                var chb = b.cells[column].node.querySelector('input:not([type="checkbox"])')
                var ca = cha != null ? cha.value.trim().toLowerCase() : -1
                var cb = chb != null ? chb.value.trim().toLowerCase() : -1
                if (ca === "") ca = -1
                if (cb === "") cb = -1
                ca = !isNaN(ca) ? parseInt(ca, 10) : ca;
                cb = !isNaN(cb) ? parseInt(cb, 10) : cb;

                var result = direction === "asc" ? (ca > cb ? 1 : -1) : (ca < cb ? 1 : -1)
                return result
            })
            return rows
        },
        sortCheckbox : function(rows, column, direction) {
            rows.sort(function(a, b) {
                var cha = a.cells[column].node.querySelector('input[type="checkbox"]')
                var chb = b.cells[column].node.querySelector('input[type="checkbox"]')
                var ca = cha ? (cha.checked ? -1 : 0) : 1
                var cb = chb ? (chb.checked ? -1 : 0) : 1
                var result = direction === "asc" ? (ca > cb ? 1 : -1) : (ca < cb ? 1 : -1)
                return result
            })
            return rows
        },
        sortSelectText: function(rows, column, direction) {
            rows.sort(function(a, b) {
                var cha = a.cells[column].node.querySelector('select')
                var chb = b.cells[column].node.querySelector('select')
                var ca = cha ? cha.selectedOptions[0].text.toLowerCase() || '' : ''
                var cb = chb ? chb.selectedOptions[0].text.toLowerCase() || '' : ''
                var result = direction === "asc" ? (ca > cb ? 1 : -1) : (ca < cb ? 1 : -1)
                return result
            })
            return rows
        },
        sortSelectValue: function(rows, column, direction) {
            rows.sort(function(a, b) {
                var cha = a.cells[column].node.querySelector('select')
                var chb = b.cells[column].node.querySelector('select')
                var ca = cha ? cha.value.toLowerCase() : -1
                var cb = chb ? chb.value.toLowerCase() : -1
                ca = !isNaN(ca) ? parseInt(ca, 10) : ca;
                cb = !isNaN(cb) ? parseInt(cb, 10) : cb;
                var result = direction === "asc" ? (ca > cb ? 1 : -1) : (ca < cb ? 1 : -1)
                return result
            })
            return rows
        },
        sortSelectCustom: function(rows, column, direction) {
            rows.sort(function(a, b) {
                var cha = a.cells[column].node.querySelector('select')
                var chb = b.cells[column].node.querySelector('select')
                var ca = cha ? cha.selectedOptions[0].getAttribute('data-sorton').toLowerCase() || 1000 : 1000
                var cb = chb ? chb.selectedOptions[0].getAttribute('data-sorton').toLowerCase() || 1000 : 1000
                ca = !isNaN(ca) ? parseInt(ca, 10) : ca;
                cb = !isNaN(cb) ? parseInt(cb, 10) : cb;
                var result = direction === "asc" ? (ca > cb ? 1 : -1) : (ca < cb ? 1 : -1)
                return result
            })
            return rows
        },
        sortCustom: function(rows, column, direction, value) {
            rows.sort(function(a, b) {
                var ca = a.cells[column].node.getAttribute('data-sorton')
                var cb = b.cells[column].node.getAttribute('data-sorton')
                ca = !isNaN(ca) ? parseInt(ca, 10) : ca;
                cb = !isNaN(cb) ? parseInt(cb, 10) : cb;
                var result = direction === "asc" ? (ca > cb ? 1 : -1) : (ca < cb ? 1 : -1)
                return result
            })
            return rows
        },

        search: function(column, query) {
            this.instance.search(query, column);
        },

        order: function(order) {
            var dt = this.instance;

            if (isArray(order)) {
                // Check for erroneous indexes
                for (var n = 0; n < order.length; n++) {
                    if (order[n] >= dt.columns().count()) {
                        throw new Error("Column index " + order[n] + " is outside the range of columns.");
                    }
                }

                var reorder = function(node) {
                    var arr = [];
                    each(order, function(column, i) {
                        arr[i] = node.cells[column];
                        arr[i].index = arr[i].node.dataIndex = i;
                        node.node.appendChild(arr[i].node);
                    });
                    node.cells = arr;
                };

                // Reorder the header
                if (dt.table.hasHeader) {
                    reorder(dt.table.header);
                }

                // Reorder the footer
                if (dt.table.hasFooter) {
                    reorder(dt.table.footer);
                }

                // Reorder the rows
                each(dt.table.rows, function(row) {
                    reorder(row);
                });

                dt.update();

                dt.emit("columns.order", order);
            }
        },

        hide: function() {
            var dt = this.instance,
                head = dt.table.header,
                rows = dt.table.rows;

            each(this.select, function(column) {
                each(head.cells, function(cell) {
                    if (column == cell.index) {
                        cell.hidden = true;
                    }
                });

                each(rows, function(row) {
                    each(row.cells, function(cell) {
                        if (column == cell.index) {
                            cell.hidden = true;
                        }
                    });
                });
            });

            this.fix(true);
            dt.update();

            dt.emit("columns.hide", this.select);
        },

        show: function() {
            var dt = this.instance,
                head = dt.table.header,
                rows = dt.table.rows;

            each(this.select, function(column) {
                each(head.cells, function(cell) {
                    if (column == cell.index) {
                        cell.hidden = false;
                    }
                });

                each(rows, function(row) {
                    each(row.cells, function(cell) {
                        if (column == cell.index) {
                            cell.hidden = false;
                        }
                    });
                });
            });

            this.fix(true);
            dt.update();

            dt.emit("columns.show", this.select);
        },

        visible: function() {
            var dt = this.instance,
                head = dt.table.header,
                cols = [];

            each(this.select, function(column) {
                cols.push(!head.cells[column].hidden);
            });

            return cols;
        },

        add: function(obj) {
            var dt = this.instance;

            if (isObject(obj)) {
                if (isset(obj, "heading")) {
                    var cell = new Cell(createElement("th"), dt.columns().count());
                    cell.setContent(obj.heading);

                    dt.table.header.node.appendChild(cell.node);
                    dt.table.header.cells.push(cell);
                }

                if (isset(obj, "data") && isArray(obj.data)) {
                    each(dt.table.rows, function(row, i) {
                        var cell = new Cell(createElement("td"), row.cells.length);
                        cell.setContent(obj.data[i] || "");

                        row.node.appendChild(cell.node);
                        row.cells.push(cell);
                    });
                }
            }

            this.fix(true);
            dt.update();

            dt.emit("columns.add");
        },

        remove: function(select, hold) {
            var dt = this.instance,
                table = dt.table,
                head = table.header;

            if (isArray(select)) {
                // Remove in reverse otherwise the indexes will be incorrect
                select.sort(function(a, b) {
                    return b - a;
                });

                each(select, function(column, i) {
                    this.remove(column, i < select.length - 1);
                }, this);

                return;
            } else {
                head.node.removeChild(head.cells[select].node);
                head.cells.splice(select, 1);

                each(table.rows, function(row) {
                    row.node.removeChild(row.cells[select].node);
                    row.cells.splice(select, 1);
                });
            }

            if (!hold) {
                each(head.cells, function(cell, i) {
                    cell.index = cell.node.dataIndex = i;
                });

                each(table.rows, function(row) {
                    each(row.cells, function(cell, i) {
                        cell.index = cell.node.dataIndex = i;
                    });
                });

                this.fix(true);
                dt.update();
            }

            dt.emit("columns.remove", select);
        },

        fix: function(update) {
            var dt = this.instance,
                table = dt.table,
                head = table.header;
            if (update) {
                if (table.hasHeader && dt.config.fixedColumns) {
                    dt.columnWidths = head.cells.map(function(cell) {
                        return cell.node.offsetWidth;
                    });
                }
            }
            /*
            each(dt.columnWidths, function(size, cell) {
                head.cells[cell].node.style.width = (size / dt.rect.width * 100) + "%";
            });
            */
        },

        cells: function() {
            var that = this,
                columns = [];

            if (this.select.length == 1) {
                this.select = this.select[0];
            }

            each(this.instance.table.rows, function(row, i) {
                if (isArray(that.select)) {
                    columns[i] = [];
                }
                each(row.cells, function(cell) {
                    if (isArray(that.select) && that.select.indexOf(cell.index) >= 0) {
                        columns[i].push(cell);
                    } else if (that.select == cell.index) {
                        columns.push(cell);
                    }
                });
            });

            return columns;
        }
    };

    // MAIN LIB
    var DataTable = function(table, config) {
        this.config = extend(defaultConfig, config);

        this.node = table;

        //Register instance on table
        table._dataTable = this

        if (this.config.ajax) {
            var that = this,
                ajax = this.config.ajax;

            this.request = new XMLHttpRequest();

            on(this.request, "load", function(xhr) {
                if (that.request.readyState === 4) {
                    if (that.request.status === 200) {
                        var obj = {};
                        obj.data = ajax.load ? ajax.load.call(that, that.request) : that.request.responseText;

                        obj.type = "json";

                        if (ajax.content && ajax.content.type) {
                            obj.type = ajax.content.type;
                            obj = extend(obj, ajax.content);
                        }

                        that.table = new Table(table, obj.data, that);

                        that.init();
                    }
                }
            });

            this.request.open("GET", typeof ajax === "string" ? that.config.ajax : that.config.ajax.url);
            this.request.send();
        } else {
            if (this.config.data) {
                this.table = new Table(table, this.config.data, this);
            } else {
                this.table = new Table(table, false, this);
            }

            this.init();
        }

    };

    DataTable.prototype = {
        init: function() {

            if (this.initialised) return;

            var that = this,
                o = that.config;

            //Register destroy on table to be called by element
            this.node._dataTable.destroy = DataTable.prototype.destroy
            this.node._dataTable.update = DataTable.prototype.update

            that.sortable = o.sortable;
            that.searchable = o.searchable;

            that.currentPage = 1;
            that.onFirstPage = true;
            that.onLastPage = false;

            that.rows().paginate();
            that.totalPages = that.pages.length;

            that.render();

            if (o.fixedColumns && that.table.hasHeader) {
                that.columns().fix();
            }

            that.extend();

            if (o.plugins) {
                each(o.plugins, function(options, plugin) {
                    if (that[plugin] !== undefined && typeof that[plugin] === "function") {
                        that[plugin] = that[plugin](that, options, utils);

                        // Init plugin
                        if (options.enabled && that[plugin].init && typeof that[plugin].init === "function") {
                            that[plugin].init();
                        }
                    }
                });
            }

            // Check for the columns option
            that.columns().init();

            that.rows().render();

            that.bindEvents();

            that.setClasses();

            that.initialised = true;

            setTimeout(function() {
                that.emit("init");
            }, 10);
        },

        setClasses: function() {
            classList.toggle(this.wrapper, "dt-selector", this.paging);
            classList.toggle(this.wrapper, "dt-sortable", this.sortable);
            classList.toggle(this.wrapper, "dt-searchable", this.searchable);
        },

        extend: function() {
            var that = this;

            each(extensions, function(ext) {
                if (that[ext] !== undefined && typeof that[ext] === "function") {
                    that[ext] = that[ext](that, that.config[ext], utils);

                    // Init extension
                    if (that[ext].init && typeof that[ext].init === "function") {
                        that[ext].init();
                    }
                }
            });
        },

        bindEvents: function() {
            var that = this,
                o = that.config;

            on(that.wrapper, "mousedown", function(e) {
                if (e.which === 1 && that.sortable && e.target.nodeName === "TH") {
                    classList.add(e.target, "loading");
                }
            });

            on(that.wrapper, "click", function(e) {
                var node = e.target;

                if (node.hasAttribute("data-page")) {
                    e.preventDefault();
                    that.page(parseInt(node.getAttribute("data-page"), 10));
                }

                if (that.sortable && node.nodeName === "TH" && classList.contains(node, o.classes.sorter)) {
                    if (node.hasAttribute("data-sortable") && node.getAttribute("data-sortable") === "false") return false;

                    e.preventDefault();
                    that
                        .columns()
                        .sort(node.dataIndex, classList.contains(node, "asc") ? "desc" : "asc");
                }
            });

            if (o.paging && o.perPageSelect) {
                on(that.wrapper, "change", function(e) {
                    var node = e.target;
                    if (
                        node.nodeName === "SELECT" &&
                        classList.contains(node, o.classes.selector)
                    ) {
                        e.preventDefault();
                        that.setPerPage(node.value);
                    }
                });
            }

            if (that.searchable) {
                on(that.wrapper, "keyup", function(e) {
                    if (
                        e.target.nodeName === "INPUT" &&
                        classList.contains(e.target, o.classes.input)
                    ) {
                        e.preventDefault();
                        that.search(e.target.value);
                    }
                });
            }

            if (that.sortable) {
                on(that.wrapper, "mousedown", function(e) {
                    if (e.target.nodeName === "TH") {
                        e.preventDefault();
                    }
                });
            }
        },

        render: function() {

            if (this.rendered) return;

            var that = this,
                o = that.config;

            if (this.table.hasHeader && o.fixedColumns && o.header) {
                this.columnWidths = this.table.header.cells.map(function(cell) {
                    return cell.node.offsetWidth;
                });
            }

            // Build
            that.wrapper = createElement("div", {
                class: o.classes.wrapper
            });

            // Template for custom layouts
            var inner = [
                "<div class='", o.classes.top, "'>", o.layout.top, "</div>",
                "<div class='", o.classes.container, "'></div>",
                "<div class='", o.classes.bottom, "'>", o.layout.bottom, "</div>"
            ].join("");

            // Info placement
            inner = inner.replace(
                "{info}",
                "<div class='" + o.classes.info + "'></div>"
            );

            // Per Page Select
            if (o.paging && o.perPageSelect) {
                var wrap = [
                    "<div class='", o.classes.dropdown, "'>",
                    "<label>", o.labels.perPage, "</label>",
                    "</div>"
                ].join("");

                // Create the select
                var select = createElement("select", {
                    class: o.classes.selector
                });

                // Create the options
                each(o.perPageSelect, function(val) {
                    var selected = val === o.perPage;
                    var option = new Option(val, val, selected, selected);
                    select.add(option);
                });

                // Custom label
                wrap = wrap.replace("{select}", select.outerHTML);

                // Selector placement
                inner = inner.replace(/\{select\}/g, wrap);
            } else {
                inner = inner.replace(/\{select\}/g, "");
            }

            // Searchable
            if (that.searchable) {
                var form = [
                    "<div class='", o.classes.search, "'>",
                    "<input class='", o.classes.input, "' placeholder='", o.labels.placeholder, "' type='text'>",
                    "</div>"
                ].join("");

                // Search input placement
                inner = inner.replace(/\{search\}/g, form);
            } else {
                inner = inner.replace(/\{search\}/g, "");
            }

            // Add table class
            that.table.node.classList.add(o.classes.table);

            // Pagers
            if (o.paging) {
                each(inner.match(/\{pager\}/g), function(pager, i) {
                    inner = inner.replace(
                        "{pager}",
                        createElement("div", {
                            class: o.classes.pagination
                        }).outerHTML
                    );
                });
            } else {
                inner = inner.replace(/\{pager\}/g, "");
            }

            that.wrapper.innerHTML = inner;

            that.pagers = [].slice.call(
                that.wrapper.querySelectorAll("." + o.classes.pagination)
            );

            each(that.pagers, function(pager, i) {
                that.pagers[i] = new Pager(that, pager);
            });

            that.container = that.wrapper.querySelector("." + o.classes.container);

            that.labels = that.wrapper.querySelectorAll("." + o.classes.info);
            that.inputs = that.wrapper.querySelectorAll("." + o.classes.input);

            that.selectors = that.wrapper.querySelectorAll("." + o.classes.selector);

            // Insert in to DOM tree
            that.table.node.parentNode.replaceChild(that.wrapper, that.table.node);
            that.container.appendChild(that.table.node);

            // Store the table dimensions
            that.rect = that.table.node.getBoundingClientRect();

            that.rendered = true;
        },

        update: function() {
            this.rows().paginate();
            setTimeout(() => {})
            this.rows().render();

            this.emit("update");
        },

        fixHeight: function() {
            this.container.style.height = null;
            if (this.config.fixedHeight) {
                this.rect = this.container.getBoundingClientRect();
                this.container.style.height = this.rect.height + "px";
            }
        },

        getInfo: function(data) {
            // Update the info
            var current = 0,
                f = 0,
                t = 0,
                l = 0,
                items;

            if (data) {
                current = data.page - 1;
                f = current * data.perpage;
                t = f + data.data.length;
                f = f + 1;
                items = data.rows;
            } else {
                if (this.config.paging && this.totalPages) {
                    current = this.currentPage - 1;
                    f = current * this.config.perPage;
                    t = f + this.pages[current].length;
                    f = f + 1;
                    items = !!this.searching ? this.searchData.length : this.rows().count();
                } else {
                    f = 0
                    t = this.table.rows.length
                    items = this.table.rows.length
                }
            }

            if (this.labels.length && this.config.labels.info.length) {
               if (items < 2) {
                  var string = '';
                }
                else {   // CUSTOM LABELS
                  var string = this.config.labels.info
                    .replace("{start}", f)
                    .replace("{end}", t)
                    .replace("{page}", this.currentPage)
                    .replace("{pages}", this.totalPages)
                    .replace("{rows}", items);
                }
                each([].slice.call(this.labels), function(label) {
                    label.innerHTML = items ? string : "";
                });
            }
        },

        search: function(query, column) {
            var that = this;

            query = query.toLowerCase();

            that.currentPage = 1;
            that.searching = true;
            that.searchData = [];

            if (!query.length) {
                that.searching = false;
                classList.remove(that.wrapper, "search-results");
                that.update();
                return false;
            }

            each(that.table.rows, function(row) {
                var inArray = that.searchData.indexOf(row) >= 0;

                // Filter column
                if (column !== undefined) {
                    each(row.cells, function(cell) {
                        if (column !== undefined && cell.index == column && !inArray) {
                            if (cell.content.toLowerCase().indexOf(query) >= 0) {
                                that.searchData.push(row);
                            }
                        }
                    });
                } else {
                    // https://github.com/Mobius1/Vanilla-DataTables/issues/12
                    var match = query.split(" ").reduce(function(bool, word) {
                        var includes = false;

                        for (var x = 0; x < row.cells.length; x++) {
                            if (that.table.header.cells[x].node.getAttribute('data-filter') === 'false') continue
                            if (row.cells[x].content.toLowerCase().indexOf(word) >= 0) {
                                if (!row.cells[x].hidden ||
                                    (row.cells[x].hidden && that.config.search.includeHiddenColumns)
                                )
                                    includes = true;
                                break;
                            }
                        }

                        return bool && includes;
                    }, true);

                    if (match && !inArray) {
                        that.searchData.push(row);
                    }
                }
            });

            classList.add(that.wrapper, "search-results");

            if (!that.searchData.length) {
                classList.remove(that.wrapper, "search-results");

                that.setMessage(that.config.labels.noRows);
            } else {
                that.update();
            }

            this.emit("search", query, this.searchData);
        },

        page: function(page) {
            // We don't want to load the current page again.
            if (page == this.currentPage) {
                return false;
            }

            if (!isNaN(page)) {
                this.currentPage = parseInt(page, 10);
            }

            this.onFirstPage = this.currentPage === 1;
            this.onLastPage = this.currentPage === this.totalPages;

            if (page > this.totalPages || page < 0) {
                return false;
            }

            this.rows().render(parseInt(page, 10));

            this.emit("page", page);
        },

        reset: function() {
            var that = this;

            if (this.searching) {
                this.searching = this.searchData = false;
                classList.remove(this.wrapper, "search-results");
            }

            each([].slice.call(this.inputs), function(input) {
                input.value = null;
                input.blur();
            });

            this.update();

            this.emit("reset");
        },

        set: function(prop, val) {
            if (this.hasOwnProperty(prop)) {
                this[prop] = val;

                classList.toggle(this.wrapper, "dt-" + prop, this[prop]);

                this.update();
            }
        },

        setPerPage: function(value) {
            if (!isNaN(value)) {
                value = parseInt(value, 10);

                this.config.perPage = value;

                this.fixHeight();

                if (this.config.perPageSelect.indexOf(value) >= 0) {
                    each([].slice.call(this.selectors), function(select) {
                        select.value = value;
                    });
                }

                this.update();

                this.emit("perpage", value);
            }
        },

        import: function(options) {
            var that = this,
                obj = false;
            var defaults = {
                // csv
                lineDelimiter: "\n",
                columnDelimiter: ","
            };

            // Check for the options object
            if (!isObject(options)) {
                return false;
            }

            options = extend(defaults, options);

            if (options.data.length || isObject(options.data)) {
                // Import CSV
                if (options.type === "csv") {
                    obj = {
                        data: []
                    };

                    // Split the string into rows
                    var rows = options.data.split(options.lineDelimiter);

                    if (rows.length) {

                        if (options.headings) {
                            obj.headings = rows[0].split(options.columnDelimiter);

                            rows.shift();
                        }

                        each(rows, function(row, i) {
                            obj.data[i] = [];

                            // Split the rows into values
                            var values = row.split(options.columnDelimiter);

                            if (values.length) {
                                each(values, function(value) {
                                    obj.data[i].push(value);
                                });
                            }
                        });
                    }
                } else if (options.type === "json") {
                    var json = isJson(options.data);

                    // Valid JSON string
                    if (json) {
                        obj = {
                            headings: [],
                            data: []
                        };

                        each(json, function(data, i) {
                            obj.data[i] = [];
                            each(data, function(value, column) {
                                if (obj.headings.indexOf(column) < 0) {
                                    obj.headings.push(column);
                                }

                                obj.data[i].push(value);
                            });
                        });
                    } else {
                        throw new Error("That's not valid JSON!");
                    }
                }

                if (isObject(options.data)) {
                    obj = options.data;
                }

                if (obj) {
                    if (obj.headings) {
                        if (!this.table.hasHeader) {
                            this.table.addHeader(obj.headings, true);
                        }
                        each(obj.headings, function(heading, i) {
                            that.table.header.cells[i].setContent(heading);
                        });

                        this.columns().init();
                    }

                    if (!this.table.rows.length) {
                        this.currentPage = 1;
                    }

                    this.rows().add(obj.data);
                }
            }

            return false;
        },

        setMessage: function(message) {
            var colspan = 1;

            if (this.rows().count()) {
                colspan = this.columns().count();
            }

            var node = createElement("tr", {
                html: '<td class="dataTables-empty" colspan="' +
                    colspan +
                    '">' +
                    message +
                    "</td>"
            });

            empty(this.table.body);

            this.table.body.appendChild(node);
        },

        columns: function(select) {
            return new Columns(this, select);
        },

        rows: function(select) {
            return new Rows(this, select);
        },

        on: function(event, callback) {
            this.events = this.events || {};
            this.events[event] = this.events[event] || [];
            this.events[event].push(callback);
        },

        off: function(event, callback) {
            this.events = this.events || {};
            if (event in this.events === false) return;
            this.events[event].splice(this.events[event].indexOf(callback), 1);
        },

        emit: function(event) {
            this.events = this.events || {};
            if (event in this.events === false) return;
            for (var i = 0; i < this.events[event].length; i++) {
                this.events[event][i].apply(this, Array.prototype.slice.call(arguments, 1));
            }
        },

        refresh: function() {
            var that = this;

            //Show all table rows:
            var dtPerPage = this.wrapper.querySelector('select.dt-selector')
            var totalRowsNb = that.table.rows.length
            if (dtPerPage) {
                this.setPerPage(totalRowsNb + 10)
            }

            var realRows = Array.from(this.node.tBodies[0].rows)
            //Empty all rows references:
            that.table.rows = []
            //Rebuild them:
            var newRow, cells
            realRows.forEach((_rRow, _idx) => {
                cells = [].slice.call(_rRow.cells).map(function(cell, i) {
                    return new Cell(cell, i, _rRow);
                }, _rRow)
                newRow = {
                    index: _idx,
                    isHeader: false,
                    node: _rRow,
                    cells: cells
                }
                that.table.rows.push(newRow)
            })
            if (dtPerPage) {
                this.setPerPage(dtPerPage.value)
            }
            that.update()
        },

        destroy: function() {

            var that = this,
                o = that.config,
                table = that.table;

            classList.remove(table.node, o.classes.table);

            each(table.header.cells, function(cell) {
                cell.node.style.width = "";
                classList.remove(cell.node, o.classes.sorter);
            });

            var frag = doc.createDocumentFragment();
            empty(table.body);

            each(table.rows, function(row) {
                frag.appendChild(row.node);
            });

            table.body.appendChild(frag);

            this.wrapper.parentNode.replaceChild(table.node, this.wrapper);

            this.rendered = false;
            this.initialised = false;

            each(extensions, function(ext) {
                if (that[ext] !== undefined && typeof that[ext] === "function") {
                    if (that[ext].destroy && typeof that[ext].destroy === "function") {
                        that[ext].destroy();
                    }
                }
            });
        }
    };

    DataTable.extend = function(prop, val) {
        if (typeof val === "function") {
            DataTable.prototype[prop] = val;
        } else {
            DataTable[prop] = val;
        }
    };

    return DataTable;
});
