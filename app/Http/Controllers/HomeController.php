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
    public function signs()
    {
        return response()->json(Item::where('type', 'signs_symptoms')->get());
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search(Request $request)
    {
        $signs = $request->signs;
        $results = null;

        if ($request->type === 'Herb') {
            $results = Herb::whereHas('signs_symptoms', function ($q) use ($signs) {
                $q->whereIn('id', $signs);
            })->with('items')->get();
        } else if ($request->type === 'Herb Formula') {
            $results = HerbFormula::whereHas('signs_symptoms', function ($q) use ($signs) {
                $q->whereIn('id', $signs);
            })->with('herbs')->with('items')->get();
        }

        return response()->json($results);
    }
}
