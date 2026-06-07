<?php

namespace App\Models;

use App\Models\BaseModel;

class M_dashboard extends BaseModel
{
    public function widgetBox()
    {
        $db = db_connect();
        $data = new \stdClass();

        $data->students = $db->table('users')
            ->where('is_student', 'true')
            ->where('is_alumni', 'false')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data->employees = $db->table('users')
            ->where('is_employee', 'true')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data->alumni = $db->table('users')
            ->where('is_alumni', 'true')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data->posts = $db->table('posts')
            ->where('post_type', 'post')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data->pages = $db->table('posts')
            ->where('post_type', 'page')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data->comments = $db->table('comments')
            ->where('comment_type', 'post')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data->messages = $db->table('comments')
            ->where('comment_type', 'message')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data->registrants = $db->table('users')
            ->where('is_prospective_student', 'true')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data->subscribers = $db->table('subscribers')
            ->countAllResults();

        $data->categories = $db->table('categories')
            ->where('category_type', 'post')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data->tags = $db->table('tags')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data->links = $db->table('links')
            ->where('link_type', 'link')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data->quotes = $db->table('quotes')
            ->where('is_deleted', 'false')
            ->countAllResults();

        return $data;
    }
}
