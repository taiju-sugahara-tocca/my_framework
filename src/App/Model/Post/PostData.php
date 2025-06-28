<?php
namespace App\Model\Post;

use Framework\Model;

class PostData extends Model
{
    public function __construct(
        private $id,
        private $title,
        private $content,
        private $user_id
    ) {

    }

    protected static function table(): string {
        return 'post_data';
    }

    protected static function standardSortable(): array
    {
        return ['id', 'title'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public static function createInstancefromArray(array $data)
    {
        return new self(
            $data['id'] ?? null,
            $data['title'] ?? null,
            $data['content'] ?? null,
            $data['user_id'] ?? null
        );
    }
}