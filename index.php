<?php
require_once 'interface/categoryInterface.php';
require_once 'model/categoryModel.php';
require_once 'controller/categoryController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'index';

$controller = new CategoryController();

// Xử lý yêu cầu
switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'create':
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->create($data);
        }
        break;
    case 'update':
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['editid'];
            $data = [
                'name_cate' => $_POST['name_cate'],
                'parent_id' => $_POST['parent_id']
            ];
            $controller->update($id, $data);
        }
        break;
    case 'delete':
        $id = $_GET['deleteid'];
        $controller->delete($id);
        break;
    case 'search':
            if(isset($_GET['key']) && $_GET['key'] != ''){
                $key = $_GET['key'];
                $controller->search($key);
            }else{
                header('Location: index.php');
                exit();
            }
        break;
    default:
        echo '404 Page Not Found';
        break;
}
