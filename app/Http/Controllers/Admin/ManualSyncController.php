<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\AddManualSyncAttendanceRequest;
use App\Models\ManualSync;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ManualSyncController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.manual-sync');
    }


    public function addManualSync(AddManualSyncAttendanceRequest $request)
    {
        try
        {
            $input = $request->validated();
            $input['user_id'] = User::where('emp_code', $input['emp_code'])->value('id');

            ManualSync::create( Arr::only($input, ManualSync::getFillables()) );

            return response()->json(['success'=> 'Sync attendance request added successfully!']);
        }
        catch(Exception $e)
        {
            return $this->respondWithAjax($e, 'syncing', 'attendance request');
        }
    }


    public function checkSyncStatus(Request $request)
    {
        $isSyncing = ManualSync::where('emp_code', $request->emp_code)->first();

        if($isSyncing)
            return response()->json(['success'=> 'Attendance data syncing is in process, please check again after sometime', 'message_type' => 'warning']);
        else
            return response()->json(['success'=> 'Attendance data synced successfully!', 'message_type' => 'success']);

        return response()->json(['success'=> 'Something went wrong while checking sync status!', 'message_type' => 'danger']);
    }
}
