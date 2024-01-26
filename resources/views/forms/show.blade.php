@extends('adminlte::page')

@section('title', 'Manage Form | ' . $form->title)

@section('content')
    <div class="container pt-3">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Manage Form #{{ $form->id }}</h3>
            </div>
            <div class="card-body">
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
                            <p>{{ $error }}</p>
                            <br/>
                        @endforeach
                    </div>
                @endif
                <form action="{{ route('forms.update', ['form' => $form->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
            
                    <!-- Current Password -->
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $form->title }}" required>
                    </div>
            
                    <!-- New Password -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" cols="30" rows="6" class="form-control">{{ $form->description }}</textarea>
                    </div>
            
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>

                <div class="d-flex align-items-center">
                    <hr style="width: 100%;"/>
                    <h4 class="my-0 mx-2">Questions</h4>
                    <hr style="width: 100%;"/>
                </div>

                <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addQuestionModal">
                    <span class="fas fa-fw fa-plus"></span> Add Question
                </button>

                <!-- Add Question Modal -->
                <div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addQuestionModalLabel">Add Question</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Add your question creation form here -->
                                <form action="{{ route('questions.store', ['form' => $form->id]) }}" method="POST">
                                    @csrf
                                    <!-- Add form fields for question data (title, description, etc.) -->
                                    <div class="form-group">
                                        <label for="question_title">Title</label>
                                        <input type="text" name="title" class="form-control" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            
                @if ($form->questions)
                    @forelse ($form->questions as $question)
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="card-title">{{ $question->title }}</p>
                                    <div>
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addStandardModal{{ $question->id }}"><span class="fas fa-fw fa-plus"></span></button>
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editQuestionModal{{ $question->id }}"><span class="fas fa-fw fa-pen"></span></button>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal{{ $question->id }}"><span class="fas fa-fw fa-trash"></span></button>
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
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="card-title">{{ $standard->title }}</p>
                                                    <div>
                                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCriteriaModal{{ $standard->id }}"><span class="fas fa-fw fa-plus"></span></button>
                                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editStandardModal{{ $standard->id }}"><span class="fas fa-fw fa-pen"></span></button>
                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmStandardDeleteModal{{ $standard->id }}"><span class="fas fa-fw fa-trash"></span></button>
                                                    </div>
                                                </div>
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
                                                                    <th>Weight</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse ($standard->criterias as $criteria)
                                                                    <tr>
                                                                        <td>{{ $criteria->description }}</td>
                                                                        <td>{{ $criteria->weight }}</td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editCriteriaModal{{ $criteria->id }}"><span class="fas fa-fw fa-pen"></span></button>
                                                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmCriteriaDeleteModal{{ $criteria->id }}"><span class="fas fa-fw fa-trash"></span></button>
                                                                        </td>
                                                                    </tr>
                                                                    <div class="modal fade" id="editCriteriaModal{{ $criteria->id }}" tabindex="-1" role="dialog" aria-labelledby="editCriteriaModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="editCriteriaModalLabel">Edit Criteria</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <form action="{{ route('criterias.update', ['criteria' => $criteria->id]) }}" method="POST">
                                                                                        @csrf
                                                                                        @method('PUT')
                                                                                        <div class="form-group">
                                                                                            <label for="description">Description</label>
                                                                                            <textarea name="description" class="form-control" rows="3" required>{{ $criteria->description }}</textarea>
                                                                                        </div>
                                                                                        
                                                                                        <div class="form-group">
                                                                                            <label for="weight">Weight</label>
                                                                                            <input type="number" name="weight" class="form-control" value="{{ $criteria->weight }}" required>
                                                                                        </div>
                                                                                        
                                                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal fade" id="confirmCriteriaDeleteModal{{ $criteria->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmCriteriaDeleteModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="confirmCriteriaDeleteModalLabel">Confirm Delete</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <p>Are you sure you want to delete the criteria</strong>?</p>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                                    <form action="{{ route('criterias.destroy', ['criteria' => $criteria->id]) }}" method="POST" class="d-inline">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
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
                                        <div class="modal fade" id="addCriteriaModal{{ $standard->id }}" tabindex="-1" role="dialog" aria-labelledby="addCriteriaModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addCriteriaModalLabel">Add Criteria for {{ $standard->title }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('criterias.store', ['standard' => $standard->id]) }}" method="POST">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label for="description">Description</label>
                                                                <textarea name="description" class="form-control" rows="3" required></textarea>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label for="weight">Weight</label>
                                                                <input type="number" name="weight" class="form-control" value="1" required>
                                                            </div>

                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="editStandardModal{{ $standard->id }}" tabindex="-1" role="dialog" aria-labelledby="editStandardModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editStandardModalLabel">Edit Standard</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('standards.update', ['standard' => $standard->id]) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="form-group">
                                                                <label for="title">Title</label>
                                                                <input type="text" name="title" class="form-control" value="{{ $standard->title }}" required>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="confirmStandardDeleteModal{{ $standard->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmStandardDeleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmStandardDeleteModalLabel">Confirm Delete</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete the standard: <strong>{{ $standard->title }}</strong>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('standards.destroy', ['standard' => $standard->id]) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
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
                        <div class="modal fade" id="addStandardModal{{ $question->id }}" tabindex="-1" role="dialog" aria-labelledby="addStandardModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addStandardModalLabel">Add Standard for {{ $question->title }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Add your standard creation form here -->
                                        <form action="{{ route('standards.store', ['question' => $question->id]) }}" method="POST">
                                            @csrf
                                            <!-- Add form fields for standard data (title, etc.) -->
                                            <div class="form-group">
                                                <label for="standard_title">Title</label>
                                                <input type="text" name="title" class="form-control" required>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="editQuestionModal{{ $question->id }}" tabindex="-1" role="dialog" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editQuestionModalLabel">Edit Question</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Add your question editing form here -->
                                        <form action="{{ route('questions.update', ['form' => $form->id, 'question' => $question->id]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <!-- Add form fields for question data (title, description, etc.) -->
                                            <div class="form-group">
                                                <label for="question_title">Title</label>
                                                <input type="text" name="title" class="form-control" value="{{ $question->title }}" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="confirmDeleteModal{{ $question->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete the question: <strong>{{ $question->title }}</strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <form action="{{ route('questions.destroy', ['question' => $question->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                        </form>
                                    </div>
                                </div>
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
