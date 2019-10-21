<?php

namespace App\Http\Controllers;

use App\Section;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $sections = Section::all();

        return view('sections.index')
            ->with('sections', $sections);
    }

    public function create()
    {
        return view('sections.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();


        $section =Section::create($input);

        Flash::success('Section saved successfully.');

        return redirect(route('sections.index'));
    }

    public function destroy($id)
    {
        $section = Section::find($id);

        if (empty($section)) {
            Flash::error('Section not found');

            return redirect(route('sections.index'));
        }

        $section->delete($id);

        Flash::success('Section deleted successfully.');

        return redirect(route('sections.index'));
    }

}
