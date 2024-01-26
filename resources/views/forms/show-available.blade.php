<!-- resources/views/forms/index.blade.php -->

@extends('adminlte::page')

@section('title', 'Available Forms')

@section('content')
    <div class="pt-3">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Available Forms</h3>
            </div>
            <div class="card-body">
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
                                    <a href="{{ route('forms.fill', ['form' => $form->id]) }}" class="btn btn-success"><span class="fas fa-fw fa-pencil-alt"></span>Fill</a>
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
@stop
