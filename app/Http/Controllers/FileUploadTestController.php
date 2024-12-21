<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadTestController extends Controller
{
    public function index()
    {
        return view('file-upload-test');
    }

    public function store(Request $request)
    {
        // return $request->all();
        if ($request->hasFile('test')) {
            $request->test->store('tests');

            return "Success";
        } else {
            return "False";
        }
    }
}
