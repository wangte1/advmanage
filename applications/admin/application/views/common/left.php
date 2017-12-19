<div class="sidebar" id="sidebar">
    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
            <button class="btn btn-success">
                <i class="icon-signal"></i>
            </button>

            <button class="btn btn-info">
                <i class="icon-pencil"></i>
            </button>

            <button class="btn btn-warning">
                <i class="icon-group"></i>
            </button>

            <button class="btn btn-danger">
                <i class="icon-cogs"></i>
            </button>
        </div>

        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
            <span class="btn btn-success"></span>

            <span class="btn btn-info"></span>

            <span class="btn btn-warning"></span>

            <span class="btn btn-danger"></span>
        </div>
    </div>

    <ul class="nav nav-list">
        <?php
        if($menu) {
            $i = 0;
            foreach($menu as $key=>$val) {
        ?>
        <li class="<?php if(isset($code) && $val['code'] == $code) { echo 'active open'; } ?>">
            <a href="#" class="dropdown-toggle">
                <i class="<?php echo $val['icon'];?>"></i>
                <span class="menu-text"><?php echo $key;?></span>
                <b class="arrow icon-angle-down"></b>
            </a>
            <ul class="submenu">
                <?php
                if($val['list']){
                foreach($val['list'] as $k=>$v){
                ?>
                 <li class="<?php if(isset($active) && $v['active'] == $active){ echo "active"; } ?>">
                    <a href="<?php echo $v['url'];?>">
                        <i class="icon-double-angle-right"></i>
                        <?php echo $v['name'];?>
                    </a>
                </li>
                <?php } $i++;}?>
            </ul>
        </li>
        <?php }}?>
    </ul>

    <div class="sidebar-collapse" id="sidebar-collapse">
        <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
    </div>
</div>

