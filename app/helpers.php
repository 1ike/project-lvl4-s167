<?php

function tagsList($task)
{
    $tagsNames = $task->tags->map(function ($tag) {
        return $tag->name;
    })->all();

    return implode(', ', $tagsNames);
}
