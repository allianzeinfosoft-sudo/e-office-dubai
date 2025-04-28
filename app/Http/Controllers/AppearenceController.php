<?php

namespace App\Http\Controllers;

use App\Models\Appearence;
use Illuminate\Http\Request;

class AppearenceController extends Controller
{

    public function index(Request $request)
    {

         if($request->ajax()) {

            $background = Appearence::get()
            ->map(function ($background) {
                return [
                    'id' => $background->id,
                    'background_type' => $background->background_type ? $background->background_type : '',
                    'image' => $background->image ? $background->image : '',

                ];
            });


            return response()->json([
                'data' => $background
            ]);

        }

        $data['meta_title'] = 'Change Appearence';
        return view('appearence.index', $data);

    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Appearence $changeAppearence)
    {
        //
    }


    public function edit(Appearence $changeAppearence)
    {
        //
    }


    public function update(Request $request, Appearence $changeAppearence)
    {
        //
    }

    public function destroy(Appearence $changeAppearence)
    {
        //
    }
}
