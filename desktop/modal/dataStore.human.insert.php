<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<table class="table table-condensed table-bordered" id="table_mod_insertDataSotreValue">
    <thead>
        <tr>
            <th style="width: 150px;">{{Nom}}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="mod_insertDataStoreValue_name">
            <select class='form-control'>
                    <?php
foreach (dataStore::byTypeLinkId(init('type', 'scenario')) as $dataStore) {
	echo '<option value="' . $dataStore->getKey() . '">' . $dataStore->getKey() . '</option>';
}

?>
               </select>
           </td>
       </tr>
   </tbody>
</table>
<script>
    function mod_insertDataStore() {
    }

    mod_insertDataStore.setOptions = function(_options) {
    }

    mod_insertDataStore.getValue = function() {
        var variable_name = $('#table_mod_insertDataSotreValue tbody tr:first .mod_insertDataStoreValue_name select option:selected').html();
        if (variable_name == undefined) {
            return '';
        }
        return '#variable(' + variable_name + ')#';
    }

    mod_insertDataStore.getId = function() {
        return $('#table_mod_insertDataSotreValue tbody tr:first .mod_insertDataStoreValue_name select').value();
    }

</script>
