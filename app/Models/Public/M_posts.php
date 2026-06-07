<?php

namespace App\Models\Public;

use CodeIgniter\Model;

class M_posts extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';

    public function getLatestPosts(int $limit = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id, x1.post_title, x1.created_at, x1.post_content,
            x1.post_image, x1.post_slug, x1.post_counter,
            x2.full_name AS post_author
        ');
        $builder->join('users x2', 'x1.post_author = x2.id', 'LEFT');
        $builder->where('x1.post_type', 'post');
        $builder->where('x1.post_status', 'publish');
        $builder->where('x1.is_deleted', 'false');
        $builder->where('x1.post_visibility', 'public');
        $builder->orderBy('x1.created_at', 'DESC');
        if ($limit > 0) $builder->limit($limit);
        return $builder->get();
    }

    public function getPopularPosts(int $limit = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id, x1.post_title, x1.created_at, x1.post_content,
            x1.post_image, x1.post_slug, x1.post_counter,
            x2.full_name AS post_author
        ');
        $builder->join('users x2', 'x1.post_author = x2.id', 'LEFT');
        $builder->where('x1.post_type', 'post');
        $builder->where('x1.post_status', 'publish');
        $builder->where('x1.is_deleted', 'false');
        $builder->where('x1.post_visibility', 'public');
        $builder->orderBy('x1.post_counter', 'DESC');
        if ($limit > 0) $builder->limit($limit);
        return $builder->get();
    }

    public function getMostCommented(int $limit = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id, x1.post_title, x1.created_at, x1.post_content,
            x1.post_image, x1.post_slug, x1.post_counter,
            x2.full_name AS post_author, COUNT(x3.id) AS total_comment
        ');
        $builder->join('users x2', 'x1.post_author = x2.id', 'LEFT');
        $builder->join('comments x3', 'x1.id = x3.comment_post_id AND x3.comment_type = "post"', 'LEFT');
        $builder->where('x1.post_type', 'post');
        $builder->where('x1.post_status', 'publish');
        $builder->where('x1.is_deleted', 'false');
        $builder->where('x1.post_visibility', 'public');
        $builder->groupBy(['1', '2', '3', '4', '5', '6', '7', '8']);
        $builder->orderBy('9', 'DESC');
        $builder->having('COUNT(x3.id) > 0');
        if ($limit > 0) $builder->limit($limit);
        return $builder->get();
    }

    public function getRandomPosts(int $limit = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id, x1.post_title, x1.created_at, x1.post_content,
            x1.post_image, x1.post_slug, x1.post_counter,
            x2.full_name AS post_author
        ');
        $builder->join('users x2', 'x1.post_author = x2.id', 'LEFT');
        $builder->where('x1.post_type', 'post');
        $builder->where('x1.post_status', 'publish');
        $builder->where('x1.is_deleted', 'false');
        $builder->where('x1.post_visibility', 'public');
        $builder->orderBy('RAND()');
        if ($limit > 0) $builder->limit($limit);
        return $builder->get();
    }

    public function getRelatedPosts(string $postCategories = '', int $id = 0)
    {
        $postCategories = explode(',', $postCategories);
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id, x1.post_title, x1.created_at, x1.post_content,
            x1.post_image, x1.post_slug, x1.post_counter,
            x2.full_name AS post_author
        ');
        $builder->join('users x2', 'x1.post_author = x2.id', 'LEFT');
        $builder->where('x1.post_type', 'post');
        $builder->where('x1.post_status', 'publish');
        $builder->where('x1.is_deleted', 'false');
        $builder->where('x1.post_visibility', 'public');
        if ($id > 0) $builder->where('x1.id !=', $id);
        $builder->groupStart();
        $i = 0;
        foreach ($postCategories as $category) {
            if ($i === 0) {
                $builder->like('x1.post_categories', $category);
            } else {
                $builder->orLike('x1.post_categories', $category);
            }
            $i++;
        }
        $builder->groupEnd();
        $builder->orderBy('x1.created_at', 'DESC');
        $reading = __session('reading');
        $limit = ($reading['post_related_count'] ?? 0) > 0 ? $reading['post_related_count'] : 5;
        $builder->limit($limit);
        return $builder->get();
    }

    public function setPostCounter(int $id)
    {
        return $this->db->table($this->table)
            ->set('post_counter', 'post_counter + 1', false)
            ->where($this->primaryKey, $id)
            ->update();
    }

    public function search(string $keyword)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id, x1.post_title, x1.created_at, x1.post_content,
            x1.post_image, x1.post_slug, x1.post_counter,
            x2.full_name AS post_author
        ');
        $builder->join('users x2', 'x1.post_author = x2.id', 'LEFT');
        $builder->where('x1.post_status', 'publish');
        $builder->where('x1.is_deleted', 'false');
        $builder->where('x1.post_visibility', 'public');
        $builder->whereIn('x1.post_type', ['post', 'page']);
        $builder->groupStart();
        $builder->like('LOWER(x1.post_title)', strtolower($keyword));
        $builder->groupEnd();
        $builder->limit(20);
        return $builder->get();
    }

    public function getYears()
    {
        return $this->db->table($this->table)
            ->select('LEFT(created_at, 4) as year')
            ->where('post_type', 'post')
            ->where('is_deleted', 'false')
            ->where('post_status', 'publish')
            ->where('post_visibility', 'public')
            ->groupBy('1')
            ->orderBy('1', 'DESC')
            ->get();
    }

    public function getArchives(string $year)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            SUBSTR(x1.created_at, 6, 2) AS `code`
            , MONTHNAME(x1.created_at) AS `month`
            , COUNT(*) AS `count`
        ");
        $builder->where('LEFT(x1.created_at, 4) = ', $year);
        $builder->where('x1.post_type', 'post');
        $builder->where('x1.post_status', 'publish');
        $builder->where('x1.post_visibility', 'public');
        $builder->where('x1.is_deleted', 'false');
        $builder->groupBy("1,2");
        $builder->orderBy('1', 'ASC');
        return $builder->get();
    }

    public function getPostArchives(string $year, string $month, int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id, x1.post_title, x1.created_at, x1.post_content,
            x1.post_image, x1.post_slug, x1.post_counter,
            x2.full_name AS post_author
        ');
        $builder->join('users x2', 'x1.post_author = x2.id', 'LEFT');
        $builder->where('x1.post_type', 'post');
        $builder->where('x1.post_status', 'publish');
        $builder->where('x1.is_deleted', 'false');
        $builder->where('x1.post_visibility', 'public');
        $builder->where('LEFT(x1.created_at, 4) = ', $year);
        $builder->where('SUBSTRING(x1.created_at, 6, 2) = ', $month);
        $builder->orderBy('x1.created_at', 'DESC');
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getPostTags(string $tag = '', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id, x1.post_title, x1.post_content, x1.created_at,
            x1.post_image, x1.post_slug, x2.full_name AS post_author,
            x1.post_counter
        ');
        $builder->join('users x2', 'x1.post_author = x2.id', 'LEFT');
        $builder->where('x1.post_type', 'post');
        $builder->where('x1.is_deleted', 'false');
        $builder->where('x1.post_status', 'publish');
        $builder->where('x1.post_visibility', 'public');
        $builder->groupStart();
        $builder->like("LOWER(REPLACE(x1.post_tags, ' ', '-'))", $tag);
        $builder->groupEnd();
        $builder->orderBy('x1.created_at', 'DESC');
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getPostCategories(string $categorySlug = '', int $limit = 0, int $offset = 0)
    {
        $id = '+0+';
        $query = $this->db->table('categories')
            ->select('id')
            ->where('category_slug', $categorySlug)
            ->where('category_type', 'post')
            ->where('is_deleted', 'false')
            ->limit(1)
            ->get();
        if ($query->getNumRows() === 1) {
            $res = $query->getRow();
            $id = '+' . $res->id . '+';
        }
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id, x1.post_title, x1.created_at, x1.post_content,
            x1.post_image, x1.post_slug, x1.post_counter,
            x2.full_name AS post_author
        ');
        $builder->join('users x2', 'x1.post_author = x2.id', 'LEFT');
        $builder->where('x1.post_type', 'post');
        $builder->where('x1.is_deleted', 'false');
        $builder->where('x1.post_status', 'publish');
        $builder->where('x1.post_visibility', 'public');
        $builder->groupStart();
        $builder->like('x1.post_categories', $id);
        $builder->groupEnd();
        $builder->orderBy('x1.created_at', 'DESC');
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getAnotherPages(int $id = 0, int $limit = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id, x1.post_title, x1.post_content, x1.created_at,
            x1.post_image, x1.post_slug, x2.full_name AS post_author,
            x1.post_counter
        ');
        $builder->join('users x2', 'x1.post_author = x2.id', 'LEFT');
        $builder->where('x1.post_type', 'page');
        $builder->where('x1.is_deleted', 'false');
        $builder->where('x1.post_status', 'publish');
        $builder->where('x1.post_visibility', 'public');
        $builder->where('x1.id !=', $id);
        $builder->orderBy('x1.created_at', 'DESC');
        if ($limit > 0) $builder->limit($limit);
        return $builder->get();
    }

    public function feed()
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, post_title, created_at, post_content, post_slug');
        $builder->where('post_type', 'post');
        $builder->where('post_status', 'publish');
        $builder->where('is_deleted', 'false');
        $builder->where('post_visibility', 'public');
        $builder->orderBy('created_at', 'DESC');
        $reading = __session('reading');
        $limit = ($reading['post_rss_count'] ?? 0) > 0 ? $reading['post_rss_count'] : 10;
        $builder->limit($limit);
        return $builder->get();
    }

    public function getOpeningSpeech()
    {
        $row = $this->db->table($this->table)
            ->select('post_content')
            ->where('post_type', 'opening_speech')
            ->limit(1)
            ->get()
            ->getRow();
        return $row ? $row->post_content : '';
    }
}
