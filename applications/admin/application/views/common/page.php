<div class="row">
    <div class="col-sm-6">
        <div class="dataTables_info" id="sample-table-2_info">
            共<a class="blue"><?php echo $data_count;?></a>条记录，当前显示第&nbsp;<a class="blue"><?php echo $page;?>&nbsp;</a>页
        </div>
    </div>
    <div class="col-sm-6">
        <div class="dataTables_paginate paging_bootstrap">
            <ul class="pagination">
                <?php echo $pagestr;?>
            </ul>
        </div>
    </div>
</div>
