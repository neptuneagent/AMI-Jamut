<!-- resources/views/forms/index.blade.php -->

@extends('adminlte::page')

@section('title', 'Manage Forms')

@section('content')
    <div class="container pt-3">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    @if( auth()->user()->hasRole('prodi') )
                        Your Submissions
                    @elseif( auth()->user()->hasRole('gkm') )
                        To be Completed
                    @elseif( auth()->user()->hasRole('jamut') )
                        All submissions
                    @elseif( auth()->user()->hasRole('auditor') )
                        To be Audited
                    @endif
                </h3>
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
                <div class="container-fluid">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Form</th>
                                <th>Submitted At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($responses as $response)
                                <tr>
                                    <td>{{ $response->id }}</td>
                                    <td>{{ $response->form->title }}</td>
                                    <td>{{ $response->submitted_at }}</td>
                                    <td>
                                        <span class="float-end badge {{ getStatusBadgeClass($response->status) }}">{{ $response->status }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('responses.show', ['response' => $response->id]) }}" class="btn btn-info">
                                            Show Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <div class="col-md-12">
                                    <p>No forms found.</p>
                                </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('.table').DataTable();
        });
    </script>
@stop
