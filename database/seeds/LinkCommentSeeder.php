<?php

use Illuminate\Database\Seeder;

class LinkCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $links = factory(\ChaseH\Models\Sharing\Link::class, 100)->create();

        $links->each(function($link) {
            $comments = factory(\ChaseH\Models\Sharing\Comment::class, 100)->create()->each(function($comment) use ($link) {
                $comment->replies()->saveMany($this->createComments($comment, $link, 3));
            });

            $link->comments()->saveMany($comments);
        });
    }

    protected function createComments($comment, $link, $depth = 3, $currentDepth = 0) {
        if($currentDepth === $depth) {
            return;
        }

        return $comment->replies()->saveMany(
            factory(\ChaseH\Models\Sharing\Comment::class, 2))->create()->each(function($reply) use ($depth, $currentDepth, $link) {
                $link->comments()->save($reply);

                $this->createComments($reply, $link, $depth, ++$currentDepth);
        });
    }
}
