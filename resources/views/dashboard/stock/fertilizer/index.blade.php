@extends('master.dashboard-master')

@section('title')
    <title>Fertilizer Stock - {{ config('app.name') }}</title>
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('fertilizer_stocks') }}
@endsection

@section('content')
    <section class="container-fluid py-3">
        <div class="fertilizer-trader-search mb-3" id="search-trader">
            <h1 class="text-center text-success mb-3 fw-900">Search Fertilizer Trader / <span class="text-urdu-kasheeda">کھاد کے تاجر تلاش کریں</span></h1>
            @include('components.error')
            <form action="{{ route('fertilizerStockSearch.searchFertilizerTrader') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-3 offset-md-2">
                        <div class="dropdown">
                            <input type="text" name="name" id="trader-name" placeholder="Name" class="form-control" data-toggle="dropdown">
                            <ul class="dropdown-menu d-none" id="fertilizer-traders">
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-1 text-center pt-1"><span class="text-uppercase fw-700" style="font-size: 20px;">OR</span></div>
                    <div class="col-md-3">
                        <input type="text" name="phone_number" id="phone-number" placeholder="Phone Number" data-mask="0000 0000000" class="form-control">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-block btn-success"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
            @if ($traders = session()->get('traders'))
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-success">
                        <tr>
                            <th>Name / <span class="text-urdu-kasheeda">نام</span></th>
                            <th>Phone Number / <span class="text-urdu-kasheeda">فون نمبر</span></th>
                            <th>Address / <span class="text-urdu-kasheeda">پتہ</span></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($traders as $trader)
                            <tr>
                                <td>{{ $trader->name }}</td>
                                <td>{{ $trader->phone_number }}</td>
                                <td>{{ $trader->address }}</td>
                                <td><a href="{{ route('fertilizerTraders.show', base64_encode(($trader->id * 123456789) / 12098)) }}">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <div class="row">
            <div class="offset-md-2 col-md-4">
                <h4 class="alert alert-success mb-3 text-center"><strong>Urea Stock: </strong>{{ $urea ?? 0 }} Sacks</h4>
            </div>
            <div class="col-md-4">
                <h4 class="alert alert-success mb-3 text-center"><strong>DAP Stock: </strong>{{ $dap ?? 0 }} Sacks</h4>
            </div>
        </div>

        <div class="stock-content">
            <h1 class="text-center fw-900 mb-3 text-success">Fertilizer Stock / <span class="text-urdu-kasheeda">کھاد کا اسٹاک</span></h1>

            @include('components.success')

            <div>
                <select id="fertilizer" class="form-control float-right" style="width: 175px;">
                    <option value="0">Fertilizer Stock</option>
                    <option value="1">Fertilizer Record</option>
                </select>
                <br class="clear">
            </div>
            <div class="fertilizer-stock-table">
                @forelse ($dates as $date)
                <h3 class="text-success fw-700 mb-3">{{ date('d-F-Y (l)', strtotime($date->date)) }}</h3>
                <div class="table-responsive" id="fertilizer-stocks-table">
                    <table class="table table-striped">
                        <thead class="table-success">
                            <tr>
                                <th style="width: 20%" class="align-middle">Trader / <span class="text-urdu-kasheeda">تاجر</span></th>
                                <th style="width: 7%" class="align-middle">Type / <span class="text-urdu-kasheeda">قسم</span></th>
                                <th style="width: 10%" class="align-middle">Quantity / <span class="text-urdu-kasheeda">مقدار</span></th>
                                <th style="width: 15%" class="align-middle">Weight per sack / <span class="text-urdu-kasheeda">وزن فی بوری</span></th>
                                <th style="width: 15%" class="align-middle">Price per sack / <span class="text-urdu-kasheeda">قیمت فی بوری</span></th>
                                <th style="width: 13%" class="align-middle">Total Price / <span class="text-urdu-kasheeda">کل قیمت</span></th>
                                <th style="width: 10%" class="align-middle">Time / <span class="text-urdu-kasheeda">وقت</span></th>
                                <th style="width: 10%" class="align-middle"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stocks as $stock)
                                @if ($date->date == date('Y-m-d', strtotime($stock->created_at)))
                                    <tr>
                                        <td class="align-middle"><a href="">{{ $stock->fertilizerTrader->name }}</a></td>
                                        <td class="align-middle">{{ $stock->type }}</td>
                                        <td class="align-middle">{{ $stock->quantity }} Sacks</td>
                                        <td class="align-middle">{{ $stock->weight }} Kgs</td>
                                        <td class="align-middle">Rs {{ $stock->price }} /-</td>
                                        <td class="align-middle">Rs {{ $stock->quantity * $stock->price }} /-</td>
                                        <td class="align-middle">{{ date('h:i A', strtotime($stock->created_at)) }}</td>
                                        <td class="align-middle">
                                            <a href="{{ route('fertilizerStock.show', base64_encode(($stock->id * 123456789) / 12098)) }}" class="d-inline">View</a>
                                            <p class="d-inline mb-0"> | </p>
                                            <a href="{{ route('fertilizerStock.edit', base64_encode(($stock->id * 123456789) / 12098)) }}" class="d-inline">Edit</a>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td class="text-center font-italic" colspan="8">No record to show.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @empty
                <div class="alert alert-danger font-italic text-center w-50 mx-auto">No record to show.</div>
                @endforelse
            </div>
            <div class="d-flex" style="justify-content: space-between">
                <div class="paginate">
                    {{ $dates->links() }}
                </div>
                <p>Showing {{ $dates->firstItem() ?? 0 }} - {{ $dates->lastItem() ?? 0 }} of {{ $dates->count() ?? 0 }} results</p>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#search-trader').on('keyup', '#trader-name', function() {
                var name = $(this).val();
                $.get("{{ route('fertilizerStockSearch.tradersList') }}", {name:name}, function(data) {
                    $('#fertilizer-traders').removeClass('d-none');
                    $('#fertilizer-traders').html(data.list);
                }, 'json');
            });

            $('#fertilizer').change(function() {
                var inte = $(this).val();
                if(inte == 0) {
                    window.location.href = "{{ route('fertilizerStock.index') }}";
                } else if (inte == 1) {
                    window.location.href = "{{ route('fertilizerRecord.index') }}";
                }
            });
        });
    </script>
@endsection
