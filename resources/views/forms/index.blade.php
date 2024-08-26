<!-- resources/views/forms/index.blade.php -->

@extends('adminlte::page')

@section('title', 'Manage Forms')

@section('content')
    <div class="pt-3">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Created Forms</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-start mb-3">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addFormModal">
                        <span class="fas fa-fw fa-plus"></span> Add Form
                    </button>
                </div>
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
                <div class="container-fluid" style="column-count: 4;">
                    @forelse($forms as $form)
                        @php
                            $cardStyles = ['primary', 'danger', 'success', 'info', 'warning', 'secondary', 'dark'];
                            $selectedStyle = $cardStyles[$form->id % count($cardStyles)];
                        @endphp
                        <div class="container-fluid">
                            <div class="card card-{{ $selectedStyle }}" style="page-break-inside: avoid;">
                                <div class="card-header">
                                    <h5 class="card-title">{{ $form->title }}</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $form->description }}</p>
                                </div>
                                <div class="card-footer">
                                    @if ($form->fillable)
                                        <a href="{{ route('forms.show', ['form' => $form->id]) }}" class="btn btn-success"><span class="fas fa-fw fa-wrench"></span>Manage</a>
                                    @else
                                        <form style="display: inline" action="{{ route('forms.setfillable', ['form' => $form->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary"><span class="fas fa-fw fa-upload"></span>Launch</button>
                                        </form>
                                    @endif
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#deleteFormModal{{ $form->id }}">
                                        <span class="fas fa-fw fa-trash"></span> Delete
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="deleteFormModal{{ $form->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteFormModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteFormModalLabel">Confirm Deletion</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete the form {{ $form->title }}?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <form action="{{ route('forms.destroy', ['form' => $form->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @empty
                        <div class="col-md-12">
                            <p>No forms found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Add Form Modal -->
    <div class="modal fade" id="addFormModal" tabindex="-1" role="dialog" aria-labelledby="addFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFormModalLabel">Add Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add your form creation form here -->
                    <form action="{{ route('forms.store') }}" method="POST">
                        @csrf
                        <!-- Add form fields for form data (title, description, etc.) -->
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control" rows=6 required></textarea>
                        </div>
                        <!-- Add other form fields as needed -->

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
