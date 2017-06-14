<div class="card card-block mb-3">
    <a href="{{ $link->out() }}">{{ $link->title }}</a>
    <small class="mb-1">Posted by {{ $link->getPoster()->handle }} @null($link->linkable) on {{ \ChaseH\Helpers\Namer::getNameOrTitle($link->linkable) }} @endnull at {{ $link->created_at->diffForHumans() }}.</small>
    <a class="card-link small" href="{{ $link->getLink() }}">Comments</a>
</div>