@extends('layouts.app')

@section('title')
Home
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="jumbotron">
                <h1 class="display-4">CoasterPoll</h1>
                <p class="lead">Objectively ranking the subjective topic of roller coasters.</p>
                <hr class="my-4">
                <p>It's easy, free, and open to anyone.</p>
                <p>Using a custom ranking algorithm that doesn't give special treatment to the big parks, or ignore the smaller ones.</p>
            </div>
            <div class="row my-4">
                <div class="col-sm-4 text-center">
                    <span class="fa-stack fa-5x">
                        <i class="fa fa-circle fa-stack-2x" style="color: #d6d6d6"></i>
                        <i class="fa fa-ticket fa-stack-1x text-success"></i>
                    </span>
                </div>
                <div class="col-sm-8">
                    <h3>1. Visit Parks  (And Ride Coasters!)</h3>
                    <p>The best place to start is visiting parks and riding coasters. Your opinion matters whether you've been to 1 park or 100. Once you ride it, just click the <button type="button" id="ridden-example" class="btn btn-sm btn-outline-success"><i class="fa fa-check-square-o"></i> Ridden</button> button to keep track of which coasters you've riddne.</p>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-sm-4 text-center">
                    <span class="fa-stack fa-5x">
                        <i class="fa fa-circle fa-stack-2x" style="color: #d6d6d6"></i>
                        <i class="fa fa-sort-amount-desc fa-stack-1x text-info"></i>
                    </span>
                </div>
                <div class="col-sm-8">
                    <h3>2. Rank Coasters</h3>
                    <p>Then you can start ranking using our drag-and-drop interface. You can even group coasters that are too awesome to pick a winner.</p>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-sm-4 text-center">
                    <span class="fa-stack fa-5x">
                        <i class="fa fa-circle fa-stack-2x" style="color: #d6d6d6"></i>
                        <i class="fa fa-trophy fa-stack-1x text-warning"></i>
                    </span>
                </div>
                <div class="col-sm-8">
                    <h3>3. We Do The Math</h3>
                    <p>We'll announce when entries will close and start calculating the results. Follow us on social media for the latest updates on when we'll get started.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        @auth
            $('#ridden-example').on('click', function() {
               toastr.warning("Sorry, that's not a real button.")
            });
        @endauth
    </script>
@endsection