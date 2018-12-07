/**
 * @license Highcharts JS v6.0.4 (2017-12-15)
 * Accessibility module
 *
 * (c) 2010-2017 Highsoft AS
 * Author: Oystein Moseng
 *
 * License: www.highcharts.com/license
 */
'use strict';
(function(factory) {
    if (typeof module === 'object' && module.exports) {
        module.exports = factory;
    } else {
        factory(Highcharts);
    }
}(function(Highcharts) {
    (function(H) {
        /**
         * Accessibility module - Screen Reader support
         *
         * (c) 2010-2017 Highsoft AS
         * Author: Oystein Moseng
         *
         * License: www.highcharts.com/license
         */
        /* eslint max-len: ["warn", 80, 4] */

        var win = H.win,
            doc = win.document,
            each = H.each,
            erase = H.erase,
            addEvent = H.addEvent,
            dateFormat = H.dateFormat,
            merge = H.merge,
            // CSS style to hide element from visual users while still exposing it to
            // screen readers
            hiddenStyle = {
                position: 'absolute',
                left: '-9999px',
                top: 'auto',
                width: '1px',
                height: '1px',
                overflow: 'hidden'
            },
            // Human readable description of series and each point in singular and
            // plural
            typeToSeriesMap = {
                'default': ['series', 'data point', 'data points'],
                'line': ['line', 'data point', 'data points'],
                'spline': ['line', 'data point', 'data points'],
                'area': ['line', 'data point', 'data points'],
                'areaspline': ['line', 'data point', 'data points'],
                'pie': ['pie', 'slice', 'slices'],
                'column': ['column series', 'column', 'columns'],
                'bar': ['bar series', 'bar', 'bars'],
                'scatter': ['scatter series', 'data point', 'data points'],
                'boxplot': ['boxplot series', 'box', 'boxes'],
                'arearange': ['arearange series', 'data point', 'data points'],
                'areasplinerange': [
                    'areasplinerange series',
                    'data point',
                    'data points'
                ],
                'bubble': ['bubble series', 'bubble', 'bubbles'],
                'columnrange': ['columnrange series', 'column', 'columns'],
                'errorbar': ['errorbar series', 'errorbar', 'errorbars'],
                'funnel': ['funnel', 'data point', 'data points'],
                'pyramid': ['pyramid', 'data point', 'data points'],
                'waterfall': ['waterfall series', 'column', 'columns'],
                'map': ['map', 'area', 'areas'],
                'mapline': ['line', 'data point', 'data points'],
                'mappoint': ['point series', 'data point', 'data points'],
                'mapbubble': ['bubble series', 'bubble', 'bubbles']
            },
            // Descriptions for exotic chart types
            typeDescriptionMap = {
                boxplot: ' Box plot charts are typically used to display groups of ' +
                    'statistical data. Each data point in the chart can have up to 5 ' +
                    'values: minimum, lower quartile, median, upper quartile and ' +
                    'maximum. ',
                arearange: ' Arearange charts are line charts displaying a range ' +
                    'between a lower and higher value for each point. ',
                areasplinerange: ' These charts are line charts displaying a range ' +
                    'between a lower and higher value for each point. ',
                bubble: ' Bubble charts are scatter charts where each data point ' +
                    'also has a size value. ',
                columnrange: ' Columnrange charts are column charts displaying a ' +
                    'range between a lower and higher value for each point. ',
                errorbar: ' Errorbar series are used to display the variability of ' +
                    'the data. ',
                funnel: ' Funnel charts are used to display reduction of data in ' +
                    'stages. ',
                pyramid: ' Pyramid charts consist of a single pyramid with item ' +
                    'heights corresponding to each point value. ',
                waterfall: ' A waterfall chart is a column chart where each column ' +
                    'contributes towards a total end value. '
            };


        // If a point has one of the special keys defined, we expose all keys to the
        // screen reader.
        H.Series.prototype.commonKeys = ['name', 'id', 'category', 'x', 'value', 'y'];
        H.Series.prototype.specialKeys = [
            'z', 'open', 'high', 'q3', 'median', 'q1', 'low', 'close'
        ];
        if (H.seriesTypes.pie) {
            // A pie is always simple. Don't quote me on that.
            H.seriesTypes.pie.prototype.specialKeys = [];
        }


        /**
         * Accessibility options
         * @type {Object}
         * @optionparent
         */
        H.setOptions({

            /**
             * Options for configuring accessibility for the chart. Requires the
             * [accessibility module](//code.highcharts.com/modules/accessibility.
             * js) to be loaded. For a description of the module and information
             * on its features, see [Highcharts Accessibility](http://www.highcharts.
             * com/docs/chart-concepts/accessibility).
             * 
             * @since 5.0.0
             */
            accessibility: {

                /**
                 * Enable accessibility features for the chart.
                 * 
                 * @type {Boolean}
                 * @default true
                 * @since 5.0.0
                 */
                enabled: true,

                /**
                 * When a series contains more points than this, we no longer expose
                 * information about individual points to screen readers.
                 * 
                 * Set to `false` to disable.
                 * 
                 * @type {Number|Boolean}
                 * @since 5.0.0
                 */
                pointDescriptionThreshold: false // set to false to disable

                /**
                 * Whether or not to add series descriptions to charts with a single
                 * series.
                 * 
                 * @type {Boolean}
                 * @default false
                 * @since 5.0.0
                 * @apioption accessibility.describeSingleSeries
                 */

                /**
                 * Function to run upon clicking the "View as Data Table" link in the
                 * screen reader region.
                 * 
                 * By default Highcharts will insert and set focus to a data table
                 * representation of the chart.
                 * 
                 * @type {Function}
                 * @since 5.0.0
                 * @apioption accessibility.onTableAnchorClick
                 */

                /**
                 * Date format to use for points on datetime axes when describing them
                 * to screen reader users.
                 * 
                 * Defaults to the same format as in tooltip.
                 * 
                 * For an overview of the replacement codes, see
                 * [dateFormat](#Highcharts.dateFormat).
                 * 
                 * @type {String}
                 * @see [pointDateFormatter](#accessibility.pointDateFormatter)
                 * @since 5.0.0
                 * @apioption accessibility.pointDateFormat
                 */

                /**
                 * Formatter function to determine the date/time format used with
                 * points on datetime axes when describing them to screen reader users.
                 * Receives one argument, `point`, referring to the point to describe.
                 * Should return a date format string compatible with
                 * [dateFormat](#Highcharts.dateFormat).
                 * 
                 * @type {Function}
                 * @see [pointDateFormat](#accessibility.pointDateFormat)
                 * @since 5.0.0
                 * @apioption accessibility.pointDateFormatter
                 */

                /**
                 * Formatter function to use instead of the default for point
                 * descriptions.
                 * Receives one argument, `point`, referring to the point to describe.
                 * Should return a String with the description of the point for a screen
                 * reader user.
                 * 
                 * @type {Function}
                 * @see [point.description](#series.line.data.description)
                 * @since 5.0.0
                 * @apioption accessibility.pointDescriptionFormatter
                 */

                /**
                 * A formatter function to create the HTML contents of the hidden screen
                 * reader information region. Receives one argument, `chart`, referring
                 * to the chart object. Should return a String with the HTML content
                 * of the region.
                 * 
                 * The link to view the chart as a data table will be added
                 * automatically after the custom HTML content.
                 * 
                 * @type {Function}
                 * @default undefined
                 * @since 5.0.0
                 * @apioption accessibility.screenReaderSectionFormatter
                 */

                /**
                 * Formatter function to use instead of the default for series
                 * descriptions. Receives one argument, `series`, referring to the
                 * series to describe. Should return a String with the description of
                 * the series for a screen reader user.
                 * 
                 * @type {Function}
                 * @see [series.description](#plotOptions.series.description)
                 * @since 5.0.0
                 * @apioption accessibility.seriesDescriptionFormatter
                 */
            }
        });

        /**
         * A text description of the chart.
         * 
         * If the Accessibility module is loaded, this is included by default
         * as a long description of the chart and its contents in the hidden
         * screen reader information region.
         * 
         * @type {String}
         * @see [typeDescription](#chart.typeDescription)
         * @default undefined
         * @since 5.0.0
         * @apioption chart.description
         */

        /**
         * A text description of the chart type.
         * 
         * If the Accessibility module is loaded, this will be included in the
         * description of the chart in the screen reader information region.
         * 
         * 
         * Highcharts will by default attempt to guess the chart type, but for
         * more complex charts it is recommended to specify this property for
         * clarity.
         * 
         * @type {String}
         * @default undefined
         * @since 5.0.0
         * @apioption chart.typeDescription
         */


        /**
         * HTML encode some characters vulnerable for XSS.
         * @param  {string} html The input string
         * @return {string} The excaped string
         */
        function htmlencode(html) {
            return html
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#x27;')
                .replace(/\//g, '&#x2F;');
        }

        /**
         * Strip HTML tags away from a string. Used for aria-label attributes, painting
         * on a canvas will fail if the text contains tags.
         * @param  {String} s The input string
         * @return {String}   The filtered string
         */
        function stripTags(s) {
            return typeof s === 'string' ? s.replace(/<\/?[^>]+(>|$)/g, '') : s;
        }


        // Utility function. Reverses child nodes of a DOM element
        function reverseChildNodes(node) {
            var i = node.childNodes.length;
            while (i--) {
                node.appendChild(node.childNodes[i]);
            }
        }


        // Whenever drawing series, put info on DOM elements
        H.wrap(H.Series.prototype, 'render', function(proceed) {
            proceed.apply(this, Array.prototype.slice.call(arguments, 1));
            if (this.chart.options.accessibility.enabled) {
                this.setA11yDescription();
            }
        });


        // Put accessible info on series and points of a series
        H.Series.prototype.setA11yDescription = function() {
            var a11yOptions = this.chart.options.accessibility,
                firstPointEl = (
                    this.points &&
                    this.points.length &&
                    this.points[0].graphic &&
                    this.points[0].graphic.element
                ),
                seriesEl = (
                    firstPointEl &&
                    firstPointEl.parentNode || this.graph &&
                    this.graph.element || this.group &&
                    this.group.element
                ); // Could be tracker series depending on series type

            if (seriesEl) {
                // For some series types the order of elements do not match the order of
                // points in series. In that case we have to reverse them in order for
                // AT to read them out in an understandable order
                if (seriesEl.lastChild === firstPointEl) {
                    reverseChildNodes(seriesEl);
                }
                // Make individual point elements accessible if possible. Note: If
                // markers are disabled there might not be any elements there to make
                // accessible.
                if (
                    this.points && (
                        this.points.length < a11yOptions.pointDescriptionThreshold ||
                        a11yOptions.pointDescriptionThreshold === false
                    )
                ) {
                    each(this.points, function(point) {
                        if (point.graphic) {
                            point.graphic.element.setAttribute('role', 'img');
                            point.graphic.element.setAttribute('tabindex', '-1');
                            point.graphic.element.setAttribute('aria-label', stripTags(
                                point.series.options.pointDescriptionFormatter &&
                                point.series.options.pointDescriptionFormatter(point) ||
                                a11yOptions.pointDescriptionFormatter &&
                                a11yOptions.pointDescriptionFormatter(point) ||
                                point.buildPointInfoString()
                            ));
                        }
                    });
                }
                // Make series element accessible
                if (this.chart.series.length > 1 || a11yOptions.describeSingleSeries) {
                    seriesEl.setAttribute(
                        'role',
                        this.options.exposeElementToA11y ? 'img' : 'region'
                    );
                    seriesEl.setAttribute('tabindex', '-1');
                    seriesEl.setAttribute(
                        'aria-label',
                        stripTags(
                            a11yOptions.seriesDescriptionFormatter &&
                            a11yOptions.seriesDescriptionFormatter(this) ||
                            this.buildSeriesInfoString()
                        )
                    );
                }
            }
        };


        // Return string with information about series
        H.Series.prototype.buildSeriesInfoString = function() {
            var typeInfo = (
                    typeToSeriesMap[this.type] ||
                    typeToSeriesMap['default'] // eslint-disable-line dot-notation
                ),
                description = this.description || this.options.description;
            return (this.name ? this.name + ', ' : '') +
                (this.chart.types.length === 1 ? typeInfo[0] : 'series') +
                ' ' + (this.index + 1) + ' of ' + (this.chart.series.length) +
                (
                    this.chart.types.length === 1 ?
                    ' with ' :
                    '. ' + typeInfo[0] + ' with '
                ) +
                (
                    this.points.length + ' ' +
                    (this.points.length === 1 ? typeInfo[1] : typeInfo[2])
                ) +
                (description ? '. ' + description : '') +
                (
                    this.chart.yAxis.length > 1 && this.yAxis ?
                    '. Y axis, ' + this.yAxis.getDescription() :
                    ''
                ) +
                (
                    this.chart.xAxis.length > 1 && this.xAxis ?
                    '. X axis, ' + this.xAxis.getDescription() :
                    ''
                );
        };


        // Return string with information about point
        H.Point.prototype.buildPointInfoString = function() {
            var point = this,
                series = point.series,
                a11yOptions = series.chart.options.accessibility,
                infoString = '',
                dateTimePoint = series.xAxis && series.xAxis.isDatetimeAxis,
                timeDesc =
                dateTimePoint &&
                dateFormat(
                    a11yOptions.pointDateFormatter &&
                    a11yOptions.pointDateFormatter(point) ||
                    a11yOptions.pointDateFormat ||
                    H.Tooltip.prototype.getXDateFormat(
                        point,
                        series.chart.options.tooltip,
                        series.xAxis
                    ),
                    point.x
                ),
                hasSpecialKey = H.find(series.specialKeys, function(key) {
                    return point[key] !== undefined;
                });

            // If the point has one of the less common properties defined, display all
            // that are defined
            if (hasSpecialKey) {
                if (dateTimePoint) {
                    infoString = timeDesc;
                }
                each(series.commonKeys.concat(series.specialKeys), function(key) {
                    if (point[key] !== undefined && !(dateTimePoint && key === 'x')) {
                        infoString += (infoString ? '. ' : '') +
                            key + ', ' +
                            point[key];
                    }
                });
            } else {
                // Pick and choose properties for a succint label
                infoString =
                    (
                        this.name ||
                        timeDesc ||
                        this.category ||
                        this.id ||
                        'x, ' + this.x
                    ) + ', ' +
                    (this.value !== undefined ? this.value : this.y);
            }

            return (this.index + 1) + '. ' + infoString + '.' +
                (this.description ? ' ' + this.description : '');
        };


        // Get descriptive label for axis
        H.Axis.prototype.getDescription = function() {
            return (
                this.userOptions && this.userOptions.description ||
                this.axisTitle && this.axisTitle.textStr ||
                this.options.id ||
                this.categories && 'categories' ||
                'values'
            );
        };


        // Whenever adding or removing series, keep track of types present in chart
        H.wrap(H.Series.prototype, 'init', function(proceed) {
            proceed.apply(this, Array.prototype.slice.call(arguments, 1));
            var chart = this.chart;
            if (chart.options.accessibility.enabled) {
                chart.types = chart.types || [];

                // Add type to list if does not exist
                if (chart.types.indexOf(this.type) < 0) {
                    chart.types.push(this.type);
                }

                addEvent(this, 'remove', function() {
                    var removedSeries = this,
                        hasType = false;

                    // Check if any of the other series have the same type as this one.
                    // Otherwise remove it from the list.
                    each(chart.series, function(s) {
                        if (
                            s !== removedSeries &&
                            chart.types.indexOf(removedSeries.type) < 0
                        ) {
                            hasType = true;
                        }
                    });
                    if (!hasType) {
                        erase(chart.types, removedSeries.type);
                    }
                });
            }
        });


        // Return simplified description of chart type. Some types will not be familiar
        // to most screen reader users, but we try.
        H.Chart.prototype.getTypeDescription = function() {
            var firstType = this.types && this.types[0],
                mapTitle = this.series[0] && this.series[0].mapTitle;
            if (!firstType) {
                return 'Empty chart.';
            } else if (firstType === 'map') {
                return mapTitle ? 'Map of ' + mapTitle : 'Map of unspecified region.';
            } else if (this.types.length > 1) {
                return 'Combination chart.';
            } else if (['spline', 'area', 'areaspline'].indexOf(firstType) > -1) {
                return 'Line chart.';
            }
            return firstType + ' chart.' + (typeDescriptionMap[firstType] || '');
        };


        // Return object with text description of each of the chart's axes
        H.Chart.prototype.getAxesDescription = function() {
            var numXAxes = this.xAxis.length,
                numYAxes = this.yAxis.length,
                desc = {},
                i;

            if (numXAxes) {
                desc.xAxis = 'The chart has ' + numXAxes +
                    (numXAxes > 1 ? ' X axes' : ' X axis') + ' displaying ';
                if (numXAxes < 2) {
                    desc.xAxis += this.xAxis[0].getDescription() + '.';
                } else {
                    for (i = 0; i < numXAxes - 1; ++i) {
                        desc.xAxis += (i ? ', ' : '') + this.xAxis[i].getDescription();
                    }
                    desc.xAxis += ' and ' + this.xAxis[i].getDescription() + '.';
                }
            }

            if (numYAxes) {
                desc.yAxis = 'The chart has ' + numYAxes +
                    (numYAxes > 1 ? ' Y axes' : ' Y axis') + ' displaying ';
                if (numYAxes < 2) {
                    desc.yAxis += this.yAxis[0].getDescription() + '.';
                } else {
                    for (i = 0; i < numYAxes - 1; ++i) {
                        desc.yAxis += (i ? ', ' : '') + this.yAxis[i].getDescription();
                    }
                    desc.yAxis += ' and ' + this.yAxis[i].getDescription() + '.';
                }
            }

            return desc;
        };


        // Set a11y attribs on exporting menu
        H.Chart.prototype.addAccessibleContextMenuAttribs = function() {
            var exportList = this.exportDivElements;
            if (exportList) {
                // Set tabindex on the menu items to allow focusing by script
                // Set role to give screen readers a chance to pick up the contents
                each(exportList, function(item) {
                    if (item.tagName === 'DIV' &&
                        !(item.children && item.children.length)) {
                        item.setAttribute('role', 'menuitem');
                        item.setAttribute('tabindex', -1);
                    }
                });
                // Set accessibility properties on parent div
                exportList[0].parentNode.setAttribute('role', 'menu');
                exportList[0].parentNode.setAttribute('aria-label', 'Chart export');
            }
        };


        // Add screen reader region to chart.
        // tableId is the HTML id of the table to focus when clicking the table anchor
        // in the screen reader region.
        H.Chart.prototype.addScreenReaderRegion = function(id, tableId) {
            var chart = this,
                series = chart.series,
                options = chart.options,
                a11yOptions = options.accessibility,
                hiddenSection = chart.screenReaderRegion = doc.createElement('div'),
                tableShortcut = doc.createElement('h4'),
                tableShortcutAnchor = doc.createElement('a'),
                chartHeading = doc.createElement('h4'),
                chartTypes = chart.types || [],
                // Build axis info - but not for pies and maps. Consider not adding for
                // certain other types as well (funnel, pyramid?)
                axesDesc = (
                    chartTypes.length === 1 && chartTypes[0] === 'pie' ||
                    chartTypes[0] === 'map'
                ) && {} || chart.getAxesDescription(),
                chartTypeInfo = series[0] && typeToSeriesMap[series[0].type] ||
                typeToSeriesMap['default']; // eslint-disable-line dot-notation

            hiddenSection.setAttribute('id', id);
            hiddenSection.setAttribute('role', 'region');
            hiddenSection.setAttribute(
                'aria-label',
                'Chart screen reader information.'
            );

            hiddenSection.innerHTML =
                a11yOptions.screenReaderSectionFormatter &&
                a11yOptions.screenReaderSectionFormatter(chart) ||
                '<div>Use regions/landmarks to skip ahead to chart' +
                (series.length > 1 ? ' and navigate between data series' : '') +
                '.</div><h3>' +
                (options.title.text ? htmlencode(options.title.text) : 'Chart') +
                (
                    options.subtitle && options.subtitle.text ?
                    '. ' + htmlencode(options.subtitle.text) :
                    ''
                ) +
                '</h3><h4>Long description.</h4><div>' +
                (options.chart.description || 'No description available.') +
                '</div><h4>Structure.</h4><div>Chart type: ' +
                (options.chart.typeDescription || chart.getTypeDescription()) +
                '</div>' +
                (
                    series.length === 1 ?
                    (
                        '<div>' + chartTypeInfo[0] + ' with ' +
                        series[0].points.length + ' ' +
                        (
                            series[0].points.length === 1 ?
                            chartTypeInfo[1] :
                            chartTypeInfo[2]
                        ) +
                        '.</div>'
                    ) : ''
                ) +
                (axesDesc.xAxis ? ('<div>' + axesDesc.xAxis + '</div>') : '') +
                (axesDesc.yAxis ? ('<div>' + axesDesc.yAxis + '</div>') : '');

            // Add shortcut to data table if export-data is loaded
            if (chart.getCSV) {
                tableShortcutAnchor.innerHTML = 'View as data table.';
                tableShortcutAnchor.href = '#' + tableId;
                // Make this unreachable by user tabbing
                tableShortcutAnchor.setAttribute('tabindex', '-1');
                tableShortcutAnchor.onclick =
                    a11yOptions.onTableAnchorClick || function() {
                        chart.viewData();
                        doc.getElementById(tableId).focus();
                    };
                tableShortcut.appendChild(tableShortcutAnchor);
                hiddenSection.appendChild(tableShortcut);
            }

            // Note: JAWS seems to refuse to read aria-label on the container, so add an
            // h4 element as title for the chart.
            chartHeading.innerHTML = 'Chart graphic.';
            chart.renderTo.insertBefore(chartHeading, chart.renderTo.firstChild);
            chart.renderTo.insertBefore(hiddenSection, chart.renderTo.firstChild);

            // Hide the section and the chart heading
            merge(true, chartHeading.style, hiddenStyle);
            merge(true, hiddenSection.style, hiddenStyle);
        };


        // Make chart container accessible, and wrap table functionality
        H.Chart.prototype.callbacks.push(function(chart) {
            var options = chart.options,
                a11yOptions = options.accessibility;

            if (!a11yOptions.enabled) {
                return;
            }

            var titleElement = doc.createElementNS(
                    'http://www.w3.org/2000/svg',
                    'title'
                ),
                exportGroupElement = doc.createElementNS(
                    'http://www.w3.org/2000/svg',
                    'g'
                ),
                descElement = chart.container.getElementsByTagName('desc')[0],
                textElements = chart.container.getElementsByTagName('text'),
                titleId = 'highcharts-title-' + chart.index,
                tableId = 'highcharts-data-table-' + chart.index,
                hiddenSectionId = 'highcharts-information-region-' + chart.index,
                chartTitle = options.title.text || 'Chart';

            // Add SVG title/desc tags
            titleElement.textContent = htmlencode(chartTitle);
            titleElement.id = titleId;
            descElement.parentNode.insertBefore(titleElement, descElement);
            chart.renderTo.setAttribute('role', 'region');
            chart.renderTo.setAttribute(
                'aria-label',
                stripTags(
                    'Interactive chart. ' + chartTitle +
                    '. Use up and down arrows to navigate with most screen readers.'
                )
            );

            // Set screen reader properties on export menu
            if (
                chart.exportSVGElements &&
                chart.exportSVGElements[0] &&
                chart.exportSVGElements[0].element
            ) {
                var oldExportCallback = chart.exportSVGElements[0].element.onclick,
                    parent = chart.exportSVGElements[0].element.parentNode;
                chart.exportSVGElements[0].element.onclick = function() {
                    oldExportCallback.apply(
                        this,
                        Array.prototype.slice.call(arguments)
                    );
                    chart.addAccessibleContextMenuAttribs();
                    chart.highlightExportItem(0);
                };
                chart.exportSVGElements[0].element.setAttribute('role', 'button');
                chart.exportSVGElements[0].element.setAttribute(
                    'aria-label',
                    'View export menu'
                );
                exportGroupElement.appendChild(chart.exportSVGElements[0].element);
                exportGroupElement.setAttribute('role', 'region');
                exportGroupElement.setAttribute('aria-label', 'Chart export menu');
                parent.appendChild(exportGroupElement);
            }

            // Set screen reader properties on input boxes for range selector. We need
            // to do this regardless of whether or not these are visible, as they are 
            // by default part of the page's tabindex unless we set them to -1.
            if (chart.rangeSelector) {
                each(['minInput', 'maxInput'], function(key, i) {
                    if (chart.rangeSelector[key]) {
                        chart.rangeSelector[key].setAttribute('tabindex', '-1');
                        chart.rangeSelector[key].setAttribute('role', 'textbox');
                        chart.rangeSelector[key].setAttribute(
                            'aria-label',
                            'Select ' + (i ? 'end' : 'start') + ' date.'
                        );
                    }
                });
            }

            // Hide text elements from screen readers
            each(textElements, function(el) {
                el.setAttribute('aria-hidden', 'true');
            });

            // Add top-secret screen reader region
            chart.addScreenReaderRegion(hiddenSectionId, tableId);

            // Add ID and summary attr to table HTML
            H.wrap(chart, 'getTable', function(proceed) {
                return proceed.apply(this, Array.prototype.slice.call(arguments, 1))
                    .replace(
                        '<table>',
                        '<table id="' + tableId + '" summary="Table representation ' +
                        'of chart">'
                    );
            });
        });

    }(Highcharts));
    (function(H) {
        /**
         * Accessibility module - Keyboard navigation
         *
         * (c) 2010-2017 Highsoft AS
         * Author: Oystein Moseng
         *
         * License: www.highcharts.com/license
         */
        /* eslint max-len: ["warn", 80, 4] */

        var win = H.win,
            doc = win.document,
            each = H.each,
            addEvent = H.addEvent,
            fireEvent = H.fireEvent,
            merge = H.merge,
            pick = H.pick,
            hasSVGFocusSupport;

        // Add focus border functionality to SVGElements.
        // Draws a new rect on top of element around its bounding box.
        H.extend(H.SVGElement.prototype, {
            addFocusBorder: function(margin, style) {
                // Allow updating by just adding new border
                if (this.focusBorder) {
                    this.removeFocusBorder();
                }
                // Add the border rect
                var bb = this.getBBox(),
                    pad = pick(margin, 3);
                this.focusBorder = this.renderer.rect(
                        bb.x - pad,
                        bb.y - pad,
                        bb.width + 2 * pad,
                        bb.height + 2 * pad,
                        style && style.borderRadius
                    )
                    .addClass('highcharts-focus-border')

                    .attr({
                        stroke: style && style.stroke,
                        'stroke-width': style && style.strokeWidth
                    })

                    .attr({
                        zIndex: 99
                    })
                    .add(this.parentGroup);
            },

            removeFocusBorder: function() {
                if (this.focusBorder) {
                    this.focusBorder.destroy();
                    delete this.focusBorder;
                }
            }
        });


        // Set for which series types it makes sense to move to the closest point with
        // up/down arrows, and which series types should just move to next series.
        H.Series.prototype.keyboardMoveVertical = true;
        each(['column', 'pie'], function(type) {
            if (H.seriesTypes[type]) {
                H.seriesTypes[type].prototype.keyboardMoveVertical = false;
            }
        });

        /**
         * Strip HTML tags away from a string. Used for aria-label attributes, painting
         * on a canvas will fail if the text contains tags.
         * @param  {String} s The input string
         * @return {String}   The filtered string
         */
        function stripTags(s) {
            return typeof s === 'string' ? s.replace(/<\/?[^>]+(>|$)/g, '') : s;
        }


        H.setOptions({
            accessibility: {

                /**
                 * Options for keyboard navigation.
                 * 
                 * @type {Object}
                 * @since 5.0.0
                 */
                keyboardNavigation: {

                    /**
                     * Enable keyboard navigation for the chart.
                     * 
                     * @type {Boolean}
                     * @default true
                     * @since 5.0.0
                     */
                    enabled: true,


                    /**
                     * Options for the focus border drawn around elements while
                     * navigating through them.
                     *
                     * @sample highcharts/accessibility/custom-focus
                     *			Custom focus ring
                     * @since 6.0.3
                     */
                    focusBorder: {
                        /**
                         * Enable/disable focus border for chart.
                         */
                        enabled: true,

                        /**
                         * Hide the browser's default focus indicator.
                         *
                         * @since 6.0.4
                         */
                        hideBrowserFocusOutline: true,

                        /**
                         * Style options for the focus border drawn around elements 
                         * while navigating through them. Note that some browsers in 
                         * addition draw their own borders for focused elements. These
                         * automatic borders can not be styled by Highcharts.
                         * 
                         * In styled mode, the border is given the 
                         * `.highcharts-focus-border` class.
                         */
                        style: {
                            color: '#335cad',
                            lineWidth: 2,
                            borderRadius: 3
                        },

                        /**
                         * Focus border margin around the elements.
                         */
                        margin: 2
                    },

                    /**
                     * Set the keyboard navigation mode for the chart. Can be "normal"
                     * or "serialize". In normal mode, left/right arrow keys move
                     * between points in a series, while up/down arrow keys move between
                     * series. Up/down navigation acts intelligently to figure out which
                     * series makes sense to move to from any given point.
                     *
                     * In "serialize" mode, points are instead navigated as a single 
                     * list. Left/right behaves as in "normal" mode. Up/down arrow keys
                     * will behave like left/right. This is useful for unifying 
                     * navigation behavior with/without screen readers enabled.
                     *
                     * @type {String}
                     * @default normal
                     * @since 6.0.4
                     * @apioption keyboardNavigation.mode
                     */

                    /**
                     * Skip null points when navigating through points with the
                     * keyboard.
                     * 
                     * @type {Boolean}
                     * @since 5.0.0
                     */
                    skipNullPoints: true
                }
            }
        });

        /**
         * Keyboard navigation for the legend. Requires the Accessibility module.
         * @since 5.0.14
         * @apioption legend.keyboardNavigation
         */

        /**
         * Enable/disable keyboard navigation for the legend. Requires the Accessibility
         * module.
         * 
         * @type {Boolean}
         * @see [accessibility.keyboardNavigation](#accessibility.keyboardNavigation.
         * enabled)
         * @default true
         * @since 5.0.13
         * @apioption legend.keyboardNavigation.enabled
         */


        // Abstraction layer for keyboard navigation. Keep a map of keyCodes to
        // handler functions, and a next/prev move handler for tab order. The
        // module's keyCode handlers determine when to move to another module.
        // Validate holds a function to determine if there are prerequisites for
        // this module to run that are not met. Init holds a function to run once
        // before any keyCodes are interpreted. Terminate holds a function to run
        // once before moving to next/prev module.
        // The chart object keeps track of a list of KeyboardNavigationModules.
        function KeyboardNavigationModule(chart, options) {
            this.chart = chart;
            this.id = options.id;
            this.keyCodeMap = options.keyCodeMap;
            this.validate = options.validate;
            this.init = options.init;
            this.terminate = options.terminate;
        }
        KeyboardNavigationModule.prototype = {
            // Find handler function(s) for key code in the keyCodeMap and run it.
            run: function(e) {
                var navModule = this,
                    keyCode = e.which || e.keyCode,
                    found = false,
                    handled = false;
                each(this.keyCodeMap, function(codeSet) {
                    if (codeSet[0].indexOf(keyCode) > -1) {
                        found = true;
                        handled = codeSet[1].call(navModule, keyCode, e) === false ?
                            // If explicitly returning false, we haven't handled it
                            false :
                            true;
                    }
                });
                // Default tab handler, move to next/prev module
                if (!found && keyCode === 9) {
                    handled = this.move(e.shiftKey ? -1 : 1);
                }
                return handled;
            },

            // Move to next/prev valid module, or undefined if none, and init
            // it. Returns true on success and false if there is no valid module
            // to move to.
            move: function(direction) {
                var chart = this.chart;
                if (this.terminate) {
                    this.terminate(direction);
                }
                chart.keyboardNavigationModuleIndex += direction;
                var newModule = chart.keyboardNavigationModules[
                    chart.keyboardNavigationModuleIndex
                ];

                // Remove existing focus border if any
                if (chart.focusElement) {
                    chart.focusElement.removeFocusBorder();
                }

                // Verify new module
                if (newModule) {
                    if (newModule.validate && !newModule.validate()) {
                        return this.move(direction); // Invalid module, recurse
                    }
                    if (newModule.init) {
                        newModule.init(direction); // Valid module, init it
                        return true;
                    }
                }
                // No module
                chart.keyboardNavigationModuleIndex = 0; // Reset counter

                // Set focus to chart or exit anchor depending on direction
                if (direction > 0) {
                    this.chart.exiting = true;
                    this.chart.tabExitAnchor.focus();
                } else {
                    this.chart.renderTo.focus();
                }

                return false;
            }
        };


        // Utility function to attempt to fake a click event on an element
        function fakeClickEvent(element) {
            var fakeEvent;
            if (element && element.onclick && doc.createEvent) {
                fakeEvent = doc.createEvent('Events');
                fakeEvent.initEvent('click', true, false);
                element.onclick(fakeEvent);
            }
        }


        // Determine if a point should be skipped
        function isSkipPoint(point) {
            var a11yOptions = point.series.chart.options.accessibility;
            return point.isNull && a11yOptions.keyboardNavigation.skipNullPoints ||
                point.series.options.skipKeyboardNavigation ||
                !point.series.visible ||
                point.visible === false ||
                // Skip all points in a series where pointDescriptionThreshold is
                // reached
                (a11yOptions.pointDescriptionThreshold &&
                    a11yOptions.pointDescriptionThreshold <= point.series.points.length);
        }


        // Get the point in a series that is closest (in distance) to a reference point
        // Optionally supply weight factors for x and y directions
        function getClosestPoint(point, series, xWeight, yWeight) {
            var minDistance = Infinity,
                dPoint,
                minIx,
                distance,
                i = series.points.length;
            if (point.plotX === undefined || point.plotY === undefined) {
                return;
            }
            while (i--) {
                dPoint = series.points[i];
                if (dPoint.plotX === undefined || dPoint.plotY === undefined) {
                    continue;
                }
                distance = (point.plotX - dPoint.plotX) *
                    (point.plotX - dPoint.plotX) * (xWeight || 1) +
                    (point.plotY - dPoint.plotY) *
                    (point.plotY - dPoint.plotY) * (yWeight || 1);
                if (distance < minDistance) {
                    minDistance = distance;
                    minIx = i;
                }
            }
            return minIx !== undefined && series.points[minIx];
        }


        // Pan along axis in a direction (1 or -1), optionally with a defined
        // granularity (number of steps it takes to walk across current view)
        H.Axis.prototype.panStep = function(direction, granularity) {
            var gran = granularity || 3,
                extremes = this.getExtremes(),
                step = (extremes.max - extremes.min) / gran * direction,
                newMax = extremes.max + step,
                newMin = extremes.min + step,
                size = newMax - newMin;
            if (direction < 0 && newMin < extremes.dataMin) {
                newMin = extremes.dataMin;
                newMax = newMin + size;
            } else if (direction > 0 && newMax > extremes.dataMax) {
                newMax = extremes.dataMax;
                newMin = newMax - size;
            }
            this.setExtremes(newMin, newMax);
        };


        // Set chart's focus to an SVGElement. Calls focus() on it, and draws the focus
        // border. If the focusElement argument is supplied, it draws the border around 
        // svgElement and sets the focus to focusElement.
        H.Chart.prototype.setFocusToElement = function(svgElement, focusElement) {
            var focusBorderOptions = this.options.accessibility
                .keyboardNavigation.focusBorder,
                browserFocusElement = focusElement || svgElement;
            // Set browser focus if possible
            if (
                browserFocusElement.element &&
                browserFocusElement.element.focus
            ) {
                browserFocusElement.element.focus();
                // Hide default focus ring
                if (focusBorderOptions.hideBrowserFocusOutline) {
                    browserFocusElement.css({
                        outline: 'none'
                    });
                }
            }
            if (focusBorderOptions.enabled && svgElement !== this.focusElement) {
                // Remove old focus border
                if (this.focusElement) {
                    this.focusElement.removeFocusBorder();
                }
                // Draw focus border (since some browsers don't do it automatically)
                svgElement.addFocusBorder(focusBorderOptions.margin, {
                    stroke: focusBorderOptions.style.color,
                    strokeWidth: focusBorderOptions.style.lineWidth,
                    borderRadius: focusBorderOptions.style.borderRadius
                });
                this.focusElement = svgElement;
            }
        };


        // Highlight a point (show tooltip and display hover state). Returns the
        // highlighted point.
        H.Point.prototype.highlight = function() {
            var chart = this.series.chart;
            if (!this.isNull) {
                this.onMouseOver(); // Show the hover marker and tooltip
            } else {
                if (chart.tooltip) {
                    chart.tooltip.hide(0);
                }
                // Don't call blur on the element, as it messes up the chart div's focus
            }

            // We focus only after calling onMouseOver because the state change can 
            // change z-index and mess up the element.
            if (this.graphic) {
                chart.setFocusToElement(this.graphic);
            }

            chart.highlightedPoint = this;
            return this;
        };


        // Function to highlight next/previous point in chart
        // Returns highlighted point on success, false on failure (no adjacent point to
        // highlight in chosen direction)
        H.Chart.prototype.highlightAdjacentPoint = function(next) {
            var chart = this,
                series = chart.series,
                curPoint = chart.highlightedPoint,
                curPointIndex = curPoint && curPoint.index || 0,
                curPoints = curPoint && curPoint.series.points,
                lastSeries = chart.series && chart.series[chart.series.length - 1],
                lastPoint = lastSeries && lastSeries.points &&
                lastSeries.points[lastSeries.points.length - 1],
                newSeries,
                newPoint;

            // If no points, return false
            if (!series[0] || !series[0].points) {
                return false;
            }

            if (!curPoint) {
                // No point is highlighted yet. Try first/last point depending on move
                // direction
                newPoint = next ? series[0].points[0] : lastPoint;
            } else {
                // We have a highlighted point.
                // Find index of current point in series.points array. Necessary for
                // dataGrouping (and maybe zoom?)
                if (curPoints[curPointIndex] !== curPoint) {
                    for (var i = 0; i < curPoints.length; ++i) {
                        if (curPoints[i] === curPoint) {
                            curPointIndex = i;
                            break;
                        }
                    }
                }

                // Grab next/prev point & series
                newSeries = series[curPoint.series.index + (next ? 1 : -1)];
                newPoint = curPoints[curPointIndex + (next ? 1 : -1)] ||
                    // Done with this series, try next one
                    newSeries &&
                    newSeries.points[next ? 0 : newSeries.points.length - 1];

                // If there is no adjacent point, we return false
                if (!newPoint) {
                    return false;
                }
            }

            // Recursively skip null points or points in series that should be skipped
            if (isSkipPoint(newPoint)) {
                chart.highlightedPoint = newPoint;
                return chart.highlightAdjacentPoint(next);
            }

            // There is an adjacent point, highlight it
            return newPoint.highlight();
        };


        // Highlight first valid point in a series. Returns the point if successfully
        // highlighted, otherwise false. If there is a highlighted point in the series,
        // use that as starting point.
        H.Series.prototype.highlightFirstValidPoint = function() {
            var curPoint = this.chart.highlightedPoint,
                start = (curPoint && curPoint.series) === this ? curPoint.index : 0,
                points = this.points;

            if (points) {
                for (var i = start, len = points.length; i < len; ++i) {
                    if (!isSkipPoint(points[i])) {
                        return points[i].highlight();
                    }
                }
                for (var j = start; j >= 0; --j) {
                    if (!isSkipPoint(points[j])) {
                        return points[j].highlight();
                    }
                }
            }
            return false;
        };


        // Highlight next/previous series in chart. Returns false if no adjacent series
        // in the direction, otherwise returns new highlighted point.
        H.Chart.prototype.highlightAdjacentSeries = function(down) {
            var chart = this,
                newSeries,
                newPoint,
                adjacentNewPoint,
                curPoint = chart.highlightedPoint,
                lastSeries = chart.series && chart.series[chart.series.length - 1],
                lastPoint = lastSeries && lastSeries.points &&
                lastSeries.points[lastSeries.points.length - 1];

            // If no point is highlighted, highlight the first/last point
            if (!chart.highlightedPoint) {
                newSeries = down ? (chart.series && chart.series[0]) : lastSeries;
                newPoint = down ?
                    (newSeries && newSeries.points && newSeries.points[0]) : lastPoint;
                return newPoint ? newPoint.highlight() : false;
            }

            newSeries = chart.series[curPoint.series.index + (down ? -1 : 1)];

            if (!newSeries) {
                return false;
            }

            // We have a new series in this direction, find the right point
            // Weigh xDistance as counting much higher than Y distance
            newPoint = getClosestPoint(curPoint, newSeries, 4);

            if (!newPoint) {
                return false;
            }

            // New series and point exists, but we might want to skip it
            if (!newSeries.visible) {
                // Skip the series
                newPoint.highlight();
                adjacentNewPoint = chart.highlightAdjacentSeries(down); // Try recurse
                if (!adjacentNewPoint) {
                    // Recurse failed
                    curPoint.highlight();
                    return false;
                }
                // Recurse succeeded
                return adjacentNewPoint;
            }

            // Highlight the new point or any first valid point back or forwards from it
            newPoint.highlight();
            return newPoint.series.highlightFirstValidPoint();
        };


        // Highlight the closest point vertically
        H.Chart.prototype.highlightAdjacentPointVertical = function(down) {
            var curPoint = this.highlightedPoint,
                minDistance = Infinity,
                bestPoint;

            if (curPoint.plotX === undefined || curPoint.plotY === undefined) {
                return false;
            }
            each(this.series, function(series) {
                each(series.points, function(point) {
                    if (point.plotY === undefined || point.plotX === undefined ||
                        point === curPoint) {
                        return;
                    }
                    var yDistance = point.plotY - curPoint.plotY,
                        width = Math.abs(point.plotX - curPoint.plotX),
                        distance = Math.abs(yDistance) * Math.abs(yDistance) +
                        width * width * 4; // Weigh horizontal distance highly

                    // Reverse distance number if axis is reversed
                    if (series.yAxis.reversed) {
                        yDistance *= -1;
                    }

                    if (
                        yDistance < 0 && down || yDistance > 0 && !down || // Wrong dir
                        distance < 5 || // Points in same spot => infinite loop
                        isSkipPoint(point)
                    ) {
                        return;
                    }

                    if (distance < minDistance) {
                        minDistance = distance;
                        bestPoint = point;
                    }
                });
            });

            return bestPoint ? bestPoint.highlight() : false;
        };


        // Show the export menu and focus the first item (if exists)
        H.Chart.prototype.showExportMenu = function() {
            if (this.exportSVGElements && this.exportSVGElements[0]) {
                this.exportSVGElements[0].element.onclick();
                this.highlightExportItem(0);
            }
        };


        // Hide export menu
        H.Chart.prototype.hideExportMenu = function() {
            var exportList = this.exportDivElements;
            if (exportList) {
                each(exportList, function(el) {
                    fireEvent(el, 'mouseleave');
                });
                if (
                    exportList[this.highlightedExportItem] &&
                    exportList[this.highlightedExportItem].onmouseout
                ) {
                    exportList[this.highlightedExportItem].onmouseout();
                }
                this.highlightedExportItem = 0;
                if (hasSVGFocusSupport) {
                    // Only focus if we can set focus back to the elements after 
                    // destroying the menu (#7422)
                    this.renderTo.focus();
                }
            }
        };


        // Highlight export menu item by index
        H.Chart.prototype.highlightExportItem = function(ix) {
            var listItem = this.exportDivElements && this.exportDivElements[ix],
                curHighlighted =
                this.exportDivElements &&
                this.exportDivElements[this.highlightedExportItem];

            if (
                listItem &&
                listItem.tagName === 'DIV' &&
                !(listItem.children && listItem.children.length)
            ) {
                if (listItem.focus && hasSVGFocusSupport) {
                    // Only focus if we can set focus back to the elements after 
                    // destroying the menu (#7422)
                    listItem.focus();
                }
                if (curHighlighted && curHighlighted.onmouseout) {
                    curHighlighted.onmouseout();
                }
                if (listItem.onmouseover) {
                    listItem.onmouseover();
                }
                this.highlightedExportItem = ix;
                return true;
            }
        };


        // Try to highlight the last valid export menu item
        H.Chart.prototype.highlightLastExportItem = function() {
            var chart = this,
                i;
            if (chart.exportDivElements) {
                i = chart.exportDivElements.length;
                while (i--) {
                    if (chart.highlightExportItem(i)) {
                        break;
                    }
                }
            }
        };


        // Highlight range selector button by index
        H.Chart.prototype.highlightRangeSelectorButton = function(ix) {
            var buttons = this.rangeSelector.buttons;
            // Deselect old
            if (buttons[this.highlightedRangeSelectorItemIx]) {
                buttons[this.highlightedRangeSelectorItemIx].setState(
                    this.oldRangeSelectorItemState || 0
                );
            }
            // Select new
            this.highlightedRangeSelectorItemIx = ix;
            if (buttons[ix]) {
                this.setFocusToElement(buttons[ix].box, buttons[ix]);
                this.oldRangeSelectorItemState = buttons[ix].state;
                buttons[ix].setState(2);
                return true;
            }
            return false;
        };


        // Highlight legend item by index
        H.Chart.prototype.highlightLegendItem = function(ix) {
            var items = this.legend.allItems,
                oldIx = this.highlightedLegendItemIx;
            if (items[ix]) {
                if (items[oldIx]) {
                    fireEvent(
                        items[oldIx].legendGroup.element,
                        'mouseout'
                    );
                }
                // Scroll if we have to
                if (items[ix].pageIx !== undefined &&
                    items[ix].pageIx + 1 !== this.legend.currentPage) {
                    this.legend.scroll(1 + items[ix].pageIx - this.legend.currentPage);
                }
                // Focus
                this.highlightedLegendItemIx = ix;
                this.setFocusToElement(items[ix].legendItem, items[ix].legendGroup);
                fireEvent(items[ix].legendGroup.element, 'mouseover');
                return true;
            }
            return false;
        };


        // Add keyboard navigation handling modules to chart
        H.Chart.prototype.addKeyboardNavigationModules = function() {
            var chart = this;

            function navModuleFactory(id, keyMap, options) {
                return new KeyboardNavigationModule(chart, merge({
                    keyCodeMap: keyMap
                }, {
                    id: id
                }, options));
            }

            // List of the different keyboard handling modes we use depending on where
            // we are in the chart. Each mode has a set of handling functions mapped to
            // key codes. Each mode determines when to move to the next/prev mode.
            chart.keyboardNavigationModules = [
                // Entry point catching the first tab, allowing users to tab into points
                // more intuitively.
                navModuleFactory('entry', []),

                // Points
                navModuleFactory('points', [
                    // Left/Right
                    [
                        [37, 39],
                        function(keyCode) {
                            var right = keyCode === 39;
                            if (!chart.highlightAdjacentPoint(right)) {
                                // Failed to highlight next, wrap to last/first
                                return this.init(right ? 1 : -1);
                            }
                            return true;
                        }
                    ],
                    // Up/Down
                    [
                        [38, 40],
                        function(keyCode) {
                            var down = keyCode !== 38,
                                navOptions = chart.options.accessibility.keyboardNavigation;
                            if (navOptions.mode && navOptions.mode === 'serialize') {
                                // Act like left/right
                                if (!chart.highlightAdjacentPoint(down)) {
                                    return this.init(down ? 1 : -1);
                                }
                                return true;
                            }
                            // Normal mode, move between series
                            var highlightMethod = chart.highlightedPoint &&
                                chart.highlightedPoint.series.keyboardMoveVertical ?
                                'highlightAdjacentPointVertical' :
                                'highlightAdjacentSeries';
                            chart[highlightMethod](down);
                            return true;
                        }
                    ],
                    // Enter/Spacebar
                    [
                        [13, 32],
                        function() {
                            if (chart.highlightedPoint) {
                                chart.highlightedPoint.firePointEvent('click');
                            }
                        }
                    ]
                ], {
                    // Always start highlighting from scratch when entering this module
                    init: function(dir) {
                        var numSeries = chart.series.length,
                            i = dir > 0 ? 0 : numSeries,
                            res;
                        if (dir > 0) {
                            delete chart.highlightedPoint;
                            // Find first valid point to highlight
                            while (i < numSeries) {
                                res = chart.series[i].highlightFirstValidPoint();
                                if (res) {
                                    return res;
                                }
                                ++i;
                            }
                        } else {
                            // Find last valid point to highlight
                            while (i--) {
                                chart.highlightedPoint = chart.series[i].points[
                                    chart.series[i].points.length - 1
                                ];
                                // Highlight first valid point in the series will also 
                                // look backwards. It always starts from currently
                                // highlighted point.
                                res = chart.series[i].highlightFirstValidPoint();
                                if (res) {
                                    return res;
                                }
                            }
                        }
                    },
                    // If leaving points, don't show tooltip anymore
                    terminate: function() {
                        if (chart.tooltip) {
                            chart.tooltip.hide(0);
                        }
                        delete chart.highlightedPoint;
                    }
                }),

                // Exporting
                navModuleFactory('exporting', [
                    // Left/Up
                    [
                        [37, 38],
                        function() {
                            var i = chart.highlightedExportItem || 0,
                                reachedEnd = true;
                            // Try to highlight prev item in list. Highlighting e.g.
                            // separators will fail.
                            while (i--) {
                                if (chart.highlightExportItem(i)) {
                                    reachedEnd = false;
                                    break;
                                }
                            }
                            if (reachedEnd) {
                                chart.highlightLastExportItem();
                                return true;
                            }
                        }
                    ],
                    // Right/Down
                    [
                        [39, 40],
                        function() {
                            var highlightedExportItem = chart.highlightedExportItem || 0,
                                reachedEnd = true;
                            // Try to highlight next item in list. Highlighting e.g.
                            // separators will fail.
                            for (
                                var i = highlightedExportItem + 1; i < chart.exportDivElements.length;
                                ++i
                            ) {
                                if (chart.highlightExportItem(i)) {
                                    reachedEnd = false;
                                    break;
                                }
                            }
                            if (reachedEnd) {
                                chart.highlightExportItem(0);
                                return true;
                            }
                        }
                    ],
                    // Enter/Spacebar
                    [
                        [13, 32],
                        function() {
                            fakeClickEvent(
                                chart.exportDivElements[chart.highlightedExportItem]
                            );
                        }
                    ]
                ], {
                    // Only run exporting navigation if exporting support exists and is
                    // enabled on chart
                    validate: function() {
                        return (
                            chart.exportChart &&
                            !(
                                chart.options.exporting &&
                                chart.options.exporting.enabled === false
                            )
                        );
                    },
                    // Show export menu
                    init: function(direction) {
                        chart.highlightedPoint = null;
                        chart.showExportMenu();
                        // If coming back to export menu from other module, try to
                        // highlight last item in menu
                        if (direction < 0) {
                            chart.highlightLastExportItem();
                        }
                    },
                    // Hide the menu
                    terminate: function() {
                        chart.hideExportMenu();
                    }
                }),

                // Map zoom
                navModuleFactory('mapZoom', [
                    // Up/down/left/right
                    [
                        [38, 40, 37, 39],
                        function(keyCode) {
                            chart[keyCode === 38 || keyCode === 40 ? 'yAxis' : 'xAxis'][0]
                                .panStep(keyCode < 39 ? -1 : 1);
                        }
                    ],

                    // Tabs
                    [
                        [9],
                        function(keyCode, e) {
                            var button;
                            // Deselect old
                            chart.mapNavButtons[chart.focusedMapNavButtonIx].setState(0);
                            if (
                                e.shiftKey && !chart.focusedMapNavButtonIx ||
                                !e.shiftKey && chart.focusedMapNavButtonIx
                            ) { // trying to go somewhere we can't?
                                chart.mapZoom(); // Reset zoom
                                // Nowhere to go, go to prev/next module
                                return this.move(e.shiftKey ? -1 : 1);
                            }
                            chart.focusedMapNavButtonIx += e.shiftKey ? -1 : 1;
                            button = chart.mapNavButtons[chart.focusedMapNavButtonIx];
                            chart.setFocusToElement(button.box, button);
                            button.setState(2);
                        }
                    ],

                    // Enter/Spacebar
                    [
                        [13, 32],
                        function() {
                            fakeClickEvent(
                                chart.mapNavButtons[chart.focusedMapNavButtonIx].element
                            );
                        }
                    ]
                ], {
                    // Only run this module if we have map zoom on the chart
                    validate: function() {
                        return (
                            chart.mapZoom &&
                            chart.mapNavButtons &&
                            chart.mapNavButtons.length === 2
                        );
                    },

                    // Make zoom buttons do their magic
                    init: function(direction) {
                        var zoomIn = chart.mapNavButtons[0],
                            zoomOut = chart.mapNavButtons[1],
                            initialButton = direction > 0 ? zoomIn : zoomOut;

                        each(chart.mapNavButtons, function(button, i) {
                            button.element.setAttribute('tabindex', -1);
                            button.element.setAttribute('role', 'button');
                            button.element.setAttribute(
                                'aria-label',
                                'Zoom ' + (i ? 'out ' : '') + 'chart'
                            );
                        });

                        chart.setFocusToElement(initialButton.box, initialButton);
                        initialButton.setState(2);
                        chart.focusedMapNavButtonIx = direction > 0 ? 0 : 1;
                    }
                }),

                // Highstock range selector (minus input boxes)
                navModuleFactory('rangeSelector', [
                    // Left/Right/Up/Down
                    [
                        [37, 39, 38, 40],
                        function(keyCode) {
                            var direction = (keyCode === 37 || keyCode === 38) ? -1 : 1;
                            // Try to highlight next/prev button
                            if (!chart.highlightRangeSelectorButton(
                                    chart.highlightedRangeSelectorItemIx + direction
                                )) {
                                return this.move(direction);
                            }
                        }
                    ],
                    // Enter/Spacebar
                    [
                        [13, 32],
                        function() {
                            // Don't allow click if button used to be disabled
                            if (chart.oldRangeSelectorItemState !== 3) {
                                fakeClickEvent(
                                    chart.rangeSelector.buttons[
                                        chart.highlightedRangeSelectorItemIx
                                    ].element
                                );
                            }
                        }
                    ]
                ], {
                    // Only run this module if we have range selector
                    validate: function() {
                        return (
                            chart.rangeSelector &&
                            chart.rangeSelector.buttons &&
                            chart.rangeSelector.buttons.length
                        );
                    },

                    // Make elements focusable and accessible
                    init: function(direction) {
                        each(chart.rangeSelector.buttons, function(button) {
                            button.element.setAttribute('tabindex', '-1');
                            button.element.setAttribute('role', 'button');
                            button.element.setAttribute(
                                'aria-label',
                                'Select range ' + (button.text && button.text.textStr)
                            );
                        });
                        // Focus first/last button
                        chart.highlightRangeSelectorButton(
                            direction > 0 ? 0 : chart.rangeSelector.buttons.length - 1
                        );
                    }
                }),

                // Highstock range selector, input boxes
                navModuleFactory('rangeSelectorInput', [
                    // Tab/Up/Down
                    [
                        [9, 38, 40],
                        function(keyCode, e) {
                            var direction =
                                (keyCode === 9 && e.shiftKey || keyCode === 38) ? -1 : 1,

                                newIx = chart.highlightedInputRangeIx =
                                chart.highlightedInputRangeIx + direction;

                            // Try to highlight next/prev item in list.
                            if (newIx > 1 || newIx < 0) { // Out of range
                                return this.move(direction);
                            }
                            chart.rangeSelector[newIx ? 'maxInput' : 'minInput'].focus();
                        }
                    ]
                ], {
                    // Only run if we have range selector with input boxes
                    validate: function() {
                        var inputVisible = (
                            chart.rangeSelector &&
                            chart.rangeSelector.inputGroup &&
                            chart.rangeSelector.inputGroup.element
                            .getAttribute('visibility') !== 'hidden'
                        );
                        return (
                            inputVisible &&
                            chart.options.rangeSelector.inputEnabled !== false &&
                            chart.rangeSelector.minInput &&
                            chart.rangeSelector.maxInput
                        );
                    },

                    // Highlight first/last input box
                    init: function(direction) {
                        chart.highlightedInputRangeIx = direction > 0 ? 0 : 1;
                        chart.rangeSelector[
                            chart.highlightedInputRangeIx ? 'maxInput' : 'minInput'
                        ].focus();
                    }
                }),

                // Legend navigation
                navModuleFactory('legend', [
                    // Left/Right/Up/Down
                    [
                        [37, 39, 38, 40],
                        function(keyCode) {
                            var direction = (keyCode === 37 || keyCode === 38) ? -1 : 1;
                            // Try to highlight next/prev legend item
                            if (!chart.highlightLegendItem(
                                    chart.highlightedLegendItemIx + direction
                                )) {
                                this.init(direction);
                            }
                        }
                    ],
                    // Enter/Spacebar
                    [
                        [13, 32],
                        function() {
                            fakeClickEvent(
                                chart.legend.allItems[
                                    chart.highlightedLegendItemIx
                                ].legendItem.element.parentNode
                            );
                        }
                    ]
                ], {
                    // Only run this module if we have at least one legend - wait for
                    // it - item. Don't run if the legend is populated by a colorAxis.
                    // Don't run if legend navigation is disabled.
                    validate: function() {
                        return chart.legend && chart.legend.allItems &&
                            chart.legend.display &&
                            !(chart.colorAxis && chart.colorAxis.length) &&
                            (chart.options.legend &&
                                chart.options.legend.keyboardNavigation &&
                                chart.options.legend.keyboardNavigation.enabled) !== false;
                    },

                    // Make elements focusable and accessible
                    init: function(direction) {
                        each(chart.legend.allItems, function(item) {
                            item.legendGroup.element.setAttribute('tabindex', '-1');
                            item.legendGroup.element.setAttribute('role', 'button');
                            item.legendGroup.element.setAttribute(
                                'aria-label',
                                stripTags('Toggle visibility of series ' + item.name)
                            );
                        });
                        // Focus first/last item
                        chart.highlightLegendItem(
                            direction > 0 ? 0 : chart.legend.allItems.length - 1
                        );
                    }
                })
            ];
        };


        // Add exit anchor to the chart
        // We use this to move focus out of chart whenever we want, by setting focus
        // to this div and not preventing the default tab action.
        // We also use this when users come back into the chart by tabbing back, in
        // order to navigate from the end of the chart.
        // Function returns the unbind function for the exit anchor's event handler.
        H.Chart.prototype.addExitAnchor = function() {
            var chart = this;
            chart.tabExitAnchor = doc.createElement('div');
            chart.tabExitAnchor.setAttribute('tabindex', '0');

            // Hide exit anchor
            merge(true, chart.tabExitAnchor.style, {
                position: 'absolute',
                left: '-9999px',
                top: 'auto',
                width: '1px',
                height: '1px',
                overflow: 'hidden'
            });

            chart.renderTo.appendChild(chart.tabExitAnchor);
            return addEvent(chart.tabExitAnchor, 'focus',
                function(ev) {
                    var e = ev || win.event,
                        curModule;

                    // If focusing and we are exiting, do nothing once.
                    if (!chart.exiting) {

                        // Not exiting, means we are coming in backwards
                        chart.renderTo.focus();
                        e.preventDefault();

                        // Move to last valid keyboard nav module
                        // Note the we don't run it, just set the index
                        chart.keyboardNavigationModuleIndex =
                            chart.keyboardNavigationModules.length - 1;
                        curModule = chart.keyboardNavigationModules[
                            chart.keyboardNavigationModuleIndex
                        ];

                        // Validate the module
                        if (curModule.validate && !curModule.validate()) {
                            // Invalid.
                            // Move inits next valid module in direction
                            curModule.move(-1);
                        } else {
                            // We have a valid module, init it
                            curModule.init(-1);
                        }

                    } else {
                        // Don't skip the next focus, we only skip once.
                        chart.exiting = false;
                    }
                }
            );
        };


        // Clear the chart and reset the navigation state
        H.Chart.prototype.resetKeyboardNavigation = function() {
            var chart = this,
                curMod = chart.keyboardNavigationModules[
                    chart.keyboardNavigationModuleIndex || 0
                ];
            if (curMod && curMod.terminate) {
                curMod.terminate();
            }
            if (chart.focusElement) {
                chart.focusElement.removeFocusBorder();
            }
            chart.keyboardNavigationModuleIndex = 0;
            chart.keyboardReset = true;
        };


        /**
         * On destroy, we need to clean up the focus border and the state
         */
        H.wrap(H.Series.prototype, 'destroy', function(proceed) {
            var chart = this.chart;
            if (chart.highlightedPoint && chart.highlightedPoint.series === this) {
                delete chart.highlightedPoint;
                if (chart.focusElement) {
                    chart.focusElement.removeFocusBorder();
                }
            }
            proceed.apply(this, Array.prototype.slice.call(arguments, 1));
        });


        // Add keyboard navigation events on chart load
        H.Chart.prototype.callbacks.push(function(chart) {
            var a11yOptions = chart.options.accessibility;
            if (a11yOptions.enabled && a11yOptions.keyboardNavigation.enabled) {

                // Test if we have focus support for SVG elements
                hasSVGFocusSupport = !!chart.renderTo
                    .getElementsByTagName('g')[0].focus;

                // Init nav modules. We start at the first module, and as the user
                // navigates through the chart the index will increase to use different
                // handler modules.
                chart.addKeyboardNavigationModules();
                chart.keyboardNavigationModuleIndex = 0;

                // Make chart container reachable by tab
                if (
                    chart.container.hasAttribute &&
                    !chart.container.hasAttribute('tabIndex')
                ) {
                    chart.container.setAttribute('tabindex', '0');
                }

                // Add tab exit anchor
                if (!chart.tabExitAnchor) {
                    chart.unbindExitAnchorFocus = chart.addExitAnchor();
                }

                // Handle keyboard events by routing them to active keyboard nav module
                chart.unbindKeydownHandler = addEvent(chart.renderTo, 'keydown',
                    function(ev) {
                        var e = ev || win.event,
                            curNavModule = chart.keyboardNavigationModules[
                                chart.keyboardNavigationModuleIndex
                            ];
                        chart.keyboardReset = false;
                        // If there is a nav module for the current index, run it.
                        // Otherwise, we are outside of the chart in some direction.
                        if (curNavModule) {
                            if (curNavModule.run(e)) {
                                // Successfully handled this key event, stop default
                                e.preventDefault();
                            }
                        }
                    });

                // Reset chart navigation state if we click outside the chart and it's
                // not already reset
                chart.unbindBlurHandler = addEvent(doc, 'mouseup', function() {
                    if (!chart.keyboardReset && !chart.pointer.chartPosition) {
                        chart.resetKeyboardNavigation();
                    }
                });

                // Add cleanup handlers
                addEvent(chart, 'destroy', function() {
                    chart.resetKeyboardNavigation();
                    if (chart.unbindExitAnchorFocus && chart.tabExitAnchor) {
                        chart.unbindExitAnchorFocus();
                    }
                    if (chart.unbindKeydownHandler && chart.renderTo) {
                        chart.unbindKeydownHandler();
                    }
                    if (chart.unbindBlurHandler) {
                        chart.unbindBlurHandler();
                    }
                });
            }
        });

    }(Highcharts));
}));
