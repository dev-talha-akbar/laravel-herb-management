@extends('layouts.app')
@section('scripts_side')
@if(isset($submission))
<script>
    var submissionForm = <?php echo json_encode($submission) ?>;
</script>
@endif
@endsection
@section('content')
<?php
$signs_symptons = DB::table('admin_signs_symptoms')->get();
$groups = $signs_symptons->pluck('group')->unique()->filter(function ($value) {
    return !is_null($value);
})->values()->all();
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form-app :signs='@json($signs_symptons)' :groups='@json($groups)' />
        </div>
    </div>
</div>
@endsection