<script>
    function onGroupChange<?php echo $entry->id; ?>() {
        $.post(`/admin_signs_symptoms/<?php echo $entry->id; ?>/group`, {
            group: $('#group-select-<?php echo $entry->id; ?>').val()
        }, function() {
            console.log('Changed');
        });
    }
</script>
<?php
$groups = [
    'Digestive Issues'
];
?>
<select id="group-select-<?php echo $entry->id; ?>" class="form-control" onchange="onGroupChange<?php echo $entry->id; ?>()">
    <option value="">Ungrouped</option>
    @foreach($groups as $group)
    <option value="{{ $group }}" <?php echo $entry->group === $group ? 'selected' : '' ?>>{{ $group }}</option>
    @endforeach
</select>