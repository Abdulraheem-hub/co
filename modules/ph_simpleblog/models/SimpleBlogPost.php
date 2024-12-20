<?php

/**
 * Blog for PrestaShop module by PrestaHome Team.
 *
 * @author    PrestaHome Team <support@prestahome.com>
 * @copyright Copyright (c) 2011-2021 PrestaHome Team - www.PrestaHome.com
 * @license   You only can use module, nothing more!
 */

require_once _PS_MODULE_DIR_ . 'ph_simpleblog/ph_simpleblog.php';

class SimpleBlogPost extends ObjectModel
{
    public $id;
    public $id_simpleblog_post;
    public $id_simpleblog_category;
    public $id_simpleblog_post_type;
    public $id_simpleblog_author;
    public $author;
    public $likes;
    public $views;
    public $allow_comments = 3;
    public $is_featured = 0;
    public $access;
    public $cover;
    public $featured;
    public $id_product;
    public $gallery_style;
    public $active = 1;

    public $title;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;
    public $canonical;
    public $short_content;
    public $content;
    public $link_rewrite;
    public $video_code;
    public $external_url;

    public $date_add;
    public $date_upd;

    public $featured_image;
    public $banner;
    public $tags;
    public $post_type;
    public $category_url;
    public $parent_category = false;
    public $category;

    public static $definition = [
        'table' => 'simpleblog_post',
        'primary' => 'id_simpleblog_post',
        'multilang' => true,
        'fields' => [
            'id_simpleblog_category' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ],
            'id_simpleblog_post_type' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ],
            'id_simpleblog_author' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ],
            'active' => [
                'type' => self::TYPE_BOOL,
            ],
            'is_featured' => [
                'type' => self::TYPE_BOOL,
            ],
            'access' => [
                'type' => self::TYPE_STRING,
            ],
            'author' => [
                'type' => self::TYPE_STRING,
            ],
            'likes' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ],
            'views' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ],
            'allow_comments' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ],
            'cover' => [
                'type' => self::TYPE_STRING,
            ],
            'featured' => [
                'type' => self::TYPE_STRING,
            ],
            'id_product' => [
                'type' => self::TYPE_STRING,
                'size' => '3999999999999',
            ],
            'gallery_style' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isAnything',
                'size' => 32,
            ],
            'date_add' => [
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
            ],
            'date_upd' => [
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
            ],

            // Lang fields
            'title' => [
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'required' => true,
                'size' => 255,
            ],
            'meta_title' => [
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'size' => 255,
            ],
            'meta_description' => [
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'size' => 255,
            ],
            'meta_keywords' => [
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'size' => 255,
            ],
            'canonical' => [
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isUrlOrEmpty',
            ],
            'link_rewrite' => [
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isLinkRewrite',
                'required' => true,
                'size' => 128,
            ],
            'short_content' => [
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml',
                'size' => 3999999999999,
            ],
            'content' => [
                'type' => self::TYPE_HTML,
                'lang' => true,
                'size' => 3999999999999,
            ],
            'video_code' => [
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml',
                'size' => 3999999999999,
            ],
            'external_url' => [
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml',
                'size' => 3999999999999,
            ],
        ],
    ];

    public function __construct($id_simpleblog_post = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id_simpleblog_post, $id_lang, $id_shop);

        $context = Context::getContext();

        if ($id_lang && $this->id) {
            $category = new SimpleBlogCategory($this->id_simpleblog_category, $id_lang);
            $this->category = $category->name;
            $this->category_rewrite = $category->link_rewrite;

            $this->url = $context->link->getModuleLink(
                'ph_simpleblog',
                'single',
                [
                    'rewrite' => $this->link_rewrite,
                    'sb_category' => $this->category_rewrite,
                ]
            );

            $this->category_url = $context->link->getModuleLink(
                'ph_simpleblog',
                'category',
                [
                    'sb_category' => $this->category_rewrite,
                ]
            );

            if ($category->id_parent > 0) {
                $this->parent_category = new SimpleBlogCategory($category->id_parent, $id_lang);
            }

            if ((int) $this->id_simpleblog_author > 0) {
                $this->author = new SimpleBlogPostAuthor($this->id_simpleblog_author, $id_lang);
            }
        }

        if (file_exists(_PS_MODULE_DIR_ . 'ph_simpleblog/covers/' . $this->id_simpleblog_post . '.' . $this->cover)) {
            $this->banner = _MODULE_DIR_ . 'ph_simpleblog/covers/' . $this->id_simpleblog_post . '.' . $this->cover;
            $this->banner_thumb = _MODULE_DIR_ . 'ph_simpleblog/covers/' . $this->id_simpleblog_post . '-thumb.' . $this->cover;
            $this->banner_wide = _MODULE_DIR_ . 'ph_simpleblog/covers/' . $this->id_simpleblog_post . '-wide.' . $this->cover;
        }

        if (file_exists(_PS_MODULE_DIR_ . 'ph_simpleblog/featured/' . $this->id_simpleblog_post . '.' . $this->featured)) {
            $this->featured_image = _MODULE_DIR_ . 'ph_simpleblog/featured/' . $this->id_simpleblog_post . '.' . $this->featured;
        }

        if ($this->id) {
            $tags = SimpleBlogTag::getPostTags((int) $this->id);
            $this->tags = $tags;

            if (isset($tags, $tags[$id_lang])) {
                $this->tags_list = $tags[$id_lang];
            }

            $this->comments = SimpleBlogComment::getCommentsCount((int) $this->id);
            $this->post_type = SimpleBlogPostType::getSlugById((int) $this->id_simpleblog_post_type);

            if ($this->post_type == 'gallery') {
                $this->gallery = SimpleBlogPostImage::getAllById((int) $this->id);
            }
        }
    }

    public function add($autodate = false, $null_values = false)
    {
        $ret = parent::add($autodate, $null_values);

        return $ret;
    }

    public function delete()
    {
        if ((int) $this->id === 0) {
            return false;
        }

        return self::deleteCover($this->id)
                && self::deleteFeatured($this->id)
                && parent::delete();
    }

    public static function getUrlRewriteInformations($id_simpleblog)
    {
        $sql = 'SELECT l.`id_lang`, c.`link_rewrite`
                FROM `' . _DB_PREFIX_ . 'simpleblog_lang` AS c
                LEFT JOIN  `' . _DB_PREFIX_ . 'lang` AS l ON c.`id_lang` = l.`id_lang`
                WHERE c.`id_simpleblog` = ' . (int) $id_simpleblog . '
                AND l.`active` = 1';

        return Db::getInstance()->executeS($sql);
    }

    public static function getSimplePosts($id_lang, $id_shop = null, Context $context = null, $filter = null, $selected = [])
    {
        if (!isset($context)) {
            $context = Context::getContext();
        }

        if (!$id_shop) {
            $id_shop = $context->shop->id;
        }

        $sql = new DbQuery();
        $sql->select('sbp.id_simpleblog_post, l.title');
        $sql->from('simpleblog_post', 'sbp');
        $sql->innerJoin('simpleblog_post_lang', 'l', 'sbp.id_simpleblog_post = l.id_simpleblog_post AND l.id_lang = ' . (int) $id_lang);
        $sql->innerJoin('simpleblog_post_shop', 'sbps', 'sbp.id_simpleblog_post = sbps.id_simpleblog_post AND sbps.id_shop = ' . (int) $id_shop);

        if ($filter) {
            $sql->where('sbp.id_simpleblog_post ' . pSQL($filter) . ' (' . implode(',', $selected) . ')');
        }

        $sql->where('sbp.date_add <= \'' . SimpleBlogHelper::now(Configuration::get('PH_BLOG_TIMEZONE')) . '\'');
        $sql->where('sbp.active = 1');

        $posts = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (is_array($posts) && sizeof($posts)) {
            $posts = self::checkAccess($posts);
        }

        return $posts;
    }

    public static function checkAccess($posts)
    {
        if (Context::getContext()->customer) {
            foreach ($posts as $key => $post) {
                if ($userGroups = Context::getContext()->customer->getGroups()) {
                    $tmpLinkGroups = unserialize($post['access']);
                    $linkGroups = [];

                    foreach ($tmpLinkGroups as $groupID => $status) {
                        if ($status) {
                            $linkGroups[] = $groupID;
                        }
                    }

                    $intersect = array_intersect($userGroups, $linkGroups);
                    if (!count($intersect)) {
                        unset($posts[$key]);
                    }
                }
            }

            return $posts;
        } else {
            return $posts;
        }
    }

    public static function findPosts($type = 'author', $keyword = false, $id_lang = null, $limit = 10, $page = null)
    {
        if ($type == 'author') {
            return self::getPosts($id_lang, $limit, null, $page, $page, null, null, null, null, false, $keyword);
        } elseif ($type == 'tag') {
            return self::getPosts($id_lang, $limit, null, $page, $page, null, null, null, null, false, false, $keyword);
        } else {
            return;
        }
    }

    public static function getAllAvailablePosts($id_lang)
    {
        return self::getPosts($id_lang, 99999, null, null, false, false, false, null, false, false, null, false, false, false);
    }

    public static function getPosts(
        $id_lang,
        $limit = 10,
        $id_simpleblog_category = null,
        $page = null,
        $active = true,
        $orderby = false,
        $orderway = false,
        $exclude = null,
        $featured = false,
        $author = false,
        $id_shop = null,
        $filter = false,
        $selected = [],
        $check_access = true
    ) {
        $context = Context::getContext();

        $start = $limit * ($page == 0 ? 0 : $page - 1);

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('simpleblog_post', 'sbp');

        if ($id_lang) {
            $sql->innerJoin('simpleblog_post_lang', 'l', 'sbp.id_simpleblog_post = l.id_simpleblog_post AND l.id_lang = ' . (int) $id_lang);
        }

        if (!$id_shop) {
            $id_shop = $context->shop->id;
        }

        $sql->innerJoin('simpleblog_post_shop', 'sbps', 'sbp.id_simpleblog_post = sbps.id_simpleblog_post AND sbps.id_shop = ' . (int) $id_shop);

        if ($active) {
            $sql->where('sbp.active = 1');
        }

        if (isset($id_simpleblog_category) && (int) $id_simpleblog_category > 0) {
            $childrens = SimpleBlogCategory::getChildrens((int) $id_simpleblog_category);
            if ($childrens && sizeof($childrens)) {
                $child_categories = [(int) $id_simpleblog_category];
                foreach ($childrens as $child) {
                    $child_categories[] = $child['id_simpleblog_category'];
                }
                $sql->where('sbp.id_simpleblog_category IN (' . implode(',', $child_categories) . ')');
            } else {
                $sql->where('sbp.id_simpleblog_category = ' . (int) $id_simpleblog_category);
            }
        }

        if ($exclude) {
            $sql->where('sbp.id_simpleblog_post != ' . (int) $exclude);
        }

        if ($featured) {
            $sql->where('sbp.is_featured = 1');
        }

        if ($author && Configuration::get('PH_BLOG_POST_BY_AUTHOR')) {
            $sql->where('sbp.author = "\'' . pSQL($author) . '\'"');
        }

        if ($filter && in_array($filter, ['IN', 'NOT IN'])) {
            $sql->where('sbp.id_simpleblog_post ' . pSQL($filter) . ' (' . implode(',', $selected) . ')');
        }

        $sql->where('sbp.date_add <= \'' . SimpleBlogHelper::now(Configuration::get('PH_BLOG_TIMEZONE')) . '\'');

        if (!$orderby) {
            $orderby = 'sbp.date_add';
        }

        if (!$orderway) {
            $orderway = 'DESC';
        }

        $sql->orderBy($orderby . ' ' . $orderway);

        $sql->limit((int) $limit, $start);

        $result = Db::getInstance()->executeS($sql);

        if (is_array($result) && sizeof($result) && $check_access == true) {
            $result = self::checkAccess($result);
        }

        if (empty($result)) {
            return;
        }


        $banner_sizes = array(
            'thumb' => [
                'width' => Configuration::get('PH_BLOG_THUMB_X'),
                'height' => Configuration::get('PH_BLOG_THUMB_Y'),
            ],
            'wide' => [
                'width' => Configuration::get('PH_BLOG_THUMB_X_WIDE'),
                'height' => Configuration::get('PH_BLOG_THUMB_Y_WIDE'),
            ],
        );

        foreach ($result as &$row) {
            $category_obj = new SimpleBlogCategory($row['id_simpleblog_category'], $id_lang);
            $category_url = SimpleBlogCategory::getLink($category_obj->link_rewrite, $id_lang);

            if (file_exists(_PS_MODULE_DIR_ . 'ph_simpleblog/covers/' . $row['id_simpleblog_post'] . '.' . $row['cover'])) {
                $row['banner'] = _MODULE_DIR_ . 'ph_simpleblog/covers/' . $row['id_simpleblog_post'] . '.' . $row['cover'];
                $row['banner_thumb'] = _MODULE_DIR_ . 'ph_simpleblog/covers/' . $row['id_simpleblog_post'] . '-thumb.' . $row['cover'];
                $row['banner_wide'] = _MODULE_DIR_ . 'ph_simpleblog/covers/' . $row['id_simpleblog_post'] . '-wide.' . $row['cover'];
                $row['banner_sizes'] = $banner_sizes;
            }

            if (file_exists(_PS_MODULE_DIR_ . 'ph_simpleblog/featured/' . $row['id_simpleblog_post'] . '.' . $row['featured'])) {
                $row['featured'] = _MODULE_DIR_ . 'ph_simpleblog/featured/' . $row['id_simpleblog_post'] . '.' . $row['featured'];
            }

            $row['url'] = self::getLink($row['link_rewrite'], $category_obj->link_rewrite, $id_lang);
            $row['category'] = $category_obj->name;
            $row['category_rewrite'] = $category_obj->link_rewrite;
            $row['category_url'] = $category_url;

            $row['allow_comments'] = self::checkIfAllowComments($row['allow_comments']);
            $row['comments'] = SimpleBlogComment::getCommentsCount((int) $row['id_simpleblog_post']);

            $tags = SimpleBlogTag::getPostTags((int) $row['id_simpleblog_post']);

            $row['tags'] = (isset($tags[$id_lang]) && is_array($tags[$id_lang]) && (sizeof($tags[$id_lang]) > 0)) ? $tags[$id_lang] : false;
            $row['post_type'] = SimpleBlogPostType::getSlugById((int) $row['id_simpleblog_post_type']);

            if ($row['post_type'] == 'gallery') {
                $row['gallery'] = SimpleBlogPostImage::getAllById((int) $row['id_simpleblog_post']);
            }
        }

        return $result;
    }

    public static function getLink($rewrite, $category, $idLang = null)
    {   
        if($idLang){
            return Context::getContext()->link->getModuleLink('ph_simpleblog', 'single', ['rewrite' => $rewrite, 'sb_category' => $category], null, $idLang);
        } else {
            return Context::getContext()->link->getModuleLink('ph_simpleblog', 'single', ['rewrite' => $rewrite, 'sb_category' => $category]);
        }
       
    }

    public static function getSearchLink($type = 'author', $keyword = false)
    {
        return Context::getContext()->link->getModuleLink('ph_simpleblog', 'search', ['type' => $type, 'keyword' => $keyword]);
    }

    public static function getByRewrite($rewrite = null, $id_lang = null, $category_rewrite = null)
    {
        if (empty($rewrite) || !Validate::isLinkRewrite($rewrite)) {
            return;
        }

        $category = false;
        if ($category_rewrite) {
            $category = SimpleBlogCategory::getByRewrite($category_rewrite, $id_lang);
        }

        $sql = new DbQuery();
        $sql->select('l.id_simpleblog_post');
        $sql->from('simpleblog_post_lang', 'l');

        if ($id_lang) {
            $sql->where('l.link_rewrite = \'' . pSQL($rewrite) . '\'');
            $sql->where('l.id_lang = \'' . (int) $id_lang . '\'');
        } else {
            $sql->where('l.link_rewrite = \'' . pSQL($rewrite) . '\'');
        }

        if ($category) {
            $sql->innerJoin('simpleblog_post', 'p', 'p.id_simpleblog_post = l.id_simpleblog_post');
            $sql->where('p.`id_simpleblog_category` = ' . (int) $category->id);
        }

        $result = Db::getInstance()->getValue($sql);

        if (!$result) {
            $sql = new DbQuery();
            $sql->select('l.id_simpleblog_post');
            $sql->from('simpleblog_post_lang', 'l');
            $sql->where('l.link_rewrite = \'' . pSQL($rewrite) . '\'');
            $searched_post = Db::getInstance()->getValue($sql);

            if ($searched_post) {
                $sql = new DbQuery();
                $sql->select('l.link_rewrite');
                $sql->from('simpleblog_post_lang', 'l');
                $sql->where('l.id_lang = ' . (int) $id_lang . ' AND l.id_simpleblog_post = ' . (int) $searched_post);
                $rewrite = Db::getInstance()->getValue($sql);
            }

            if ($rewrite) {
                $sql = new DbQuery();
                $sql->select('l.id_simpleblog_post');
                $sql->from('simpleblog_post_lang', 'l');

                if ($id_lang) {
                    $sql->where('l.link_rewrite = \'' . pSQL($rewrite) . '\' AND l.id_lang = ' . (int) $id_lang);
                } else {
                    $sql->where('l.link_rewrite = \'' . pSQL($rewrite) . '\'');
                }

                $post = new self(Db::getInstance()->getValue($sql), $id_lang);

                return $post;
            } else {
                return '404';
            }
        } else {
            $post = new self(Db::getInstance()->getValue($sql), $id_lang);

            return $post;
        }
    }

    public function getTags($id_lang)
    {
        if (is_null($this->tags)) {
            $this->tags = SimpleBlogTag::getPostTags($this->id);
        }

        if (!($this->tags && key_exists($id_lang, $this->tags))) {
            return '';
        }

        $result = '';
        foreach ($this->tags[$id_lang] as $tag_name) {
            $result .= $tag_name . ', ';
        }

        return rtrim($result, ', ');
    }

    public static function getPageLink($page_nb, $type = false, $rewrite = false)
    {
        $context = Context::getContext();

        $params = [];
        $additionalParams = [];
        $params['p'] = $page_nb;

        $entityPages = ['category', 'author'];

        if (in_array($type, $entityPages) && !$rewrite) {
            throw new Exception('Invalid parameters for SimpleBlogPosts::getPageLink');
        }

        if ($type == 'category') {
            $params['sb_category'] = $rewrite;
            if ($page_nb == 1) {
                unset($params['p']);
                return $context->link->getModuleLink('ph_simpleblog', 'category', $params);
            }

            return $context->link->getModuleLink('ph_simpleblog', 'categorypage', $params);
        }

        if ($type == 'author') {
            $params['rewrite'] = $rewrite;
            if ($page_nb == 1) {
                unset($params['p']);
                return $context->link->getModuleLink('ph_simpleblog', 'author', $params);
            }

            return $context->link->getModuleLink('ph_simpleblog', 'authorpage', $params);
        }

        if (Tools::getValue('y', 0)) {
            $additionalParams['y'] = (int) Tools::getValue('y');
        }

        if ($page_nb > 1) {
            return $context->link->getModuleLink('ph_simpleblog', 'page', array_merge($params, $additionalParams));
        }

        return $context->link->getModuleLink('ph_simpleblog', 'list', $additionalParams);
    }

    public static function getPaginationLink($nb = false, $sort = false, $pagination = true, $array = false)
    {
        $vars = [];
        $vars_nb = ['n', 'search_query'];
        $vars_sort = ['orderby', 'orderway'];
        $vars_pagination = ['p'];
        $url = ph_simpleblog::myRealUrl();

        foreach ($_GET as $k => $value) {
            if (Configuration::get('PS_REWRITING_SETTINGS') && ($k == 'isolang' || $k == 'id_lang')) {
                continue;
            }
            $if_nb = (!$nb || ($nb && !in_array($k, $vars_nb)));
            $if_sort = (!$sort || ($sort && !in_array($k, $vars_sort)));
            $if_pagination = (!$pagination || ($pagination && !in_array($k, $vars_pagination)));
            if ($if_nb && $if_sort && $if_pagination) {
                if (!is_array($value)) {
                    $vars[urlencode($k)] = $value;
                } else {
                    foreach (explode('&', http_build_query([$k => $value], '', '&')) as $key => $val) {
                        $data = explode('=', $val);
                        $vars[urldecode($data[0])] = $data[1];
                    }
                }
            }
        }

        if (!$array) {
            if (count($vars)) {
                return $url . ((Configuration::get('PS_REWRITING_SETTINGS') == 1) ? '?' : '&') . http_build_query($vars, '', '&');
            } else {
                return $url;
            }
        }

        $vars['requestUrl'] = $url;

        return $vars;
    }

    public static function deleteCover($id_simpleblog_post)
    {
        $post = new SimpleBlogPost((int) $id_simpleblog_post);

        if (!Validate::isLoadedObject($post)) {
            return;
        }

        $tmp_location = _PS_TMP_IMG_DIR_ . 'ph_simpleblog_' . $post->id . '.' . $post->cover;
        if (file_exists($tmp_location)) {
            @unlink($tmp_location);
        }

        $orig_location = _PS_MODULE_DIR_ . 'ph_simpleblog/covers/' . $post->id . '.' . $post->cover;
        $thumb = _PS_MODULE_DIR_ . 'ph_simpleblog/covers/' . $post->id . '-thumb.' . $post->cover;
        $thumbWide = _PS_MODULE_DIR_ . 'ph_simpleblog/covers/' . $post->id . '-wide.' . $post->cover;

        if (file_exists($orig_location)) {
            @unlink($orig_location);
        }

        if (file_exists($thumb)) {
            @unlink($thumb);
        }

        if (file_exists($thumbWide)) {
            @unlink($thumbWide);
        }

        Db::getInstance()->update('simpleblog_post', ['cover' => ''], 'id_simpleblog_post = ' . $post->id);

        return true;
    }

    public static function deleteFeatured($id_simpleblog_post)
    {
        $post = new SimpleBlogPost((int) $id_simpleblog_post);

        if (!Validate::isLoadedObject($post)) {
            return;
        }

        $tmp_location = _PS_TMP_IMG_DIR_ . 'ph_simpleblog_' . $post->id . '.' . $post->featured;
        if (file_exists($tmp_location)) {
            @unlink($tmp_location);
        }

        $orig_location = _PS_MODULE_DIR_ . 'ph_simpleblog/featured/' . $post->id . '.' . $post->featured;

        if (file_exists($orig_location)) {
            @unlink($orig_location);
        }

        Db::getInstance()->update('simpleblog_post', ['featured' => ''], 'id_simpleblog_post = ' . $post->id);

        return true;
    }

    public static function regenerateThumbnails()
    {
        $posts = self::getAllAvailablePosts(Context::getContext()->language->id);

        if (!$posts) {
            return;
        }

        foreach ($posts as $post) {
            if (isset($post['cover']) && !empty($post['cover'])) {
                $tmp_location = _PS_TMP_IMG_DIR_ . 'ph_simpleblog_' . $post['id_simpleblog_post'] . '.' . $post['cover'];
                if (file_exists($tmp_location)) {
                    @unlink($tmp_location);
                }

                $orig_location = _PS_MODULE_DIR_ . 'ph_simpleblog/covers/' . $post['id_simpleblog_post'] . '.' . $post['cover'];
                $thumbLoc = _PS_MODULE_DIR_ . 'ph_simpleblog/covers/' . $post['id_simpleblog_post'] . '-thumb.' . $post['cover'];
                $thumbWideLoc = _PS_MODULE_DIR_ . 'ph_simpleblog/covers/' . $post['id_simpleblog_post'] . '-wide.' . $post['cover'];

                if (file_exists($thumbLoc)) {
                    @unlink($thumbLoc);
                }

                if (file_exists($thumbWideLoc)) {
                    @unlink($thumbWideLoc);
                }

                $thumbX = Configuration::get('PH_BLOG_THUMB_X');
                $thumbY = Configuration::get('PH_BLOG_THUMB_Y');

                $thumb_wide_X = Configuration::get('PH_BLOG_THUMB_X_WIDE');
                $thumb_wide_Y = Configuration::get('PH_BLOG_THUMB_Y_WIDE');

                $thumbMethod = Configuration::get('PH_BLOG_THUMB_METHOD');

                try {
                    $thumb = PhpThumbFactory::create($orig_location);
                    $thumbWide = PhpThumbFactory::create($orig_location);
                } catch (Exception $e) {
                    continue;
                }

                if ($thumbMethod == '1') {
                    $thumb->adaptiveResize($thumbX, $thumbY);
                    $thumbWide->adaptiveResize($thumb_wide_X, $thumb_wide_Y);
                } elseif ($thumbMethod == '2') {
                    $thumb->cropFromCenter($thumbX, $thumbY);
                    $thumbWide->cropFromCenter($thumb_wide_X, $thumb_wide_Y);
                }

                $thumb->save($thumbLoc);
                $thumbWide->save($thumbWideLoc);

                unset($thumb);
                unset($thumbWide);

                // regenerate gallery
                $gallery = SimpleBlogPostImage::getAllById((int) $post['id_simpleblog_post']);
                if ($gallery) {
                    $gallery_dir = _SIMPLEBLOG_GALLERY_DIR_;
                    foreach ($gallery as $image) {
                        $fileName = explode('-', $image['image'], 3)[2];
                        if (!$fileName) {
                            continue;
                        }
                        $destFiles = [
                            'original' => $gallery_dir . $image['id_simpleblog_post_image'] . '-' . $image['id_simpleblog_post'] . '-' . $fileName . '.jpg',
                            'thumbnail' => $gallery_dir . $image['id_simpleblog_post_image'] . '-' . $image['id_simpleblog_post'] . '-' . $fileName . '-thumb.jpg',
                            'square' => $gallery_dir . $image['id_simpleblog_post_image'] . '-' . $image['id_simpleblog_post'] . '-' . $fileName . '-square.jpg',
                            'wide' => $gallery_dir . $image['id_simpleblog_post_image'] . '-' . $image['id_simpleblog_post'] . '-' . $fileName . '-wide.jpg',
                        ];

                        try {
                            $thumb = PhpThumbFactory::create($destFiles['original']);
                            $square = PhpThumbFactory::create($destFiles['original']);
                            $wide = PhpThumbFactory::create($destFiles['original']);
                        } catch (Exception $e) {
                            echo $e;
                        }
                        if ($thumbMethod == '1') {
                            $thumb->adaptiveResize($thumbX, $thumbY);
                            $wide->adaptiveResize($thumb_wide_X, $thumb_wide_Y);
                        } elseif ($thumbMethod == '2') {
                            $thumb->cropFromCenter($thumbX, $thumbY);
                            $wide->cropFromCenter($thumb_wide_X, $thumb_wide_Y);
                        }

                        $square->adaptiveResize(800, 800);

                        $thumb->save($destFiles['thumbnail']);
                        $square->save($destFiles['square']);
                        $wide->save($destFiles['wide']);
                    }
                }
            }
        }
    }

    public static function changeRating($way = 'up', $id_simpleblog_post = false)
    {
        if (!in_array($way, ['up', 'down'])) {
            return false;
        }

        if ($way == 'up') {
            $sql = 'UPDATE `' . _DB_PREFIX_ . 'simpleblog_post` SET `likes` = `likes` + 1 WHERE id_simpleblog_post = ' . (int) $id_simpleblog_post;
        }

        if ($way == 'down') {
            $sql = 'UPDATE `' . _DB_PREFIX_ . 'simpleblog_post` SET `likes` = `likes` - 1 WHERE id_simpleblog_post = ' . (int) $id_simpleblog_post;
        }

        Db::getInstance()->Execute($sql);

        return Db::getInstance()->ExecuteS('SELECT `likes` FROM `' . _DB_PREFIX_ . 'simpleblog_post` WHERE id_simpleblog_post = ' . (int) $id_simpleblog_post);
    }

    public function increaseViewsNb()
    {
        return Db::getInstance()->Execute('UPDATE `' . _DB_PREFIX_ . 'simpleblog_post` SET `views` = `views` + 1 WHERE id_simpleblog_post = ' . (int) $this->id_simpleblog_post);
    }

    public function isAccessGranted()
    {
        if ($userGroups = Context::getContext()->customer->getGroups()) {
            if (!isset($this->id_simpleblog_post)) {
                return false;
            }

            $tmpLinkGroups = unserialize($this->access);
            $linkGroups = [];

            foreach ($tmpLinkGroups as $groupID => $status) {
                if ($status) {
                    $linkGroups[] = $groupID;
                }
            }

            $intersect = array_intersect($userGroups, $linkGroups);
            if (count($intersect)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function getRelatedProducts($ids)
    {
        if (!$ids) {
            return false;
        }

        $context = Context::getContext();
        $id_lang = $context->language->id;

        $front = true;
        if (!in_array($context->controller->controller_type, ['front', 'modulefront'])) {
            $front = false;
        }

        $groups = FrontController::getCurrentCustomerGroups();
        $sql_groups = (count($groups) ? 'IN (' . implode(',', $groups) . ')' : '= 1');

        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, MAX(product_attribute_shop.id_product_attribute) id_product_attribute,
                    pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`,
                    pl.`name`, MAX(image_shop.`id_image`) id_image, il.`legend`, m.`name` AS manufacturer_name,
                    DATEDIFF(
                        p.`date_add`,
                        DATE_SUB(
                            NOW(),
                            INTERVAL ' . (Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20) . ' DAY
                        )
                    ) > 0 AS new
                FROM `' . _DB_PREFIX_ . 'product` p
                ' . Shop::addSqlAssociation('product', 'p') . '
                LEFT JOIN ' . _DB_PREFIX_ . 'product_attribute pa ON (pa.id_product = p.id_product)
                ' . Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1') . '
                ' . Product::sqlStock('p', 0, false, $context->shop) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (
                    p.`id_product` = pl.`id_product`
                    AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl') . '
                )
                LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product`)' .
                Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1') . '
                LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $id_lang . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
                WHERE product_shop.`active` = 1
                ' . ($front ? ' AND p.`visibility` IN ("both", "catalog")' : '') . '
                AND p.`id_product` IN (' . $ids . ')
                AND p.`id_product` IN (
                    SELECT cp.`id_product`
                    FROM `' . _DB_PREFIX_ . 'category_group` cg
                    LEFT JOIN `' . _DB_PREFIX_ . 'category_product` cp ON (cp.`id_category` = cg.`id_category`)
                    WHERE cg.`id_group` ' . $sql_groups . '
                )
                GROUP BY product_shop.id_product
                ORDER BY `pl`.name ASC';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (!$result) {
            return false;
        }

        return Product::getProductsProperties($id_lang, $result);
    }

    public static function checkIfAllowComments($flag)
    {
        $allow_comments = false;

        switch ($flag) {
            case 1:
                $allow_comments = true;
                break;

            case 2:
                $allow_comments = false;
                break;

            case 3:
                $allow_comments = Configuration::get('PH_BLOG_COMMENT_ALLOW');
                break;

            default:
                $allow_comments = false;
        }

        return $allow_comments;
    }

    /**
     * Gets previous post if available
     *
     * @return array previous blog post
     */
    public function getPreviousPost()
    {
        $finder = new BlogPostsFinder();
        $finder->setLimit(1);
        $finder->setIdCategory($this->id_simpleblog_category);
        $finder->setCustomWhere($finder->getTablePrefix() . 'date_add < "' . $this->date_add . '"');
        $post = $finder->findPosts();

        return $post;
    }

    /**
     * Gets next post if available
     *
     * @return array next blog post
     */
    public function getNextPost()
    {
        $finder = new BlogPostsFinder();
        $finder->setLimit(1);
        $finder->setOrderWay('ASC');
        $finder->setIdCategory($this->id_simpleblog_category);
        $finder->setCustomWhere($finder->getTablePrefix() . 'date_add > "' . $this->date_add . '"');
        $post = $finder->findPosts();

        return $post;
    }

    /**
     * Check if post allows to send comments.
     *
     * @return bool allowing comments status
     */
    public function allowComments()
    {
        return self::checkIfAllowComments($this->allow_comments);
    }

    public function getPostCategory()
    {
        return new SimpleBlogCategory((int) $this->id_simpleblog_category, (int) Context::getContext()->language->id);
    }

    public function getPostType()
    {
        return new SimpleBlogPostType((int) $this->id_simpleblog_post_type, (int) Context::getContext()->language->id);
    }

    public function authorForJsonLd(){

        if(isset($this->author->firstname)){
            return [
                '@type' =>  'Person',
                'name' => $this->author->firstname . ' '. $this->author->lastname,
                'url' => $this->author->getUrl()
            ];
        } else {
            return [
                '@type' =>  'Person',
                'name' => $this->author,
            ];
        }

    }

    public function renderJsonLd()
    {
        $context = Context::getContext();


        $json = [];
        $json['@context'] = 'http://schema.org';
        $json['@type'] = 'BlogPosting';
        $json['headline'] = $this->title;
        $json['genre'] = $this->category;
        $json['editor'] = $this->authorForJsonLd();
        if (isset($this->tags_list)) {
            $json['keywords'] = implode(' ', $this->tags_list);
        }
        $json['wordcount'] = strlen(strip_tags($this->content));
        $json['publisher']['@type'] = 'Organization';
        $json['publisher']['name'] = Configuration::get('PS_SHOP_NAME');
        $json['publisher']['logo']['@type'] = 'ImageObject';
        $json['publisher']['logo']['url'] = (Configuration::get('PS_LOGO'))
            ? rtrim($context->shop->getBaseUrl(true, false), '/') . _PS_IMG_ . Configuration::get('PS_LOGO')
            : '';

        $json['url'] = $this->url;
        $json['datePublished'] = $this->date_add;
        $json['dateCreated'] = $this->date_add;
        if ((method_exists('Validate', 'isDateOrNull') && Validate::isDateOrNull($this->date_upd))) {
            $json['dateModified'] = $this->date_add;
        } else {
            $json['dateModified'] = $this->date_upd;
        }
        $json['description'] = strip_tags($this->short_content);
        $json['articleBody'] = strip_tags($this->content);
        $json['mainEntityOfPage'] = $context->shop->getBaseURL();

        $json['author'] = $this->authorForJsonLd();
       

        $json['commentCount'] = $this->comments;
        $json['discussionUrl'] = $this->url;

        if (!empty($this->banner)) {
            $json['image'] = rtrim($context->shop->getBaseUrl(true, false), '/') . $this->banner;
        } elseif (!empty($this->featured_image)) {
            $json['image'] = rtrim($context->shop->getBaseUrl(true, false), '/') . $this->featured_image;
        }

        return json_encode($json);
    }
}
