<style>
    /* Reduce table cell padding */
.table td, 
.table th {
    padding: 5px; 
    font-size: 12px; 
    vertical-align: middle; 
}

/* Compact the dropdown actions */
.dropdown-menu {
    font-size: 12px; 
    padding: 5px;
}

.dropdown-menu a {
    padding: 5px 10px; 
}

/* Adjust header row for compact size */
.table thead th {
    font-size: 12px; 
    padding: 5px; 
}

/* Optional: Adjust overall table styling */
.table {
    margin-bottom: 10px; 
}


.form-focus .form-control {
    height: 46px;
    padding: 10px 12px;
}

#advanced-search {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.form-focus select.form-control {
    padding-top: 10px;
}

</style>
@extends('layouts.master')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">	
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Candidates List</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item">Jobs</li>
                            <li class="breadcrumb-item active">Candidates List</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" data-toggle="modal" data-target="#add_candidate" class="btn add-btn"> Add Candidates</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <!-- Search Filter -->
            <form id="search-form">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="name" id="search-name">
                            <label class="focus-label">Name</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="email" id="search-email">
                            <label class="focus-label">Email</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3"> 
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="candidate_id" id="search-candidate-id">
                            <label class="focus-label">Candidate ID</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <button type="button" class="btn btn-secondary btn-block" id="toggle-advanced">Advanced Search</button>
                    </div>
                </div>

                <!-- Advanced Search Fields -->
                <div class="row filter-row" id="advanced-search" style="display: none;">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="job_title" id="search-job-title">
                            <label class="focus-label">Job Title</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <select class="form-control floating" name="gender" id="search-gender">
                                <option value=""></option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <label class="focus-label">Gender</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="number" class="form-control floating" name="experience" id="search-experience" min="0">
                            <label class="focus-label">Experience (Years)</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <select class="form-control floating" name="race" id="search-race">
                                <option value=""></option>
                                <option value="Malay">Malay</option>
                                <option value="Chinese">Chinese</option>
                                <option value="Indian">Indian</option>
                                <option value="Others">Others</option>
                            </select>
                            <label class="focus-label">Race</label>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /Search Filter -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Candidate ID</th>
                                    <th>Gender</th>
                                    <th>Mobile Number</th>
                                    <th>Email</th>
                                    <th>Work Experience</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($candidates as $candidate)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <h2 class="table-avatar">
                                            <a href="" class="avatar">
                                                <img alt="" src="{{ URL::to('assets/images/profiles/' . ($candidate->gender == 'Female' ? 'avatar4.jpg' : 'avatar2.jpg')) }}">
                                            </a>
                                                <a href="">{{$candidate -> name}}</a>
                                            </h2>
                                        </td>
                                        <td>{{ $candidate->candidate_id }}</td>
                                        <td>{{ $candidate->gender }}</td>
                                        <td>{{ $candidate->phone_number }}</td>
                                        <td>{{ $candidate->email }}</td>
                                        <td>{{ $candidate->work_experiences }} years</td>
                                        <td class="text-center">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#"><i class="fa fa-check m-r-5"></i> Approval</a>
                                                    <a class="dropdown-item" href="#"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item" href="#"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No candidates found.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <!-- <tbody>
                                @foreach ($candidates as $candidate )
                                
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <h2 class="table-avatar">
                                        <a href="" class="avatar">
                                            <img alt="" src="{{ URL::to('assets/images/profiles/' . ($candidate->gender == 'Female' ? 'avatar4.jpg' : 'avatar2.jpg')) }}">
                                        </a>
                                            <a href="">{{$candidate -> name}}</a>
                                        </h2>
                                    </td>
                                    <td>{{$candidate -> candidate_id}}</td>
                                    <td>{{$candidate -> gender}}</td>
                                    <td>{{$candidate -> phone_number}}</td>
                                    <td>{{$candidate -> email}}/td>
                                    <td>{{$candidate -> work_experiences}}</td>
                                    <td class="text-center">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#approval_modal"><i class="fa fa-check m-r-5"></i> Approval</a>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_job"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_job"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody> -->
                        </table>
                    </div>
                </div>
            </div>
            <!-- /Search Filter -->
        </div>
        <!-- /Page Content -->
        
        <!-- Add Candidate Modal -->
        <div id="add_candidate" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Candidates</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Name</label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email">
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Candidate ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Created Date <span class="text-danger">*</span></label>
                                        <div class="cal-icon"><input class="form-control datetimepicker" type="text"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Phone </label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Candidate Modal -->

        <!-- Approval Modal -->
        <div id="approval_modal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Approval Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to approve the candidate as an interviewer?</p>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn-sm">Yes</button>
                            <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Approval Modal -->

        <!-- Edit Job Modal -->
        <div id="edit_job" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Candidates</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label"><span class="text-danger">Position*</span></label>
                                        <input class="form-control" type="text" value="" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Name</label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email">
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Candidate ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Created Date <span class="text-danger">*</span></label>
                                        <div class="cal-icon"><input class="form-control datetimepicker" type="text"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Phone </label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Job Modal -->

        <!-- Delete Job Modal -->
        <div class="modal custom-modal fade" id="delete_job" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Job Modal -->
    </div>
    <!-- /Page Wrapper -->
<!-- JavaScript to Toggle Advanced Search -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('search-form');
    const advancedSearch = document.getElementById('advanced-search');
    const toggleButton = document.getElementById('toggle-advanced');
    const inputs = searchForm.querySelectorAll('input, select');
    let debounceTimer;

    // Fix for Advanced Search Toggle
    toggleButton.addEventListener('click', function() {
        const isHidden = advancedSearch.style.display === 'none' || advancedSearch.style.display === '';
        advancedSearch.style.display = isHidden ? 'flex' : 'none';
        this.textContent = isHidden ? 'Hide Advanced Search' : 'Advanced Search';
    });

    // Function to perform AJAX search with debounce
    function performSearch() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const formData = new FormData(searchForm);
            
            fetch("{{ route('candidates/search') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('.table tbody');
                tableBody.innerHTML = '';

                if (data.candidates.length > 0) {
                    data.candidates.forEach((candidate, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>
                                <h2 class="table-avatar">
                                    <a href="" class="avatar">
                                        <img alt="" src="{{ URL::to('assets/images/profiles/') }}/${candidate.gender === 'Female' ? 'avatar4.jpg' : 'avatar2.jpg'}">
                                    </a>
                                    <a href="">${candidate.name}</a>
                                </h2>
                            </td>
                            <td>${candidate.candidate_id || ''}</td>
                            <td>${candidate.gender || ''}</td>
                            <td>${candidate.phone_number || ''}</td>
                            <td>${candidate.email || ''}</td>
                            <td>${candidate.work_experiences ? candidate.work_experiences + ' years' : ''}</td>
                            <td class="text-center">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#"><i class="fa fa-check m-r-5"></i> Approval</a>
                                        <a class="dropdown-item" href="#"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="#"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td colspan="8" class="text-center">No candidates found.</td>`;
                    tableBody.appendChild(row);
                }
            })
            .catch(error => console.error('Error:', error));
        }, 300);
    }

    // Add event listeners for all input and select fields
    inputs.forEach(input => {
        if (input.tagName === 'SELECT') {
            input.addEventListener('change', performSearch);
        } else {
            input.addEventListener('input', performSearch);
        }
    });
});
</script>
@endsection