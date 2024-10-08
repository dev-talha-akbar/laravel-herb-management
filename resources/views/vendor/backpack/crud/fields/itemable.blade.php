<!-- select2 multiple -->
@php
if (!isset($field['options'])) {
$options = $field['model']::all();
} else {
$options = call_user_func($field['options'], $field['model']::query());
}
$multiple = isset($field['multiple']) && $field['multiple']===false ? '': 'multiple';
@endphp

<div @include('crud::inc.field_wrapper_attributes')>
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <select name="{{ $field['name'] }}[]" style="width: 100%" data-init-function="bpFieldInitSelect2MultipleElement" @include('crud::inc.field_attributes', ['default_class'=> 'form-control select2_multiple'])
        {{$multiple}}>

        @if (isset($field['allows_null']) && $field['allows_null']==true)
        <option value="">-</option>
        @endif

        @if (isset($field['model']))
        @foreach ($options as $option)
        @if( (old(square_brackets_to_dots($field["name"])) && in_array($option->getKey(), old($field["name"]))) || (is_null(old(square_brackets_to_dots($field["name"]))) && isset($field['value']) && in_array($option->getKey(), $field['value']->pluck($option->getKeyName(), $option->getKeyName())->toArray())))
        <option value="{{ $option->getKey() }}" selected>{{ $option->{$field['attribute']} }}</option>
        @else
        <option value="{{ $option->getKey() }}">{{ $option->{$field['attribute']} }}</option>
        @endif
        @endforeach
        @endif
    </select>

    @if(isset($field['select_all']) && $field['select_all'])
    <a class="btn btn-xs btn-default select_all" style="margin-top: 5px;"><i class="fa fa-check-square-o"></i> {{ trans('backpack::crud.select_all') }}</a>
    <a class="btn btn-xs btn-default clear" style="margin-top: 5px;"><i class="fa fa-times"></i> {{ trans('backpack::crud.clear') }}</a>
    @endif

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

{{-- FIELD CSS - will be loaded in the after_styles section --}}
@push('crud_fields_styles')
<!-- include select2 css-->
<link href="{{ asset('packages/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
<!-- include select2 js-->
<script src="{{ asset('packages/select2/dist/js/select2.full.min.js') }}"></script>
@if (app()->getLocale() !== 'en')
<script src="{{ asset('packages/select2/dist/js/i18n/' . app()->getLocale() . '.js') }}"></script>
@endif
<script>
    function bpFieldInitSelect2MultipleElement(element) {
        if (!element.hasClass("select2-hidden-accessible")) {
            var $obj = element.select2({
                theme: "bootstrap",
                tags: true,
                sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                createTag: function(params) {
                    return {
                        id: params.term,
                        text: params.term,
                        newOption: true
                    };
                },
                templateResult: function(data) {
                    var $result = $("<span></span>");

                    $result.text(data.text);

                    if (data.newOption) {
                        $result.append(" <em>(new)</em>");
                    }

                    return $result;
                }
            });

            var options = [];
            @if(count($options))
            @foreach($options as $option)
            options.push(
                <?php
                echo $option->getKey();
                ?>
            );
            @endforeach
            @endif

            @if(isset($field['select_all']) && $field['select_all'])
            element.parent().find('.clear').on("click", function() {
                $obj.val([]).trigger("change");
            });
            element.parent().find('.select_all').on("click", function() {
                $obj.val(options).trigger("change");
            });
            @endif
        }
    }
</script>
@endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}