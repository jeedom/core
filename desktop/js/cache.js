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

"use strict"

var $tableCache = $('#tableCache')
$(function() {
	jeedomUtils.initTableSorter()
	$tableCache[0].config.widgetOptions.resizable_widths = ['25%', '45%', '8%', '8%', '10%', '4%']
	$tableCache.trigger('applyWidgets')
		.trigger('resizableReset')
		.trigger('sorton', [[[0]]])
})

$('#bt_cacheRefresh').on('click', function() {
	$('#md_modal').dialog("close");
	$('#md_modal').dialog({
		title: "{{Gestion du cache}}"
	}).load('index.php?v=d&modal=cache').dialog('open')
})

$("#bt_cacheAdd").on('click', function(event) {
	var d_msg = '';
	d_msg += '<div class="form-group"><label class="col-sm-3 control-label">{{Clé : }}</label>';
	d_msg += '<input class="col-sm-8 bootbox-input bootbox-input-text form-control" autocomplete="off" type="text" id="cacheNKey"></div>';
	d_msg += '<div class="form-group"><label class="col-sm-3 control-label">{{Valeur : }}</label>';
	d_msg += '<input class="col-sm-8 bootbox-input bootbox-input-text form-control" autocomplete="off" type="text" id="cacheNVal"></div>';
	d_msg += '<div class="form-group"><label class="col-sm-3 control-label">{{Options : }}</label>';
	d_msg += '<input class="col-sm-8 bootbox-input bootbox-input-text form-control" autocomplete="off" type="text" id="cacheNOpt"></div>';
	d_msg += '<div class="form-group"><label class="col-sm-3 control-label">{{Life Time : }}</label>';
	d_msg += '<input class="col-sm-8 bootbox-input bootbox-input-text form-control" autocomplete="off" type="text" id="cacheNLife"></div>';
	bootbox.confirm({
		title: '{{Ajouter une Entrée au Cache}}',
		message: d_msg,
		callback: function (result){ if (result) {
			jeedom.cache.set({
				key: $('#cacheNKey').val(),
				val: $('#cacheNVal').val(),
				opt: $('#cacheNOpt').val(),
				life: $('#cacheNLife').val(),
				error: function(error) {
					$.fn.showAlert({
						message: error.message,
						level: 'danger'
					})
				},
				success: function(data) {
					$.fn.showAlert({
						message: '{{Entrée ajoutée}}',
						level: 'success'
					})
				}
			})
		}}
	});
})

$('.bt_cacheSave').on('click', function() {
	var tr = $(this).closest('tr');
	var id = tr.attr('data-key');
	bootbox.confirm('{{Vous allez modifier l\'entrée : }}' + id, function(result) {
		if (result) {
			jeedom.cache.set({
				key: id,
				val: tr.find('#cacheVal').val(),
				opt: tr.find('#cacheOpt').val(),
				life: tr.find('#cacheLife').val(),
				error: function(error) {
					$.fn.showAlert({
						message: error.message,
						level: 'danger'
					})
				},
				success: function(data) {
					$.fn.showAlert({
						message: '{{Entrée modifiée}}',
						level: 'success'
					})
				}
			})
		}
	})
})

$('.bt_cacheRemove').on('click', function() {
	var tr = $(this).closest('tr');
	var id = tr.attr('data-key');
	bootbox.confirm('{{Vous allez supprimer l\'entrée : }}' + id, function(result) {
		if (result) {
			jeedom.cache.delete({
				key: id,
				error: function(error) {
					$.fn.showAlert({
						message: error.message,
						level: 'danger'
					})
				},
				success: function(data) {
					tr.remove();
					updateCacheStats()
					$.fn.showAlert({
						message: '{{Entrée supprimée}}',
						level: 'success'
					})
				}
			})
		}
	})
})
