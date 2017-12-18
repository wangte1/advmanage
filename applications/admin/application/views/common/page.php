<div class="row">
    <div class="col-sm-6">
        <div class="dataTables_info" id="sample-table-2_info">
            共<a class="blue"><?php if(isset($data_count)){echo $data_count;}else{echo 0;}?></a>条记录，当前显示第&nbsp;<a class="blue"><?php if(isset($page)){echo $page;}else{echo 1;}?>&nbsp;</a>页
        </div>
    </div>
    <div class="col-sm-6">
        <div class="dataTables_paginate paging_bootstrap">
            <ul class="pagination">
                <?php if(isset($pagestr)){echo $pagestr;}?>
            </ul>
        </div>
    </div>
</div>
