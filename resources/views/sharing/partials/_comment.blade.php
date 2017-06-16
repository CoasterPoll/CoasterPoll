<?php
    $traverse = function($comments) use (&$traverse) {
        foreach($comments as $comment):

?>
    <div class="card-block comment" data-comment="{{ $comment->id }}">
        <h4>
            {{ $comment->user->handle ?? "[DELETED]" }}
            @if($comment->parent_id ?? false)
                <small>in reply to {{ $comment->parent->user->handle }}</small>
            @endif
        </h4>
        <p class="card-text">{{ $comment->body ?? "Comment Not Found" }}</p>
        <a class="card-link small" href="#{{ $comment->getId() }}">Permalink</a>
        @can('Can comment')
            <a class="card-link small reply-to-comment" role="button">Reply</a>
        @endcan
        @if($comment->children ?? false)
            <?php $traverse($comment->children) ?>
        @endif
    </div>
<?php
        endforeach;
    };

    $traverse($comments);
?>