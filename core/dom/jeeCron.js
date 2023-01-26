/*
 * Adapted from http://shawnchin.github.com/jquery-cron without jQuery requirement
 * Some options like url, save, user input have been removed for simplification
 *
 * Notes:
 * At this stage, we only support a subset of possible cron options.
 * For example, each cron entry can only be digits or "*", no commas
 * to denote multiple entries. We also limit the allowed combinations:
 * - Every minute : * * * * *
 * - Every hour   : ? * * * *
 * - Every day    : ? ? * * *
 * - Every week   : ? ? * * ?
 * - Every month  : ? ? ? * *
 * - Every year   : ? ? ? ? *
 */

var jeeCron = function(_UIgenerator, _options) {
  'use strict'
  var jCrInstance = {
    _description: 'js cron generator. /core/dom/jeeCron.js'
  }

  var _c_ = {} //Internal passthrough

  var defaultOptions = {
    initial : "* * * * *",
    minuteOpts : {
      minWidth  : 100, // only applies if columns and itemWidth not set
      itemWidth : 30,
      columns   : 4,
      rows      : undefined,
      title     : "{{Minutes Past the Hour}}"
    },
    timeHourOpts : {
      minWidth  : 100, // only applies if columns and itemWidth not set
      itemWidth : 20,
      columns   : 2,
      rows      : undefined,
      title     : "{{Time: Hour}}"
    },
    domOpts : {
      minWidth  : 100, // only applies if columns and itemWidth not set
      itemWidth : 30,
      columns   : undefined,
      rows      : 10,
      title     : "{{Day of Month}}"
    },
    monthOpts : {
      minWidth  : 100, // only applies if columns and itemWidth not set
      itemWidth : 100,
      columns   : 2,
      rows      : undefined,
      title     : undefined
    },
    dowOpts : {
      minWidth  : 100, // only applies if columns and itemWidth not set
      itemWidth : undefined,
      columns   : undefined,
      rows      : undefined,
      title     : undefined
    },
    timeMinuteOpts : {
      minWidth  : 100, // only applies if columns and itemWidth not set
      itemWidth : 20,
      columns   : 4,
      rows      : undefined,
      title     : "{{Time: Minute}}"
    },
    customValues : undefined,
    onChange: undefined, // callback function each time value changes
  }
  var options = _options ? _options : {}
  var o = domUtils.extend({}, defaultOptions, options)
  domUtils.extend(o, {
      minuteOpts     : domUtils.extend({}, defaultOptions.minuteOpts, options.minuteOpts),
      domOpts        : domUtils.extend({}, defaultOptions.domOpts, options.domOpts),
      monthOpts      : domUtils.extend({}, defaultOptions.monthOpts, options.monthOpts),
      dowOpts        : domUtils.extend({}, defaultOptions.dowOpts, options.dowOpts),
      timeHourOpts   : domUtils.extend({}, defaultOptions.timeHourOpts, options.timeHourOpts),
      timeMinuteOpts : domUtils.extend({}, defaultOptions.timeMinuteOpts, options.timeMinuteOpts)
  })

  jCrInstance.root = _UIgenerator
  // store options and block pointer
  jCrInstance.options = o
  jCrInstance.block = {}

  getDefaultStrOps()
  build()
  // remember base value to detect changes:
  jCrInstance.current_value = o.initial

  function getDefaultStrOps() {
    // -------  build some static data -------

    // options for minutes in an hour
    _c_.str_opt_mih = ""
    for (var i = 0; i < 60; i++) {
      var j = (i < 10)? "0":""
      _c_.str_opt_mih += "<option value='"+i+"'>" + j +  i + "</option>\n"
    }

    // options for hours in a day
    _c_.str_opt_hid = ""
    for (var i = 0; i < 24; i++) {
      var j = (i < 10)? "0":""
      _c_.str_opt_hid += "<option value='"+i+"'>" + j + i + "</option>\n"
    }

    // options for days of month
    _c_.str_opt_dom = ""
    for (var i = 1; i < 32; i++) {
      if (i == 1 || i == 21 || i == 31) { var suffix = "st" }
      else if (i == 2 || i == 22) { var suffix = "nd" }
      else if (i == 3 || i == 23) { var suffix = "rd" }
      else { var suffix = "th"; }
      _c_.str_opt_dom += "<option value='"+i+"'>" + i + suffix + "</option>\n";
    }

    // options for months
    _c_.str_opt_month = ""
    var months = ['{{Janvier}}', '{{Février}}', '{{Mars}}', '{{Avril}}', '{{Mai}}', '{{Juin}}', '{{Juillet}}', '{{Août}}', '{{Septembre}}', '{{Octobre}}', '{{Novembre}}', '{{Décembre}}']
    for (var i = 0; i < months.length; i++) {
      _c_.str_opt_month += "<option value='"+(i+1)+"'>" + months[i] + "</option>\n"
    }

    // options for day of week
    _c_.str_opt_dow = ""
    var days = ['{{Dimanche}}', '{{Lundi}}', '{{Mardi}}', '{{Mercredi}}', '{{Jeudi}}', '{{Vendredi}}', '{{Samedi}}']
    for (var i = 0; i < days.length; i++) {
      _c_.str_opt_dow += "<option value='"+i+"'>" + days[i] + "</option>\n"
    }

    // options for period
    _c_.str_opt_period = ""
    var periods = {'minute':'{{minute}}', 'hour':'{{heure}}', 'day':'{{jour}}', 'week':'{{semaine}}', 'month':'{{mois}}', 'year':'{{année}}'}
    for (var i in periods) {
      _c_.str_opt_period += "<option value='" + i + "'>" + periods[i] + "</option>\n"
    }

    // display matrix
    _c_.toDisplay = {
      "minute" : [],
      "hour"   : ["mins"],
      "day"    : ["time"],
      "week"   : ["dow", "time"],
      "month"  : ["dom", "time"],
      "year"   : ["dom", "month", "time"]
    }

    _c_.combinations = {
      "minute" : /^(\*\s){4}\*$/,                    // "* * * * *"
      "hour"   : /^\d{1,2}\s(\*\s){3}\*$/,           // "? * * * *"
      "day"    : /^(\d{1,2}\s){2}(\*\s){2}\*$/,      // "? ? * * *"
      "week"   : /^(\d{1,2}\s){2}(\*\s){2}\d{1,2}$/, // "? ? * * ?"
      "month"  : /^(\d{1,2}\s){3}\*\s\*$/,           // "? ? ? * *"
      "year"   : /^(\d{1,2}\s){4}\*$/                // "? ? ? ? *"
    }
  }

  function build() {
    var custom_periods = ""
    var cv = o.customValues
    if (cv) { // prepend custom values if specified
      for (var key in cv) {
        custom_periods += "<option value='" + cv[key] + "'>" + key + "</option>\n"
      }
    }

    jCrInstance.block["period"] = "<span class='cron-period'>"
            + "{{Chaque}} <select name='cron-period'>" + custom_periods
            + _c_.str_opt_period + "</select> </span>"
    _UIgenerator.insertAdjacentHTML('beforeend', jCrInstance.block["period"])
    jCrInstance.block["period"] = _UIgenerator.querySelector('span.cron-period')
    jCrInstance.block["period"]._jeeCron = {root: _UIgenerator}

    var select = jCrInstance.block["period"].querySelector("select")
    select._jeeCron = {root: _UIgenerator, options: o}

    select.addEventListener("change", function(event) {
      jCrInstance.periodChanged(event)
    })

    jCrInstance.block["dom"] = "<span class='cron-block cron-block-dom'>"
            + "{{Le}}  <select name='cron-dom'>" + _c_.str_opt_dom
            + "</select> </span>"
    _UIgenerator.insertAdjacentHTML('beforeend', jCrInstance.block["dom"])
    jCrInstance.block["dom"] = _UIgenerator.querySelector('span.cron-block-dom')
    jCrInstance.block["dom"]._jeeCron = {root: _UIgenerator}

    select = jCrInstance.block["dom"].querySelector("select")._jeeCron = {root: _UIgenerator}

    jCrInstance.block["month"] = "<span class='cron-block cron-block-month'>"
            + "{{De}} <select name='cron-month'>" + _c_.str_opt_month
            + "</select> </span>"
    _UIgenerator.insertAdjacentHTML('beforeend', jCrInstance.block["month"])
    jCrInstance.block["month"] = _UIgenerator.querySelector('span.cron-block-month')
    jCrInstance.block["month"]._jeeCron = {root: _UIgenerator}

    select = jCrInstance.block["month"].querySelector("select")._jeeCron = {root: _UIgenerator}

    jCrInstance.block["mins"] = "<span class='cron-block cron-block-mins'>"
            + "{{A}} <select name='cron-mins'>" + _c_.str_opt_mih
            + "</select> {{minutes après l'heure}} </span>"
    _UIgenerator.insertAdjacentHTML('beforeend', jCrInstance.block["mins"])
    jCrInstance.block["mins"] = _UIgenerator.querySelector('span.cron-block-mins')
    jCrInstance.block["mins"]._jeeCron = {root: _UIgenerator}

    select = jCrInstance.block["mins"].querySelector("select")._jeeCron = {root: _UIgenerator}

    jCrInstance.block["dow"] = "<span class='cron-block cron-block-dow'>"
            + "{{Le}}  <select name='cron-dow'>" + _c_.str_opt_dow
            + "</select> </span>"
    _UIgenerator.insertAdjacentHTML('beforeend', jCrInstance.block["dow"])
    jCrInstance.block["dow"] = _UIgenerator.querySelector('span.cron-block-dow')
    jCrInstance.block["dow"]._jeeCron = {root: _UIgenerator}

    select = jCrInstance.block["dow"].querySelector("select")._jeeCron = {root: _UIgenerator}

    jCrInstance.block["time"] = "<span class='cron-block cron-block-time'>"
            + "{{A}} <select name='cron-time-hour' class='cron-time-hour'>" + _c_.str_opt_hid
            + "</select>:<select name='cron-time-min' class='cron-time-min'>" + _c_.str_opt_mih
            + " </span>"
    _UIgenerator.insertAdjacentHTML('beforeend', jCrInstance.block["time"])
    jCrInstance.block["time"] = _UIgenerator.querySelector('span.cron-block-time')
    jCrInstance.block["time"]._jeeCron = {root: _UIgenerator}

    jCrInstance.block["time"].querySelector("select.cron-time-hour")._jeeCron = {root: _UIgenerator}
    jCrInstance.block["time"].querySelector("select.cron-time-min")._jeeCron = {root: _UIgenerator}


    _UIgenerator.querySelectorAll('select').forEach(_select => {
      _select.addEventListener("change", function(event) {
        jCrInstance.dataChanged(event)
      })
    })
  }

  function getCurrentValue(_jCr) {
    var block = _jCr.block

    var min, hour, day, month, dow
    min = hour = day = month = dow = "*"
    var selectedPeriod = block["period"].querySelector("select").value

    switch (selectedPeriod) {
        case "minute":
            break;
        case "hour":
            min = block["mins"].querySelector("select").value
            break
        case "day":
            min  = block["time"].querySelector("select.cron-time-min").value
            hour = block["time"].querySelector("select.cron-time-hour").value
            break
        case "week":
            min  = block["time"].querySelector("select.cron-time-min").value
            hour = block["time"].querySelector("select.cron-time-hour").value
            dow  =  block["dow"].querySelector("select").value
            break
        case "month":
            min  = block["time"].querySelector("select.cron-time-min").value
            hour = block["time"].querySelector("select.cron-time-hour").value
            day  = block["dom"].querySelector("select").value
            break
        case "year":
            min  = block["time"].querySelector("select.cron-time-min").value
            hour = block["time"].querySelector("select.cron-time-hour").value
            day  = block["dom"].querySelector("select").value
            month = block["month"].querySelector("select").value
            break
        default:
            // we assume this only happens when customValues is set
            return selectedPeriod
    }
    return [min, hour, day, month, dow].join(" ")
  }

  //Call it in onChange user callback:
  jCrInstance.value = function(_cron_str) {
    if (!_cron_str) {
      return getCurrentValue(this)
    }
  }

  //First select period changed, show/hide related options
  jCrInstance.periodChanged = function(event) {
    var period = this.block['period'].querySelector('select').value
    this.root.querySelectorAll("span.cron-block").unseen()
    if (_c_.toDisplay.hasOwnProperty(period)) {// not custom value
      var b = _c_.toDisplay[period]
      for (var i = 0; i < b.length; i++) {
        this.block[b[i]].seen()
      }
    }
  }

  jCrInstance.dataChanged = function(event) {
    //Callback:
    if (this.options.onChange && typeof this.options.onChange === 'function') {
        this.options.onChange.apply(this, [this.current_value])
    }
  }

  jCrInstance.value()

  _UIgenerator._jeeCron = jCrInstance
  return jCrInstance
}

