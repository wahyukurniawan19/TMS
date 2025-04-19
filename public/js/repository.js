// Ensure jQuery is loaded
$(document).ready(function() {
    console.log('Repository.js loaded');

    // Handle select all trashed test cases
    $('#select-all-trashed').on('change', function() {
        $('.trashed-test-case-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Handle single restore
    $('.restore-single').on('click', function() {
        const testCaseId = $(this).data('id');
        restoreTestCase(testCaseId);
    });

    // Handle single force delete
    $('.force-delete-single').on('click', function() {
        const testCaseId = $(this).data('id');
        forceDeleteTestCase(testCaseId);
    });

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

function duplicateSelectedTestCases() {
    console.log('Duplicate button clicked');
    
    const selectedTestCases = [];
    $('.test-case-checkbox:checked').each(function() {
        selectedTestCases.push($(this).val());
    });

    console.log('Selected test cases:', selectedTestCases);

    if (selectedTestCases.length === 0) {
        alert('Please select at least one test case to duplicate');
        return;
    }

    if (confirm(`Are you sure you want to duplicate ${selectedTestCases.length} test case(s)?`)) {
        console.log('Sending duplicate request...');
        
        $.ajax({
            url: '/test-case/duplicate',
            method: 'POST',
            data: {
                test_case_ids: selectedTestCases,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Duplicate response:', response);
                if (response.success) {
                    alert('Test cases duplicated successfully!');
                    location.reload();
                } else {
                    alert('Error duplicating test cases: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Duplicate error:', xhr.responseText);
                alert('Error duplicating test cases. Please try again.');
            }
        });
    }
}

function restoreTestCase(testCaseId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to restore this test case?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, restore it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/test-cases/${testCaseId}/restore`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire(
                        'Restored!',
                        response.message,
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Something went wrong.',
                        'error'
                    );
                }
            });
        }
    });
}

function forceDeleteTestCase(testCaseId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete permanently!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/test-cases/${testCaseId}/force-delete`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        response.message,
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Something went wrong.',
                        'error'
                    );
                }
            });
        }
    });
}

function loadTrashedTestCases() {
    $.ajax({
        url: '/test-case/trashed',
        method: 'GET',
        data: {
            suite_id: '{{ request()->route("suite_id") }}',
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#trashed-cases-list').html(response);
        },
        error: function() {
            $('#trashed-cases-list').html(`
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>Error loading trashed test cases. Please try again.
                </div>
            `);
        }
    });
}