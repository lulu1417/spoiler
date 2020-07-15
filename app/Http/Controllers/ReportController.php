<?php


namespace App\Http\Controllers;


use App\Report;
use Illuminate\Http\Request;

class ReportController extends BaseController
{
    function report(Request $request)
    {
        $request->validate([
            'content' => ['required','min:2'],
            'reporter_is_user' => ['required', 'boolean'],
        ]);

        if (intval($request->reporter_is_user)) {

            $request->validate([
                'object_id' => ['required','exists:owners,id'],
                'reporter_id' =>['required','exists:users,id'],
            ]);
        } else {
            $request->validate([
                'object_id' => ['required','exists:users,id'],
                'reporter_id' => ['required','exists:owners,id'],
            ]);
        }

        $create = Report::create([
            'content' => $request['content'],
            'object_id' => $request->object_id,
            'reporter_is_user' => $request->reporter_is_user,
            'reporter_id' => $request->reporter_id,
        ]);

        return response()->json($create);

    }

}
