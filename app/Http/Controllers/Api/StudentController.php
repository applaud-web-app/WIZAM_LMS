<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;

class StudentController extends Controller
{
    public function syllabus(){
        try {
            $data = SubCategory::where('status',1)->where('type','banner')->get();
            return response()->json(['status'=> true,'data' => $data], 201);
        } catch (\Throwable $th) {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }
}
