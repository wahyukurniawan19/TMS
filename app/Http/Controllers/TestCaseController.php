<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Repository;
use App\Models\Suite;
use App\Models\TestCase;
use Illuminate\Http\Request;

class TestCaseController extends Controller
{
    public function store(Request $request)
    {
        if (!auth()->user()->can('add_edit_test_cases')) {
            abort(403);
        }

        $testCase = new TestCase();

        $testCase->title = $request->title;
        $testCase->automated = (bool) $request->automated;
        $testCase->priority = $request->priority;
        $testCase->suite_id = $request->suite_id;
        $testCase->order = $request->order;
        $testCase->data = $request->data;
        $testCase->created_by = auth()->id();
        $testCase->updated_by = auth()->id();

        $testCase->save();

        $suite = Suite::findOrFail($testCase->suite_id);
        $repository = Repository::findOrFail($suite->repository_id);

        $testCase->repository_id = $suite->repository_id;  // это нужно для загрузки формы  read в js

        return [
            'html' => '',
            'json' => $testCase->toJson()
        ];
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('add_edit_test_cases')) {
            abort(403);
        }

        $testCase = TestCase::findOrFail($request->id);

        $testCase->title = $request->title;
        $testCase->automated = (bool) $request->automated;
        $testCase->priority = $request->priority;
        $testCase->suite_id = $request->suite_id;
        $testCase->data = $request->data;
        $testCase->updated_by = auth()->id();

        $testCase->save();

        $suite = Suite::findOrFail($testCase->suite_id);
        $repository = Repository::findOrFail($suite->repository_id);

        $testCase->repository_id = $suite->repository_id;  // это нужно для загрузки формы в js

        return [
            'html' => '',
            'json' => $testCase->toJson()
        ];
    }

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('delete_test_cases')) {
            abort(403);
        }

        $testCase = TestCase::findOrFail($request->id);
        $testCase->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Test case has been deleted'
        ]);
    }

    public function restore($id)
    {
        if (!auth()->user()->can('delete_test_cases')) {
            abort(403);
        }

        $testCase = TestCase::withTrashed()->findOrFail($id);
        $testCase->restore();

        return response()->json([
            'success' => true,
            'message' => 'Test case has been restored'
        ]);
    }

    public function forceDelete($id)
    {
        if (!auth()->user()->can('delete_test_cases')) {
            abort(403);
        }

        $testCase = TestCase::withTrashed()->findOrFail($id);
        $testCase->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Test case has been permanently deleted'
        ]);
    }

    public function duplicate(Request $request)
    {
        if (!auth()->user()->can('add_edit_test_cases')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $duplicatedCases = [];
            $testCaseIds = $request->test_case_ids;

            if (empty($testCaseIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No test cases selected'
                ], 400);
            }

            foreach ($testCaseIds as $testCaseId) {
                $originalCase = TestCase::findOrFail($testCaseId);
                
                $newCase = new TestCase();
                $newCase->title = $originalCase->title . ' (Copy)';
                $newCase->automated = $originalCase->automated;
                $newCase->priority = $originalCase->priority;
                $newCase->suite_id = $originalCase->suite_id;
                $newCase->order = $originalCase->order;
                $newCase->data = $originalCase->data;
                $newCase->created_by = auth()->id();
                $newCase->updated_by = auth()->id();
                
                $newCase->save();
                $duplicatedCases[] = $newCase;
            }

            // Store duplicated cases in session
            session()->flash('duplicated_cases', $duplicatedCases);

            return response()->json([
                'success' => true,
                'message' => 'Test cases duplicated successfully',
                'duplicated_cases' => $duplicatedCases
            ]);
        } catch (\Exception $e) {
            \Log::error('Error duplicating test cases: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error duplicating test cases: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateOrder(Request $request)
    {
        foreach ($request->order as $data) {
            $testCase = TestCase::findOrFail($data['id']);
            $testCase->order = $data['order'];
            $testCase->save();
        }
    }

    /*****************************************
     *  PAGES / FORMS / HTML BLOCKS
     *****************************************/

    public function show($test_case_id)
    {
        $testCase = TestCase::findOrFail($test_case_id);
        $data = json_decode($testCase->data);

        $parentTestSuite = Suite::findOrFail($testCase->suite_id);
        $repository = Repository::findOrFail($parentTestSuite->repository_id);
        $project = Project::findOrFail($repository->project_id);

        return view('test_case.show_page')
            ->with('project', $project)
            ->with('repository', $repository)
            ->with('parentTestSuite', $parentTestSuite)
            ->with('testCase', $testCase)
            ->with('data', $data);
    }

    public function loadCreateForm($repository_id, $parent_test_suite_id = null)
    {
        if ($parent_test_suite_id != null) {
            $parentTestSuite = Suite::where('id', $parent_test_suite_id)->first();
        } else {
            $parentTestSuite = Suite::where('repository_id', $repository_id)->first();
        }

        $repository = Repository::findOrFail($parentTestSuite->repository_id);
        $project = Project::findOrFail($repository->project_id);

        return view('test_case.create_form')
            ->with('project', $project)
            ->with('repository', $repository)
            ->with('parentTestSuite', $parentTestSuite);
    }

    public function loadShowForm($test_case_id)
    {
        $testCase = TestCase::findOrFail($test_case_id);
        $data = json_decode($testCase->data);

        $parentTestSuite = Suite::findOrFail($testCase->suite_id);
        $repository = Repository::findOrFail($parentTestSuite->repository_id);
        $project = Project::findOrFail($repository->project_id);

        return view('test_case.show_form')
            ->with('project', $project)
            ->with('repository', $repository)
            ->with('parentTestSuite', $parentTestSuite)
            ->with('testCase', $testCase)
            ->with('data', $data);
    }

    public function loadShowOverlay($test_case_id)
    {
        $testCase = TestCase::findOrFail($test_case_id);
        $data = json_decode($testCase->data);

        $parentTestSuite = Suite::findOrFail($testCase->suite_id);
        $repository = Repository::findOrFail($parentTestSuite->repository_id);
        $project = Project::findOrFail($repository->project_id);

        return view('test_case.show_overlay')
            ->with('project', $project)
            ->with('repository', $repository)
            ->with('parentTestSuite', $parentTestSuite)
            ->with('testCase', $testCase)
            ->with('data', $data);
    }

    public function loadEditForm($test_case_id)
    {
        $testCase = TestCase::findOrFail($test_case_id);
        $data = json_decode($testCase->data);

        $parentTestSuite = Suite::findOrFail($testCase->suite_id);
        $repository = Repository::findOrFail($parentTestSuite->repository_id);
        $project = Project::findOrFail($repository->project_id);

        return view('test_case.edit_form')
            ->with('project', $project)
            ->with('repository', $repository)
            ->with('parentTestSuite', $parentTestSuite)
            ->with('testCase', $testCase)
            ->with('data', $data);
    }

    public function getTrashed(Request $request)
    {
        $suiteId = $request->input('suite_id');
        $trashedTestCases = TestCase::onlyTrashed()
            ->where('suite_id', $suiteId)
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('repository.partials.trashed_test_cases', [
            'trashedTestCases' => $trashedTestCases
        ]);
    }

}
