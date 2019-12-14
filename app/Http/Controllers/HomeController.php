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
        $nameSearch = $request->nameSearch;
        $nameToSearch = $request->nameToSearch;
        $results = null;
        $type = $request->type;
        $typeClass = $request->type === 'Herb' ? Herb::class : HerbFormula::class;

        if (!$nameSearch && count($signs) > 0) {
            $results = $typeClass::withCount(['signs_symptoms' => function ($q) use ($signs) {
                $q->whereIn('id', $signs);
            }])->whereHas('signs_symptoms', function ($q) use ($signs) {
                $q->whereIn('id', $signs);
            });
        } else if ($nameSearch && isset($nameToSearch)) {
            $results = $typeClass::where(function ($q) use ($nameSearch, $nameToSearch, $type) {
                $cols = ['english_name', 'chinese_name'];

                if ($type === 'Herb') {
                    $cols = array_merge($cols, ['pharmaceutical_name', 'literal_name']);
                }

                $q->where(\DB::raw('1'), \DB::raw('1'));

                foreach ($cols as $col) {
                    $q->orWhere($col, 'LIKE', "%{$nameToSearch}%");
                }
            });
        } else {
            $results = $typeClass::where(\DB::raw('1'), \DB::raw('1'));
        }

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

        $results = $results->with('items');

        if (!$nameSearch && count($signs) > 0) {
            $results = $results->orderBy('signs_symptoms_count', 'desc');
        }

        $results = $results->get();

        return response()->json($results);
    }
}
