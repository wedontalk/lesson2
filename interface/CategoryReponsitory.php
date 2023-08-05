<?php

    require_once 'model/database.php';

    abstract class CategoryReponsitory implements CategoryInterface
    {
        protected $model;
        public function __construct()
        {
            $database = new Database();
            $this->model = $database->connection_database();
        }

        public function getPaginate($startIndex, $perPage){
            $sql = "SELECT * FROM category ORDER BY parent_id ASC LIMIT $startIndex, $perPage";
            $stmt = $this->model->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getAll()
        {
            $sql = "SELECT * FROM category";
            $stmt = $this->model->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        public function find($id)
        {
            $stmt = $this->model->prepare('SELECT * FROM category WHERE id = :id');
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create($data)
        {
            $stmt = $this->model->prepare('INSERT INTO category (name_cate, parent_id) VALUES (:name_cate, :parent_id)');
            $stmt->execute([
                ':name_cate' => $data['name_cate'],
                ':parent_id' => $data['parent_id']
            ]);
    
            return $this->model->lastInsertId();
        }

        public function checkparentid($id){
            $table = "SELECT * FROM category WHERE parent_id = '$id' LIMIT 1";
            $stmt = $this->model->prepare($table);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function update($id, $data)
        {
            $stmt = $this->model->prepare('UPDATE category SET name_cate = :name_cate, parent_id = :parent_id WHERE id = :id');
            $stmt->execute([
                ':name_cate' => $data['name_cate'],
                ':parent_id' => $data['parent_id'],
                ':id' => $id
            ]);
    
            return $stmt->rowCount();
        }

        public function delete($id){
            $stmt = $this->model->prepare('DELETE FROM category WHERE id = :id');
            $stmt->execute([':id' => $id]);
            
            return $stmt->rowCount();
        }

        public function search($key){
            $stmt = $this->model->prepare("SELECT * FROM category where name_cate like '%$key%' ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


    }

?>