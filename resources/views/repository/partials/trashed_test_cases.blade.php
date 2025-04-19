@if($trashedTestCases->isEmpty())
    <div class="alert alert-info" role="alert">
        No trashed test cases found.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-trashed"></th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Deleted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trashedTestCases as $testCase)
                    <tr>
                        <td>
                            <input type="checkbox" class="trashed-test-case-checkbox" value="{{ $testCase->id }}">
                        </td>
                        <td>{{ $testCase->title }}</td>
                        <td>{{ Str::limit($testCase->description, 100) }}</td>
                        <td>{{ $testCase->deleted_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <button class="btn btn-sm btn-success restore-single" data-id="{{ $testCase->id }}" title="Restore">
                                <i class="fas fa-trash-restore"></i>
                            </button>
                            <button class="btn btn-sm btn-danger force-delete-single" data-id="{{ $testCase->id }}" title="Delete Permanently">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif 