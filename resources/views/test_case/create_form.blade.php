<div id="test_case_editor" class="animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between border-bottom mt-2 pb-2 mb-2">
        <div>
            <h4 class="mb-0 d-flex align-items-center">
                <i class="bi bi-plus-circle-dotted me-2"></i>
                Create Test Case
            </h4>
        </div>

        <div>
            <button href="button" class="btn btn-outline-secondary btn-sm" onclick="closeTestCaseEditor()">
                <i class="bi bi-x-lg"></i> <span class="ms-1">Cancel</span>
            </button>
        </div>
    </div>

    <div id="test_case_content" class="px-4 pt-0">
        <div class="row mb-4">
            <div class="col-12 mb-3">
                <div class="card bg-light border">
                    <div class="card-body d-flex justify-content-start gap-4">
                        <div class="flex-grow-1">
                            <label for="test_suite_id" class="form-label d-flex align-items-center">
                                <i class="bi bi-folder me-2"></i>
                                <strong>Test Suite</strong>
                            </label>
                            <select name="suite_id" id="tce_test_suite_select" class="form-select">
                                @foreach($repository->suites as $repoTestSuite)
                                    <option value="{{$repoTestSuite->id}}"
                                            @if($repoTestSuite->id == $parentTestSuite->id)
                                                selected
                                            @endif
                                    >
                                        {{$repoTestSuite->title}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label d-flex align-items-center">
                                <i class="bi bi-flag me-2"></i>
                                <strong>Priority</strong>
                            </label>
                            <div class="input-group">
                                <select id="tce_priority_select" name="priority" class="form-select">
                                    <option value="{{\App\Enums\CasePriority::HIGH}}">
                                        <i class="bi bi-chevron-double-up text-danger"></i> High
                                    </option>
                                    <option value="{{\App\Enums\CasePriority::MEDIUM}}" selected>
                                        <i class="bi bi-list text-info"></i> Medium
                                    </option>
                                    <option value="{{\App\Enums\CasePriority::LOW}}">
                                        <i class="bi bi-chevron-double-down text-warning"></i> Low
                                    </option>
                                </select>
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-chevron-double-up text-danger"></i>|
                                    <i class="bi bi-list text-info"></i>|
                                    <i class="bi bi-chevron-double-down text-warning"></i>
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="form-label d-flex align-items-center">
                                <i class="bi bi-gear me-2"></i>
                                <strong>Type</strong>
                            </label>
                            <div class="input-group">
                                <select name="automated" class="form-select" id="tce_automated_select">
                                    <option value="0" selected> Manual</option>
                                    <option value="1">Automated</option>
                                </select>
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-person"></i>|<i class="bi bi-robot"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-3">
                <label for="title" class="form-label d-flex align-items-center">
                    <i class="bi bi-card-heading me-2"></i>
                    <strong>Title</strong>
                </label>
                <input name="title" id="tce_title_input" type="text" class="form-control"
                       placeholder="Enter test case title" autofocus>
            </div>

            <div class="col-12">
                <label class="form-label d-flex align-items-center">
                    <i class="bi bi-list-check me-2"></i>
                    <strong>Preconditions</strong>
                </label>
                <textarea name="pre_conditions" class="editor_textarea form-control"
                          id="tce_preconditions_input" rows="3"
                          placeholder="Enter any preconditions required for this test case"></textarea>
            </div>
        </div>

        <div class="row" id="steps_container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-list-ol me-2"></i>
                        Steps
                    </h5>
                    <small class="text-muted">Action <i class="bi bi-arrow-right"></i> Expected Result</small>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addStep()">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Step
                </button>
            </div>

            <div class="row m-0 p-0 mt-2 step">
                <div class="col-auto p-0 d-flex flex-column align-items-center">
                    <span class="badge bg-secondary mb-2 step_number">1</span>

                    <div class="btn-group-vertical">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-2 py-1"
                                onclick="stepUp(this)" title="Move Up">
                            <i class="bi bi-arrow-up"></i>
                        </button>

                        <button type="button" class="btn btn-outline-danger btn-sm px-2 py-1"
                                onclick="removeStep(this)" title="Remove Step">
                            <i class="bi bi-trash"></i>
                        </button>

                        <button type="button" class="btn btn-outline-secondary btn-sm px-2 py-1"
                                onclick="stepDown(this)" title="Move Down">
                            <i class="bi bi-arrow-down"></i>
                        </button>
                    </div>
                </div>

                <div class="col p-0 px-2 test_case_step">
                    <textarea class="editor_textarea form-control step_action" rows="2"
                              placeholder="Describe the action to be performed"></textarea>
                </div>
                <div class="col p-0 test_case_step">
                    <textarea class="editor_textarea form-control step_result" rows="2"
                              placeholder="Describe the expected result"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div id="test_case_editor_footer" class="border-top mt-4 pt-3 pb-2 px-4 bg-light">
        <div class="d-flex justify-content-end gap-2">
            <button id="tce_save_btn" type="button" class="btn btn-success" onclick="createTestCase(true)">
                <i class="bi bi-plus-circle me-1"></i>
                Create and Add Another
            </button>
            <button id="tce_save_btn" type="button" class="btn btn-primary" onclick="createTestCase()">
                <i class="bi bi-check-lg me-1"></i>
                Create Test Case
            </button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    #test_case_editor {
        background: white;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    
    .form-control, .form-select {
        border-color: #dee2e6;
        transition: all 0.2s ease-in-out;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.15);
    }
    
    .input-group-text {
        border-color: #dee2e6;
    }
    
    .step {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px !important;
        margin-bottom: 15px !important;
        border: 1px solid #dee2e6;
    }
    
    .step_number {
        font-size: 1rem;
        min-width: 30px;
        text-align: center;
    }
    
    .btn-group-vertical > .btn {
        margin-bottom: 5px;
    }
    
    #test_case_editor_footer {
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    
    textarea {
        resize: vertical;
    }
</style>
