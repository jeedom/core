<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$date = array(
	'start' => init('startDate', date('Y-m-d', strtotime(config::byKey('history::defautShowPeriod') . ' ' . date('Y-m-d')))),
	'end' => init('endDate', date('Y-m-d')),
);
?>
<div class="md_history">
	<div class="row">
		<div class="col-lg-4 col-sm-12 center">
			<div class="input-group input-group-sm">
				<input id="in_startDate" class="form-control input-sm in_datepicker roundedLeft" style="width: 90px;" value="<?php echo $date['start'] ?>"/>
				<input id="in_endDate" class="form-control input-sm in_datepicker" style="width: 90px;" value="<?php echo $date['end'] ?>"/>
				<a class="btn btn-success btn-sm roundedRight" id='bt_validChangeDate' title="{{Attention : une trop grande plage de dates peut mettre très longtemps à être calculée ou même ne pas s'afficher.}}">
					<i class="fas fa-check"></i>
				</a>
			</div>
		</div>
		<div class="col-lg-8 col-sm-12">
			<div class="input-group input-group-sm pull-right">
				<?php
				if (init('derive', 0) == 1) {
					echo '<span>{{Variation}} <input type="checkbox" class="cb_derive" checked /></span>';
				} else {
					echo '<span>{{Variation}} <input type="checkbox" class="cb_derive" /></span>';
				}
				if (init('step', 0) == 1) {
					echo '<span>{{Escalier}} <input type="checkbox" class="cb_step" checked /></span>';
				} else {
					echo '<span>{{Escalier}} <input type="checkbox" class="cb_step" /></span>';
				}
				?>
				<select class="form-control roundedLeft sel_groupingType" style="width: 180px;">
					<option value="">{{Aucun groupement}}</option>
					<option value="sum::hour">{{Somme par heure}}</option>
					<option value="average::hour">{{Moyenne par heure}}</option>
					<option value="low::hour">{{Minimum par heure}}</option>
					<option value="high::hour">{{Maximum par heure}}</option>
					<option value="sum::day">{{Somme par jour}}</option>
					<option value="average::day">{{Moyenne par jour}}</option>
					<option value="low::day">{{Minimum par jour}}</option>
					<option value="high::day">{{Maximum par jour}}</option>
					<option value="sum::week">{{Somme par semaine}}</option>
					<option value="average::week">{{Moyenne par semaine}}</option>
					<option value="low::week">{{Minimum par semaine}}</option>
					<option value="high::week">{{Maximum par semaine}}</option>
					<option value="sum::month">{{Somme par mois}}</option>
					<option value="average::month">{{Moyenne par mois}}</option>
					<option value="low::month">{{Minimum par mois}}</option>
					<option value="high::month">{{Maximum par mois}}</option>
					<option value="sum::year">{{Somme par année}}</option>
					<option value="average::year">{{Moyenne par année}}</option>
					<option value="low::year">{{Minimum par année}}</option>
					<option value="high::year">{{Maximum par année}}</option>
				</select>
				<select class="form-control roundedRight sel_chartType" style="width: 100px;">
					<option value="line">{{Ligne}}</option>
					<option value="areaspline">{{Aire}}</option>
					<option value="column">{{Barre}}</option>
				</select>
			</div>
		</div>
	</div>
	<center><div id="div_historyChart"></div></center>
	
	
	<script>
	$(".in_datepicker").datepicker();
	$('#ui-datepicker-div').hide();
	
	$('#div_historyChart').css('position', 'relative').css('width', '100%');
	delete jeedom.history.chart['div_historyChart'];
	jeedom.history.drawChart({
		cmd_id: "<?php echo init('id'); ?>",
		el: 'div_historyChart',
		dateRange : 'all',
		dateStart : $('#in_startDate').value(),
		dateEnd :  $('#in_endDate').value(),
		newGraph : true,
		showLegend : false,
		height : jQuery(window).height() - 270,
		success: function (data) {
			if(isset(data.cmd.display)){
				if (init(data.cmd.display.graphStep) != '') {
					$('.cb_step').off().value(init(data.cmd.display.graphStep));
				}
				if (init(data.cmd.display.groupingType) != '') {
					$('.sel_groupingType').off().value(init(data.cmd.display.groupingType));
				}
				if (init(data.cmd.display.graphType) != '') {
					$('.sel_chartType').off().value(init(data.cmd.display.graphType));
				}
				if (init(data.cmd.display.graphDerive) != '') {
					$('.cb_derive').off().value(init(data.cmd.display.graphDerive));
				}
			}
			
			$('.sel_chartType').off('change').on('change', function () {
				jeedom.cmd.save({
					cmd: {id: <?php echo init('id'); ?>, display: {graphType: $(this).value()}},
					error: function (error) {
						$('#div_alert').showAlert({message: error.message, level: 'danger'});
					},
					success: function () {
						var modal = false;
						if($('#md_modal').is(':visible')){
							modal = $('#md_modal');
						}else if($('#md_modal2').is(':visible')){
							modal = $('#md_modal2');
						}
						if(modal !== false){
							modal.dialog({title: "{{Historique}}"});
							modal.load('index.php?v=d&modal=cmd.history&id=<?php echo init('id'); ?>&startDate='+$('#in_startDate').val()+'&endDate='+$('#in_endDate').val()).dialog('open');
						}
					}
				});
			});
			$('.sel_groupingType').off('change').on('change', function () {
				jeedom.cmd.save({
					cmd: {id: <?php echo init('id'); ?>, display: {groupingType: $(this).value()}},
					error: function (error) {
						$('#div_alert').showAlert({message: error.message, level: 'danger'});
					},
					success: function () {
						var modal = false;
						if($('#md_modal').is(':visible')){
							modal = $('#md_modal');
						}else if($('#md_modal2').is(':visible')){
							modal = $('#md_modal2');
						}
						if(modal !== false){
							modal.dialog({title: "{{Historique}}"});
							modal.load('index.php?v=d&modal=cmd.history&id=<?php echo init('id'); ?>&startDate='+$('#in_startDate').val()+'&endDate='+$('#in_endDate').val()).dialog('open');
						}
					}
				});
			});
			$('.cb_derive').on('change', function () {
				jeedom.cmd.save({
					cmd: {id: <?php echo init('id'); ?>, display: {graphDerive: $(this).value()}},
					error: function (error) {
						$('#div_alert').showAlert({message: error.message, level: 'danger'});
					},
					success: function () {
						var modal = false;
						if($('#md_modal').is(':visible')){
							modal = $('#md_modal');
						}else if($('#md_modal2').is(':visible')){
							modal = $('#md_modal2');
						}
						if(modal !== false){
							modal.dialog({title: "{{Historique}}"});
							modal.load('index.php?v=d&modal=cmd.history&id=<?php echo init('id'); ?>&startDate='+$('#in_startDate').val()+'&endDate='+$('#in_endDate').val()).dialog('open');
						}
					}
				});
			});
			$('.cb_step').on('change', function () {
				jeedom.cmd.save({
					cmd: {id: <?php echo init('id'); ?>, display: {graphStep: $(this).value()}},
					error: function (error) {
						$('#div_alert').showAlert({message: error.message, level: 'danger'});
					},
					success: function () {
						var modal = false;
						if($('#md_modal').is(':visible')){
							modal = $('#md_modal');
						}else if($('#md_modal2').is(':visible')){
							modal = $('#md_modal2');
						}
						if(modal !== false){
							modal.dialog({title: "{{Historique}}"});
							modal.load('index.php?v=d&modal=cmd.history&id=<?php echo init('id'); ?>&startDate='+$('#in_startDate').val()+'&endDate='+$('#in_endDate').val()).dialog('open');
						}
					}
				});
			});
			$('#bt_validChangeDate').on('click',function(){
				var modal = false;
				if($('#md_modal').is(':visible')){
					modal = $('#md_modal');
				}else if($('#md_modal2').is(':visible')){
					modal = $('#md_modal2');
				}
				if(modal !== false){
					modal.dialog({title: "{{Historique}}"});
					modal.load('index.php?v=d&modal=cmd.history&id=<?php echo init('id'); ?>&startDate='+$('#in_startDate').val()+'&endDate='+$('#in_endDate').val()).dialog('open');
				}
			});
			
			
			if ($(window).width() > 768) {
				width = 780
				height = 500
				$('.ui-dialog[aria-describedby="md_modal2"]').width(width).height(height)
				$('#md_modal2').width(width-26).height(height-40)
				$('.ui-dialog[aria-describedby="md_modal2"]').position({
					my: "center",
					at: "center",
					of: window
				})
			}
			
			$('#div_historyChart').highcharts().setSize( $('#md_modal2').width(), $('#md_modal2').height() - $('#md_modal2 .md_history .row').height()-20)
			$('.ui-dialog[aria-labelledby="ui-id-4"]').resize(function() {
				$('#div_historyChart').highcharts().setSize( $('#md_modal2').width(), $('#md_modal2').height() - $('#md_modal2 .md_history .row').height()-20)
			})
		}
	});
	
	
</script>
</div>
