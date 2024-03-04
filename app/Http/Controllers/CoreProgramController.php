<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoreProgram;

class CoreProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
        $coreprogram = CoreProgram::all();
        return $coreprogram;
    }

    public function getCoreProgram()
    {
        $coreprogram    = CoreProgram::select('program_id', 'program_name', 'program_photo', 'program_remark')->where('data_state','=', 0)->get();
        
        for($i = 0; $i< count($coreprogram); $i++){
            $program_photo =  url('/')."/".$coreprogram[$i]['program_photo'];

            $coreprogram[$i]->program_photo = $program_photo;
        }

        return $coreprogram;
    }
}
