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
window.addEventListener('click', function(event) {
  if (!event.path) {
    return;
  }
});
if (getUrlVars('fullscreen') == '1') {
  $('#div_colPlan3d').removeClass('col-lg-10').addClass('col-lg-12');
  $('#div_colMenu').remove();
  $('header').hide();
  $('footer').hide();
  $('#div_mainContainer').css('margin-top', '-50px');
  $('#wrap').css('margin-bottom', '0px');
  $('#div_colPlan3d').height($('html').height());
}
var container, scene, camera, renderer, controls;
var SCREEN_WIDTH = $('#div_display3d').width();
var SCREEN_HEIGHT = $('#div_display3d').height();
var JEEDOM_OBJECT = [];
var CMDS = {};
var raycaster = new THREE.Raycaster();
var mouse = new THREE.Vector2();
var EDIT_MODE = 0

display3d(plan3dHeader_id);

$('#bt_editMode').on('click', function() {
  EDIT_MODE = (EDIT_MODE == 0) ? 1 : 0;
  if (EDIT_MODE) {
    $(this).removeClass('btn-default').addClass('btn-success');
    $('#div_btEdit').show();
  } else {
    $(this).removeClass('btn-success').addClass('btn-default');
    $('#div_btEdit').hide();
    refresh3dObject();
  }
});

$('#bt_showAllObject').on('click', function() {
  jeedom.plan3d.byplan3dHeader({
    plan3dHeader_id: plan3dHeader_id,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      });
    },
    success: function(data) {
      for (var i in data) {
        var object = scene.getObjectByName(data[i].name);
        if (object) {
          object.visible = true
        }
      }
    }
  });
});

$('#bt_plan3dHeaderConfigure').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Configuration du plan 3D}}"
  }).load('index.php?v=d&modal=plan3dHeader.configure&plan3dHeader_id=' + plan3dHeader_id).dialog('open')
});

$('#bt_plan3dHeaderAdd').on('click', function() {
  bootbox.prompt("{{Nom du design 3D ?}}", function(result) {
    if (result !== null) {
      jeedom.plan3d.saveHeader({
        plan3dHeader: {
          name: result
        },
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          });
        },
        success: function(data) {
          window.location = 'index.php?v=d&p=plan3d&plan3d_id=' + data.id;
        }
      });
    }
  });
});

$('#bt_plan3dHeaderFullScreen').on('click', function() {
  window.location.href = 'index.php?v=d&fullscreen=1&p=plan3d&plan3d_id=' + plan3dHeader_id;
});

$('body').on('cmd::update', function(_event, _options) {
  if (EDIT_MODE) {
    return;
  }
  for (var i in _options) {
    if (CMDS[_options[i].cmd_id]) {
      for (var j in CMDS[_options[i].cmd_id]) {
        try {
          jeedom3d[j].update(_options[i]);
        } catch (e) {

        }
      }
    }
  }
})

window.addEventListener('resize', function() {
  if (getUrlVars('fullscreen') == '1') {
    $('#div_colPlan3d').height($('html').height());
  } else {
    $('#div_colPlan3d').style('height', '')
  }
  SCREEN_WIDTH = $('#div_display3d').width();
  SCREEN_HEIGHT = $('#div_display3d').height();
  try {
    camera.aspect = SCREEN_WIDTH / SCREEN_HEIGHT;
    camera.updateProjectionMatrix();
    renderer.setSize(SCREEN_WIDTH, SCREEN_HEIGHT);
  } catch (error) {}
}, false);

window.addEventListener('dblclick', function() {
  if (!isset(event.path)) return
  if (!EDIT_MODE || event.path[0].nodeName != 'CANVAS') {
    return;
  }
  offset = $('#div_display3d').offset();
  mouse.x = ((event.clientX - offset.left) / SCREEN_WIDTH) * 2 - 1;
  mouse.y = -((event.clientY - offset.top) / SCREEN_HEIGHT) * 2 + 1;
  raycaster.setFromCamera(mouse, camera);
  var intersects = raycaster.intersectObjects(scene.children, true);
  if (intersects.length > 0 && intersects[0].object.name != '') {
    $('#md_modal').dialog({
      title: "{{Configuration de l\'objet}}"
    }).load('index.php?v=d&modal=plan3d.configure&&plan3dHeader_id=' + plan3dHeader_id + '&name=' + intersects[0].object.name).dialog('open')
  }
}, false);

window.addEventListener('click', handleClick3d, false);
window.addEventListener('touchend', handleClick3d, false);

function handleClick3d(event) {
  if (!isset(event.path)) return
  if (!event.path[0] || event.path[0].nodeName != 'CANVAS') {
    return;
  }
  $('#md_plan3dWidget').empty();
  offset = $('#div_display3d').offset();
  mouse.x = ((event.clientX - offset.left) / SCREEN_WIDTH) * 2 - 1;
  mouse.y = -((event.clientY - offset.top) / SCREEN_HEIGHT) * 2 + 1;
  raycaster.setFromCamera(mouse, camera);
  var intersects = raycaster.intersectObjects(scene.children, true);
  if (intersects.length > 0) {
    jeedom.plan3d.byName({
      global: false,
      name: intersects[0].object.name,
      plan3dHeader_id: plan3dHeader_id,
      error: function(request, status, error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        });
      },
      success: function(data) {
        if (data.html) {
          $('#md_plan3dWidget').empty().append(data.html)
          jeedomUtils.positionEqLogic();
        }
      }
    });
  }
}

function display3d(_id) {
  if (_id == -1) {
    return;
  }
  jeedom.plan3d.getHeader({
    id: _id,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      });
    },
    success: function(data) {
      if (!data.configuration || !data.configuration.path || !data.configuration.objfile) {
        return;
      }
      $.showLoading();
      projector = new THREE.Projector();
      mouseVector = new THREE.Vector3();
      THREE.Vector3.Zero = new THREE.Vector3(0, 0, 0);
      THREE.Vector3.XAxis = new THREE.Vector3(1, 0, 0);
      container = document.getElementById("div_display3d");
      scene = new THREE.Scene();
      if (data.configuration.backgroundColor && data.configuration.backgroundColor != '#000000') {
        scene.background = new THREE.Color(data.configuration.backgroundColor);
      } else {
        scene.background = new THREE.Color(0xaaaaaa);
      }
      camera = new THREE.PerspectiveCamera(45, SCREEN_WIDTH / SCREEN_HEIGHT, 0.1, 99999999);
      scene.add(camera);
      if (data.configuration.globalLightPower) {
        var hemiLight = new THREE.HemisphereLight(0xffffff, 0xffffff, data.configuration.globalLightPower);
      } else {
        var hemiLight = new THREE.HemisphereLight(0xffffff, 0xffffff, 0.3);
      }
      scene.add(hemiLight);
      renderer = new THREE.WebGLRenderer({
        antialias: true
      });
      renderer.setSize(SCREEN_WIDTH, SCREEN_HEIGHT);
      container.appendChild(renderer.domElement);
      if (data.configuration.mtlfile && data.configuration.mtlfile != '') {
        $.showLoading();
        var mtlLoader = new THREE.MTLLoader();
        mtlLoader.setPath(data.configuration.path);
        mtlLoader.load(data.configuration.mtlfile, function(materials) {
          $.showLoading();
          materials.lights = false;
          materials.preload();
          var objLoader = new THREE.OBJLoader();
          objLoader.setMaterials(materials);
          objLoader.load(data.configuration.path + data.configuration.objfile, function(object) {
            $('#span_loadPercent3dPlan').remove();
            var bBox = new THREE.Box3().setFromObject(object);
            camera.position.set(bBox.max.x * 1.3, bBox.max.y * 1.3, bBox.max.z * 1.3);
            object.position.x = -(bBox.max.x - bBox.min.x) / 2;
            object.position.y = -bBox.min.y;
            object.position.z = -(bBox.max.z - bBox.min.z) / 2;
            scene.add(object);
            camera.lookAt(object.position);
            add3dObjects(_id);
            $.hideLoading();
          }, function(progress) {
            $('#span_loadPercent3dPlan').remove();
            $('body').append('<span id="span_loadPercent3dPlan" style="font-size:4em;z-index:9999;position:fixed;top: 40%;left : 47%;">2/2 : ' + Math.round((progress.loaded / progress.total) * 100) + '%' + '</span>');
          }, function(error) {
            console.log(error)
          });
        }, function(progress) {
          $('#span_loadPercent3dPlan').remove();
          $('body').append('<span id="span_loadPercent3dPlan" style="font-size:4em;z-index:9999;position:fixed;top: 40%;left : 47%;">1/2 : ' + Math.round((progress.loaded / progress.total) * 100) + '%' + '</span>');
        }, function(error) {
          console.log(error)
        });
      } else {
        $.showLoading();
        var objLoader = new THREE.OBJLoader();
        objLoader.load(data.configuration.path + data.configuration.objfile, function(object) {
          $('#span_loadPercent3dPlan').remove();
          var bBox = new THREE.Box3().setFromObject(object);
          camera.position.set(bBox.max.x * 1.3, bBox.max.y * 1.3, bBox.max.z * 1.3);
          object.position.x = -(bBox.max.x - bBox.min.x) / 2;
          object.position.y = -bBox.min.y;
          object.position.z = -(bBox.max.z - bBox.min.z) / 2;
          scene.add(object);
          camera.lookAt(object.position);
          add3dObjects(_id);
          $.hideLoading();
        }, function(progress) {
          $('#span_loadPercent3dPlan').remove();
          $('body').append('<span id="span_loadPercent3dPlan" style="font-size:4em;z-index:9999;position:fixed;top: 40%;left : 51%;">' + Math.round((progress.loaded / progress.total) * 100) + '%' + '</span>');
        }, function(error) {
          console.log(error)
        });
      }
      controls = new THREE.OrbitControls(camera, renderer.domElement);
      controls.maxPolarAngle = 0.9 * Math.PI / 2;
      controls.addEventListener('change', render);
      scene.add(new THREE.HemisphereLight(0xffffbb, 0x080820, 0.5));
      renderer.render(scene, camera);
      animate();
    }
  });
}

function animate() {
  requestAnimationFrame(animate);
  render();
  controls.update();
}

function render() {
  renderer.render(scene, camera);
}

function refresh3dObject() {
  CMDS = {};
  for (var i in JEEDOM_OBJECT) {
    var object = scene.getObjectByProperty('uuid', JEEDOM_OBJECT[i]);
    if (object) {
      scene.remove(object)
    }
  }
  JEEDOM_OBJECT = [];
  add3dObjects(plan3dHeader_id);
}

function add3dObjects(_id) {
  jeedom.plan3d.byplan3dHeader({
    plan3dHeader_id: _id,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      });
    },
    success: function(data) {
      for (var i in data) {
        add3dObject(data[i]);
      }
    }
  });
}

function add3dObject(_info) {
  if (!_info.configuration || !_info.configuration['3d::widget'] || _info.configuration['3d::widget'] == '') {
    return;
  }
  var object = scene.getObjectByName(_info.name);
  if (!object) {
    return;
  }
  if (jeedom3d[_info.configuration['3d::widget']]) {
    if (jeedom3d[_info.configuration['3d::widget']].reset) {
      jeedom3d[_info.configuration['3d::widget']].reset(_info, object);
    }
    jeedom3d[_info.configuration['3d::widget']].create(_info, object);
  }
}

/***************************************JEEDOM 3D OBJECT***************************/

jeedom3d = function() {};

/***************************************LIGHT***************************/

jeedom3d.light = function() {};

jeedom3d.light.create = function(_info, _object) {
  var bBox = new THREE.Box3().setFromObject(_object);
  light = new THREE.PointLight(new THREE.Color('#ffffff'), 0, 300, 2);
  light.position.set((bBox.max.x - bBox.min.x) / 2 + bBox.min.x, (bBox.max.y - bBox.min.y) / 2 + bBox.min.y, (bBox.max.z - bBox.min.z) / 2 + bBox.min.z);
  light.castShadow = true;
  JEEDOM_OBJECT.push(light.uuid);
  scene.add(light);
  if (!_info.additionalData.cmd_id) {
    return;
  }
  if (!CMDS[_info.additionalData.cmd_id]) {
    CMDS[_info.additionalData.cmd_id] = {
      'light': []
    };
  } else if (!CMDS[_info.additionalData.cmd_id]['light']) {
    CMDS[_info.additionalData.cmd_id]['light'] = [];
  }
  CMDS[_info.additionalData.cmd_id]['light'].push({
    object: light,
    info: _info
  });
  jeedom3d.light.update({
    display_value: _info.additionalData.state,
    cmd_id: _info.additionalData.cmd_id
  });
};

jeedom3d.light.update = function(_options) {
  var lights = CMDS[_options.cmd_id]['light']
  for (var i in lights) {
    var max = lights[i].info.configuration['3d::widget::light::power'] || 6
    var intensity = 0;
    var color = '#ffffff';
    if (_options.display_value) {
      intensity = max;
      if (lights[i].info.additionalData.subType == 'numeric') {
        intensity = (max / 100) * _options.display_value;
      }
      if (lights[i].info.additionalData.subType == 'string') {
        color = _options.display_value;
        if (color == '#000000') {
          intensity = 0;
        }
      }
    }
    lights[i].object.intensity = intensity;
    lights[i].object.color = new THREE.Color(color);
  }
}

/***************************************TEXT***************************/

jeedom3d.text = function() {};
jeedom3d.text.data = {};

jeedom3d.text.reset = function(_info, _object) {
  if (!jeedom3d.text.data[_object.uuid]) {
    return;
  }
  for (var j in scene.children) {
    if (scene.children[j].uuid == jeedom3d.text.data[_object.uuid]) {
      scene.remove(scene.children[j]);
    }
  }
}

jeedom3d.text.create = function(_info, _object) {
  var text = jeedom3d.text.generate(_info, _object, _info.additionalData.text);
  scene.add(text);
  jeedom3d.text.data[_object.uuid] = text.uuid;
  if (_info.additionalData.cmds) {
    for (var i in _info.additionalData.cmds) {
      cmd_id = _info.additionalData.cmds[i];
      if (!CMDS[cmd_id]) {
        CMDS[cmd_id] = {
          'text': []
        };
      } else if (!CMDS[cmd_id]['text']) {
        CMDS[cmd_id]['text'] = [];
      }
      CMDS[cmd_id]['text'].push({
        text: text,
        info: _info,
        object: _object
      });
    }
  }
};

jeedom3d.text.update = function(_options) {
  var texts = CMDS[_options.cmd_id]['text']
  for (var i in texts) {
    if (_options.object && _options.object != texts[i].object) {
      continue;
    }
    if (_options.text) {
      var text = jeedom3d.text.generate(texts[i].info, texts[i].object, data.additionalData.text);
      for (var j in scene.children) {
        if (scene.children[j].uuid == texts[i].text.uuid) {
          scene.remove(scene.children[j]);
        }
      }
      scene.add(text);
      CMDS[_options.cmd_id]['text'][i].text = text;
    } else {
      jeedom.plan3d.byId({
        id: texts[i].info.id,
        global: false,
        async: false,
        success: function(data) {
          var text = jeedom3d.text.generate(texts[i].info, texts[i].object, data.additionalData.text);
          for (var j in scene.children) {
            if (scene.children[j].uuid == texts[i].text.uuid) {
              scene.remove(scene.children[j]);
            }
          }
          JEEDOM_OBJECT.push(text.uuid);
          scene.add(text);
          CMDS[_options.cmd_id]['text'][i].text = text;
        }
      });
    }
  }
}

jeedom3d.text.generate = function(_options, _object, _text) {
  var borderColor = jeedomUtils.hexToRgb(_options.configuration['3d::widget::text::bordercolor']);
  borderColor.a = parseFloat(_options.configuration['3d::widget::text::bordertransparency']);
  var backgroundColor = jeedomUtils.hexToRgb(_options.configuration['3d::widget::text::backgroundcolor']);
  backgroundColor.a = parseFloat(_options.configuration['3d::widget::text::backgroundtransparency']);
  var textColor = jeedomUtils.hexToRgb(_options.configuration['3d::widget::text::textcolor']);
  textColor.a = parseFloat(_options.configuration['3d::widget::text::texttransparency']);
  var spritey = jeedom3d.text.makeTextSprite(_text, {
    fontsize: parseInt(_options.configuration['3d::widget::text::fontsize']),
    borderColor: borderColor,
    backgroundColor: backgroundColor,
    textColor: textColor
  });
  var bBox = new THREE.Box3().setFromObject(_object);
  spritey.position.set((bBox.max.x - bBox.min.x) / 2 + bBox.min.x, bBox.max.y + parseInt(_options.configuration['3d::widget::text::space::z']), (bBox.max.z - bBox.min.z) / 2 + bBox.min.z);
  return spritey;
}


jeedom3d.text.makeTextSprite = function(message, parameters) {
  message = " " + message + " ";
  if (parameters === undefined) parameters = {};
  var fontface = parameters.hasOwnProperty("fontface") ? parameters["fontface"] : "Arial";
  var fontsize = parameters.hasOwnProperty("fontsize") ? parameters["fontsize"] : 18;
  var borderThickness = parameters.hasOwnProperty("borderThickness") ? parameters["borderThickness"] : 2;
  var borderColor = parameters.hasOwnProperty("borderColor") ? parameters["borderColor"] : {
    r: 0,
    g: 0,
    b: 0,
    a: 1.0
  };
  var backgroundColor = parameters.hasOwnProperty("backgroundColor") ? parameters["backgroundColor"] : {
    r: 255,
    g: 255,
    b: 255,
    a: 1.0
  };
  var textColor = parameters.hasOwnProperty("textColor") ? parameters["textColor"] : {
    r: 0,
    g: 0,
    b: 0,
    a: 1.0
  };
  var canvas = document.createElement('canvas');

  var context = canvas.getContext('2d');
  context.font = "Bold " + fontsize + "px " + fontface;
  var texts = message.split('\n');
  var totalLine = texts.length;
  var textWidth = jeedom3d.text.getMaxWidth(context, texts);
  var size = Math.max(300, textWidth + 2 * borderThickness);
  canvas.width = size;
  canvas.height = size;
  context.font = "Bold " + fontsize + "px " + fontface;
  context.fillStyle = "rgba(" + backgroundColor.r + "," + backgroundColor.g + "," + backgroundColor.b + "," + backgroundColor.a + ")";
  context.strokeStyle = "rgba(" + borderColor.r + "," + borderColor.g + "," + borderColor.b + "," + borderColor.a + ")";
  context.lineWidth = borderThickness;
  let totalTextHeight = fontsize * 1.2 * totalLine;
  jeedom3d.text.roundRect(context, (size / 2 - textWidth / 2) - borderThickness / 2, size / 2 - fontsize / 2 - totalTextHeight / 2, textWidth + borderThickness, totalTextHeight + fontsize / 2, 6);
  context.fillStyle = "rgba(" + textColor.r + "," + textColor.g + "," + textColor.b + "," + textColor.a + ")";
  let startY = size / 2 - totalTextHeight / 2 + fontsize / 2;
  for (var i = 0; i < totalLine; i++) {
    let curWidth = context.measureText(texts[i]).width;
    context.fillText(texts[i], size / 2 - curWidth / 2, startY + fontsize * i * 1.2);
  }
  var texture = new THREE.Texture(canvas);
  texture.needsUpdate = true;
  var spriteMaterial = new THREE.SpriteMaterial({
    map: texture
  });
  var sprite = new THREE.Sprite(spriteMaterial);
  sprite.scale.set(300, 150, 1.0);
  return sprite;
}

jeedom3d.text.roundRect = function(ctx, x, y, w, h, r) {
  ctx.beginPath();
  ctx.moveTo(x + r, y);
  ctx.lineTo(x + w - r, y);
  ctx.quadraticCurveTo(x + w, y, x + w, y + r);
  ctx.lineTo(x + w, y + h - r);
  ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
  ctx.lineTo(x + r, y + h);
  ctx.quadraticCurveTo(x, y + h, x, y + h - r);
  ctx.lineTo(x, y + r);
  ctx.quadraticCurveTo(x, y, x + r, y);
  ctx.closePath();
  ctx.fill();
  ctx.stroke();
}

jeedom3d.text.getMaxWidth = function(context, texts) {
  let maxWidth = 0;
  for (let i in texts)
    maxWidth = Math.max(maxWidth, context.measureText(texts[i]).width);
  return maxWidth;
}

/***************************************DOOR***************************/

jeedom3d.door = function() {};
jeedom3d.door.data = {};

jeedom3d.door.reset = function(_info, _object) {
  if (jeedom3d.door.data[_object.uuid]) {
    _object.material.color = jeedom3d.door.data[_object.uuid];
  }
}

jeedom3d.door.create = function(_info, _object) {
  _object.material = _object.material.clone();
  for (var i in _info.additionalData.cmds) {
    if (_info.additionalData.cmds[i] == '') {
      continue;
    }
    cmd_id = _info.additionalData.cmds[i];
    if (!CMDS[cmd_id]) {
      CMDS[cmd_id] = {
        'door': []
      };
    } else if (!CMDS[cmd_id]['door']) {
      CMDS[cmd_id]['door'] = [];
    }
    CMDS[cmd_id]['door'].push({
      object: _object,
      info: _info
    });
    jeedom3d.door.update({
      state: _info.additionalData.state,
      cmd_id: cmd_id,
      object: _object
    });
  }
};

jeedom3d.door.update = function(_options) {
  var doors = CMDS[_options.cmd_id]['door'];
  for (var i in doors) {
    if (_options.object && _options.object != doors[i].object) {
      continue;
    }
    if (_options.state) {
      CMDS[_options.cmd_id]['door'][i].info.lastPosition = jeedom3d.door.doUpdate(_options.state, doors[i]);
    } else {
      jeedom.plan3d.byId({
        id: doors[i].info.id,
        global: false,
        async: false,
        success: function(data) {
          CMDS[_options.cmd_id]['door'][i].info.lastPosition = jeedom3d.door.doUpdate(data.additionalData.state, doors[i]);
        }
      });
    }
  }
}

jeedom3d.door.doUpdate = function(_state, _door) {
  var result = ''
  var convert = {
    0: _door.info.configuration['3d::widget::door::windowopen'],
    1: _door.info.configuration['3d::widget::door::windowclose'],
    2: _door.info.configuration['3d::widget::door::shutterclose']
  }
  var enable = {
    0: _door.info.configuration['3d::widget::door::windowopen::enableColor'],
    1: _door.info.configuration['3d::widget::door::windowclose::enableColor'],
    2: _door.info.configuration['3d::widget::door::shutterclose::enableColor']
  }
  if (convert[_state] && enable[_state] && enable[_state] == 1) {
    if (!jeedom3d.door.data[_door.object.uuid]) {
      jeedom3d.door.data[_door.object.uuid] = {
        r: _door.object.material.color.r,
        g: _door.object.material.color.g,
        b: _door.object.material.color.b
      };
    }
    _door.object.material.color.set(convert[_state]);
  }
  if (!_door.info.lastPosition || _door.info.lastPosition == '') {
    _door.info.lastPosition = 'close'
  }
  if (parseInt(_door.info.configuration['3d::widget::door::rotate']) == 1) {
    rparams = {
      mode: [_door.info.configuration['3d::widget::door::rotate::0'], _door.info.configuration['3d::widget::door::rotate::1']]
    }
    if (_door.info.lastPosition == 'close') {
      rparams.repeat = parseInt(_door.info.configuration['3d::widget::door::rotate::way']);
    } else {
      rparams.repeat = (parseInt(_door.info.configuration['3d::widget::door::rotate::way']) == 1) ? 3 : 1;
    }
    if (_state == 0 && _door.info.lastPosition == 'close') {
      jeedom3d.door.rotate(_door.object, rparams);
      result = 'open';
    }
    if (_state == 1 && _door.info.lastPosition == 'open') {
      jeedom3d.door.rotate(_door.object, rparams);
      result = 'close';
    }
  } else if (parseInt(_door.info.configuration['3d::widget::door::translate']) == 1) {
    if (_door.info.lastPosition == 'close') {
      tparams = {
        way: _door.info.configuration['3d::widget::door::translate::way']
      };
    } else {
      if (_door.info.configuration['3d::widget::door::translate::way'] == 'left') {
        tparams = {
          way: 'right'
        };
      } else if (_door.info.configuration['3d::widget::door::translate::way'] == 'right') {
        tparams = {
          way: 'left'
        };
      } else if (_door.info.configuration['3d::widget::door::translate::way'] == 'up') {
        tparams = {
          way: 'down'
        };
      } else if (_door.info.configuration['3d::widget::door::translate::way'] == 'down') {
        tparams = {
          way: 'up'
        };
      }
    }
    tparams.repeat = (_door.info.configuration['3d::widget::door::translate::repeat']) ? _door.info.configuration['3d::widget::door::translate::repeat'] : 1;
    if (_state == 0 && _door.info.lastPosition == 'close') {
      jeedom3d.door.translate(_door.object, tparams);
      result = 'open';
    }
    if (_state == 1 && _door.info.lastPosition == 'open') {
      jeedom3d.door.translate(_door.object, tparams);
      result = 'close';
    }
  } else if (parseInt(_door.info.configuration['3d::widget::door::hide']) == 1) {
    if (!_door.info.lastPosition || _door.info.lastPosition == '') {
      _door.info.lastPosition = 'close'
    }
    if (_state == 0 && _door.info.lastPosition == 'close') {
      _door.object.visible = false;
      result = 'open';
    }
    if (_state == 1 && _door.info.lastPosition == 'open') {
      jeedom3d.door.translate(_door.object, tparams);
      _door.object.visible = true;
      result = 'close';
    }
  } else if (parseInt(_door.info.configuration['3d::widget::shutter::hide']) == 1) {
    if (_state == 2) {
      _door.object.visible = true;
    } else {
      _door.object.visible = false;
    }
  }
  return result;
}

jeedom3d.door.rotate = function(_obj, _params) {
  if (!_params.repeat || !_params.mode) {
    return
  }
  for (var i = 0; i < _params.repeat; i++) {
    var bBox = new THREE.Box3().setFromObject(_obj);
    var size = {
      x: bBox.max.x - bBox.min.x,
      y: bBox.max.y - bBox.min.y,
      z: bBox.max.z - bBox.min.z
    };
    var center = _obj.geometry.center();
    var translate = {}
    translate.y = -center.y;
    _obj.rotation.y += Math.PI / 2;
    if (_params.mode[0] == 'left') {
      translate.z = -center.x - size.x / 2;
    } else {
      translate.z = -center.x + size.x / 2;
    }
    if (_params.mode[1] == 'front') {
      translate.x = center.z - size.x / 2;
    } else {
      translate.x = center.z + size.x / 2;
    }
    _obj.geometry.applyMatrix(new THREE.Matrix4().makeTranslation(translate.x, translate.y, translate.z));
  }
}

jeedom3d.door.translate = function(_obj, _params) {
  if (!_params.repeat || !_params.way) {
    return
  }
  for (var i = 0; i < _params.repeat; i++) {
    var bBox = new THREE.Box3().setFromObject(_obj);
    var size = {
      x: bBox.max.x - bBox.min.x,
      y: bBox.max.y - bBox.min.y,
      z: bBox.max.z - bBox.min.z
    };
    var translate = {}
    if (_params.way == 'right') {
      translate.x = size.x;
      translate.y = 0;
    } else if (_params.way == 'left') {
      translate.x = -size.x;
      translate.y = 0;
    } else if (_params.way == 'up') {
      translate.y = size.y;
      translate.x = 0;
    } else if (_params.way == 'down') {
      translate.y = -size.y;
      translate.x = 0;
    }
    translate.z = 0;
    _obj.geometry.applyMatrix(new THREE.Matrix4().makeTranslation(translate.x, translate.y, translate.z));
  }
}


/***************************************CONDITIONAL COLOR***************************/

jeedom3d.conditionalColor = function() {};
jeedom3d.conditionalColor.data = {};

jeedom3d.conditionalColor.reset = function(_info, _object) {
  if (jeedom3d.conditionalColor.data[_object.uuid]) {
    _object.material.color = jeedom3d.conditionalColor.data[_object.uuid];
  }
}

jeedom3d.conditionalColor.create = function(_info, _object) {
  _object.material = _object.material.clone();
  for (var i in _info.additionalData.cmds) {
    cmd_id = _info.additionalData.cmds[i];
    if (!CMDS[cmd_id]) {
      CMDS[cmd_id] = {
        'conditionalColor': []
      };
    } else if (!CMDS[cmd_id]['conditionalColor']) {
      CMDS[cmd_id]['conditionalColor'] = [];
    }
    CMDS[cmd_id]['conditionalColor'].push({
      object: _object,
      info: _info
    });
    jeedom3d.conditionalColor.update({
      color: _info.additionalData.color,
      cmd_id: cmd_id,
      object: _object
    });
  }
};

jeedom3d.conditionalColor.update = function(_options) {
  var conditionalColor = CMDS[_options.cmd_id]['conditionalColor']
  for (var i in conditionalColor) {
    if (_options.object && _options.object != conditionalColor[i].object) {
      continue;
    }
    if (_options.color) {
      if (!jeedom3d.conditionalColor.data[conditionalColor[i].object.uuid]) {
        jeedom3d.conditionalColor.data[conditionalColor[i].object.uuid] = {
          r: conditionalColor[i].object.material.color.r,
          g: conditionalColor[i].object.material.color.g,
          b: conditionalColor[i].object.material.color.b
        };
      }
      conditionalColor[i].object.material.color.set(_options.color);
    } else {
      jeedom.plan3d.byId({
        id: conditionalColor[i].info.id,
        global: false,
        async: false,
        success: function(data) {
          if (data.additionalData.color == '') {
            return;
          }
          if (!jeedom3d.conditionalColor.data[conditionalColor[i].object.uuid]) {
            jeedom3d.conditionalColor.data[conditionalColor[i].object.uuid] = {
              r: conditionalColor[i].object.material.color.r,
              g: conditionalColor[i].object.material.color.g,
              b: conditionalColor[i].object.material.color.b
            };
          }
          conditionalColor[i].object.material.color.set(new THREE.Color(data.additionalData.color));
        }
      });
    }
  }
}

/***************************************CONDITIONAL SHOW***************************/

jeedom3d.conditionalShow = function() {};

jeedom3d.conditionalShow.reset = function(_info, _object) {
  _object.visible = true;
}

jeedom3d.conditionalShow.create = function(_info, _object) {
  for (var i in _info.additionalData.cmds) {
    cmd_id = _info.additionalData.cmds[i];
    if (!CMDS[cmd_id]) {
      CMDS[cmd_id] = {
        'conditionalShow': []
      };
    } else if (!CMDS[cmd_id]['conditionalShow']) {
      CMDS[cmd_id]['conditionalShow'] = [];
    }
    CMDS[cmd_id]['conditionalShow'].push({
      object: _object,
      info: _info
    });
    jeedom3d.conditionalShow.update({
      show: _info.additionalData.show,
      cmd_id: cmd_id,
      object: _object
    });
  }
};

jeedom3d.conditionalShow.update = function(_options) {
  var conditionalShow = CMDS[_options.cmd_id]['conditionalShow']
  for (var i in conditionalShow) {
    if (_options.object && _options.object != conditionalShow[i].object) {
      continue;
    }
    if (typeof _options.show != 'undefined') {
      _options.object.visible = _options.show;
    } else {
      jeedom.plan3d.byId({
        id: conditionalShow[i].info.id,
        global: false,
        async: false,
        success: function(data) {
          if (typeof data.additionalData.show == 'undefined') {
            return;
          }
          conditionalShow[i].object.visible = data.additionalData.show;
        }
      });
    }
  }
}