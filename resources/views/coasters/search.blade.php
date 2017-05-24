@extends('layouts.app')

@section('title')
    Search
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-block mb-4" id="search-bar"></div>
            <div class="card card-block" id="search-results"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.css">
    <script src="https://cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.js"></script>
    <script>
        var search = instantsearch({
            appId: '{!! config('scout.algolia.id') !!}',
            apiKey: '{!! config('scout.algolia.search') !!}',
            indexName: 'coasters',
            urlSync: true,
            searchFunction: function(helper) {
                if (helper.state.query === '') {
                    return;
                }
                var showmore = $('.ais-infinite-hits--showmore');
                showmore.find('button:disabled').addClass('btn btn-secondary');
                showmore.find('button').addClass('btn btn-secondary');
                helper.search();
            }
        });
        @can('Can track coasters')
            window.riddenIds = [ @foreach($ridden_coasters as $r) {{ $r }}, @endforeach ];

            window.isRidden1 = '<button type="button" class="btn btn-sm btn-block btn-success _ridden-btn" onclick="riddenBtn(this)" has-ridden="true" data-id="';
            window.isRidden2 = '"><i class="fa fa-check-square-o"></i> Ridden</button>';
            window.notRidden1 = '<button type="button" class="btn btn-sm btn-block btn-outline-success _ridden-btn" onclick="riddenBtn(this)" has-ridden="false" data-id="';
            window.notRidden2 = '"><i class="fa fa-square-o"></i> Ridden</button>';
        @endcan
        var searchBar = instantsearch.widgets.searchBox({
            container: "#search-bar",
            poweredBy: true,
            placeholder: "Search for a Coaster, Park, or Manufacturer",
            autofocus: true,
            cssClasses: {
                input: "form-control form-control-lg"
            }
        });
        var searchResults = instantsearch.widgets.infiniteHits({
            container: "#search-results",
            hitsPerPage: 21,
            showMoreLabel: "Keep looking",
            templates: {
                item: function(data) {
                    var str1 = '<div class="card card-block mb-4">' +
                           '<h3 class="lead"><a class="lead-unstyled" href="/@'+data.park.short+'/'+data.slug+'">'+data.name+'</a></h3>'+
                           '<p class="mb-2"><a href="/p/' + data.park.short + '">' + data.park.name + '</a></p>' +
                           '<p><a href="/m/' + data.manufacturer.abbreviation + '">' + data.manufacturer.name + '</a></p>';
                    var str3 = '</div>';
                    @can('Can track coasters')
                        if(riddenIds.includes(data.id)) {
                            var str2 = window.isRidden1 + data.id + window.isRidden2;
                        } else {
                            var str2 = window.notRidden1 + data.id + window.notRidden2;
                        }
                    @endcan
                    @cannot('Can track coasters')
                        var str2 = "";
                    @endcannot

                    return str1 + str2 + str3;
                },
                empty: function() {
                    return "Oops. Looks like there's nothing here by that name.";
                }
            },
            cssClasses: {
                root: "row",
                item: "col-sm-6 col-md-4 col-lg-3",
                empty: "card-outline-danger"
            }

        });
        search.addWidget(searchBar);
        search.addWidget(searchResults);
        search.start();
        $(document).on('ready', function() {
            $('.ais-infinite-hits--showmore').find('button:disabled').addClass('btn btn-secondary uses-fa');
            $('.ais-infinite-hits--showmore').find('button').addClass('btn btn-secondary uses-fa');
        })
    </script>
@endsection