@php
if (!isset($field['wrapperAttributes']) || !isset($field['wrapperAttributes']['class']))
{
$field['wrapperAttributes']['data-init-function'] = 'bpFieldInitUploadMultipleElement';
$field['wrapperAttributes']['data-field-name'] = $field['name'];
}
@endphp

<!-- upload multiple input -->
<div @include('crud::inc.field_wrapper_attributes')>
	<label>{!! $field['label'] !!}</label>
	@include('crud::inc.field_translatable_icon')

	{{-- Show the file name and a "Clear" button on EDIT form. --}}
	@if (isset($field['value']))
	@php
	if (is_string($field['value'])) {
	$values = json_decode($field['value'], true) ?? [];
	} else {
	$values = $field['value'];
	}
	@endphp
	@if (count($values))
	<div class="well well-sm existing-file">
		@foreach($values as $key => $file_path)
		<div class="file-preview">
			@if (isset($field['temporary']))
			<img src="{{ (asset(\Storage::disk($field['disk'])->temporaryUrl(array_get($field, 'prefix', '').$file_path, Carbon\Carbon::now()->addMinutes($field['temporary'])))) }}">
			@else
			<img src="{{ (asset(\Storage::disk($field['disk'])->url(array_get($field, 'prefix', '').$file_path))) }}">
			@endif
			@if (isset($field['temporary']))
			<a class="btn btn-light" target="_blank" href="{{ (asset(\Storage::disk($field['disk'])->temporaryUrl(array_get($field, 'prefix', '').$file_path, Carbon\Carbon::now()->addMinutes($field['temporary'])))) }}">
				@else
				<a class="btn btn-light" target="_blank" href="{{ (asset(\Storage::disk($field['disk'])->url(array_get($field, 'prefix', '').$file_path))) }}">
					@endif
					<i class="fa fa-download"></i> Download
				</a>
				<a href="#" data-filename="{{ $file_path }}" class="file-clear-button file_clear_button btn btn-link text-danger btn-sm float-right" title="Clear file"><i class="fa fa-trash"></i> Delete</a>
				<div class="clearfix"></div>
		</div>
		@endforeach
	</div>
	@endif
	@endif
	{{-- Show the file picker on CREATE form. --}}
	<input name="{{ $field['name'] }}[]" type="hidden" value="">
	<div class="backstrap-file mt-2">
		<input type="file" name="{{ $field['name'] }}[]" value="@if (old(square_brackets_to_dots($field['name']))) old(square_brackets_to_dots($field['name'])) @elseif (isset($field['default'])) $field['default'] @endif" @include('crud::inc.field_attributes', ['default_class'=> isset($field['value']) && $field['value']!=null?'file_input backstrap-file-input':'file_input backstrap-file-input'])
		multiple
		>
		<label class="backstrap-file-label" for="customFile"></label>
	</div>

	{{-- HINT --}}
	@if (isset($field['hint']))
	<p class="help-block">{!! $field['hint'] !!}</p>
	@endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
@php
$crud->markFieldTypeAsLoaded($field);
@endphp

@push('crud_fields_scripts')
<!-- no scripts -->
<script>
	function bpFieldInitUploadMultipleElement(element) {
		var fieldName = element.attr('data-field-name');
		var clearFileButton = element.find(".file-clear-button");
		var fileInput = element.find("input[type=file]");
		var inputLabel = element.find("label.backstrap-file-label");

		clearFileButton.click(function(e) {
			e.preventDefault();
			var container = $(this).parent().parent();
			var parent = $(this).parent();
			// remove the filename and button
			parent.remove();
			// if the file container is empty, remove it
			if ($.trim(container.html()) == '') {
				container.remove();
			}
			$("<input type='hidden' name='clear_" + fieldName + "[]' value='" + $(this).data('filename') + "'>").insertAfter(fileInput);
		});

		fileInput.change(function() {
			inputLabel.html("Files selected. After save, they will show up above.");
			// remove the hidden input, so that the setXAttribute method is no longer triggered
			$(this).next("input[type=hidden]").remove();
		});
	}
</script>
@endpush
@endif