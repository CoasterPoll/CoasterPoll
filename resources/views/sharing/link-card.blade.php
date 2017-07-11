<div class="card card-block mb-3 p-1">
    <div class="row no-gutters">
        <div class="col-1 align-middle">
            <ul class="list-unstyled text-center thumb-group">
                <li><a role="button" class="thumb-up" data-thing="L{{ $link->id }}" data-direction="1"><i class="fa fa-thumbs-up @auth(){{ $link->getVoteClass('up') }}@endauth"></i></a></li>
                <li class="thumb-score">{{ $link->score }}</li>
                <li><a role="button" class="thumb-up" data-thing="L{{ $link->id }}" data-direction="-1"><i class="fa fa-thumbs-down @auth(){{ $link->getVoteClass('down') }}@endauth"></i></a></li>
            </ul>
        </div>
        <div class="col-11" style="display:block">
            <a href="{{ $link->out() }}" target="_blank" style="display:block">{{ $link->title }}</a>
            <small class="mb-1" style="display:block">Posted by <a href="{{ $link->getPoster()->getProfileLink() }}">{{ $link->getPoster()->handle }}</a> @null($link->linkable) on {{ \ChaseH\Helpers\Namer::getNameOrTitle($link->linkable) }} @endnull about {{ $link->created_at->diffForHumans() }}.</small>
            <a class="card-link small" href="{{ $link->getLink() }}">{{ $link->comment_count }} Comments</a>
        </div>
    </div>
</div>