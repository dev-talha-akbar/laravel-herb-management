<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }
        tr {
            font-family: 'arial';
            font-size: 12px;
        }

        .table-bordered,
        th,
        td {
            border: 1px solid #dee2e6;
        }

        th,
        td {
            padding: 3px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        table {
            border-collapse: collapse;
        }

        .page-break {
            page-break-after: always;
        }
        .title {
            margin-top: 250px;
        }

        .text-center {
            text-align: center;
        }

    </style>

    <h1 class="title text-center">Submission Form Results</h1>
    <h2 class="text-center">Patient Name: {{ $patientName }} </h2>
    <h2 class="text-center">Submitted At: {{ $submittedAt }}</h2>
    <div class="page-break"></div>
    <h4>Herbs:</h4>
    <table>
        <tbody>
            @foreach ($herbs as $herb)
                <tr>
                    <td>
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr>
                                    <th>Chinese Name</th>
                                    <td>{{ $herb->chinese_name }}</td>
                                </tr>
                                <tr>
                                    <th>English Name</th>
                                    <td>{{ $herb->english_name }}</td>
                                </tr>
                                <tr>
                                    <th>Pharmaceutical Name</th>
                                    <td>{{ $herb->pharmaceutical_name }}</td>
                                </tr>
                                <tr>
                                    <th>Literal Name</th>
                                    <td>{{ $herb->literal_name }}</td>
                                </tr>
                                <tr>
                                    <th>Signs / Symptoms</th>
                                    <td>
                                        @foreach ($herb->items as $item)
                                            @if ($item->type == 'signs_symptoms')
                                                {{ $item->value }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dosage</th>
                                    <td>
                                        {{ $herb->dosage_with_unit }} (Maximum:
                                        {{ $herb->max_dosage }}g)
                                    </td>
                                </tr>
                                <tr>
                                    <th>Administered</th>
                                    <td>{{ $herb->usage }}</td>
                                </tr>
                                <tr>
                                    <th>Formulas Found In</th>
                                    <td>
                                        @foreach ($herb->formulas as $formula)
                                            {{ $formula->chinese_name }}
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Properties</th>
                                    <td>
                                        @foreach ($herb->items as $item)
                                            @if ($item->type == 'properties')
                                                {{ $item->value }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Systems Affected</th>
                                    <td>
                                        @foreach ($herb->items as $item)
                                            @if ($item->type == 'systems_affected')
                                                {{ $item->value }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Actions</th>
                                    <td>
                                        @foreach ($herb->items as $item)
                                            @if ($item->type == 'actions')
                                                {{ $item->value }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Chemicial Composition</th>
                                    <td>
                                        @foreach ($herb->items as $item)
                                            @if ($item->type == 'chemical_composition')
                                                {{ $item->value }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Pharmacology</th>
                                    <td>
                                        @foreach ($herb->items as $item)
                                            @if ($item->type == 'pharmacology')
                                                {{ $item->value }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Antibiotic Strains</th>
                                    <td>
                                        @foreach ($herb->items as $item)
                                            @if ($item->type == 'antibiotic_strains')
                                                {{ $item->value }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Hormones</th>
                                    <td>
                                        @foreach ($herb->items as $item)
                                            @if ($item->type == 'hormones')
                                                {{ $item->value }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Herb-Herb Interaction</th>
                                    <td>
                                        @foreach ($herb->items as $item)
                                            @if ($item->type == 'herb_herb_interaction')
                                                {{ $item->value }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Herb-Drug Interaction</th>
                                    <td>
                                        @foreach ($herb->items as $item)
                                            @if ($item->type == 'herb_drug_interaction')
                                                {{ $item->value }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Toxicity / Contraindications</th>
                                    <td>
                                        @foreach ($herb->items as $item)
                                            @if ($item->type == 'toxicity_contraindications')
                                                {{ $item->value }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <h4>Herbs Formulas: </h4>
    <table class="table-bordered">
        <tbody>
            @foreach ($herb_formulas as $herb_formula)
                <tr>
                    <td>
                        <table class="table table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <th>Chinese Name</th>
                                    <td>{{ $herb_formula->chinese_name }}</td>
                                </tr>
                                <tr>
                                    <th>English Name</th>
                                    <td>{{ $herb_formula->english_name }}</td>
                                </tr>
                                <tr>
                                    <th>Sign / Symptoms</th>
                                    <td>{{ $herb_formula->signs_symptoms }}</td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>{{ $herb_formula->categories }}</td>
                                </tr>
                                <tr>
                                    <th>Formula diagnosis</th>
                                    <td>{{ $herb_formula->formula_diagnosis }}</td>
                                </tr>
                                <tr>
                                    <th>Tongue diagnosis</th>
                                    <td>{{ $herb_formula->tongue_diagnosis }}</td>
                                </tr>
                                <tr>
                                    <th>Pulse diagnosis</th>
                                    <td>{{ $herb_formula->pulse_diagnosis }}</td>
                                </tr>
                                <tr>
                                    <th>Formula actions</th>
                                    <td>{{ $herb_formula->formula_actions }}</td>
                                </tr>
                                <tr>
                                    <th>Herb-Drug interaction</th>
                                    <td>{{ $herb_formula->herb_drug_interaction }}</td>
                                </tr>
                                <tr>
                                    <th>Toxicity / Contraindications</th>
                                    <td>{{ $herb_formula->toxicity_contraindications }}</td>
                                </tr>
                                <tr>
                                    <th>Herbs Used in this Formula</th>
                                    <td>{{ $herb_formula->herbs_used }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
