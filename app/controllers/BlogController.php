<?php
/**
 * Blog Controller
 */

class BlogController extends BaseController {
    
    private $blogModel;

    public function __construct() {
        $this->blogModel = new Blog();
    }

    /**
     * Blog Index Page (List all posts)
     */
    public function index() {
        $search = $_GET['search'] ?? '';
        $tag = $_GET['tag'] ?? '';
        
        $posts = $this->blogModel->getAllPublished($search, $tag);
        
        // Extract all tags from all published posts for the "Popular tags" section
        $allPosts = $this->blogModel->getAllPublished();
        $tags = [];
        foreach ($allPosts as $p) {
            if (!empty($p['tags'])) {
                foreach ($p['tags'] as $t) {
                    $t = trim($t);
                    if (!empty($t)) $tags[$t] = ($tags[$t] ?? 0) + 1;
                }
            }
        }
        arsort($tags);
        $popularTags = array_slice(array_keys($tags), 0, 8);

        $this->render('blog/index', [
            'title' => 'BudgetBuddy Blog | Money Moves That Matter',
            'posts' => $posts,
            'featuredPost' => $posts[0] ?? null,
            'otherPosts' => count($posts) > 1 ? array_slice($posts, 1) : [],
            'popularTags' => $popularTags,
            'currentSearch' => $search,
            'currentTag' => $tag
        ]);
    }

    /**
     * Blog Single View Page
     */
    public function view($slug) {
        $post = $this->blogModel->findBySlug($slug);
        
        if (!$post) {
            $this->redirect('/BudgetBuddy-/blog');
        }

        $this->render('blog/show', [
            'title' => $post['title'],
            'post' => $post
        ]);
    }
}
