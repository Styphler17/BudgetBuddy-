<?php
/**
 * Admin Dashboard Controller
 */
class AdminDashboardController extends BaseController {
    
    public function __construct() {
        if (!isset($_SESSION['admin_id'])) {
            $this->redirect('/admin-login');
        }
    }

    public function index() {
        $userModel = new User();
        $adminModel = new Admin();
        
        $rawStats = $adminModel->getSystemStats();
        $stats = [
            'totalUsers' => $rawStats['totalUsers'] ?? 0,
            'totalAdmins' => count($adminModel->getAll()),
            'totalTransactions' => $rawStats['totalTransactions'] ?? 0,
            'totalCategories' => $rawStats['totalCategories'] ?? 0,
            'totalGoals' => $rawStats['totalGoals'] ?? 0,
            'totalAccounts' => $rawStats['totalAccounts'] ?? 0
        ];

        // Fetch users and admins for the respective tabs
        $users = $userModel->getAll(100);
        $admins = $adminModel->getAll();
        
        // Real logs from database
        $logs = $adminModel->getLogs(5); // Show only 5 on dashboard

        $this->render('admin/index', [
            'title' => 'Admin Dashboard',
            'layout' => 'admin',
            'stats' => $stats,
            'users' => $users,
            'admins' => $admins,
            'logs' => $logs
        ]);
    }

    public function users() {
        $userModel = new User();
        $users = $userModel->getAll();
        
        $this->render('admin/users', [
            'title' => 'User Management',
            'layout' => 'admin',
            'users' => $users
        ]);
    }

    public function userEdit($id) {
        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) $this->redirect('/admin/users');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'currency' => $_POST['currency'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            if ($userModel->update($id, $data)) {
                $adminModel = new Admin();
                $adminModel->logAction($_SESSION['admin_id'], 'update_user', 'user', $id, "Updated user: " . $data['name']);
                $this->redirect('/admin/users');
            }
        }

        $this->render('admin/user-edit', [
            'title' => 'Edit User',
            'layout' => 'admin',
            'user' => $user
        ]);
    }

    public function blog() {
        $blogModel = new Blog();
        $articles = $blogModel->getAll();
        
        $stats = [
            'total' => count($articles),
            'published' => count(array_filter($articles, function($a) { return $a['status'] === 'published'; })),
            'draft' => count(array_filter($articles, function($a) { return $a['status'] === 'draft'; })),
            'archived' => count(array_filter($articles, function($a) { return $a['status'] === 'archived'; })),
        ];

        $this->render('admin/blog', [
            'title' => 'Blog Management',
            'layout' => 'admin',
            'articles' => $articles,
            'stats' => $stats
        ]);
    }

    public function blogCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $blogModel = new Blog();
            $data = [
                'admin_id' => $_SESSION['admin_id'],
                'title' => $_POST['title'],
                'slug' => $_POST['slug'] ?: strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['title']))),
                'excerpt' => $_POST['excerpt'],
                'cover_image_url' => $_POST['cover_image_url'],
                'status' => $_POST['status'],
                'content' => $_POST['content'] ?? '',
                'tags' => $_POST['tags'],
                'reading_time' => (int)$_POST['reading_time']
            ];
            
            if ($blogModel->create($data)) {
                $adminModel = new Admin();
                $adminModel->logAction($_SESSION['admin_id'], 'create_blog', 'system', null, "Created post: " . $data['title']);
                $this->redirect('/admin/blog');
            }
        }

        $this->render('admin/blog-edit', [
            'title' => 'Create New Post',
            'layout' => 'admin',
            'mode' => 'create',
            'post' => []
        ]);
    }

    public function blogEdit($id) {
        $blogModel = new Blog();
        $post = $blogModel->findById($id);

        if (!$post) $this->redirect('/admin/blog');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'slug' => $_POST['slug'],
                'excerpt' => $_POST['excerpt'],
                'cover_image_url' => $_POST['cover_image_url'],
                'status' => $_POST['status'],
                'content' => $_POST['content'] ?? '',
                'tags' => $_POST['tags'],
                'reading_time' => (int)$_POST['reading_time']
            ];
            
            if ($blogModel->update($id, $data)) {
                $adminModel = new Admin();
                $adminModel->logAction($_SESSION['admin_id'], 'update_blog', 'system', $id, "Updated post: " . $data['title']);
                $this->redirect('/admin/blog');
            }
        }

        $this->render('admin/blog-edit', [
            'title' => 'Edit Post',
            'layout' => 'admin',
            'post' => $post,
            'mode' => 'edit'
        ]);
    }

    public function blogDelete($id) {
        $blogModel = new Blog();
        $adminModel = new Admin();
        $adminModel->logAction($_SESSION['admin_id'], 'delete_blog', 'system', $id, "Deleted post ID: " . $id);
        $blogModel->delete($id);
        $this->redirect('/admin/blog');
    }

    public function blogUpload() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
            $file = $_FILES['image'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('blog_') . '.' . $ext;
            $uploadDir = __DIR__ . '/../../public/blog/';
            
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $targetPath = $uploadDir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $url = BASE_URL . '/public/blog/' . $filename;
                $this->json(['url' => $url]);
            } else {
                $this->json(['error' => 'Failed to move uploaded file.'], 500);
            }
        }
    }

    public function profile() {
        $adminModel = new Admin();
        $admin = $adminModel->findByEmail($_SESSION['admin_email']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ];

            // Handle Admin Profile Picture Upload
            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'public/uploads/profile_pics/';
                $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
                $fileName = $_FILES['profile_pic']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $allowedExtensions = array('jpg', 'gif', 'png', 'jpeg', 'webp');

                if (in_array($fileExtension, $allowedExtensions)) {
                    $dest_path = $uploadDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $data['profile_pic'] = $newFileName;
                        $_SESSION['admin_profile_pic'] = $newFileName;
                    }
                }
            }

            if (!empty($_POST['password'])) {
                $data['password_hash'] = $_POST['password'];
            }

            if ($adminModel->update($admin['id'], $data)) {
                $_SESSION['admin_name'] = $data['name'];
                $_SESSION['admin_email'] = $data['email'];
                $adminModel->logAction($admin['id'], 'update_profile', 'system', $admin['id'], "Updated profile details");
                $this->redirect('/admin/profile');
            }
        }

        $this->render('admin/profile', [
            'title' => 'Profile Settings',
            'layout' => 'admin',
            'admin' => $admin
        ]);
    }

    public function logs() {
        $adminModel = new Admin();
        $logs = $adminModel->getLogs(100); // Show more on logs page

        $this->render('admin/logs', [
            'title' => 'System Logs',
            'layout' => 'admin',
            'logs' => $logs
        ]);
    }

    public function admins() {
        $adminModel = new Admin();
        $admins = $adminModel->getAll();
        
        $this->render('admin/admins', [
            'title' => 'Admin Management',
            'layout' => 'admin',
            'admins' => $admins
        ]);
    }

    public function adminEdit($id) {
        $adminModel = new Admin();
        $admins = $adminModel->getAll();
        $adminToEdit = null;
        foreach ($admins as $a) {
            if ($a['id'] == $id) {
                $adminToEdit = $a;
                break;
            }
        }

        if (!$adminToEdit) $this->redirect('/admin/admins');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'role' => $_POST['role']
            ];
            if (!empty($_POST['password'])) {
                $data['password_hash'] = $_POST['password'];
            }
            
            if ($adminModel->update($id, $data)) {
                $adminModel->logAction($_SESSION['admin_id'], 'update_admin', 'system', $id, "Updated admin: " . $data['name']);
                $this->redirect('/admin/admins');
            }
        }

        $this->render('admin/admin-edit', [
            'title' => 'Edit Admin',
            'layout' => 'admin',
            'admin' => $adminToEdit,
            'mode' => 'edit'
        ]);
    }

    public function adminCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminModel = new Admin();
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'role' => $_POST['role']
            ];
            
            if ($adminModel->create($data)) {
                $adminModel->logAction($_SESSION['admin_id'], 'create_admin', 'system', null, "Created admin: " . $data['name']);
                $this->redirect('/admin/admins');
            }
        }

        $this->render('admin/admin-edit', [
            'title' => 'Create New Admin',
            'layout' => 'admin',
            'mode' => 'create',
            'admin' => []
        ]);
    }
}
