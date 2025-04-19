@php use App\Enums\CasePriority;use App\Models\Repository;use App\Models\TestCase;
/**
 * @var TestCase[] $testCases
 * @var Repository $repository
 */
@endphp

<style>
    .test_case {
        user-select: none;
        -webkit-user-drag: none;
        -khtml-user-drag: none;
        -moz-user-drag: none;
        -o-user-drag: none;
        user-drag: none;
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

@if(session('duplicated_cases'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <h5 class="alert-heading"><i class="bi bi-check-circle-fill me-2"></i>Test Cases Duplicated Successfully!</h5>
        <ul class="mb-0">
            @foreach(session('duplicated_cases') as $case)
                <li>{{ $repository->prefix }}-{{ $case->id }}: {{ $case->title }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($testCases->isEmpty())
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>No test cases found in this suite.
    </div>
@else
    @foreach($testCases as $testCase)
        <div id="{{$testCase->id}}" class="card mb-2 test_case" data-case_id="{{$testCase->id}}">
            <div class="card-body p-2">
                <div class="d-flex align-items-center">
                    <div class="form-check me-3">
                        <input class="form-check-input test-case-checkbox" type="checkbox" value="{{$testCase->id}}">
                    </div>
                    
                    <div class="flex-grow-1 test_case_clickable_area" onclick="renderTestCase('{{$testCase->id}}')">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                @if($testCase->priority == CasePriority::MEDIUM)
                                    <i class="bi bi-list text-info" title="Medium Priority"></i>
                                @elseif($testCase->priority == CasePriority::HIGH)
                                    <i class="bi bi-chevron-double-up text-danger" title="High Priority"></i>
                                @else
                                    <i class="bi bi-chevron-double-down text-warning" title="Low Priority"></i>
                                @endif

                                @if($testCase->automated)
                                    <i class="bi bi-robot text-primary mx-1" title="Automated Test"></i>
                                @else
                                    <i class="bi bi-person text-secondary mx-1" title="Manual Test"></i>
                                @endif
                            </div>

                            <div class="me-2">
                                <a href="{{route('test_case_show_page', $testCase->id)}}" target="_blank" class="text-decoration-none">
                                    <span class="badge bg-secondary">{{$repository->prefix}}-{{$testCase->id}}</span>
                                </a>
                            </div>

                            <div class="flex-grow-1">
                                <span class="text-dark">{{$testCase->title}}</span>
                                <div class="small text-muted">
                                    Pembuat: {{ $testCase->creator?->name ?? 'Unknown' }} pada {{ $testCase->created_at->format('d/m/Y H:i:s') }}
                                    @if($testCase->updated_at != $testCase->created_at)
                                        <br>
                                        Terakhir diubah oleh {{ $testCase->updater?->name ?? 'Unknown' }} pada {{ $testCase->updated_at->format('d/m/Y H:i:s') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="test_case_controls ms-2">
                        @can('add_edit_test_cases')
                            <button class="btn btn-sm btn-outline-primary" type="button" title="Edit"
                                    onclick="renderTestCaseEditForm('{{$testCase->id}}')">
                                <i class="bi bi-pencil"></i>
                            </button>
                        @endcan

                        @can('delete_test_cases')
                            <button class="btn btn-sm btn-outline-danger" type="button" title="Delete" 
                                    onclick="deleteTestCase({{$testCase->id}})">
                                <i class="bi bi-trash"></i>
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

<script>
    function showAlert(title, text, icon) {
        if (typeof Swal !== 'undefined') {
            return Swal.fire({
                title: title,
                text: text,
                icon: icon,
                confirmButtonColor: '#3085d6'
            });
        } else {
            alert(text);
            return Promise.resolve({ isConfirmed: true });
        }
    }

    function toggleSelectAll() {
        const checkboxes = $('.test-case-checkbox');
        const selectAllButton = $('#selectAllButton');
        const isAllChecked = checkboxes.length === checkboxes.filter(':checked').length;
        
        if (isAllChecked) {
            // Uncheck all
            checkboxes.prop('checked', false);
            selectAllButton.html('<i class="bi bi-check-square me-1"></i> Select All');
        } else {
            // Check all
            checkboxes.prop('checked', true);
            selectAllButton.html('<i class="bi bi-square me-1"></i> Deselect All');
        }
    }

    function deleteSelectedTestCases() {
        const selectedTestCases = [];
        $('.test-case-checkbox:checked').each(function() {
            selectedTestCases.push($(this).val());
        });

        if (selectedTestCases.length === 0) {
            showAlert('No Selection', 'Please select at least one test case to delete', 'warning');
            return;
        }

        showAlert(
            'Confirm Deletion',
            `Are you sure you want to delete ${selectedTestCases.length} test case(s)? This action cannot be undone.`,
            'warning'
        ).then((result) => {
            if (result.isConfirmed) {
                // Delete each test case
                let deletedCount = 0;
                selectedTestCases.forEach(testCaseId => {
                    $.ajax({
                        url: "/test-case/delete",
                        method: "POST",
                        data: {
                            "id": testCaseId,
                            "_token": $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {
                            $(`[data-case_id=${testCaseId}]`).remove();
                            deletedCount++;
                            
                            if (deletedCount === selectedTestCases.length) {
                                showAlert('Success!', `${deletedCount} test case(s) deleted successfully`, 'success');
                            }
                        },
                        error: function() {
                            showAlert('Error', 'Error deleting test case(s). Please try again.', 'error');
                        }
                    });
                });
            }
        });
    }

    function duplicateSelectedTestCases() {
        const selectedTestCases = [];
        $('.test-case-checkbox:checked').each(function() {
            selectedTestCases.push($(this).val());
        });

        if (selectedTestCases.length === 0) {
            showAlert('No Selection', 'Please select at least one test case to duplicate', 'warning');
            return;
        }

        showAlert(
            'Confirm Duplication',
            `Are you sure you want to duplicate ${selectedTestCases.length} test case(s)?`,
            'question'
        ).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/test-case/duplicate',
                    method: 'POST',
                    data: {
                        test_case_ids: selectedTestCases,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('Success!', 'Test cases duplicated successfully', 'success')
                                .then(() => {
                                    location.reload();
                                });
                        } else {
                            showAlert('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        showAlert('Error', 'Error duplicating test cases. Please try again.', 'error');
                    }
                });
            }
        });
    }

    function deleteTestCase(id) {
        showAlert(
            'Konfirmasi Hapus',
            'Apakah Anda yakin ingin menghapus test case ini?',
            'warning'
        ).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/test-case/delete",
                    method: "POST",
                    data: {
                        "id": id,
                        "_token": $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $(`[data-case_id=${id}]`).fadeOut(300, function() {
                                $(this).remove();
                                showAlert('Berhasil!', 'Test case telah dihapus', 'success');
                            });

                            if ($('#tce_case_id').val() == id || $('#tce_case_id').text() == id) {
                                closeTestCaseEditor();
                            }
                        } else {
                            showAlert('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        showAlert('Error', 'Gagal menghapus test case. Silakan coba lagi.', 'error');
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        // Initialize select all button
        $('#selectAllButton').on('click', function() {
            toggleSelectAll();
        });

        // Initialize delete button
        $('#deleteButton').on('click', function() {
            deleteSelectedTestCases();
        });

        // Initialize duplicate button
        $('#duplicateButton').on('click', function() {
            duplicateSelectedTestCases();
        });

        // Update select all button state when individual checkboxes change
        $('.test-case-checkbox').on('change', function() {
            const checkboxes = $('.test-case-checkbox');
            const selectAllButton = $('#selectAllButton');
            const checkedCount = checkboxes.filter(':checked').length;
            
            if (checkedCount === 0) {
                selectAllButton.html('<i class="bi bi-check-square me-1"></i> Select All');
            } else if (checkedCount === checkboxes.length) {
                selectAllButton.html('<i class="bi bi-square me-1"></i> Deselect All');
            } else {
                selectAllButton.html('<i class="bi bi-check-square me-1"></i> Select All');
            }
        });
    });
</script>
