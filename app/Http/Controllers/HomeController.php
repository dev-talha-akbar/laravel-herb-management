<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Herb;
use App\Models\HerbFormula;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search(Request $request)
    {
        $signs = $request->signs;
        $hormones = $request->hormones;
        $chemicalComposition = $request->chemicalComposition;
        $pharmacology = $request->pharmacology;
        $antibioticStrains = $request->antibioticStrains;
        $advancedSearch = $request->advancedSearch;
        $results = null;
        $typeClass = $request->type === 'Herb' ? Herb::class : HerbFormula::class;

        $results = $typeClass::withCount(['signs_symptoms' => function ($q) use ($signs) {
            $q->whereIn('id', $signs);
        }])->whereHas('signs_symptoms', function ($q) use ($signs) {
            $q->whereIn('id', $signs);
        });

        if ($advancedSearch) {
            if (count($hormones) > 0) {
                $results = $results
                    ->whereHas('hormones', function ($q) use ($hormones) {
                        $q->whereIn('id', $hormones);
                    });
            }

            if (count($chemicalComposition) > 0) {
                $results = $results
                    ->whereHas('chemical_composition', function ($q) use ($chemicalComposition) {
                        $q->whereIn('id', $chemicalComposition);
                    });
            }

            if (count($pharmacology) > 0) {
                $results = $results->whereHas('pharmacology', function ($q) use ($pharmacology) {
                    $q->whereIn('id', $pharmacology);
                });
            }

            if (count($antibioticStrains) > 0) {
                $results = $results->whereHas('antibiotic_strains', function ($q) use ($antibioticStrains) {
                    $q->whereIn('id', $antibioticStrains);
                });
            }
        }

        if ($request->type === 'Herb Formula') {
            $results = $results->with('herbs');
        } else if ($request->type === 'Herb') {
            $results = $results->with('formulas');
        }

        $results = $results->with('items')->orderBy('signs_symptoms_count', 'desc')->get();

        return response()->json($results);
    }
}
