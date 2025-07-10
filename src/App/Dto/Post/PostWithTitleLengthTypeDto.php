<?php

namespace App\Dto\Post;

use App\Model\Post\PostData;

class PostWithTitleLengthTypeDto
{
    public function __construct(
        public PostData $post,
        public string $title_length_type // "long" or "short"
    ) {}
}