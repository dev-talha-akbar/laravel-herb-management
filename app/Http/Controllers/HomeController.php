<?php

namespace App\Http\Controllers;

use App\Models\Herb;
use App\Models\HerbFormula;
use App\Models\Item;
use App\Models\Submission;
use DB;
use Illuminate\Http\Request;
use PDF;
use \stdClass;

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
            return view('welcome')->with('is_admin', false);
        }
    }

    public function submission($id)
    {
        $submission = Submission::findOrFail($id);
        $user = \Auth::user();

        if ($user) {
            $is_admin = $user->hasRole('Admin') || $user->hasRole('Super Admin');
        } else {
            $is_admin = false;
        }

        return view('welcome')->with('submission', $submission)->with('is_admin', $is_admin);
    }

    private function recursive_unset(&$array, $unwanted_key) {
        if(is_array($array)) {
            unset($array[$unwanted_key]);
            foreach ($array as &$value) {
                if (is_array($value) || is_object($value)) {
                    $this->recursive_unset($value, $unwanted_key);
                }
            }
        } else if (is_object($array)) {
            unset($array->$unwanted_key);
            foreach ($array as $key => &$value) {
                if (is_array($value) || is_object($value)) {
                    $this->recursive_unset($value, $unwanted_key);
                }
            }
        }
    }

    public function submission_download($id)
    {
        set_time_limit(300);
        $submission = Submission::findOrFail($id);
        $user = \Auth::user();

        if ($user) {
            $is_admin = $user->hasRole('Admin') || $user->hasRole('Super Admin');
        } else {
            $is_admin = false;
        }

        $form = json_decode($submission->form);
        $patientName = "$form->pfname $form->pmname $form->plname";
        $submittedAt = $submission->created_at;

        $sign_ids = json_decode($submission->result);
        $res = json_encode($this->searchForSigns($sign_ids));
        $results = json_decode($res);
        
        $herbs = $results->herbs;
        $herbs = array_slice($herbs, 0, 10);
        $this->recursive_unset($herbs, "created_at");
        $this->recursive_unset($herbs, "updated_at");
        $this->recursive_unset($herbs, "pivot");


        $herb_formulas = $results->herb_formulas;
        $herb_formulas = array_slice($herb_formulas, 0, 10);
        $this->recursive_unset($herb_formulas, "created_at");
        $this->recursive_unset($herb_formulas, "updated_at");
        $this->recursive_unset($herb_formulas, "pivot");
        $this->recursive_unset($herb_formulas, "dropbox_herb_image");
        $this->recursive_unset($herb_formulas, "herb_image");
        $this->recursive_unset($herb_formulas, "dropbox_constituent_images");

        for ($i=0; $i < count($herb_formulas); $i++) { 
            $items = $herb_formulas[$i]->items;

            $herb_formulas[$i]->signs_symptoms = "";
            $herb_formulas[$i]->categories = "";
            $herb_formulas[$i]->formula_diagnosis = "";
            $herb_formulas[$i]->tongue_diagnosis = "";
            $herb_formulas[$i]->pulse_diagnosis = "";
            $herb_formulas[$i]->formula_actions = "";
            $herb_formulas[$i]->herb_drug_interaction = "";
            $herb_formulas[$i]->toxicity_contraindications = "";

            foreach ($items as $item) {

                if($item->type =="signs_symptoms"){
                    $herb_formulas[$i]->signs_symptoms .= $item->value.", ";
                } else if($item->type =="categories"){
                    $herb_formulas[$i]->categories .= $item->value.", ";
                } else if($item->type =="formula_diagnosis"){
                    $herb_formulas[$i]->formula_diagnosis .= $item->value.", ";
                } else if($item->type =="tongue_diagnosis"){
                    $herb_formulas[$i]->tongue_diagnosis .= $item->value.", ";
                } else if($item->type =="pulse_diagnosis"){
                    $herb_formulas[$i]->pulse_diagnosis .= $item->value.", ";
                } else if($item->type =="formula_actions"){
                    $herb_formulas[$i]->formula_actions .= $item->value.", ";
                } else if($item->type =="herb_drug_interaction"){
                    $herb_formulas[$i]->herb_drug_interaction .= $item->value.", ";
                } else if($item->type =="toxicity_contraindications"){
                    $herb_formulas[$i]->toxicity_contraindications .= $item->value.", ";
                }
            }

            $herb_formulas[$i]->herbs_used = "";
            foreach ($herb_formulas[$i]->herbs as $herbUsed) {
                $herb_formulas[$i]->herbs_used .= "$herbUsed->chinese_name ($herbUsed->english_name) $herbUsed->dosage_with_unit";
            }
        }
        $this->recursive_unset($herb_formulas, "items");
        $this->recursive_unset($herb_formulas, "herbs");
        // echo "<pre>";
        // print_r($herb_formulas);
        // echo "</pre>";
        // return view('submission_download')->with('herbs', $herbs)->with('herb_formulas', $herb_formulas);
        $data = [
            "herbs" => $herbs,
            "herb_formulas" => $herb_formulas,
            "patientName" => $patientName,
            "submittedAt" => $submittedAt
        ];
        $pdf = PDF::loadView('submission_download', $data);
        return $pdf->download('Submission.pdf');

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
            'antibiotic_strains',
        ])->get();

        return response()->json($options->groupBy('type'));
    }

    public function group(Request $request, $id)
    {
        $sign_symptom = DB::table('sign_symptom_groups')->where('item_id', $id)->first();

        if ($sign_symptom) {
            DB::table('sign_symptom_groups')->where('item_id', $id)->update([
                'group' => $request->group,
            ]);
        } else {
            DB::table('sign_symptom_groups')->insert([
                'item_id' => $id,
                'group' => $request->group,
            ]);
        }
    }

    public function submit(Request $request)
    {
        // $result = $this->searchForSigns($request->signsSelected);

        // $this->recursive_unset($result, "created_at");
        // $this->recursive_unset($result, "updated_at");
        // $this->recursive_unset($result, "pivot");

        $submission = Submission::create(['form' => $request->form, 'result' => json_encode($request->signsSelected), 'name' => $request->name, 'status' => 1]);
        \Session::flash('success', 'We have received your submission successfully!');
        return response()->json($submission->id);
    }

    public function editSubmission(Request $request, $id)
    {
        $submission = Submission::findOrFail($id);

        // $result = $this->searchForSigns($request->signsSelected);

        $submission->update([
            'form' => $request->form,
            'result' => json_encode($request->signsSelected),
            'name' => $request->name,
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
            'herb_formulas' => $herb_formula_results,
        ];
    }

    public function getSignResults($sign_ids) {
        $sign_ids = json_decode($sign_ids);

        $result = $this->searchForSigns($sign_ids);

        $this->recursive_unset($result, "created_at");
        $this->recursive_unset($result, "updated_at");
        $this->recursive_unset($result, "pivot");

        return response()->json($result);
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
