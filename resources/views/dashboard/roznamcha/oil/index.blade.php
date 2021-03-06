@extends('master.dashboard-master')

@section('title')
    <title>Oil - {{ config('app.name') }}</title>
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('oil_records') }}
@endsection

@section('content')
    <section class="container-fluid py-3">

        @include('components.customer-search')

        <div class="row">
            <div class="col-md-3">
                <h4 class="alert alert-success text-center mb-3"><strong>Total Sale:</strong> {{ $oil->quantity ?? 0 }} Litres</h4>
            </div>
            <div class="col-md-3">
                <h4 class="alert alert-success text-center mb-3"><strong>ُPrice per litre:</strong> Rs {{ $oil_litre->paid ?? 0 }} /-</h4>
            </div>
            <div class="col-md-3">
                <h4 class="alert alert-success text-center mb-3"><strong>Total Price:</strong> Rs {{ $oil_paid->amount ?? 0 }} /-</h4>
            </div>
            <div class="col-md-3">
                <h4 class="alert alert-success text-center mb-3"><strong>Total Profit:</strong> Rs {{ $oil_profit ?? 0 }} /-</h4>
            </div>
        </div>

        <div class="oil-record">
            <h1 class="text-center text-success fw-900 mb-3">Oil Records / <span class="text-urdu-kasheeda">تیل کے ریکارڈ</span></h1>

            @include('components.error')
            @include('components.success')

            <div class="mb-3">
                <input type="text" name="search-name" id="search-name" class="form-control" style="width: 175px;" placeholder="Search ...">
            </div>

            <div id="oil-record">
                @if ($dates->count() > 0)
                    @foreach ($dates as $date)
                        <h3 class="mb-3 text-success fw-700">{{ date('d F, Y (l)', strtotime($date->date)) }}</h3>

                        <div class="table-responsive">
                            <table class="table table-striped" id="oil-record-table">
                                <thead class="table-success">
                                    <tr>
                                        <th style="width: 20%;" class="align-middle">Customer / <span class="text-urdu-kasheeda">خریدار</span></th>
                                        <th style="width: 10%;" class="align-middle">Quantity / <span class="text-urdu-kasheeda">مقدار</span></th>
                                        <th style="width: 10%;" class="align-middle">Price / <span class="text-urdu-kasheeda">قیمت</span></th>
                                        <th style="width: 10%;" class="align-middle">Price Paid / <span class="text-urdu-kasheeda">ادا شدہ قیمت</span></th>
                                        <th style="width: 10%;" class="align-middle">Total Price / <span class="text-urdu-kasheeda">کل قیمت</span></th>
                                        <th style="width: 20%;" class="align-middle">Filling Station / <span class="text-urdu-kasheeda">پیٹرول پمپ</span></th>
                                        <th style="width: 10%;" class="align-middle">Time / <span class="text-urdu-kasheeda">وقت</span></th>
                                        <th style="width: 10%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($oils->count() > 0)
                                        @foreach ($oils as $record)
                                            @if (date('Y-m-d', strtotime($record->created_at)) == $date->date)
                                                <tr>
                                                    <td class="align-middle"><a class="view-customers" data-id="{{ base64_encode(($record->profile->id * 123456789) / 12098) }}" href="">{{ $record->profile->name }}</a></td>
                                                    <td class="align-middle">{{ $record->quantity . ' Litres' }}</td>
                                                    <td class="align-middle">{{ 'Rs ' . $record->price_per_litre . ' /-' }}</td>
                                                    <td class="align-middle">{{ 'Rs ' . $record->paid_per_litre . ' /-' }}</td>
                                                    <td class="align-middle">{{ 'Rs ' . ($record->paid_per_litre * $record->quantity) . ' /-' }}</td>
                                                    <td class="align-middle"><a href="" data-id="{{ base64_encode(($record->fillingStation->id * 123456789) / 12098) }}" class="view-stations">{{ $record->fillingStation->name }}</a></td>
                                                    <td class="align-middle">{{ date('h:i A', strtotime($record->created_at)) }}</td>
                                                    <td class="align-middle">
                                                        <a href="{{ route('oilRecord.show', base64_encode(($record->id * 123456789) / 12098)) }}" class="d-inline">View</a>
                                                        <p class="mb-0 d-inline"> | </p>
                                                        <a href="{{ route('oilRecord.edit', base64_encode(($record->id * 123456789) / 12098)) }}" class="d-inline">Edit</a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8" class="text-center">No record to show</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-danger w-50 mx-auto">
                        <p class="mb-0 text-center">No record to show.</p>
                    </div>
                @endif
            </div>
            <div class="pagination-settings">
                <div class="float-left">
                    {{ $dates->links() }}
                </div>
                <div class="float-right">
                    <p class="mb-0">Showing {{ $dates->firstItem() ?? 0 }} - {{ $dates->lastItem() ?? 0 }} of {{ $dates->count() }} days</p>
                </div>
                <br class="clear">
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#oil-record').on('click', '.view-customers', function(e) {
                var key = $(this).data('id');
                $.get('{{ route("customerSearch.searchCustomer") }}', {id:key}, function(data){
                    $('#data-popup').html(data.profile);
                    $('#display-customer').modal('show');
                }, 'json');
                e.preventDefault();
            });
            $('#oil-record').on('click', '.view-stations', function(e) {
                var station_key = $(this).data('id');
                $.get('{{ route("fillingStation.searchFillingStation") }}', {id:station_key}, function(data) {
                    $('#data-popup').html(data.data_output);
                    $('#station-search-popup').modal('show');
                }, 'json');
                e.preventDefault();
            });
            $('#search-name').keyup(function() {
                var name = $(this).val();
                $.get('{{ route("oilRecord.searchOilRecord") }}', {name:name}, function(data) {
                    $('#oil-record').html(data.name_results);
                }, 'json');
                $('.pagination-settings').addClass('d-none');
            });
        });
    </script>
    @include('components.customer-search-js')
@endsection
