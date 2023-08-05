<?php
// include_once(__DIR__.'./../interface/CategoryInterface.php');
require_once 'model/categoryModel.php';
class CategoryController{
    protected $model;

    public function __construct() {
        $this->model = new Category();
    }

    public function index() {
        $perPage = 10;
        $categories_page =  $this->model->getPaginate($startIndex = 0, $perPage);

        $categories = $this->model->getAll(); // Lấy danh sách danh mục từ model

        $totalCategories = count($categories); // Tổng số danh mục

        $totalPages = ceil($totalCategories / $perPage); // Tổng số trang

        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1; // Trang hiện tại

        $startIndex = ($currentPage - 1) * $perPage; // Chỉ mục bắt đầu của danh sách danh mục

        $endIndex = $startIndex + $perPage - 1; // Chỉ mục kết thúc của danh sách danh mục
        
        $pagedCategories = array_slice($categories, $startIndex, $perPage);

        // Xử lý dữ liệu để tạo cây danh mục
        $categoryTree = $this->buildCategoryTree($pagedCategories, 0, $startIndex, $perPage);
        // Hiển thị cây danh mục
        $htmlCategoryTree = $this->displayCategoryTree($categoryTree);

        require_once 'view/main.php';
    }

    public function search($key) {
        $perPage = 10;
 
        $categories = $this->model->search($key); // Lấy danh sách danh mục từ model

        $categories_page =  $this->model->getPaginate($startIndex = 0, $perPage);


        $totalCategories = count($categories); // Tổng số danh mục

        $totalPages = ceil($totalCategories / $perPage); // Tổng số trang

        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1; // Trang hiện tại

        $startIndex = ($currentPage - 1) * $perPage; // Chỉ mục bắt đầu của danh sách danh mục

        $endIndex = $startIndex + $perPage - 1; // Chỉ mục kết thúc của danh sách danh mục
        
        $pagedCategories = array_slice($categories, $startIndex, $perPage);

        // Xử lý dữ liệu để tạo cây danh mục
        $categoryTree = $this->buildCategoryTree($pagedCategories, 0, $startIndex, $perPage);
        // Hiển thị cây danh mục
        $htmlCategoryTree = $this->displayCategoryTree($pagedCategories);

        require_once 'view/main.php';
    }

    private function buildCategoryTree($pagedCategories, $parentId) {
        $categoryTree = array();

        foreach ($pagedCategories as $category) {
            if ($category['parent_id'] == $parentId) {
                $category['children'] = $this->buildCategoryTree($pagedCategories, $category['id']);
                $categoryTree[] = $category;
            }
        }

        return $categoryTree;
    }

    private function displayCategoryTree($categoryTree, $level = 0) {
        $html = '';
        foreach ($categoryTree as $category) {
            $html .='<tr>';
            $html .="<td>".$category['id']."</td>";
            $html .= "<td id='content'>".str_repeat('--', $level) . $category['name_cate'] . "</td>";
            $html .='<td>
            <a id="clickedit" 
            data-toggle="modal" 
            data-target="#editcategory" 
            data-id="'.$category['id'].'"
            data-name="'.$category['name_cate'].'"
            data-parent="'.$category['parent_id'].'"
            ><i class="fa fa-pencil" aria-hidden="true"></i></a>

            <button id="clickCopy"><i class="fa fa-files-o" aria-hidden="true"></i></button>

            <a onclick="deleteRecord('.$category['id'].')"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            </td>';
            $html .='</tr>';
    
            if (!empty($category['children'])) {
                $html .= $this->displayCategoryTree($category['children'], $level + 1);
            }
        }
        return $html;
    }

    public function find($id) {
        // lấy thông tin id
    }

    public function create($data) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name_cate' => $_POST['name_cate'],
                'parent_id' => $_POST['parent_id']
            ];
            $addcategory = $this->model->create($data);
            if($addcategory){
                header('Location: index.php');
            }
            exit;
        }
        require_once 'view/main.php';
    }


    public function update($id, $data) {
        $id = $_GET['editid'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name_cate' => $_POST['name_cate'],
                'parent_id' => $_POST['parent_id']
            ];
            $checkFindId = $this->model->find($id);
            $checkparentid= $this->model->checkparentid($id);
            // kiểm tra parent_id cha không được update thành parent_id con
            if($checkFindId['parent_id'] >= $data['parent_id'] || count($checkparentid) == 0){
                $this->model->update($id, $data);
                header('Location: index.php');
            }else{
                echo "Sửa thất bại";
            }
            exit;
        }

        // Lấy thông tin người dùng cần chỉnh sửa
        $user = $this->model->find($_GET['id']);

        // Hiển thị form chỉnh sửa người dùng
        include 'view/main.php';

    }

    public function delete($id) {
        $id = $_GET['deleteid'];
        $checkparentid= $this->model->checkparentid($id);
        if(count($checkparentid) == 0){
            $this->model->delete($id);
            header('Location: index.php?action=index');
        }else{
            echo "xóa thất bại";
        }
        exit;
    }
}

?>