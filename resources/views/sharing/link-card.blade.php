<div class="card card-block mb-3">
    <a href="{{ $link->out() }}" target="_blank">{{ $link->title }}</a>
    <small class="mb-1">Posted by <a href="{{ $link->getPoster()->getProfileLink() }}">{{ $link->getPoster()->handle }}</a> @null($link->linkable) on {{ \ChaseH\Helpers\Namer::getNameOrTitle($link->linkable) }} @endnull about {{ $link->created_at->diffForHumans() }}.</small>
    <a class="card-link small" href="{{ $link->getLink() }}">{{ $link->comment_count }} Comments</a>
</div>