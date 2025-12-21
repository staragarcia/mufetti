<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Content;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reportable_id' => 'required|integer',
            'reportable_type' => 'required|string',
            'motive' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Prevent users from reporting their own content
        if ($request->reportable_type === 'post' || $request->reportable_type === 'comment') {
            $content = Content::find($request->reportable_id);
            
            if ($content && $content->owner === Auth::id()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'You cannot report your own content.'
                ], 403);
            }
        }

        $report = Report::create([
            'id_user' => Auth::id(),
            'reportable_id' => $request->reportable_id,
            'reportable_type' => $request->reportable_type,
            'motive' => $request->motive,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return response()->json(['success' => true, 'report' => $report]);
    }

    public function index()
    {
        $reports = Report::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.reports.index', compact('reports'));
    }

    public function updateStatus(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $report->status = $request->status;
        $report->save();
        return redirect()->back()->with('success', 'Report status updated.');
    }
}
