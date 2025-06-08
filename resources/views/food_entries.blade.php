@extends('base')
@section('content')
<div class="col-lg-12">
    @if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger">{{ $error }}</div>
    @endforeach
    @endif
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    <ul class="nav custom-tab" id="myTab" role="tablist">
        @foreach($foodEntries as $index => $value)
            @php
                $day = $value['dayNameHuman'];
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $day == $selectedDayName ? 'active show' : '' }}" id="{{ strtolower($day) }}-tab" data-toggle="tab" href="#{{ strtolower($day) }}" role="tab" aria-controls="{{ strtolower($day) }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" data-datestring="{{ $value['date']->format('Y-m-d') }}">
                    <span class="span1">{{ $day }}</span><br/>
                    <span class="small"> {{ $value['date']->format('j') }} {{ $roMonthNames[$value['date']->format('m')] }}</span>
                    </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content" id="myTabContent">
        @foreach($foodEntries as $index => $value)
        @php
            $day = $value['dayNameHuman'];
        @endphp
        <div>
        </div>
        <div class="tab-pane fade {{ $day == $selectedDayName ? 'active show' : '' }}" id="{{ strtolower($day) }}" role="tabpanel" aria-labelledby="{{ strtolower($day) }}-tab">
            <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-12">
                <h2 class="text-center" style="margin-bottom:15px;">{{ $day }} - {{ $value['date']->format('j') }} {{ $roMonthNames[$value['date']->format('m')] }} {{ $value["date"]->format('Y') }}</h2>
            </div>
            @foreach($goalTypes as $goalType)
            <div class="col-md-{{ count($goalTypes) > 3 ? '6' : '4' }}">
            @php
                $goalProgress = $value["goals"]->has($goalType->id) ? $value["goals"][$goalType->id]->qty : 0;
                $goalProgressPercentage = $goalType->target_qty > 0 ? ($goalProgress / $goalType->target_qty) * 100 : 0;
            @endphp
            <div style="margin-bottom:10px;" class="row">
            <div style="vertical-align: middle; text-align: center;" class="pull-left">
            <strong>{{ $goalType->name }}:</strong> {{ $goalProgress }} / {{ $goalType->target_qty }}
            @if(Auth::user()->name == 'Călina')
            <form method="POST" action="{{ route('goals.update', $goalType->id) }}" class="d-inline">
                @csrf
                <input type="hidden" name="date" value="{{ $value['date']->format('Y-m-d') }}">
                <input type="hidden" name="goal_type_id" value="{{ $goalType->id }}">
                <input type="hidden" name="qty" value="{{ $goalProgress + 1}}">
                <button type="submit" class="btn btn-secondary btn-sm" style="margin-left: 10px;">
                    <i class="fa fa-plus"></i>
                </button>
            </form>
            @endif
            </div>
            </div>
            <div class="progress" style="margin-bottom: 10px;" >
                <div class="progress-bar progress-bar-striped" role="progressbar" style="width: {{ $goalProgressPercentage }}%" aria-valuenow="{{$goalProgress}}" aria-valuemin="0" aria-valuemax="{{ $goalType->target_qty }}">
                </div>
            </div>
            </div>
            @endforeach
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col" style="width:15%">ora</th>
                            <th scope="col" style="width:15%">cantitate</th>
                            <th scope="col" style="width:40%">food 😋 </th>
                            <th scope="col" style="15%">kcal</th>
                            <th scope="col" style="15%">proteine</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($value['entries'] as $entry)
                        <tr class="inner-box">
                            <th scope="row">
                                <div class="event-date">
                                    <span class="colorspan">
                                        {{ $entry->ate_at->format("H:i")}}
                                    </span>
                                </div>
                            </th>
                            <td>
                                <div class="r-no">
                                    <span>
                                        {!! $entry->qtyForHumans !!}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="r-no">
                                    <div class="largeish">{{ $entry->food_name }}</div>
                                    <div class="meta">
                                        {{ $entry->description }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="r-no">
                                    <span>{{ number_format($entry->kcal, 0) }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="r-no">
                                    <span>{{ number_format($entry->protein, 2) }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                @if(Auth::user()->name == 'Călina')
                                    @if(!$value['dayObject'])
                                    <form method="POST" action="{{ route('food-entries.destroy', $entry->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn" data-toggle="confirmation">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <button type="button" class="btn btn-secondary" disabled> <i class="fa fa-lock"> </i></button>
                                    @endif
                                @else
                                <button type="button" class="btn btn-secondary" disabled> TODO: eval. aliment </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        <tr class="inner-box">
                            <td colspan="6">
                            <!-- Button trigger modal -->
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="inner-box">
                            <th scope="row">
                                <div class="event-date">
                                    <span>
                                    </span>
                                </div>
                            </th>
                            <td>
                                <div class="r-no">
                                    <span>
                                    </span>
                                </div>
                            </td>
                            <td class="table-secondary">
                                <div style="text-align: right;">
                                    <span class="colorspan">
                                        Total:
                                    </span>
                                    <br/>
                                    @if(Auth::user()->name == 'Călina')
                                    <span class="badge badge-secondary">
                                        Rămase:
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="table-{{ $value['kcalClass'] }}" style="text-align: center;">
                                <span class="colorspan">{{ $value['sumKcal'] }} kcal</span>
                                <br/>
                                @if(Auth::user()->name == 'Călina')
                                <span class="badge badge-{{ $value['kcalClass'] }}">
                                    {{ 1300 - $value['sumKcal'] }} kcal
                                </span>
                                @endif
                            </td>
                            <td class="table-{{ $value['proteinClass'] }}" style="text-align: center;">
                                <span class="colorspan"> {{ $value['sumProtein'] }}g</span>
                                <br/>
                                @if(Auth::user()->name == 'Călina')
                                <span class="badge badge-{{ $value['proteinClass'] }}">
                                    {{ 90 - $value['sumProtein'] }}g
                                </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if(Auth::user()->name == 'Călina')
                                    @if(!$value['dayObject'])
                                        <form method="POST" action="{{ route('days.store') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="date" value="{{ $value['date']->format('Y-m-d') }}">
                                            <input type="hidden" name="done" value="1">
                                            <button type="submit" class="btn btn-secondary btn-lg" id="addEntryButton">
                                                <i class="fa fa-lock"></i> Închide ziua
                                            </button>
                                        </form>
                                    @else
                                        @if($value['dayObject']->rating)
                                            <div class="largeish"> Ai primit rating: </div>
                                                @if($value['dayObject']->rating == 1)
                                                <button type="button" class="btn btn-danger" value="1" @if($value['dayObject']->rating != 1) disabled @endif>😤 Jale extremală</button>
                                                @elseif($value['dayObject']->rating == 2)
                                                <button type="button" class="btn btn-warning" value="2" @if($value['dayObject']->rating != 2) disabled @endif>🥴 Nasol</button>
                                                @elseif($value['dayObject']->rating == 3)
                                                <button type="button" class="btn btn-secondary" value="3" @if($value['dayObject']->rating != 3) disabled @endif>🤷‍♂️ Meh</button>
                                                @elseif($value['dayObject']->rating == 4)
                                                <button type="button" class="btn btn-info" value="4" @if($value['dayObject']->rating != 4) disabled @endif>✅ OK</button>
                                                @else
                                                <button type="button" class="btn btn-success" value="5" @if($value['dayObject']->rating != 5) disabled @endif>🎉 Forță</button>
                                                @endif
                                        @else
                                        <div style="vertical-align: middle; text-align:center;">Ziua este închisă.</div>
                                        @endif
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @if(Auth::user()->name != 'Călina')
                        <tr>
                            @if($value['dayObject'])
                            @if ($value['dayObject']->done)
                                @if ($value['dayObject']->rating)
                                <td class="text-center" colspan="6">
                                    <div class="largeish"> Ai acordat rating: </div>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-danger" value="1" @if($value['dayObject']->rating != 1) disabled @endif>😤 Jale extremală</button>
                        <button type="button" class="btn btn-warning" value="2" @if($value['dayObject']->rating != 2) disabled @endif>🥴 Nasol</button>
                        <button type="button" class="btn btn-secondary" value="3" @if($value['dayObject']->rating != 3) disabled @endif>🤷‍♂️ Meh</button>
                        <button type="button" class="btn btn-info" value="4" @if($value['dayObject']->rating != 4) disabled @endif>✅ OK</button>
                        <button type="button" class="btn btn-success" value="5" @if($value['dayObject']->rating != 5) disabled @endif>🎉 Forță</button>
                                    </div>
                                    <input type="hidden" name="rating" value="">
                                    </form
                                </td>
                                @else
                                <td class="text-center" colspan="6">
                                    <form method="POST" action="{{ route('days.update', $value['dayObject']->id) }}" class="d-inline">
                                    @csrf
                                    <div class="largeish"> Adaugă rating: </div>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn rating btn-danger" value="1">😤 Jale extremală</button>
                                        <button type="button" class="btn rating btn-warning" value="2">🥴 Nasol</button>
                                        <button type="button" class="btn rating btn-secondary" value="3">🤷‍♂️ Meh</button>
                                        <button type="button" class="btn rating btn-info" value="4">✅ OK</button>
                                        <button type="button" class="btn rating btn-success" value="5">🎉 Forță</button>
                                    </div>
                                    <input type="hidden" name="rating" value="">
                                    </form
                                </td>
                            @endif
                            @else
                            <td class="text-center" colspan="6">
                                <div class="largeish"> Ziua nu e gata încă. Ratingul mai trebuie să aștepte.</div>
                            </td>
                            @endif
                            @endif
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>
        @endforeach
        @if(Auth::user()->name == 'Călina')
            @if (!$value['dayObject'])
            <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#exampleModal" style="width: 100%;" id="addEntryButton">
                <i class="fa fa-plus"></i> Adaugă
            </button>
            @endif
        @endif
    </div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="POST" action="{{ route('food-entries.store') }}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">


    <div class="entryForm" style="margin-top: 20px; padding: 20px;">
                    <div style="margin-bottom: 20px;">
                <h3>Adaugă </h3>
                    <span class="badge badge-secondary dt-badge"> pentru {{ $selectedDay->format('Y-m-d' )}}</span>
                    </div>
        @csrf
        <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">

        <div class="form-row entryFormRow">
            <div class="col">
                <input type="time" class="form-control @if ($errors->has('time')) is-invalid @endif" name="time" value="{{ old('time') }}" required placeholder="Ora">
            </div>
        </div>
        <span> <strong>DIN CATALOG:</strong></span>
        <div style="padding: 20px; border: 1px solid #ccc; margin-bottom: 20px;">
        <div class="form-row entryFormRow">
            <div class="col">
                <select class="form-control @if ($errors->has('food_item_id')) is-invalid @endif" name="food_item_id">
                </select>
            </div>
        </div>
        <div class="form-row entryFormRow">
            <div class="col-md-6 pull-left">
                <input type="number" step="0.1" placeholder="fracție" class="form-control @if ($errors->has('multiplier')) is-invalid @endif " name="multiplier" value="{{ old('multiplier') ?: 1 }}" required>
            </div>
            <div class="col">
                <span class="the-unit" style="font-size:22px; vertical-align:middle; padding-left:10px"> x </span>
            </div>
        </div>
        </div>

        <span> <strong>SAU DIRECT:</strong></span>
        <div style="padding: 20px; border: 1px solid #ccc; margin-bottom: 20px;">
        <div class="form-row">
            <div class="col-md-12 entryFormRow">
                <input type="text" class="form-control @if ($errors->has('direct_name')) is-invalid @endif" placeholder="Nume aliment" name="direct_name" value="{{ old('direct_name') }}" required>
            </div>
            <div class="col-md-6 entryFormRow">
                <input type="number" step="1" placeholder="calorii" class="form-control @if ($errors->has('direct_kcal')) is-invalid @endif" name="direct_kcal" value="{{ old('direct_kcal') }}" required>
            </div>
            <div class="col-md-6 entryFormRow">
                <input type="number" step="0.01" placeholder="proteine" class="form-control @if ($errors->has('direct_protein')) is-invalid @endif" name="direct_protein" value="{{ old('direct_protein') }}" required>
            </div>
        </div>

        <div class="form-row entryFormRow">
            <div class="col-md-12">
                <input type="text" class="form-control @if ($errors->has('description')) is-invalid @endif" placeholder="Descriere" name="description" value="{{ old('description') }}">
            </div>
        </div>
    </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Renunță</button>
        <button type="submit" class="btn btn-primary">Confirmare</button>
      </div>
    </div>
  </div>
    </form>
</div>
</div>


    <nav style="margin-top: 20px;">
    <ul class="pagination pagination-sm justify-content-center">
        <li class="page-item">
            <a href="{{ route('food-entries.index', ['date' => (clone $startDate)->subDays(14)->format('Y-m-d')])}}" class="btn btn-primary mb-3"> << </a>
        </li>
        <li class="page-item">
            <a href="{{ route('food-entries.index', ['date' => (clone $startDate)->subDays(7)->format('Y-m-d')])}}" class="btn btn-primary mb-3"> < </a>
        </li>
        <li class="page-item">
            <a href="{{ route('food-entries.index', ['date' => (clone $endDate)->addDays(7)->format('Y-m-d')])}}" class="btn btn-primary mb-3"> > </a>
        </li>
        <li class="page-item">
            <a href="{{ route('food-entries.index', ['date' => (clone $endDate)->addDays(7)->format('Y-m-d')])}}" class="btn btn-primary mb-3"> >> </a>
        </li>
        &nbsp;
        <li class="page-item">
            <a href="{{ route('food-entries.index', ['date' => now()->format('Y-m-d')])}}" class="btn btn-primary mb-3 @if($startDate >= (now()->startOfWeek()) && $startDate <= (now()->endOfWeek())) active @else @endif"> Săptămâna asta </a>
        </li>
    </ul>
    </nav>
</div>
@endsection

@section('scripts')
$(document).ready(function() {
    // Initialize Selectize for the food item select
    $('select[name="food_item_id"]').selectize({
        'plugins': ["restore_on_backspace", "clear_button"],
        'maxItems': 1,
        'options': [
            @foreach($foodItems as $foodItem)
                {
                    value: "{{ $foodItem->id }}",
                    text: "{{ $foodItem->name }} @if ($foodItem->brand) ({{ $foodItem->brand }}) @endif",
                    unitName: "{{ $foodItem->unit_name }}",
                    unitBaseQty: "{{ $foodItem->unit_base_quantity }}"
                },
            @endforeach
        ],
        onChange: function(value) {
            if (!value) {
                $('.the-unit').text('');
                $('input[name="multiplier"]').prop('disabled', true);
                $('input[name="direct_kcal"]').prop('required', true);
                $('input[name="direct_protein"]').prop('required', true);
                $('input[name="direct_name"]').prop('required', true);
                return;
            }

            $.get('/api/food-item-format/' + value, function(data) {
                $('.the-unit').text('x ' + data.qtyForHumans);
                $('input[name="multiplier"]').prop('disabled', false);
                $('input[name="direct_kcal"]').prop('required', false);
                $('input[name="direct_protein"]').prop('required', false);
                $('input[name="direct_name"]').prop('required', false);
            });
        }
    });

    $('input[name="direct_name"]').on('input', function() {
        if ($(this).val().trim() !== '') {
            $('select[name="food_item_id"]')[0].selectize.clear();
        }
    });

    $('.event-schedule-area-two ul.custom-tab li a').on('click', function() {
        var dateString = $(this).data('datestring');
        $("[name='date']").val(dateString);
        $('.dt-badge').text('pentru ' + dateString);
    });

    $('.rating').on('click', function() {
        var rating = $(this).val();
        $(this).closest('form').find('input[name="rating"]').val(rating);
        $(this).closest('form').submit();
    });
});
@endsection
