@extends('layouts.app')

@section('content')
<script>
    var submissionForm = <?php echo json_encode($submission) ?>;
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form-app />
        </div>
    </div>
</div>
@endsection