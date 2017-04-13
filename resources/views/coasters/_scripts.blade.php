<script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
<script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
<script>
    var client = algoliasearch('{!! env('ALGOLIA_APP_ID') !!}', '{!! env('ALGOLIA_SEARCH') !!}');
    var index = client.initIndex('coasters');
    autocomplete('#coaster-search', { hint: true }, [
        {
            source: autocomplete.sources.hits(index, { hitsPerPage: 5 }),
            displayKey: 'name',
            templates: {
                suggestion: function(suggestion) {
                    return '<span><a href="/'+suggestion.park.short+'/'+suggestion.slug+'">'+suggestion.name+'</a></span><span>'+suggestion.park.short+'</span>';
                }
            },
            empty: '<div class="aa-empty">No Coasters :(</div>'
        }
    ]).on('autocomplete:selected', function(event, suggestion) {

    });
</script>