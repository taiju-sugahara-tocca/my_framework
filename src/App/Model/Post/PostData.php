<?php
namespace App\Model\Post;

use Framework\Model;
use App\Model\User\UserData;
use App\Model\Post\Parts\Id;
use App\Model\Post\Parts\Title;
use App\Model\Post\Parts\Content;
use App\Model\Post\Parts\UserId;

class PostData extends Model
{
    /**
     *
     * @param ?Id $id ID（本来は必須だが部分取得時はnull許容）
     * @param ?Title $title タイトル（本来は必須だが部分取得時はnull許容）
     * @param ?Content $content 内容 （本来は必須だが部分取得時はnull許容）
     * @param ?userId $user_id ユーザID（本来は必須だが部分取得時はnull許容）
     */
    public function __construct(
        private ?Id $id,
        private ?Title $title,
        private ?Content $content,
        private ?userId $user_id
    ) {

    }

    private UserData $user;

    protected static function table(): string {
        return 'post_data';
    }

    protected static function standardSortable(): array
    {
        return ['id', 'title'];
    }

    public function getId()
    {
        return $this->id ? $this->id->getValue() : null;
    }

    public function getTitle()
    {
        return $this->title ? $this->title->getValue() : null;
    }

    public function getContent()
    {
        return $this->content ? $this->content->getValue() : null;
    }

    public function getUserId()
    {
        return $this->user_id ? $this->user_id->getValue() : null;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public static function createInstancefromArray(array $data)
    {
        return new self(
            isset($data['id']) && $data['id'] ? new Id($data['id']) : null,
            isset($data['title']) && $data['title'] ? new Title($data['title']) : null,
            isset($data['content']) && $data['content'] ? new Content($data['content']) : null,
            isset($data['user_id']) && $data['user_id'] ? new UserId($data['user_id']) : null
        );
    }
}