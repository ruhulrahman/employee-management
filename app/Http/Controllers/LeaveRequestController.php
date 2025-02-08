<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function createData(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'reason' => 'required',
        ]);

        $leaveRequest = LeaveRequest::create([
            'employee_id' => $user->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => $request->status != null ? $request->status : 'Pending',
        ]);
        // $leaveRequest->employee_id = $user->id;
        // $leaveRequest->status = 'Pending';
        // $leaveRequest->save();

        return response()->json([
            'data' => $leaveRequest,
            'message' => 'Leave request successfully',
        ]);
    }

    public function updateData(Request $request) {

        $request->validate([
            'id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'reason' => 'required',
        ]);

        $leaveRequest = LeaveRequest::find($request->id);

        if (!$leaveRequest) {
            return response()->json(['error' => 'Leave Request not found'], 404);
        }
        $leaveRequest->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ]);

        return response()->json([
            'data' => $leaveRequest,
            'message' => 'Leave request updated successfully',
        ]);
    }

    public function getAllPendingRequest() {

        $user = auth()->user();

        $list = [];

        if ($user->role == 'Employee') {
            $list = LeaveRequest::with('employee')
            ->where('employee_id', $user->id)
            ->where('status', 'Pending')->get();
        } else {
            $list = LeaveRequest::with('employee')->where('status', 'Pending')->get();
        }

        return response()->json($list);
    }


    public function getAllLeaveRequest() {

        $user = auth()->user();

        $list = [];

        if ($user->role == 'Employee') {
            $list = LeaveRequest::with('employee')
            ->where('employee_id', $user->id)->get();
        } else {
            $list = LeaveRequest::with('employee')->get();
        }

        return response()->json($list);
    }


    public function getAllApproveRequest() {
        $user = auth()->user();

        $list = [];

        if ($user->role == 'Employee') {
            $list = LeaveRequest::with('employee')
            ->where('employee_id', $user->id)
            ->where('status', 'Approved')->get();
        } else {
            $list = LeaveRequest::with('employee')->where('status', 'Approved')->get();
        }

        return response()->json($list);
    }

    public function getAllRejectedRequest() {
        $user = auth()->user();

        $list = [];

        if ($user->role == 'Employee') {
            $list = LeaveRequest::with('employee')
            ->where('employee_id', $user->id)
            ->where('status', 'Rejected')->get();
        } else {
            $list = LeaveRequest::with('employee')->where('status', 'Rejected')->get();
        }

        return response()->json($list);
    }

    public function ApproveLeaveRequest(Request $request) {

        $request->validate([
            'id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'reason' => 'required',
            'status' => 'required',
        ]);

        $leaveRequest = LeaveRequest::with('employee')->find($request->id);

        if (!$leaveRequest) {
            return response()->json(['error' => 'Leave Request not found'], 404);
        }

        $leaveRequest->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'data' => $leaveRequest,
            'message' => 'Leave request status updated successfully',
        ]);
    }


    public function getLoggedInUserLeaveRequest(Request $request)
    {
        $user = auth()->user();

        $list = LeaveRequest::with('employee')->where('employee_id', $user->id)->get();

        return response()->json($list);
    }
}
