@extends('layouts.app')

@section('title')
    Rankings
@endsection

@section('content')
    <h1>Rank Coasters</h1>
    <ul class="nav nav-tabs" style="margin-bottom: 15px;">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('coasters.rank', ['method' => 'dragdrop']) }}">Drag & Drop</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('coasters.rank', ['method' => 'spreadsheet']) }}">Spreadsheet</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-md-10">
            <form action="{{ route('coasters.rank.spreadsheet') }}" method="post" id="the-spreadsheet">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><a href="?sort=coaster" title="Sort by name">Coaster</a></th>
                            <th><a href="?sort=manufacturer" title="Sort by manufacturer">Manufacturer</a></th>
                            <th><a href="?sort=park" title="Sort by park">Park</a></th>
                            <th><a href="?sort=ranking" title="Sort by ranking">Ranking</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($all as $coaster)
                            <tr>
                                <td>{{ $coaster->getName() }}</td>
                                <td>{{ $coaster->getManufacturerName() }}</td>
                                <td>{{ $coaster->getParkName() }}</td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="coasters[{{ $coaster->getId() }}]" value="{{ $coaster->getRank() }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ csrf_field() }}
            </form>
        </div>
        <div class="col-md-2">
            <div class="card card-block" id="utils">
                <button type="button" class="save-spreadsheet btn btn-primary"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.save-spreadsheet').on('click', function() {
            $('#the-spreadsheet').submit();
        });

        $(window).on('scroll', function() {
            if($(window).scrollTop() > 125){
                //begin to scroll
                $("#utils").css("position","fixed");
                $("#utils").css("top",75);
            }
            else{
                //lock it back into place
                $("#utils").css("position","relative");
                $("#utils").css("top",0);
            }
        });
    </script>
@endsection