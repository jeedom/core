<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
if (init('interactDef_id') == '') {
	throw new Exception('{{Interact Def ID ne peut être vide}}');
}

$interactQueries = interactQuery::byInteractDefId(init('interactDef_id'));
sendVarToJS('interactDisplay_interactDef_id', init('interactDef_id'));
if (count($interactQueries) == 0) {
	throw new Exception('{{Aucune phrase trouvée}}');
}
?>

<div style="display: none;" id="md_displayInteractQueryAlert"></div>
<br/><br/>
<table class="table table-bordered table-condensed tablesorter" id="table_interactQuery" style="width:100%">
    <thead>
        <tr>
            <th>{{Phrase}}</th>
        </tr>
    </thead>
    <tbody>
        <?php
foreach ($interactQueries as $interactQuery) {
	echo '<tr data-interactQuery_id="' . $interactQuery->getId() . '">';
	echo '<td>' . $interactQuery->getQuery() . '</td>';
	echo '</tr>';
}
?>
    </tbody>
</table>

<script>
    initTableSorter();
</script>