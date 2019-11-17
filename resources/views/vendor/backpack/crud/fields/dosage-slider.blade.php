<!-- range-slider -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>Dosage (mg)</label>
    @include('crud::inc.field_translatable_icon')

    <div style="padding: 60px 0 30px;">
        <div id="dosage-slider"></div>
    </div>
    <input type="hidden" id="dosage-input" name="dosage">

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
        <link href="{{ asset('packages/nouislider/distribute/nouislider.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <script src="{{ asset('packages/nouislider/distribute/nouislider.min.js') }}"></script>
        <script>
            var dosageSliderElem = document.getElementById('dosage-slider');
            var dosageInput = document.getElementById('dosage-input');
            var [start, end] = "{!! $field['value'] ?? $field['default'] ?? '' !!}".split('-');

            var dosageSlider = noUiSlider.create(dosageSliderElem, {
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
                dosageInput.value = `${values[0]}-${values[1]}`;
            }

            dosageSlider.on('update', setInputValue);
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
