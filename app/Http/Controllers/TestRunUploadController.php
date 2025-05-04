<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestRunUpload;
use App\Models\TestRun;
use App\Models\TestCase;

class TestRunUploadController extends Controller
{
    public function upload(Request $request, $testRunId, $testCaseId)
    {
        $request->validate([
            'result_file' => 'required|file|max:10240', // max 10MB
        ]);

        $file = $request->file('result_file');
        $path = $file->store('test_run_uploads', 'public');

        TestRunUpload::create([
            'test_run_id' => $testRunId,
            'test_case_id' => $testCaseId,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
        ]);

        return back()->with('success', 'File berhasil diupload.');
    }

    public function destroy($testRunId, $testCaseId, $uploadId)
    {
        $upload = TestRunUpload::where('id', $uploadId)
            ->where('test_run_id', $testRunId)
            ->where('test_case_id', $testCaseId)
            ->firstOrFail();

        \Storage::disk('public')->delete($upload->file_path);
        $upload->delete();

        return back()->with('success', 'File berhasil dihapus.');
    }

    public function show($testRunId, $testCaseId)
    {
        

        $uploadedFiles = TestRunUpload::where('test_run_id', $testRunId)
            ->where('test_case_id', $testCaseId)
            ->get();

        return view('test_run.test_case', compact(
            'testRun', 'testCase', 'data', 'uploadedFiles', /* variabel lain */
        ));
    }

    public function loadTestCase($testRunId, $testCaseId)
    {
        $testRun = TestRun::findOrFail($testRunId);
        $testCase = TestCase::findOrFail($testCaseId);
        $data = json_decode($testCase->data);

        $uploadedFiles = \App\Models\TestRunUpload::where('test_run_id', $testRunId)
            ->where('test_case_id', $testCaseId)
            ->get();

        return view('test_run.test_case', compact('testRun', 'testCase', 'data', 'uploadedFiles'));
    }
}
