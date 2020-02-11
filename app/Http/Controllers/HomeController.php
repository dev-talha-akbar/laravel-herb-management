<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Herb;
use App\Models\HerbFormula;
use App\Models\Submission;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (\Auth::check()) {
            return view('home');
        } else {
            return view('welcome');
        }
    }

    public function submission($id)
    {
        $submission = Submission::findOrFail($id);

        return view('welcome')->with('submission', $submission);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function options()
    {
        $options = Item::whereIn('type', [
            'signs_symptoms',
            'hormones',
            'chemical_composition',
            'pharmacology',
            'antibiotic_strains'
        ])->get();

        return response()->json($options->groupBy('type'));
    }

    public function group(Request $request, $id)
    {
        $sign_symptom = DB::table('sign_symptom_groups')->where('item_id', $id)->first();

        if ($sign_symptom) {
            DB::table('sign_symptom_groups')->where('item_id', $id)->update([
                'group' => $request->group
            ]);
        } else {
            DB::table('sign_symptom_groups')->insert([
                'item_id' => $id,
                'group' => $request->group
            ]);
        }
    }

    public function submit(Request $request)
    {
        $result = $this->searchForSigns($request->signsSelected);

        $submission = Submission::create(['form' => $request->form, 'result' => json_encode($result), 'status' => 1]);
        \Session::flash('success', 'We have received your submission successfully!');
        return response()->json($submission->id);
    }

    public function editSubmission(Request $request, $id)
    {
        $submission = Submission::findOrFail($id);

        $result = $this->searchForSigns($request->signsSelected);

        $submission->update([
            'form' => $request->form,
            'result' => json_encode($result)
        ]);

        return response()->json(true);
    }

    private function searchForSigns($signs)
    {
        $herb_results = Herb::withCount(['signs_symptoms' => function ($q) use ($signs) {
            $q->whereIn('id', $signs);
        }])->whereHas('signs_symptoms', function ($q) use ($signs) {
            $q->whereIn('id', $signs);
        })->orderBy('signs_symptoms_count', 'desc')->with('formulas')->with('items')->get();

        $herb_formula_results = HerbFormula::withCount(['signs_symptoms' => function ($q) use ($signs) {
            $q->whereIn('id', $signs);
        }])->whereHas('signs_symptoms', function ($q) use ($signs) {
            $q->whereIn('id', $signs);
        })->orderBy('signs_symptoms_count', 'desc')->with('herbs')->with('items')->get();

        return [
            'herbs' => $herb_results,
            'herb_formulas' => $herb_formula_results
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search(Request $request)
    {
        $options = $request->options;
        // $hormones = $request->hormones;
        // $chemicalComposition = $request->chemicalComposition;
        // $pharmacology = $request->pharmacology;
        // $antibioticStrains = $request->antibioticStrains;
        // $advancedSearch = $request->advancedSearch;
        $nameSearch = $request->nameSearch;
        $nameToSearch = strtolower($request->nameToSearch);
        $results = null;
        $type = $request->type;
        $typeClass = $request->type === 'Herb' ? Herb::class : HerbFormula::class;

        if (!$nameSearch && count($options) > 0) {
            $results = $typeClass::withCount(['items' => function ($q) use ($options) {
                $q->whereIn('id', $options);
            }])->whereHas('items', function ($q) use ($options) {
                $q->whereIn('id', $options);
            });
        } else if ($nameSearch && isset($nameToSearch)) {
            $results = $typeClass::where(function ($q) use ($nameSearch, $nameToSearch, $type) {
                $cols = ['english_name', 'chinese_name'];

                if ($type === 'Herb') {
                    $cols = array_merge($cols, ['pharmaceutical_name', 'literal_name']);
                }

                $q->where(\DB::raw('1'), '<>', \DB::raw('1'));

                foreach ($cols as $col) {
                    $q->orWhere(\DB::raw("$col COLLATE UTF8MB4_GENERAL_CI"), 'LIKE', "%{$nameToSearch}%");
                }
            });
        } else {
            $results = $typeClass::where(\DB::raw('1'), \DB::raw('1'));
        }

        // if ($advancedSearch) {
        //     if (count($hormones) > 0) {
        //         $results = $results
        //             ->whereHas('hormones', function ($q) use ($hormones) {
        //                 $q->whereIn('id', $hormones);
        //             });
        //     }

        //     if (count($chemicalComposition) > 0) {
        //         $results = $results
        //             ->whereHas('chemical_composition', function ($q) use ($chemicalComposition) {
        //                 $q->whereIn('id', $chemicalComposition);
        //             });
        //     }

        //     if (count($pharmacology) > 0) {
        //         $results = $results->whereHas('pharmacology', function ($q) use ($pharmacology) {
        //             $q->whereIn('id', $pharmacology);
        //         });
        //     }

        //     if (count($antibioticStrains) > 0) {
        //         $results = $results->whereHas('antibiotic_strains', function ($q) use ($antibioticStrains) {
        //             $q->whereIn('id', $antibioticStrains);
        //         });
        //     }
        // }

        if ($request->type === 'Herb Formula') {
            $results = $results->with('herbs');
        } else if ($request->type === 'Herb') {
            $results = $results->with('formulas');
        }

        $results = $results->with('items');

        if (!$nameSearch && count($options) > 0) {
            $results = $results->orderBy('items_count', 'desc');
        }

        $results = $results->get();

        return response()->json($results);
    }
}
