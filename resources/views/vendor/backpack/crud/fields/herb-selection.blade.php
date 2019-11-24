<!-- select2 multiple -->
@php
if (!isset($field['options'])) {
$options = $field['model']::all();
} else {
$options = call_user_func($field['options'], $field['model']::query());
}
if(isset($field['id'])) {
$dosages = \App\Models\HerbsInFormula::where('herb_formula_id', $field['id'])->get();
}
$multiple = isset($field['multiple']) && $field['multiple']===false ? '': 'multiple';
@endphp

<div @include('crud::inc.field_wrapper_attributes')>
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <select id="herb-selection" name="{{ $field['name'] }}[]" style="width: 100%" data-init-function="bpFieldInitSelect2MultipleElementHerb" @include('crud::inc.field_attributes', ['default_class'=> 'form-control select2_multiple'])
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
<div id="herb-container">

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
<link href="{{ asset('packages/nouislider/distribute/nouislider.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    #herb-container {
        padding: 10px 15px 40px;
        width: 100%;
    }

    #herb-container .herb {
        width: 100%;
        margin-bottom: 25px;
    }

    #herb-container .dosage-slider {
        margin-top: 50px;
    }
</style>
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
<script src="{{ asset('packages/nouislider/distribute/nouislider.min.js') }}"></script>

<!-- include select2 js-->
<script src="{{ asset('packages/select2/dist/js/select2.full.min.js') }}"></script>
@if (app()->getLocale() !== 'en')
<script src="{{ asset('packages/select2/dist/js/i18n/' . app()->getLocale() . '.js') }}"></script>
@endif
<script>
    var herbValues = {};

    function bpFieldInitSelect2MultipleElementHerb() {
        var element = $('#herb-selection');
        if (!element.hasClass("select2-hidden-accessible")) {
            var selectedHerbs = [];

            @foreach($options as $option)
            <?php
            if ((old(square_brackets_to_dots($field["name"])) && in_array($option->getKey(), old($field["name"]))) || (is_null(old(square_brackets_to_dots($field["name"]))) && isset($field['value']) && in_array($option->getKey(), $field['value']->pluck($option->getKeyName(), $option->getKeyName())->toArray()))) {
                ?>
                selectedHerbs.push({
                    id: <?php
                            echo $option->getKey();
                            ?>,
                    text: '<?php
                                echo $option->{$field['attribute']};
                                ?>',
                });
                herbValues[<?php
                                echo $option->getKey();
                                ?>] = '<?php
                                            $dosage = isset($dosages) ? $dosages->where('herb_id', $option->getKey())->first() : null;
                                            $dosage = isset($dosage) ? $dosage->dosage : null;

                                            echo old('herbs') !== null ? old('herb_dosage')[array_search($option->getKey(), old('herbs'))] : $dosage ?? $option->dosage ?? '' ?>';
            <?php } else { ?>
                herbValues[<?php
                                echo $option->getKey();
                                ?>] = '{{ $option->dosage }}';
            <?php } ?>
            @endforeach

            function addHerbs(herbs) {
                var herbContainer$ = $('#herb-container');
                herbContainer$.html('');

                var herbs$ = herbs.forEach(function(herb, i) {
                    var herb$ = $(`
                        <div class="herb">
                            <b>${herb.text}</b> (Dosage in g)
                        </div>
                    `);
                    herbContainer$.append(herb$);

                    var dosageSliderElem = $('<div class="dosage-slider"></div>');
                    var dosageInput = $('<input type="hidden" class="input-slider" name="herb_dosage[]">');

                    dosageSliderElem.append(dosageInput);
                    herb$.append(dosageSliderElem);

                    var [start, end] = (herbValues[herb.id] || '5-30').split('-');


                    var dosageSlider = noUiSlider.create(dosageSliderElem.get(0), {
                        range: {
                            min: 0,
                            max: 100
                        },
                        tooltips: [true, true],
                        step: 0.1,
                        start: [parseFloat(start), parseFloat(end)],
                        connect: true
                    });

                    function setInputValue(values) {
                        dosageInput.val(`${values[0]}-${values[1]}`);
                        herbValues[herb.id] = `${values[0]}-${values[1]}`;
                    }

                    dosageSlider.on('update', setInputValue);


                });
            }
            var $obj = element.select2({
                theme: "bootstrap"
            });

            element.on('change', function(e) {
                var herbs = $(this).select2('data');
                addHerbs(herbs);
            });

            addHerbs(selectedHerbs);

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