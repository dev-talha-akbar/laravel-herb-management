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
    'Cold and Flu',
    'Pain / Arthritis',
    'Digestive Issues',
    'Respiratory',
    'Cardiovascular',
    'Cough',
    'Diet/Lifestyle',
    'Emotional',
    'Skin Conditions',
    'Neck & Head',
    'Genitourinary',
    'Neurological',
    'General',
    'Musculoskeletal',
    'Men Only',
    'Women Only',
    'Known Diagnosis',
    'Mouth/Tongue',
    'Body Temperature',
    'Known Deficiencies',
    'Side Affects From Medication',
    'Injuries/Post Surgical',
    'Ears/Eyes',
    'Dental/Mouth',
    'Sweating Disorders',
    'Edema/Skin Problems',
    'Headaches',
    'Abdominal Issues',
    'Turn Emotional into Emotional/Sleep',
    'Allergies',
    'Body Temperature'
];
?>
<select id="group-select-<?php echo $entry->id; ?>" class="form-control" onchange="onGroupChange<?php echo $entry->id; ?>()">
    <option value="">Ungrouped</option>
    @foreach($groups as $group)
    <option value="{{ $group }}" <?php echo $entry->group === $group ? 'selected' : '' ?>>{{ $group }}</option>
    @endforeach
</select>
