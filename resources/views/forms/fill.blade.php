@extends('adminlte::page')

@section('title', 'Fill Form | ' . $form->title)

@section('content')
    <style>
        input[type=radio]{
            transform:scale(1.2);
            cursor: pointer;
        }
    </style>
    <form method="post" action="{{ route('forms.submit', ['form' => $form->id]) }}">
    @csrf
    <div class="container pt-3">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form #{{ $form->id }}</h3>
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
                <h3>{{ $form->title }}</h3>
                <p>{{ $form->description }}</p>

                <div class="d-flex align-items-center mb-3">
                    <hr style="width: 100%;"/>
                    <h4 class="my-0 mx-2">Questions</h4>
                    <hr style="width: 100%;"/>
                </div>
            
                @if ($form->questions)
                    @forelse ($form->questions as $question)
                        <div class="card card-primary">
                            <div class="card-header">
                                <p class="card-title">{{ $question->title }}</p>
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
                                                    @forelse ($standard->criterias as $criteria)
                                                        <div class="mb-3">
                                                            <div class="mx-0 mx-sm-auto">
                                                                <p>{{ $criteria->description }}</p>
                                                                <div class="text-center mb-3 d-flex justify-content-around align-items-center">
                                                                    <div class="d-inline mx-3">
                                                                    Sangat Kurang
                                                                    </div>
                                                                    <div class="d-flex align-items-center">
                                                                        @for ($i=0; $i<5; $i++)
                                                                            <div class="form-check form-check-inline mx-2" style="flex-direction: column;">
                                                                                <label class="form-check-label" style="visibility: hidden;">-</label>
                                                                                <input class="form-check-input mx-0 my-1" type="radio" name="criteria_answers[{{ $criteria->id }}]" id="inlineRadio{{ $i }}" value="{{ $i }}" required/>
                                                                                <label class="form-check-label" for="inlineRadio{{ $i }}">{{ $i }}</label>
                                                                            </div>
                                                                            @if($i<4)
                                                                                <hr style="width: 50px;"/>
                                                                            @endif
                                                                        @endfor
                                                                    </div>
                                                                    <div class="d-inline me-4">
                                                                    Sangat Baik
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr style="width: 100%;"/>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3">No criteria found for this standard.</td>
                                                        </tr>
                                                    @endforelse
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
                <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            <span class="fas fa-fw fa-paper-plane"></span>Submit
                        </button>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection
