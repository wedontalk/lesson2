<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bài test</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.css" integrity="sha512-72McA95q/YhjwmWFMGe8RI3aZIMCTJWPBbV8iQY3jy1z9+bi6+jHnERuNrDPo/WGYEzzNs4WdHNyyEr/yXJ9pA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<?php if(isset($alert)):?>
<div class="alert alert-primary" role="alert">
  <?php echo $alert?>
</div>
<?php endif;?>
<div class="container mt-3">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link" id="pills-product-tab" data-toggle="pill" href="#pills-product" role="tab" aria-controls="pills-product" aria-selected="true">products</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" id="pills-category-tab" data-toggle="pill" href="#pills-category" role="tab" aria-controls="pills-category" aria-selected="false">categories</a>
      </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
      <div class="tab-pane fade" id="pills-product" role="tabpanel" aria-labelledby="pills-product-tab">...</div>
      <div class="tab-pane fade show active" id="pills-category" role="tabpanel" aria-labelledby="pills-category-tab">
            <!-- search -->
            <div class="col-md-12 mt-5">
                <form role="search" action="index.php?action=search" method="GET">
                    <input type="hidden" name="action" value="search">
                    <div class="form-group">
                        <input type="text" class="form-control" name="key" value="<?php echo (isset($_GET["key"]) && $_GET["key"]!="" ? $_GET["key"]:"")?>" id="search" placeholder="Tìm Kiếm">
                    </div>
                </form>
            </div>
            <!-- end search -->
            <div class="col-md-12 d-inline-flex justify-content-between">
                <p>search found <?= count($categories)?> result</p>
                <!-- button modal -->
                <i class="fa fa-plus" aria-hidden="true" data-toggle="modal" data-target="#exampleModal" style="font-size:30px"></i>
            </div>
            <!-- table -->
            <div class="col-md-12 mt-5">
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Category Name</th>
                    <th scope="col">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $htmlCategoryTree; ?>
                </tbody>
            </table>
            <!-- end table -->
            <!-- paginate -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <?php 
                            if ($currentPage > 1) {
                                echo '<a class="page-link" href="?page=' . ($currentPage - 1) . '">Previous</a>';
                            }
                        ?>
                    </li>
                    <?php
                       for ($i = 1; $i <= $totalPages; $i++) {
                        echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                        }
                    ?>
                    <li class="page-item">
                        <?php
                            if ($currentPage < $totalPages) {
                                echo '<a class="page-link" href="?page=' . ($currentPage + 1) . '">Next</a>';
                            }
                        ?>
                    </li>
                </ul>
            </nav>
            <!-- end paginate -->
            </div>
      </div>
    </div>
</div>

<!-- Modal create -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add new category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="index.php?action=create">
                <div class="form-group">
                    <label for="exampleInputEmail1">Category Name</label>
                    <input type="text" class="form-control" name="name_cate" placeholder="Category Name">
                </div>
                <div class="form-group">
                    <label for="select_parent_id">parent category</label>
                    <select class="form-control" name="parent_id" id="select_parent_id">
                        <option value="">-- Rỗng --</option>
                        <?php foreach ($categories as $key => $value):?>
                        <option value="<?php echo $value['id']?>"><?php echo $value['name_cate']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <input type="submit" value="Create" class="btn btn-primary">
            </form>
        </div>
    </div>
  </div>
</div>

<!-- modal update -->
<div class="modal fade" id="editcategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit category</h5>
                <button type="button" id="clicknotedit" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <form id="updateEdit" method="POST">
                <div class="form-group">
                    <label for="exampleInputEmail1">Category Name</label>
                    <input type="text" class="form-control" name="name_cate" id="name_cate" placeholder="Category Name">
                </div>
                <div class="form-group">
                    <label for="select_parent_id">parent category</label>
                    <select class="form-control" name="parent_id" id="select_parent_id">
                        <option value="">-- Rỗng --</option>
                        <?php foreach ($categories as $key => $value):?>
                        <option value="<?php echo $value['id']?>"><?php echo $value['name_cate']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <input type="submit" value="update" class="btn btn-primary">
            </form>
        </div>
    </div>
  </div>
</div>
</body>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        jQuery(document).ready(function(){
            $(document).on('click', '#clickedit', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var nameCate = $(this).data('name');
                var nameepisode = $(this).data('parent');
  
                // console.log('chaning to"'+ showsource +'"');
                $('#name_cate').val(nameCate);
                $('#updateEdit').attr('action', 'index.php?action=update&editid='+id);
            });
            $('#clicknotedit').click(function(){
                    $('#name_cate').val('');
                    
            });
        })
    </script>
    <!-- em chưa hiểu phần copy này là gì nên em làm copy theo text của name category -->
    <script>
        var clickput = document.querySelectorAll("#clickCopy");
        var content = document.querySelectorAll("#content");
        for (let i = 0; i < clickput.length; i++) {
        const element = clickput[i];
            element.onclick = function() {copyToClipboard(content[i])}
        }
        function copyToClipboard(e) {
            var tempItem = document.createElement('input');
            tempItem.setAttribute('type', 'text');
            tempItem.setAttribute('display', 'none');
            
            let content = e.innerHTML;
            tempItem.setAttribute('value', content);
            document.body.appendChild(tempItem);
            
            tempItem.select();
            document.execCommand('Copy');

            tempItem.parentElement.removeChild(tempItem);
        }
    </script>
    <!-- delete -->
    <script>
        function deleteRecord(id) {
        var confirmDelete = confirm("Bạn có chắc chắn muốn xóa trường này ?");
        if (confirmDelete) {
            window.location = '?action=delete&deleteid='+id;
            };
        }
        
    </script>

</html>