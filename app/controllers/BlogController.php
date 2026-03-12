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
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        
        $postsPerPage = 12; // SEO optimal: multiple of grid cols (4), balanced load speed
        $totalPosts = $this->blogModel->countPublished($search, $tag);
        $totalPages = ceil($totalPosts / $postsPerPage);
        
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
        $offset = ($page - 1) * $postsPerPage;

        $posts = $this->blogModel->getAllPublished($search, $tag, $postsPerPage, $offset);
        
        // Extract all tags from all published posts for the "Popular tags" section
        // We do this without pagination to get all tags
        $allPublishedPostsCount = $this->blogModel->countPublished();
        // Just fetch a reasonable amount of posts to extract tags from if database is huge
        // Or better yet, add a getTags method to the model. For now, keep current logic but optimized
        $postsForTags = $this->blogModel->getAllPublished('', '', 50); 
        $tags = [];
        foreach ($postsForTags as $p) {
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
            'featuredPost' => ($page === 1 && empty($search) && empty($tag)) ? ($posts[0] ?? null) : null,
            'otherPosts' => ($page === 1 && empty($search) && empty($tag)) ? array_slice($posts, 1) : $posts,
            'popularTags' => $popularTags,
            'currentSearch' => $search,
            'currentTag' => $tag,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts,
            'layout' => isset($_GET['ajax']) ? false : 'main'
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
