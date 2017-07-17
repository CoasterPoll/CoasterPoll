<?php

namespace ChaseH\Traits\Eloquent;

trait NestableTrait {
    public function nestedComments($page = 1, $perPage = 25) {
        $comments = $this->comments();

        if($comments->count() == 0) {
            return null;
        }

        $grouped = $comments->get()->groupBy('parent_id');
        $root = $grouped->get(null)->forPage($page, $perPage);

        $ids = $this->buildIDNest($root, $grouped);

        $grouped = $comments->whereIn('id', $ids)->with([
            'user' => function($query) {
                return $query->select('id', 'handle');
            },
            'parent.user' => function($query) {
                return $query->select('id', 'handle');
            },
        ])->get()->groupBy('parent_id');
        $root = $grouped->get(null)->forPage($page, $perPage);

        return $this->buildCommentNest($root, $grouped);
    }

    protected function buildCommentNest($comments, $groupedComments) {
        return $comments->each(function($comment) use ($groupedComments) {
            if($replies = $groupedComments->get($comment->id)) {
                $comment->children = $replies;

                $this->buildCommentNest($comment->children, $groupedComments);
            }
        });
    }

    protected function buildIDNest($root, $grouped, &$ids = []) {
        foreach($root as $comment) {
            $ids[] = $comment->id;

            if($replies = $grouped->get($comment->id)) {
                $this->buildIDNest($replies, $grouped, $ids);
            }
        }

        return $ids;
    }
}