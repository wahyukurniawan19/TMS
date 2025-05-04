<div id="test_case_block">

    <div class="border rounded py-1 mt-2 mb-2 d-flex justify-content-center">

        <div class="position-static">
            <button type="button" class="btn btn-outline-success test_run_case_btn"
                    data-status="{{\App\Enums\TestRunCaseStatus::PASSED}}"
                    data-test_run_id="{{$testRun->id}}"
                    onclick="updateCaseStatus({{$testRun->id}}, {{$testCase->id}}, {{\App\Enums\TestRunCaseStatus::PASSED}})">
                Passed
            </button>

            <button type="button" class="btn btn-outline-danger test_run_case_btn"
                    data-status="{{\App\Enums\TestRunCaseStatus::FAILED}}"
                    data-test_run_id="{{$testRun->id}}"
                    onclick="updateCaseStatus({{$testRun->id}}, {{$testCase->id}}, {{\App\Enums\TestRunCaseStatus::FAILED}})">
                Failed
            </button>

            <button type="button" class="btn btn-outline-warning test_run_case_btn"
                    data-status="{{\App\Enums\TestRunCaseStatus::BLOCKED}}"
                    data-test_run_id="{{$testRun->id}}"
                    onclick="updateCaseStatus({{$testRun->id}}, {{$testCase->id}}, {{\App\Enums\TestRunCaseStatus::BLOCKED}})">
                <b>Blocked</b>
            </button>

            <button type="button" class="btn btn-outline-secondary test_run_case_btn"
                    data-status="{{\App\Enums\TestRunCaseStatus::NOT_TESTED}}"
                    data-test_run_id="{{$testRun->id}}"
                    onclick="updateCaseStatus({{$testRun->id}}, {{$testCase->id}}, {{\App\Enums\TestRunCaseStatus::NOT_TESTED}})">
                Not Tested
            </button>
        </div>

    </div>

    <div id="test_case_content">

        <div class="d-flex justify-content-between border-bottom mt-2 pb-2 mb-2">
            <div>
                <span class="fs-6 badge bg-secondary">{{$repository->prefix}}-{{$testCase->id}}</span>
                <span class="fs-5">
            @if($testCase->automated)
                        <i class="bi bi-robot"></i>
                    @else
                        <i class="bi bi-person"></i>
                    @endif
            </span>
                <span class="fs-6">{{$testCase->title}}</span>
            </div>
        </div>

        <div class="p-4 pt-0 position-relative">

            @if(isset( $data->preconditions) && !empty($data->preconditions) )
                <strong class="fs-5 pb-3">Preconditions</strong>
                <div class="row mb-3 border p-3 rounded">

                    <div>
                        {!! $data->preconditions !!}
                    </div>

                </div>
            @endif

            @if(isset($data->steps) && !empty($data->steps))
                <strong class="fs-5 pb-3">Steps</strong>
                <div class="row mb-3 border p-3 rounded" id="steps_container">


                    <div class="row step pb-2 mb-2">
                        <div class="col-6">
                            <b>Action</b>
                        </div>
                        <div class="col-6">
                            <b>Expected result</b>
                        </div>
                    </div>

                    @foreach($data->steps as $id => $step)
                        <div class="row step border-top mb-2 pt-2" data-badge="{{$id+1}}">

                            <div class="col-6">
                                <div>
                                    {!! $step->action !!}
                                </div>
                            </div>

                            <div class="col-6">
                                <div>
                                    {!! $step->result !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- UPLOAD FILE SECTION --}}
                <div class="row mb-3 border p-3 rounded">
                    <form action="{{ route('test_run.upload_file', [$testRun->id, $testCase->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group mb-2">
                            <input type="file" name="result_file" class="form-control" required>
                            <button class="btn btn-primary" type="submit">Upload Hasil</button>
                        </div>
                    </form>

                    {{-- DAFTAR FILE HASIL UPLOAD --}}
                    <div>
                        <strong>Hasil Upload:</strong>
                        <ul class="list-group mt-2">
                            @forelse($uploadedFiles as $file)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">{{ $file->file_name }}</a>
                                    <form action="{{ route('test_run.delete_file', [$testRun->id, $testCase->id, $file->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Hapus file ini?')">Hapus</button>
                                    </form>
                                </li>
                            @empty
                                <li class="list-group-item">Belum ada file hasil upload.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                {{-- END UPLOAD FILE SECTION --}}

            @else
                <p>No additional details available.</p>
            @endif

        </div>

    </div>


</div>
