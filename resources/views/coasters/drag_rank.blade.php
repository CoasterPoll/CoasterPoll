@extends('layouts.app')

@section('title')
    Rankings
@endsection

@section('content')
    <h1>Rank Coasters <span class="pull-right"><button type="button" class="btn btn-primary" id="updating-btn" onclick="updateRanks()"><i id="updating-i" class="fa fa-save"></i> Save</button></span></h1>
    <ul class="nav nav-tabs" style="margin-bottom: 15px;">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('coasters.rank', ['method' => 'dragdrop']) }}">Drag & Drop</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('coasters.rank', ['method' => 'spreadsheet']) }}">Spreadsheet</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-md-12 card card-block card-outline-info" id="rankings" style="min-height: 100px">
            @foreach($ranked as $rank)
                @if($rank->coaster instanceof \ChaseH\Models\Coasters\Coaster)
                    <div class="card card-block py-1 my-1">
                        <div class="row">
                            <div class="col-sm-10 align-content-center">
                                <span class="lead handle"><i class="fa fa-arrows-v"></i> &nbsp;&nbsp;{{ $rank->coaster->name }}</span> <span class="small"><a href="{{ route('coasters.manufacturer', ['manufacturer' => $rank->coaster->manufacturer->abbreviation]) }}">{{ $rank->coaster->manufacturer->abbreviation }}</a> at <a href="{{ route('coasters.park', ['park' => $rank->coaster->park->short]) }}">{{ $rank->coaster->park->short }}</a>.</span>
                            </div>
                            <div class="col-sm-2 text-right">
                                <input type="number" class="form-control form-control-sm my-1 rank" data-coaster="{{ $rank->coaster_id }}" value="{{ $rank->rank }}">
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
            @if($ranked->count() == 0)
                <p>It's kinda empty here. @if($unranked->count() > 0)Try dragging a coaster from below up to this box.@endif @if($unranked->count() == 0) First, you'll need to track any coasters you've ridden. You can start by searching for a park you've been to in the search bar above. @endif </p>
            @endif
        </div>
    </div>
    @if($unranked->count() != 0)
        <hr id="unranked-divider" data-step="2" data-intro="Just above this." style="background-color: #333333">
        <div class="row">
            <div class="col-md-12 bg-faded card card-block" id="unranked">
                <p>These haven't been added to your rankings yet. Just drag them out of this box to put them on your rankings.</p>
                @foreach($unranked as $coaster)
                    @if($coaster instanceof \ChaseH\Models\Coasters\Coaster)
                        <div class="card card-block py-1 my-1">
                            <div class="row">
                                <div class="col-sm-10 align-content-center">
                                    <span class="lead handle" @if($ranked->count() == 0) data-step="1" data-intro="Start by dragging this..." @endif ><i class="fa fa-arrow-up"></i> &nbsp;&nbsp;{{ $coaster->name }}</span> <span class="small"><a href="{{ route('coasters.manufacturer', ['manufacturer' => $coaster->manufacturer->abbreviation]) }}">{{ $coaster->manufacturer->abbreviation }}</a> at <a href="{{ route('coasters.park', ['park' => $coaster->park->short]) }}">{{ $coaster->park->short }}</a>.</span>
                                </div>
                                <div class="col-sm-2 text-right">
                                    <input type="number" class="form-control form-control-sm my-1 rankable hidden" data-coaster="{{ $coaster->id }}" value="">
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('js/Sortable.min.js') }}"></script>
    <script src="{{ asset('js/jquery.binding.js') }}"></script>
    <script>
        function byId(id) {
            return document.getElementById(id);
        }

        @if($ranked->count() == 0)
            $('.lead').on('mouseenter', function() {
                introJs().start().onexit(function() {
                    $('.lead').unbind('mouseenter');
                });
            });
        @endif

        Sortable.create(byId('rankings'), {
            handle: '.handle',
            animation: 150,
            group: {
                name: 'coasters',
                pull: false,
                put: true
            },
            scrollSensitivity: 90,
            scroll: true,
            onUpdate: function(e) {
                var row = $(e.item);

                if(e.newIndex > e.oldIndex) { // Draggin' Down
                    $.each(row.prevUntil(function() {
                        return $(this).index() <= (e.oldIndex - 1);
                    }), function() {
                        var desc = $(this).find('.rank');
                        var oldRank = desc.val();
                        desc.val(parseFloat(oldRank) - parseFloat(1));
                    });

                    var newRank = parseFloat(row.prev().find('.rank').val()) + parseFloat(1); // Find where we are
                    row.find('.rank').val(newRank); // Update input for where we are
                } else { // Draggin' Up!
                    $.each(row.nextUntil(function() {
                        return $(this).index() > (e.oldIndex);
                    }), function() {
                        var desc = $(this).find('.rank');
                        var oldRank = desc.val();
                        desc.val(parseFloat(oldRank) + parseFloat(1));
                    });

                    // Check if we're aiming for the number 1 spot.
                    if(!row.prev().length) {
                        var newRank = 1;
                    } else {
                        var newRank = parseFloat(row.prev().find('.rank').val()) + parseFloat(1); // Find where we are
                    }

                    row.find('.rank').val(newRank); // Update input for where we are
                }

                row = false;
            },
            onAdd: function(e) {
                var row = $(e.item);

                $.each(row.nextAll(), function() {
                    var desc = $(this).find('.rank');
                    var oldRank = desc.val();
                    desc.val(parseFloat(oldRank) + parseFloat(1));
                });

                if(!row.prev().length) {
                    var newRank = parseFloat(row.next().find('.rank').val()) - parseFloat(1); // Find where we are
                } else {
                    var newRank = parseFloat(row.prev().find('.rank').val()) + parseFloat(1); // Find where we are
                }

                if(isNaN(newRank)) {
                    newRank = 1;
                }

                row.find('.rankable').val(newRank).addClass('rank').removeClass('hidden rankable').show(); // Update input for where we are
                row.find('i').removeClass('fa-arrow-up').addClass('fa-arrows-v');

                addNewRank(row);
            },
            onSort: function() {
                window.updatingIn = window.setTimeout(updateRanks, 3000);
            },
            onStart: function() {
                window.clearTimeout(window.updatingIn);
            }
        });

        @if($unranked->count() != 0)
            Sortable.create(byId('unranked'), {
                handle: '.handle',
                animation: 150,
                group: {
                    name: 'coasters',
                    pull: true,
                    put: false
                },
                scrollSensitivity: 90,
                scroll: true,
            });
        @endif

        function addNewRank(row) {
            var use = row.find('.rank');
            var coaster = use.data('coaster');
            var rank = use.val();

            $.post({
                url: "{!! route('coasters.rank.put') !!}",
                method: "PUT",
                data: {
                    coaster: coaster,
                    rank: rank
                },
                success: function(res) {
                    toastr.success("We've added that to your ranking!");
                },
                error: function(res) {
                    toastr.error(res.statusText);
                },
                beforeSend: function() {
                    $('#updating-btn').prop('disabled', 'disabled').find('#updating-i').addClass('fa-spinner fa-spin').removeClass('fa-save');
                },
                complete: function() {
                    $('#updating-btn').prop('disabled', null).find('#updating-i').removeClass('fa-spinner fa-spin').addClass('fa-save');
                }
            });
        }

        function updateRanks() {
            var data = [];
            $.each($('.rank'), function() {
                data.push({
                    coaster: $(this).data('coaster'),
                    rank: $(this).val()
                });
            });
            
            $.post({
                url: "{!! route('coasters.rank.post') !!}",
                data: {
                    all: data
                },
                success: function (res) {
                    toastr.success(res.message);
                },
                error: function(res) {
                    toastr.error(res.responseJSON.message)
                },
                beforeSend: function () {
                    $('#updating-btn').prop('disabled', 'disabled').find('#updating-i').addClass('fa-spinner fa-spin').removeClass('fa-save');
                },
                complete: function () {
                    $('#updating-btn').prop('disabled', null).find('#updating-i').removeClass('fa-spinner fa-spin').addClass('fa-save');
                }
            });
        }
    </script>
@endsection