/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

jeedom.appMobile = function () {};

jeedom.appMobile.postToApp = function (_action, _options) {
  let message = {}
  if (window.ReactNativeWebView != undefined) {
    message.action = _action
    message.options = _options
    window.ReactNativeWebView.postMessage(JSON.stringify(message))
  }
}

jeedom.appMobile.vibration = function (type = "impactMedium") {
  jeedom.appMobile.postToApp('vibration', {type: type})
}

jeedom.appMobile.notifee = function (title, body, time) {
   /**
   * time : display time for inapp notification, in ms
   * 
 */
  jeedom.appMobile.postToApp('notifee', {
    body: body,
    time: time,
    title: title
  });
}

jeedom.appMobile.modal = function (_options) {
  /**
   * default sizeModal : 100 (optionnal)
   * @example  _options = { 'type' : 'WebviewApp', 'uri' : '/plugins/mobile/core/php/menuForPanel.php' , 'sizeModal' : 50 }
   *   type : 'WebviewApp' for internalLink or 'urlwww' for externalLink
 */
  jeedom.appMobile.postToApp('modal', _options);
}
