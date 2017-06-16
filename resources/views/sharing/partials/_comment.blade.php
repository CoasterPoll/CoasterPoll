<?php
    $traverse = function($comments) use (&$traverse) {
        foreach($comments as $comment):

?>
    <div class="card-block comment" id="comment{{ $comment->getId() }}" data-comment="{{ $comment->id }}">
        <h4>
            <a href="{{ $comment->user->getProfileLink() }}" class="lead-unstyled">{{ $comment->user->handle ?? "[DELETED]" }}</a>
            @if($comment->parent_id ?? false)
                <small>in reply to <a href="{{ $comment->parent->user->getProfileLink() }}">{{ $comment->parent->user->handle }}</a></small>
            @endif
        </h4>
        <p class="card-text">{{ $comment->body ?? "Comment Not Found" }}</p>
        <a class="card-link small" href="#comment{{ $comment->getId() }}">Permalink</a>
        @can('Can comment')
            <a class="card-link small reply-to-comment" role="button">Reply</a>
        @endcan
        @auth
            <a class="card-link small" role="button" id="report-{{ $comment->getId() }}">Report</a>
        @endauth
        @if($comment->children ?? false)
            <?php $traverse($comment->children) ?>
        @endif
    </div>
<?php
        endforeach;
    };

    $traverse($comments);
?>