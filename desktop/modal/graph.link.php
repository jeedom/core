<?php
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

if (!isConnect('admin')) {
  throw new Exception('401 Unauthorized');
}
sendVarToJS([
  'prerenderGraph' => config::byKey('graphlink::prerender', 'core', 10),
  'renderGraph' => config::byKey('graphlink::render', 'core', 3000)
]);
?>

<script type="text/javascript" src="3rdparty/vivagraph/vivagraph.min.js"></script>
<div id="div_alertGraphLink"></div>
<div id="div_graphLinkRenderer" style="height:100%;width: 100%;"></div>

<script>
var graph = Viva.Graph.graph()
jeedom.getGraphData({
  filter_type : '<?php echo init('filter_type', '') ?>',
  filter_id : '<?php echo init('filter_id', '') ?>',
  error: function(error) {
    $('#div_alertGraphLink').showAlert({message: error.message, level: 'danger'})
  },
  success : function(data) {
    for (var i in data.node) {
      graph.addNode(i, data.node[i])
    }
    for (var i in data.link) {
      graph.addLink(data.link[i].from, data.link[i].to,data.link[i])
    }
    render()
  }
})

function render() {
  var hWindow = $('#div_graphLinkRenderer').parent().height()
  $('#div_graphLinkRenderer').height(hWindow).css('overflow-y', 'hidden').css('overflow-x', 'hidden').css('padding-top','5px')
  var graphics = Viva.Graph.View.svgGraphics()
  highlightRelatedNodes = function(nodeId, isOn) {
    graph.forEachLinkedNode(nodeId, function(node, link) {
      var linkUI = graphics.getLinkUI(link.id)
      if (linkUI) {
        linkUI.attr('stroke', isOn ? '#FF0000' : '#B7B7B7')
      }
    })
  }

  graphics.node(function(node) {
    if (typeof node.data == 'undefined') {
      graph.removeNode(node.id)
      return
    }
    var ui = Viva.Graph.svg('g')
    text = Viva.Graph.svg('text')
        .attr('y', -15).text(node.data.name)
        .attr('alignment-baseline','central')
        .attr('text-anchor','middle')
        .attr('font-weight',node.data.fontweight)
    if (typeof node.data.image != 'undefined' && $.trim(node.data.image) != '') {
      img = Viva.Graph.svg('image')
          .attr('width', node.data.width)
          .attr('height', node.data.height)
          .attr('y', -node.data.height/2)
          .attr('x', -node.data.width/2)
          .link(node.data.image)
      if (typeof node.data.isActive != 'undefined' && node.data.isActive == 0) {
        img.attr('class','nodeNoActive')
      }
      text.attr('y', -node.data.height/2 -7)
    } else if (typeof node.data.icon != 'undefined' && $.trim(node.data.icon) != '') {
      img = Viva.Graph.svg('text')
          .attr("font-family",node.data.fontfamily)
          .attr("font-size",node.data.fontsize)
          .attr('alignment-baseline','central')
          .attr('text-anchor','middle')
          .text(String.fromCodePoint(parseInt(node.data.icon, 16)))
      if (node.data.fontfamily == 'Font Awesome 5 Free') {
        img.attr('class','fas')
      }
      text.attr("y",node.data.texty*1.5)
      text.attr("x",node.data.textx)
      if (typeof node.data.type != 'undefined' && node.data.type == 'Objet') {
        text.attr("font-size", "1.5em")
        img.attr("font-size", "3em")
      }
    } else if (typeof node.data.shape != 'undefined' && $.trim(node.data.shape) != '') {
      img = Viva.Graph.svg(node.data.shape)
          .attr("width", node.data.width)
          .attr("height", node.data.height)
          .attr("fill", node.data.color)
    } else {
      img = Viva.Graph.svg('rect')
          .attr("width", 24)
          .attr("height", 24)
          .attr("fill", 'black')
    }
    if (typeof node.data.title != 'undefined' && $.trim(node.data.title) != '') {
      ui.attr('title',node.data.title)
    }
    ui.append(text)
    ui.append(img)
    $(ui).on('dblclick',function() {
      if (node.data.url != 'undefined') {
        jeedomUtils.loadPage(node.data.url)
      }
    })
    $(ui).hover(function() {
      highlightRelatedNodes(node.id, true)
    }, function() {
      highlightRelatedNodes(node.id, false)
    });
    return ui
  }).placeNode(function(nodeUI, pos) {
    nodeUI.attr('transform','translate(' + (pos.x) + ',' + (pos.y) +')')
  })

  var layout = Viva.Graph.Layout.forceDirected(graph, {
    springLength: 200,
    stableThreshold: 0.9,
    dragCoeff: 0.01,
    springCoeff: 0.0004,
    gravity: -20,
    springTransform: function(link, spring) {
      spring.length = 200 * (1 - link.data.lengthfactor)
    }
  })

  graphics.link(function(link) {
    if (typeof link.data.dashvalue == 'undefined') {
      link.data.dashvalue = '0,0'
    }
    return Viva.Graph.svg('line')
        .attr('stroke', '#B7B7B7')
        .attr('stroke-dasharray', link.data.dashvalue)
        .attr('stroke-width', '1.5px')
  })

  var renderer = Viva.Graph.View.renderer(graph, {
    layout: layout,
    graphics: graphics,
    prerender: parseInt(prerenderGraph),
    renderLinks: true,
    container: document.getElementById('div_graphLinkRenderer')
  })
  renderer.run()
  setTimeout(function() {
    renderer.pause()
    renderer.reset()
    jeedomUtils.initTooltips()
  }, parseInt(renderGraph))
}
</script>