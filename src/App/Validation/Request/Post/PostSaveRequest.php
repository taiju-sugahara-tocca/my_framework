<?php

namespace App\Validation\Request\Post;

use App\Model\Post\Parts\Id;
use App\Model\Post\Parts\Title;
use App\Model\Post\Parts\Content;
use Framework\Request;
use App\Session\Session;

class PostSaveRequest
{
    /**
     * @return array
     */
    public static function validate(): array
    {   
        $errors = [];
        $request = new Request();
        
        //ID
        $id = $request->post('id', null);
        if($id) {
            try {
                $id = new Id($id);
            } catch (\InvalidArgumentException $e) {
                $errors['id'] = $e->getMessage();
            }
        } 

        //タイトル
        $title = $request->post('title', null);
        try {
            $title = new Title($title);
        } catch (\InvalidArgumentException $e) {
            $errors['title'] = $e->getMessage();
        }

        //内容
        $content = $request->post('content', null);
        try {
            $content = new Content($content);
        } catch (\InvalidArgumentException $e) {
            $errors['content'] = $e->getMessage();
        }

        Session::setErrors($errors);

        return $errors;
    }
}