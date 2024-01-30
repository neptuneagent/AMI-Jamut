<!-- resources/views/responses/show.blade.php -->

@extends('adminlte::page')

@section('title', 'Form Submission Details')

@section('content')
    <div class="container pt-3">
        <div class="card card-primary">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">Submission #{{ $response->id }}</h3>
                    <span class="float-end badge {{ getStatusBadgeClass($response->status) }}">{{ $response->status }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h3>{{ $response->form->title }}</h3>
                        <p>{{ $response->form->description }}</p>
                        <p>Submitted At: {{ beautifyDateTime($response->submitted_at) }}</p>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-info">
                            <div class="card-header">
                                <p class="card-title">Submitted by</p>
                            </div>
                            <div class="card-body py-1">
                                <p class="my-1">{{ $response->user->name }}</p>
                                <p class="my-1">{{ $response->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex align-items-center">
                    <hr style="width: 100%;"/>
                    <h4 class="my-0 mx-2">Questions</h4>
                    <hr style="width: 100%;"/>
                </div>

                @if ($response->form->questions)
                    @forelse ($response->form->questions as $question)
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="card-title">{{ $question->title }}</p>
                                    <div>
                                        <button type="button" class="btn btn-secondary toggle-card-body" data-target="#cardBody{{ $question->id }}"><span class="fas fa-fw fa-eye"></span></button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body question-card-body" id="cardBody{{ $question->id }}">
                                <div class="d-flex align-items-center mb-3">
                                    <hr style="width: 100%;"/>
                                    <h5 class="my-0 mx-2">Standards</h5>
                                    <hr style="width: 100%;"/>
                                </div>
                                @if ($question->standards)
                                    @forelse ($question->standards as $standard)
                                        <div class="card card-primary">
                                            <div class="card-header">
                                                <p class="card-title">{{ $standard->title }}</p>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <hr style="width: 100%;"/>
                                                    <h6 class="my-0 mx-2">Criteria</h6>
                                                    <hr style="width: 100%;"/>
                                                </div>
                                                @if ($standard->criterias)
                                                    @if ($standard->criterias->count() > 0)
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th width="70%">Description</th>
                                                                    <th>Score</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse ($standard->criterias as $criteria)
                                                                    <tr>
                                                                        <td>{{ $criteria->description }}</td>
                                                                        <td>
                                                                            {{ $criteria->responseDetails()->where('response_id', $response->id)->first()->answer }}
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="2">No criteria found for this standard.</td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    @else
                                                        <div class="col-md-12" style="text-align: center;">
                                                            <p>No criteria found for this standard.</p>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="col-md-12" style="text-align: center;">
                                                        <p>No criteria found for this standard.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-md-12" style="text-align: center;">
                                            <p>No standards found for this question.</p>
                                        </div>
                                    @endforelse
                                @else
                                    <div class="col-md-12" style="text-align: center;">
                                        <p>No standards found for this question.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12" style="text-align: center;">
                            <p>No questions found for this form.</p>
                        </div>
                    @endforelse
                @else
                    <div class="col-md-12" style="text-align: center;">
                        <p>No questions found for this form.</p>
                    </div>
                @endif
            </div>
        </div>
        <div class="card card-secondary mt-3">
            <div class="card-header">
                <h3 class="card-title">Form History</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Action</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($response->histories()->orderBy('created_at', 'desc')->get() as $history)
                            <tr>
                                <td>{{ $history->id }}</td>
                                <td>{{ $history->user->name }} {{ $history->action }}</td>
                                <td>{{ $history->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No history found for this form.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card card-info mt-3">
            <div class="card-header">
                <h3 class="card-title">Supporting Evidence</h3>
            </div>
            <div class="card-body">
                @if (auth()->user()->hasRole('gkm'))

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <!-- Button to trigger the modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadEvidenceModal"><span class="fas fa-fw fa-upload"></span> Upload Evidence</button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#markCompleteModal"><span class="fas fa-fw fa-check"></span> Mark as Complete</button>
                @endif
                @if ($response->evidences)
                    @if (auth()->user()->hasRole('gkm'))
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Uploaded On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($response->evidences as $evidence)
                                    <tr>
                                        <td>{{ $evidence->name }}</td>
                                        <td>{{ $evidence->description }}</td>
                                        <td>{{ $evidence->created_at }}</td>
                                        <td>
                                            <a class="btn btn-info" href="{{ asset('storage/'.$evidence->file_path) }}" target="_blank"><span class="fas fa-fw fa-file"></span> View</a>
                                            <button class="btn btn-warning" data-toggle="modal" data-target="#editEvidenceModal{{ $evidence->id }}"><span class="fas fa-fw fa-edit"></span> Edit</button>
                                            <button class="btn btn-danger" data-toggle="modal" data-target="#deleteEvidenceModal{{ $evidence->id }}"><span class="fas fa-fw fa-trash"></span> Delete</button>
                                        </td>
                                    </tr>

                                    <!-- Modal for editing evidence -->
                                    <div class="modal fade" id="editEvidenceModal{{ $evidence->id }}" tabindex="-1" aria-labelledby="editEvidenceModalLabel{{ $evidence->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editEvidenceModalLabel{{ $evidence->id }}">Edit Evidence</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Form to edit evidence -->
                                                    <form action="{{ route('responses.updateEvidence', ['response' => $response->id, 'evidence' => $evidence->id]) }}" method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label for="editEvidenceName{{ $evidence->id }}" class="form-label">Evidence Name</label>
                                                            <input type="text" class="form-control" id="editEvidenceName{{ $evidence->id }}" name="evidence_name" value="{{ $evidence->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="editEvidenceDescription{{ $evidence->id }}" class="form-label">Evidence Description</label>
                                                            <textarea class="form-control" id="editEvidenceDescription{{ $evidence->id }}" name="evidence_description" required>{{ $evidence->description }}</textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal for deleting evidence -->
                                    <div class="modal fade" id="deleteEvidenceModal{{ $evidence->id }}" tabindex="-1" aria-labelledby="deleteEvidenceModalLabel{{ $evidence->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteEvidenceModalLabel{{ $evidence->id }}">Delete Evidence</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this evidence?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <!-- Form to delete evidence -->
                                                    <form action="{{ route('responses.deleteEvidence', ['response' => $response->id, 'evidence' => $evidence->id]) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Modal for uploading evidence -->
                        <div class="modal fade" id="uploadEvidenceModal" tabindex="-1" aria-labelledby="uploadEvidenceModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="uploadEvidenceModalLabel">Upload Evidence</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form to upload new evidence -->
                                        <form action="{{ route('responses.uploadEvidence', ['response' => $response->id]) }}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="evidenceName" class="form-label">Evidence Name</label>
                                                <input type="text" class="form-control" id="evidenceName" name="evidence_name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="evidenceDescription" class="form-label">Evidence Description</label>
                                                <textarea class="form-control" id="evidenceDescription" name="evidence_description" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="evidenceFile" class="form-label">Drag and Drop or Upload File</label>
                                                <input type="file" class="form-control" id="evidenceFile" name="evidence_file" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Upload Evidence</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal for mark completed -->
                        <div class="modal fade" id="markCompleteModal" tabindex="-1" aria-labelledby="markCompleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="markCompleteModalLabel">Mark as Completed</h5>
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to mark this response as completed? This will be passed to next step</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <!-- Form to delete evidence -->
                                        <form action="{{ route('responses.markComplete', ['response' => $response->id] ) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-primary">Continue</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Uploaded On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($response->evidences as $evidence)
                                    <tr>
                                        <td>{{ $evidence->name }}</td>
                                        <td>{{ $evidence->description }}</td>
                                        <td>{{ $evidence->created_at }}</td>
                                        <td>
                                            <a class="btn btn-info" href="{{ asset('storage/'.$evidence->file_path) }}" target="_blank"><span class="fas fa-fw fa-file"></span> View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @else
                    <div class="col-md-12" style="text-align: center;">No supporting evidence found.</div> 
                @endif
            </div>
        </div>
        <div class="card card-warning mt-3">
            <div class="card-header">
                <h3 class="card-title">Findings</h3>
            </div>
            <div class="card-body">
                @if (auth()->user()->hasRole('auditor'))
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    
                    <button type="button" class="btn btn-warning mb-2" data-toggle="modal" data-target="#addFindingModal"><span class="fas fa-fw fa-plus"></span> Add Finding</button>
                    <button type="button" class="btn btn-success mb-2 mx-1" data-toggle="modal" data-target="#markAuditedModal"><span class="fas fa-fw fa-check"></span> Mark as Audited</button>

                    <!-- Modal for adding finding -->
                    <div class="modal fade" id="addFindingModal" tabindex="-1" aria-labelledby="addFindingModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addFindingModalLabel">Add Finding</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form to add new finding -->
                                    <form action="{{ route('findings.store', ['response' => $response->id]) }}" method="post">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="findingDescription" class="form-label">Finding Description</label>
                                            <textarea class="form-control" id="findingDescription" name="finding_description" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="criteriaSelect" class="form-label">Select Criteria</label>
                                            <select class="form-control" id="criteriaSelect" name="criteria_id" required>
                                                <option value="" disabled selected>Select Criteria</option>
                                                @foreach ($response->form->questions as $question)
                                                    @foreach ($question->standards as $standard)
                                                        @foreach ($standard->criterias as $criteria)
                                                            <option value="{{ $criteria->id }}">{{ $criteria->description }}</option>
                                                        @endforeach
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="rootCause" class="form-label">Root Cause</label>
                                            <textarea class="form-control" id="rootCause" name="root_cause" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="recommendation" class="form-label">Recommendation</label>
                                            <textarea class="form-control" id="recommendation" name="recommendation" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="categorySelect" class="form-label">Select Category</label>
                                            <select class="form-control" id="categorySelect" name="category" required>
                                                <option value="" disabled selected>Select Criteria</option>
                                                <option value="observation">Observation</option>
                                                <option value="discrepancy">Discrepancy</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add Finding</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal for mark audited -->
                    <div class="modal fade" id="markAuditedModal" tabindex="-1" aria-labelledby="markAuditedModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="markAuditedModalLabel">Mark as Audited</h5>
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to mark this response as audited? This will be passed to next step</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <!-- Form to delete evidence -->
                                        <form action="{{ route('responses.markAudited', ['response' => $response->id] ) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-primary">Continue</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endif
                @if ($response->findings)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Criteria</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($response->findings as $finding)
                                <tr>
                                    <td>{{ $finding->description }}</td>
                                    <td>{{ $finding->criteria->description }}</td>
                                    <td>{{ $finding->category }}</td>
                                    <td>
                                        <button class="btn btn-info m-1" data-toggle="modal" data-target="#viewFindingModal{{ $finding->id }}"><span class="fas fa-fw fa-eye"></span> View</button>
                                        @if( auth()->user()->hasRole('auditor') ) 
                                            <button class="btn btn-danger m-1"data-toggle="modal" data-target="#deleteFindingModal{{ $finding->id }}"><span class="fas fa-fw fa-trash"></span> Delete</button>
                                        @endif
                                    </td>
                                </tr>
                                <!-- Modal for viewing finding details -->
                                <div class="modal fade" id="viewFindingModal{{ $finding->id }}" tabindex="-1" aria-labelledby="viewFindingModalLabel{{ $finding->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewFindingModalLabel{{ $finding->id }}">Finding Details</h5>
                                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                            <form action="{{ route('findings.update', ['finding' => $finding->id]) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="findingDescription" class="form-label">Finding Description</label>
                                                    <textarea class="form-control" id="findingDescription" name="finding_description" rows="4" @if( !auth()->user()->hasRole('auditor') ) disabled @endif  required>{{ $finding->description }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="criteriaSelect" class="form-label">Criteria</label>
                                                    <select class="form-control" id="criteriaSelect" name="criteria_id" @if( !auth()->user()->hasRole('auditor') ) disabled @endif required>
                                                        @foreach ($response->form->questions as $question)
                                                            @foreach ($question->standards as $standard)
                                                                @foreach ($standard->criterias as $criteria)
                                                                    <option value="{{ $criteria->id }}" @if( $criteria->id == $finding->criteria->id ) selected @endif >{{ $criteria->description }}</option>
                                                                @endforeach
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="rootCause" class="form-label">Root Cause</label>
                                                    <textarea class="form-control" id="rootCause" name="root_cause" rows="4" @if( !auth()->user()->hasRole('auditor') ) disabled @endif required>{{ $finding->root_cause }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="recommendation" class="form-label">Recommendation</label>
                                                    <textarea class="form-control" id="recommendation" name="recommendation" rows="4" @if( !auth()->user()->hasRole('auditor') ) disabled @endif required>{{ $finding->recommendation }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="categorySelect" class="form-label">Category</label>
                                                    <select class="form-control" id="categorySelect" name="category" @if( !auth()->user()->hasRole('auditor') ) disabled @endif required>
                                                        <option value="observation" @if( $finding->criteria->category =='observation' ) selected @endif >Observation</option>
                                                        <option value="discrepancy" @if( $finding->criteria->category =='discrepancy' ) selected @endif >Discrepancy</option>
                                                    </select>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    @if( auth()->user()->hasRole('auditor') ) 
                                                       <button type="submit" class="btn btn-primary mx-3">Update</button>
                                                    @endif
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal for deleting findings -->
                                <div class="modal fade" id="deleteFindingModal{{ $finding->id }}" tabindex="-1" aria-labelledby="deleteFindingModalLabel{{ $finding->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteFindingModalLabel{{ $finding->id }}">Delete Finding</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this finding?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <!-- Form to delete evidence -->
                                                    <form action="{{ route('findings.destroy', ['finding' => $finding->id]) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @empty
                                <tr>
                                    <td colspan="3">No findings available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @else
                    <div class="col-md-12" style="text-align: center;">No findings yet</div>
                @endif
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        $(document).ready(function() {
            $('.toggle-card-body').on('click', function() {
                var target = $($(this).data('target'));
                target.slideToggle();
            });

            $('.question-card-body').slideToggle();

        });
    </script>
@stop
