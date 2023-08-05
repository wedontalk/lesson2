<?php
    interface CategoryInterface{

        public function getAll();

        public function getPaginate($startIndex, $perPage);
        
        public function search($key);

        public function find($id);
        
        public function checkparentid($id);

        public function create($data);

        public function update($id, $data);

        public function delete($id);
    }

?>