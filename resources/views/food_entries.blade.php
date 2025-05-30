@extends('base')
@section('content')
<div class="col-lg-12">
    <ul class="nav custom-tab" id="myTab" role="tablist">
        @foreach($foodEntries as $index => $value)
            @php
                $day = $value['dayNameHuman'];
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $day == $value['today'] ? 'active show' : '' }}" id="{{ strtolower($day) }}-tab" data-toggle="tab" href="#{{ strtolower($day) }}" role="tab" aria-controls="{{ strtolower($day) }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    <span class="span1">{{ $day }}</span><br/>
                    <span class="small"> {{ $value['date'] }}</span>
                    </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content" id="myTabContent">
        @foreach($foodEntries as $index => $value)
        @php
            $day = $value['dayNameHuman'];
        @endphp
        <div class="tab-pane fade {{ $day == $value['today'] ? 'active show' : '' }}" id="{{ strtolower($day) }}" role="tabpanel" aria-labelledby="{{ strtolower($day) }}-tab">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center" style="width:15%">ora</th>
                            <th scope="col" style="width:15%">cantitate</th>
                            <th scope="col" style="width:40%">food 😋 </th>
                            <th scope="col" style="15%">kcal</th>
                            <th scope="col" style="15%">proteine</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($value['entries'] as $entry)
                        <tr class="inner-box">
                            <th scope="row">
                                <div class="event-date">
                                    <span>
                                        {{ $entry->ate_at_hour }}:{{$entry->ate_at_min ?: '00' }}
                                    </span>
                                </div>
                            </th>
                            <td>
                                <div class="event-img">
                                    <span>
                                        {{ $entry->qtyForHumans }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="event-wrap">
                                    <h3><a href="#">{{ $entry->food_name }}</a></h3>
                                    <div class="meta">
                                        ceva descriere
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
                        </tr>
                        @endforeach
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
                                <div class="event-img">
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
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endforeach
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
