<?php

if (!function_exists('theme_folder')) {
    function theme_folder() {
        $theme = session('theme');
        return $theme ?: '1';
    }
}

if (!function_exists('get_links')) {
    function get_links($limit = 0) {
        return model('App\Models\M_links')->getLinks($limit);
    }
}

if (!function_exists('get_tags')) {
    function get_tags($limit = 0) {
        return model('App\Models\M_tags')->getTags($limit, true);
    }
}

if (!function_exists('get_banners')) {
    function get_banners($limit = 0) {
        return model('App\Models\M_banners')->getBanners($limit);
    }
}

if (!function_exists('get_years')) {
    function get_years() {
        return model('App\Models\Public\M_posts')->getYears();
    }
}

if (!function_exists('get_archives')) {
    function get_archives($year) {
        return model('App\Models\Public\M_posts')->getArchives($year);
    }
}

if (!function_exists('get_quotes')) {
    function get_quotes($limit = 0) {
        return model('App\Models\M_quotes')->getQuotes($limit);
    }
}

if (!function_exists('get_image_sliders')) {
    function get_image_sliders($limit = 0) {
        return model('App\Models\M_image_sliders')->getImageSliders($limit);
    }
}

if (!function_exists('get_active_question')) {
    function get_active_question() {
        return model('App\Models\M_questions')->getActiveQuestion();
    }
}

if (!function_exists('get_answers')) {
    function get_answers($question_id) {
        return model('App\Models\M_answers')->getAnswers($question_id);
    }
}

if (!function_exists('get_latest_posts')) {
    function get_latest_posts($limit = 0) {
        return model('App\Models\Public\M_posts')->getLatestPosts($limit);
    }
}

if (!function_exists('get_popular_posts')) {
    function get_popular_posts($limit = 0) {
        return model('App\Models\Public\M_posts')->getPopularPosts($limit);
    }
}

if (!function_exists('get_most_commented')) {
    function get_most_commented($limit = 0) {
        return model('App\Models\Public\M_posts')->getMostCommented($limit);
    }
}

if (!function_exists('get_random_posts')) {
    function get_random_posts($limit = 0) {
        return model('App\Models\Public\M_posts')->getRandomPosts($limit);
    }
}

if (!function_exists('get_post_categories')) {
    function get_post_categories($category_slug, $limit = 0) {
        return model('App\Models\Public\M_posts')->getPostCategories($category_slug, $limit);
    }
}

if (!function_exists('get_categories')) {
    function get_categories($category_type, $limit = 0) {
        return model('App\Models\M_categories')->getCategories($category_type, $limit);
    }
}

if (!function_exists('get_related_posts')) {
    function get_related_posts($categories, $id) {
        return model('App\Models\Public\M_posts')->getRelatedPosts($categories, $id);
    }
}

if (!function_exists('get_another_pages')) {
    function get_another_pages($id, $limit = 0) {
        return model('App\Models\Public\M_posts')->getAnotherPages($id, $limit);
    }
}

if (!function_exists('get_recent_comments')) {
    function get_recent_comments($limit = 0) {
        return model('App\Models\M_post_comments')->getRecentComments($limit);
    }
}

if (!function_exists('get_opening_speech')) {
    function get_opening_speech() {
        return model('App\Models\Public\M_posts')->getOpeningSpeech();
    }
}

if (!function_exists('get_videos')) {
    function get_videos($limit = 0) {
        return model('App\Models\M_videos')->getVideos($limit);
    }
}

if (!function_exists('get_albums')) {
    function get_albums($limit = 0) {
        return model('App\Models\M_albums')->getAlbums($limit);
    }
}

if (!function_exists('get_menus')) {
    function get_menus() {
        return model('App\Models\M_menus')->nestedMenus();
    }
}

if (!function_exists('recursive_list')) {
    function recursive_list($menus) {
        $nav = '';
        foreach ($menus as $menu) {
            $url = $menu['menu_url'] == '#' ? $menu['menu_url'] : base_url() . $menu['menu_url'];
            if ($menu['menu_type'] == 'links') $url = $menu['menu_url'];
            $nav .= '<li>';
            $nav .= '<a href="' . $url . '" target="' . $menu['menu_target'] . '">' . strtoupper($menu['menu_title']) . '</a>';
            $sub_nav = recursive_list($menu['children']);
            if ($sub_nav) $nav .= "<ul>" . $sub_nav . "</ul>";
            $nav .= "</li>";
        }
        return $nav;
    }
}

if (!function_exists('opening_speech_route')) {
    function opening_speech_route() {
        $level = (int) session('school_level');
        if ($level == 5) return 'sambutan-rektor';
        if ($level == 6) return 'sambutan-ketua';
        if ($level == 7) return 'sambutan-direktur';
        return 'sambutan-kepala-sekolah';
    }
}
