@extends('layouts.app')

@section('title')
    Search
@endsection

@section('content')
    @include('coasters.nav')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-block mb-4" id="search-bar"></div>
            <div class="card card-block" id="search-results"></div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('coasters._scripts')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.css">
    <script src="https://cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.js"></script>
    <script>
        var search = instantsearch({
            appId: '{!! env('ALGOLIA_APP_ID') !!}',
            apiKey: '{!! env('ALGOLIA_SEARCH') !!}',
            indexName: 'coasters',
            urlSync: true,
            searchFunction: function(helper) {
                if (helper.state.query === '') {
                    return;
                }

                helper.search();
            }
        });
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
                    return '<div class="card card-block mb-4">' +
                           '<h3 class="lead"><a class="lead-unstyled" href="/coasters/'+data.park.short+'/'+data.slug+'">'+data.name+'</a></h3>'+
                           '<p><a href="/coasters/p/' + data.park.short + '">' + data.park.name + '</a></br>' +
                           '<a href="/coasters/m/' + data.manufacturer.abbreviation + '">' + data.manufacturer.name + '</a></p>' +
                           '</div>';
                },
                empty: function() {
                    return "Oops. Looks like there's nothing here by that name.";
                }
            },
            cssClasses: {
                root: "row",
                item: "col-sm-6 col-md-4 col-lg-3",
            }
        });
        search.addWidget(searchBar);
        search.addWidget(searchResults);
        search.start();
        $(document).on('ready', function() {
            $('.ais-infinite-hits--showmore').find('button, :disabled').addClass('btn btn-secondary');
        })
    </script>
@endsection