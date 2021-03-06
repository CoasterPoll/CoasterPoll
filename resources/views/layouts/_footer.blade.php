@if(!isset($_override_footer_image))
<img src="@if(!isset($_footer_image)) {{ config('app.cdn') }}/img/track-footer.png @else {{ config('app.cdn') }}{{ $_footer_image }} @endif" id="footer-track" alt="Track">
@endif
<footer class="footer bg-faded">
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-center">
                <h1 class="branding"><a class="lead-unstyled" href="/">CoasterPoll.com</a></h1>
                <p class="text-muted">A <a href="https://chaseh.net">ChaseH</a> Project</p>
            </div>
            <div class="col-md-8">
                <div class="nav justify-content-center flex-md-row flex-column">
                    <a class="nav-link" href="/">Home</a>
                    <a class="nav-link" href="{{ route('contact') }}">Contact Us</a>
                    @if(config('app.ads'))
                        <a class="nav-link" href="{{ route('ads') }}">Sponsor</a>
                    @endif
                    @isset($_footer_links)
                        @foreach($_footer_links as $_link)
                            <a class="nav-link" href="{{ $_link->href }}">{{ $_link->text }}</a>
                        @endforeach
                    @endisset
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-4 offset-sm-4 text-center">
                <ul class="nav justify-content-center">
                    <li class="nav-link">
                        <a href="https://fb.me/themeparkreview" class="link-unstyled"><i class="fa fa-facebook fa-2x"></i></a>
                    </li>
                    <li class="nav-link">
                        <a href="https://twitter.com/themeparkreview" class="link-unstyled"><i class="fa fa-twitter fa-2x"></i></a>
                    </li>
                    <li class="nav-link">
                        <a href="http://www.themeparkreview.com/forum/index.php" class="link-unstyled"><i class="fa fa-comments-o fa-2x"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>