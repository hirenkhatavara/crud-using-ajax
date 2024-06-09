<!DOCTYPE html>
<html>
<head>
    <title>Laravel Ajax CRUD with DataTables</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Laravel Ajax CRUD</h2>
            <button class="btn btn-success mb-2" id="addNew">Add New</button>
            <button class="btn btn-danger mb-2" id="bulkDelete">Bulk Delete</button>
            <table class="table table-bordered" id="userTable">
                <thead>
                <tr>
                    <th>Select</th>
                    <th data-field="name">Name</th>
                    <th data-field="contact_number">Contact Number</th>
                    <th data-field="hobbies">Hobbies</th>
                    <th data-field="category">Category</th>
                    <th data-field="profile_picture">Profile Picture</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <form id="addForm" class="d-none">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number:</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number">
                </div>
                <div class="form-group">
                    <label for="hobbies">Hobbies:</label>
                    <div id="hobbies">
                        @foreach($hobbies as $hobby)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="hobby_{{ $hobby->id }}" name="hobbies[]" value="{{ $hobby->id }}">
                                <label class="form-check-label" for="hobby_{{ $hobby->id }}">{{ $hobby->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select class="form-control" id="category" name="category">
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="profile_picture">Profile Picture:</label>
                    <input type="file" class="form-control-file" id="profile_picture" name="profile_picture">
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('user.index') }}",
        columns: [
            { data: 'select', name: 'select', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'contact_number', name: 'contact_number' },
            { data: 'hobbies', name: 'hobbies' },
            { data: 'category', name: 'category' },
            { data: 'profile_picture', name: 'profile_picture', orderable: false, searchable: false, render: function (data) {
                return data ? `<img src="/images/${data}" width="50" height="50" />` : '';
            }},
            { data: 'actions', name: 'actions', orderable: false, searchable: false }


        ],
    select: {
        style: 'os',
        selector: 'td:first-child'
    }
    });

    $('#userTable tbody').on('click', '.editBtn', function() {
    var row = $(this).closest('tr');
    var rowData = table.row(row).data();

    // Fetch and display hobbies
    var fields = ['name', 'contact_number', 'hobbies', 'category'];

    row.find('td').each(function(index) {
        if (fields[index - 1]) {
            var field = fields[index - 1];

            if (field == 'hobbies') {
                $.ajax({
                    url: "/user/hobbies",
                    type: "GET",
                    success: function(response) {
                        var hobbiesHtml = '';
                        response.hobbies.forEach(function(hobby) {
                            var checked = rowData.hobbies.includes(hobby.name) ? 'checked' : '';
                            hobbiesHtml += `
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="hobby_${hobby.id}" name="hobbies[]" value="${hobby.id}" ${checked}>
                                    <label class="form-check-label" for="hobby_${hobby.id}">${hobby.name}</label>
                                </div>
                            `;
                        });
                        row.find('td:eq(' + index + ')').html(hobbiesHtml);
                    },
                    error: function(response) {
                        alert('Error fetching hobbies.');
                    }
                });
            } else {
                var value = rowData[field];
                $(this).html(`<input type="text" class="form-control" data-id="${rowData.id}" data-field="${field}" value="${value}">`);
            }
        }
    });

    // Change edit button to save button
    var saveButton = '<button class="btn btn-success saveBtn">Save</button>';
    $(this).replaceWith(saveButton);
});


    // Add New
    $('#addNew').click(function() {
        $('#addForm').removeClass('d-none');
        $('#userTable_wrapper').addClass('d-none');
    });

    // Submit Form
    $('#addForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

         // Collect checkbox values
         $('input[name="hobbies[]"]:checked').each(function() {
            formData.append('hobbies[]', $(this).val());
        });

        $.ajax({
            url: "{{ route('user.store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response.success);
                table.ajax.reload();
                $('#addForm').addClass('d-none');
                $('#userTable_wrapper').removeClass('d-none');
                $('#addForm').trigger('reset');  // Reset all form fields

            },
            error: function(response) {
                alert('Error submitting form.');
            }
        });
    });

    // Inline Edit
    // $('#userTable tbody').on('blur', '[contenteditable="true"]', function() {
    //     var id = $(this).data('id');
    //     var field = $(this).data('field');
    //     var value = $(this).text();

    //     var data = {
    //         _token: "{{ csrf_token() }}"
    //     };
    //     data[field] = value;

    //     $.ajax({
    //         url: "/user/update/" + id,
    //         type: "POST",
    //         data: data,
    //         success: function(response) {
    //             alert(response.success);
    //         }
    //     });
    // });

    // Delete
    $('#userTable tbody').on('click', '.deleteBtn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                url: "/user/destroy/" + id,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert(response.success);
                    table.ajax.reload();
                }
            });
        }
    });

    // Bulk Delete
    $('#bulkDelete').click(function() {
        var ids = [];
        $('.selectBox:checked').each(function() {
            ids.push($(this).data('id'));
        });

        if (ids.length > 0 && confirm('Are you sure you want to delete selected records?')) {
            $.ajax({
                url: "/user/bulk-delete",
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}",
                    ids: ids
                },
                success: function(response) {
                    alert(response.success);
                    table.ajax.reload();
                }
            });
        }
    });

    // $('#userTable tbody').on('blur', '[contenteditable="true"]', function() {
    //     var id = $(this).data('id');
    //     var field = $(this).data('field');
    //     var value = $(this).text();

    //     var data = {
    //         _token: "{{ csrf_token() }}"
    //     };
    //     data[field] = value;

    //     $.ajax({
    //         url: "/user/update/" + id,
    //         type: "POST",
    //         data: data,
    //         success: function(response) {
    //             alert(response.success);
    //         }
    //     });
    // });
    $('#userTable tbody').on('click', '.saveBtn', function() {
    var row = $(this).closest('tr');
    var rowData = table.row(row).data();
    var updatedData = {};

    // Loop through each editable field in the row
    row.find('input[type="text"]').each(function() {
        var field = $(this).data('field');
        var value = $(this).val();
        updatedData[field] = value;
    });

    // Handle checkboxes for hobbies
    var hobbies = [];
    row.find('input[type="checkbox"]:checked').each(function() {
        hobbies.push($(this).val()); // Push the value of the checked hobby
    });
    updatedData['hobbies'] = hobbies; // Assign hobbies as an array

    // Send updated data to the server via AJAX for processing
    $.ajax({
        url: "/user/update/" + rowData.id,
        type: "POST",
        data: JSON.stringify(updatedData), // Convert updatedData to JSON string
        contentType: 'application/json', // Set content type to JSON
        success: function(response) {
            alert(response.success); // Display success message
            table.ajax.reload(); // Reload the DataTable
        },
        error: function(response) {
            alert('Error updating data.'); // Display error message
        }
    });
});





});

</script>
</body>
</html>
