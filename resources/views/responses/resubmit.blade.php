@extends('adminlte::page')

@section('title', 'Resubmit Form | ' . $response->form->title)

@section('content')
    <style>
        input[type=radio]{
            transform:scale(1.2);
            cursor: pointer;
        }
    </style>
    <form method="post" action="{{ route('responses.resubmit', ['response' => $response->id]) }}">
    @csrf
    @method('PUT')
    <div class="container pt-3">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form #{{ $response->form->id }}</h3>
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
                <h3>{{ $response->form->title }}</h3>
                <p>{{ $response->form->description }}</p>

                <div class="d-flex align-items-center mb-3">
                    <hr style="width: 100%;"/>
                    <h4 class="my-0 mx-2">Questions</h4>
                    <hr style="width: 100%;"/>
                </div>

                @if ($response->form->questions)
                    @forelse ($response->form->questions as $question)
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
                                                                <p>
                                                                    {{ $criteria->description }}
                                                                    <span class="float-end badge bg-info">Unit: {{ $criteria->satuan }}</span>
                                                                    <span class="float-end badge bg-info">Target:
                                                                        {{ ($criteria->satuan == "percentage") ? ($criteria->target . "%") : $criteria->target }}</span>
                                                                </p>
                                                                <div
                                                                    class="text-center mb-3 d-flex justify-content-around align-items-center">
                                                                    @if ($criteria->satuan == "percentage")
                                                                        <input type="number" name="criteria_answers[{{ $criteria->id }}]" class="form-control mx-2"
                                                                            min="1" max="100" step="1" value="{{ $criteria->responseDetails()->where('response_id', $response->id)->first()->answer }}" required />
                                                                    @elseif ($criteria->satuan == "availability")
                                                                        <select name="criteria_answers[{{ $criteria->id }}]" class="form-control mx-2" required>
                                                                            <option value="available" {{ ($criteria->responseDetails()->where('response_id', $response->id)->first()->answer == "available")?"selected":"" }}>Available</option>
                                                                            <option value="unavailable" {{ ($criteria->responseDetails()->where('response_id', $response->id)->first()->answer == "unavailable")?"selected":"" }}>Unavailable</option>
                                                                        </select>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="additional_info_{{ $criteria->id }}">Additional Information</label>
                                                                    <textarea name="information[{{ $criteria->id }}]" id="additional_info_{{ $criteria->id }}" class="form-control" rows="3">{{ $criteria->responseDetails()->where('response_id', $response->id)->first()->information }}</textarea>
                                                                </div>
                                                                <?php $finding = $criteria->findings()->where('response_id', $response->id)->first(); ?>
                                                                @if( $finding )
                                                                    <div class="alert alert-{{ $finding->category === 'observation' ? 'info' : 'danger'}}">
                                                                        {{ $finding->description }}
                                                                        <br/>
                                                                        <b>Root Cause:</b> {{ $finding->root_cause }}
                                                                        <br/>
                                                                        <b>Recommendation:</b> {{ $finding->recommendation }}
                                                                    </div>
                                                                @endif
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
