@if(!isset($_override_footer_image))
<img src="@if(!isset($_footer_image)) {{ config('app.cdn') }}/img/track-footer.png @else {{ config('app.cdn') }}{{ $_footer_image }} @endif" id="footer-track" alt="Track">
@endif
<footer class="footer bg-faded">
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-center">
                <h1 class="branding"><a class="lead-unstyled" href="/">CoasterPoll.com</a></h1>
                <p class="text-muted">Powered by <a href="https://chaseh.net">ChaseH.net</a> </p>
            </div>
            <div class="col-md-8">
                <div class="nav justify-content-center flex-md-row flex-column">
                    <a class="nav-link" href="/">Home</a>
                    <a class="nav-link" href="{{ route('contact') }}">Contact Us</a>
                    <a class="nav-link" href="https://blog.coasterpoll.com">Blog</a>
                    <a class="nav-link" href="https://things.chaseh.net/">Bugs/Features</a>
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
                        <a href="https://fb.me/coasterpoll" class="link-unstyled"><i class="fa fa-facebook fa-2x"></i></a>
                    </li>
                    <li class="nav-link">
                        <a href="https://twitter.com/coasterpoll" class="link-unstyled"><i class="fa fa-twitter fa-2x"></i></a>
                    </li>
                    <li class="nav-link">
                        <a href="https://reddit.com/r/coasterpoll" class="link-unstyled"><i class="fa fa-reddit-alien fa-2x"></i></a>
                    </li>
                    <li class="nav-link">
                        <a href="https://blog.coasterpoll.com/" class="link-unstyled"><i class="fa fa-wordpress fa-2x"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>