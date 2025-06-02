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
                    <span class="small"> {{ $value['date']->format('j F') }}</span>
                    </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content" id="myTabContent">
        @foreach($foodEntries as $index => $value)
        @php
            $day = $value['dayNameHuman'];
        @endphp
        <div class="tab-pane fade {{ $day == $selectedDayName ? 'active show' : '' }}" id="{{ strtolower($day) }}" role="tabpanel" aria-labelledby="{{ strtolower($day) }}-tab">
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
                                        {{ $entry->qtyForHumans }}
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
                                <form method="POST" action="{{ route('food-entries.destroy', $entry->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" data-toggle="confirmation">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
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
                                    <span class="badge badge-secondary">
                                        Rămase:
                                    </span
                                </div>
                            </td>
                            <td class="table-{{ $value['kcalClass'] }}">
                                <span class="colorspan">{{ $value['sumKcal'] }} kcal</span>
                                <br/>
                                <span class="badge badge-{{ $value['kcalClass'] }}">
                                    {{ 1300 - $value['sumKcal'] }} kcal
                                </div>
                            </td>
                            <td class="table-{{ $value['proteinClass'] }}">
                                <span class="colorspan"> {{ $value['sumProtein'] }}g</span>
                                <br/>
                                <span class="badge badge-{{ $value['proteinClass'] }}">
                                    {{ 90 - $value['sumProtein'] }}g
                                </span>
                            </td>
                            <td class="text-center"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endforeach
        <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#exampleModal" style="width: 100%;" id="addEntryButton">
            <i class="fa fa-plus"></i> Adaugă
        </button>
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
                    <option value="" data-unit-name="" data-unit-base-qty="">Selectează un aliment</option>
                    @foreach($foodItems as $foodItem)
                        <option value="{{ $foodItem->id }}" {{ old('food_item_id') == $foodItem->id ? 'selected' : '' }} data-unit-name="{{ $foodItem->unit_name }}" data-unit-base-qty="{{ $foodItem->unit_base_quantity }}">
                            {{ $foodItem->name }} @if ($foodItem->brand) ({{ $foodItem->brand }}) @endif
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row entryFormRow">
            <div class="col-md-6 pull-left">
                <input type="number" step="0.1" placeholder="fracție" class="form-control @if ($errors->has('multiplier')) is-invalid @endif " name="multiplier" value="{{ old('multiplier') }}" required>
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
    });
    // Set the unit based on the selected food item
    $('select[name="food_item_id"]').change(function() {
        var selectedOption = $(this).find('option:selected');
        var unitName = selectedOption.data('unit-name');
        var unitBaseQty = selectedOption.data('unit-base-qty');
        $('.the-unit').text("x " + unitBaseQty + unitName);
        if (unitName) {
            $('input[name="multiplier"]').prop('disabled', false);
            $('input[name="direct_kcal"]').prop('required', false);
            $('input[name="direct_protein"]').prop('required', false);
            $('input[name="direct_name"]').prop('required', false);
        } else {
            $('input[name="multiplier"]').prop('disabled', true);
            $('input[name="direct_kcal"]').prop('required', true);
            $('input[name="direct_protein"]').prop('required', true);
            $('input[name="direct_name"]').prop('required', true);
        }
    });

    // Trigger change to set initial unit
    $('select[name="food_item_id"]').trigger('change');

    $('.event-schedule-area-two ul.custom-tab li a').on('click', function() {
        var dateString = $(this).data('datestring');
        $("[name='date']").val(dateString);
        $('.dt-badge').text('pentru ' + dateString);
    });
});
@endsection
