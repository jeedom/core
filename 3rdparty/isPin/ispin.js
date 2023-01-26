(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
  typeof define === 'function' && define.amd ? define(factory) :
  (global.ISpin = factory());
}(this, (function () { 'use strict';

  function _classCallCheck(instance, Constructor) {
    if (!(instance instanceof Constructor)) {
      throw new TypeError("Cannot call a class as a function");
    }
  }

  function _defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
      var descriptor = props[i];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ("value" in descriptor) descriptor.writable = true;
      Object.defineProperty(target, descriptor.key, descriptor);
    }
  }

  function _createClass(Constructor, protoProps, staticProps) {
    if (protoProps) _defineProperties(Constructor.prototype, protoProps);
    if (staticProps) _defineProperties(Constructor, staticProps);
    return Constructor;
  }

  function _defineProperty(obj, key, value) {
    if (key in obj) {
      Object.defineProperty(obj, key, {
        value: value,
        enumerable: true,
        configurable: true,
        writable: true
      });
    } else {
      obj[key] = value;
    }

    return obj;
  }

  function _extends() {
    _extends = Object.assign || function (target) {
      for (var i = 1; i < arguments.length; i++) {
        var source = arguments[i];

        for (var key in source) {
          if (Object.prototype.hasOwnProperty.call(source, key)) {
            target[key] = source[key];
          }
        }
      }

      return target;
    };

    return _extends.apply(this, arguments);
  }

  function _objectSpread(target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i] != null ? arguments[i] : {};
      var ownKeys = Object.keys(source);

      if (typeof Object.getOwnPropertySymbols === 'function') {
        ownKeys = ownKeys.concat(Object.getOwnPropertySymbols(source).filter(function (sym) {
          return Object.getOwnPropertyDescriptor(source, sym).enumerable;
        }));
      }

      ownKeys.forEach(function (key) {
        _defineProperty(target, key, source[key]);
      });
    }

    return target;
  }

  function _toConsumableArray(arr) {
    return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _nonIterableSpread();
  }

  function _arrayWithoutHoles(arr) {
    if (Array.isArray(arr)) {
      for (var i = 0, arr2 = new Array(arr.length); i < arr.length; i++) arr2[i] = arr[i];

      return arr2;
    }
  }

  function _iterableToArray(iter) {
    if (Symbol.iterator in Object(iter) || Object.prototype.toString.call(iter) === "[object Arguments]") return Array.from(iter);
  }

  function _nonIterableSpread() {
    throw new TypeError("Invalid attempt to spread non-iterable instance");
  }

  var ISpin =
  /*#__PURE__*/
  function () {
    function ISpin(el, opts) {
      _classCallCheck(this, ISpin);

      this.el = el; // $FlowFixMe temporary assignment

      this.options = {};
      this._onKeyDown = this._onKeyDown.bind(this);
      this._onMouseDown = this._onMouseDown.bind(this);
      this._onMouseUp = this._onMouseUp.bind(this);
      this._onMouseLeave = this._onMouseLeave.bind(this);
      this._onWheel = this._onWheel.bind(this);
      this.build();
      this.update(opts);
    }

    _createClass(ISpin, [{
      key: "build",
      value: function build() {
        var _this = this;

        // wrap element
        this._wrapper = document.createElement('div');
        if (this.el.parentNode) this.el.parentNode.replaceChild(this._wrapper, this.el);

        this._wrapper.appendChild(this.el); // add buttons


        this._buttons = {
          inc: document.createElement('button'),
          dec: document.createElement('button')
        }; // listen to events

        Object.keys(this._buttons).forEach(function (k) {
          var b = _this._buttons[k];

          _this._wrapper.appendChild(b);

          b.setAttribute('type', 'button');
          b.addEventListener('mousedown', _this._onMouseDown);
          b.addEventListener('mouseup', _this._onMouseUp);
          b.addEventListener('mouseleave', _this._onMouseLeave);
        });
        this.el.addEventListener('keydown', this._onKeyDown);
        this.el.addEventListener('wheel', this._onWheel);
      }
    }, {
      key: "update",
      value: function update(opts) {
        var _this2 = this;

        opts = _objectSpread({}, ISpin.DEFAULTS, this.options, opts); // update wrapper class

        if (opts.wrapperClass !== this.options.wrapperClass) {
          if (this.options.wrapperClass) this._wrapper.classList.remove(this.options.wrapperClass);
          if (opts.wrapperClass) this._wrapper.classList.add(opts.wrapperClass);
        }

        if (opts.buttonsClass !== this.options.buttonsClass) {
          if (this.options.buttonsClass) {
            Object.keys(this._buttons).forEach(function (k) {
              _this2._buttons[k].classList.remove(_this2.options.buttonsClass);

              _this2._buttons[k].classList.remove(_this2.options.buttonsClass + '-' + k);
            });
          }

          if (opts.buttonsClass) {
            Object.keys(this._buttons).forEach(function (k) {
              _this2._buttons[k].classList.add(opts.buttonsClass);

              _this2._buttons[k].classList.add(opts.buttonsClass + '-' + k);
            });
          }
        }

        this.disabled = opts.disabled;

        _extends(this.options, opts);
      }
    }, {
      key: "destroy",
      value: function destroy() {
        if (this._wrapper.parentNode) this._wrapper.parentNode.replaceChild(this.el, this._wrapper);
        delete this.el;
        delete this._wrapper;
        delete this._buttons;
      }
    }, {
      key: "_onKeyDown",
      value: function _onKeyDown(e) {
        switch (e.keyCode) {
          case 38:
            // arrow up
            e.preventDefault();
            return this.spin(this.options.step);

          case 40:
            // arrow down
            e.preventDefault();
            return this.spin(-this.options.step);

          case 33:
            // page up
            e.preventDefault();
            return this.spin(this.options.pageStep);

          case 34:
            // page down
            e.preventDefault();
            return this.spin(-this.options.pageStep);
        }
      }
    }, {
      key: "_onMouseDown",
      value: function _onMouseDown(e) {
        e.preventDefault();
        var direction = e.currentTarget === this._buttons.inc ? 1 : -1;
        this.spin(direction * this.options.step);
        this.el.focus();

        this._startSpinning(direction);
      }
    }, {
      key: "_onMouseUp",
      value: function _onMouseUp(e) {
        this._stopSpinning();
      }
    }, {
      key: "_onMouseLeave",
      value: function _onMouseLeave(e) {
        this._stopSpinning();
      }
    }, {
      key: "_startSpinning",
      value: function _startSpinning(direction) {
        var _this3 = this;

        this._stopSpinning();

        this._spinTimer = setInterval(function () {
          return _this3.spin(direction * _this3.options.step);
        }, this.options.repeatInterval);
      }
    }, {
      key: "_stopSpinning",
      value: function _stopSpinning() {
        clearInterval(this._spinTimer);
      }
    }, {
      key: "_onWheel",
      value: function _onWheel(e) {
        if (document.activeElement !== this.el) return;
        e.preventDefault();
        var direction = e.deltaY > 0 ? -1 : 1;
        this.spin(direction * this.options.step);
      }
    }, {
      key: "adjustValue",
      value: function adjustValue(value) {
        value = Number(value.toFixed(this.precision));
        if (this.options.max != null && value > this.options.max) value = this.options.max;
        if (this.options.min != null && value < this.options.min) value = this.options.min;
        return value;
      }
    }, {
      key: "wrapValue",
      value: function wrapValue(value) {
        if (this.options.wrapOverflow && this.options.max != null && this.options.min != null) {
          if (value < this.options.min) value = this.options.max;else if (value > this.options.max) value = this.options.min;
        }

        return value;
      }
    }, {
      key: "spin",
      value: function spin(step) {
        this.value = this.adjustValue(this.wrapValue(this.value + step));
      }
    }, {
      key: "value",
      get: function get() {
        return this.options.parse(this.el.value) || 0;
      },
      set: function set(value) {
        var strValue = this.options.format(this.options.parse(String(value)));
        this.el.value = strValue;
        if (this.options.onChange) this.options.onChange(strValue);
      }
    }, {
      key: "disabled",
      get: function get() {
        return this._buttons.inc.disabled;
      },
      set: function set(disabled) {
        if (this.disabled === disabled) return;
        this._buttons.inc.disabled = this._buttons.dec.disabled = disabled;
      }
    }, {
      key: "precision",
      get: function get() {
        return Math.max.apply(Math, _toConsumableArray([this.options.step, this.options.min].filter(function (v) {
          return v != null;
        }) // $FlowFixMe already checked above
        .map(precision)));
      }
    }]);

    return ISpin;
  }();
  ISpin.DEFAULTS = {
    wrapperClass: 'ispin-wrapper',
    buttonsClass: 'ispin-button',
    step: 1,
    pageStep: 10,
    disabled: false,
    repeatInterval: 200,
    wrapOverflow: false,
    parse: Number,
    format: String
  };

  function precision(num) {
    return (String(num).split('.')[1] || '').length;
  }

  return ISpin;

})));
//# sourceMappingURL=ispin.js.map
