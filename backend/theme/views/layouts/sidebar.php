<aside class="main-sidebar sidebar-dark-blue elevation-4">
    <!-- Brand Logo -->
    <a href="index.php?r=site/index" class="brand-link">
<!--        <img src="--><?php //echo Yii::$app->request->baseUrl; ?><!--/uploads/logo/narono_logo.png" alt="Narono" class="brand-image">-->
<!--        <span class="brand-text font-weight-light">VORAPAT</span>-->
        <span class="brand-text font-weight-light">NARONO</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="index.php?r=site/index" class="nav-link site">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            ภาพรวมระบบ
                            <!--                                <i class="right fas fa-angle-left"></i>-->
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview has-sub">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            ข้อมูลบริษัท
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php //if (\Yii::$app->user->can('company/index')): ?>
                            <li class="nav-item">
                                <a href="index.php?r=company/index" class="nav-link company">
                                    <i class="far fa-circlez nav-icon"></i>
                                    <p>บริษัท</p>
                                </a>
                            </li>
                        <?php //endif; ?>
                    </ul>
                </li>
                <?php if (\Yii::$app->user->can('mainconfig/index')): ?>
                    <li class="nav-item has-treeview has-sub">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                ตั้งค่าทั่วไป
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="index.php?r=mainconfig" class="nav-link mainconfig">
                                    <i class="far fa-file-import nav-icon"></i>
                                    <p>Import Master</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?r=sequence" class="nav-link sequence">
                                    <i class="far fa-file-import nav-icon"></i>
                                    <p>เลขที่เอกสาร</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <li class="nav-item has-treeview has-sub">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>
                            ข้อมูลน้ำมัน
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php //if (\Yii::$app->user->can('warehouse/index')): ?>
                            <li class="nav-item">
                                <a href="index.php?r=fueltype/index" class="nav-link fueltype">
                                    <i class="far fa-circlez nav-icon"></i>
                                    <p>ประเภทน้ำมัน</p>
                                </a>
                            </li>
                        <?php //endif; ?>
                        <?php //if (\Yii::$app->user->can('location/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=fuel" class="nav-link fuel">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    น้ำมัน
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
                            </a>
                        </li>
                        <?php //endif; ?>
                        <?php //if (\Yii::$app->user->can('location/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=fueldailyprice" class="nav-link fueldailyprice">
                                <i class="far fa-oil-can nav-icon"></i>
                                <p>
                                    ราคาน้ำมัน
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
                            </a>
                        </li>
                        <?php //endif; ?>



                    </ul>
                </li>
                <li class="nav-item has-treeview has-sub">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>
                            ข้อมูลรถ
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php //if (\Yii::$app->user->can('producttype/index')): ?>
                            <li class="nav-item">
                                <a href="index.php?r=cartype/index" class="nav-link cartype">
                                    <i class="far fa-circlez nav-icon"></i>
                                    <p>ประเภทรถ</p>
                                </a>
                            </li>
                        <?php //endif; ?>
                        <?php // if (\Yii::$app->user->can('productgroup/index')): ?>
                            <li class="nav-item">
                                <a href="index.php?r=car" class="nav-link car">
                                    <i class="far fa-circlez nav-icon"></i>
                                    <p>
                                        รถ
                                        <!--                                <span class="right badge badge-danger">New</span>-->
                                    </p>
                                </a>
                            </li>
                        <?php //endif; ?>

                    </ul>
                </li>
                <li class="nav-item has-treeview has-sub">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            ลูกค้า
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php //if (\Yii::$app->user->can('customergroup/index')): ?>
                            <li class="nav-item">
                                <a href="index.php?r=customergroup/index" class="nav-link customergroup">
                                    <i class="far fa-circlez nav-icon"></i>
                                    <p>กลุ่มลูกค้า</p>
                                </a>
                            </li>
                        <?php //endif; ?>
                        <?php //if (\Yii::$app->user->can('customertype/index')): ?>
<!--                            <li class="nav-item">-->
<!--                                <a href="index.php?r=customertype/index" class="nav-link customertype">-->
<!--                                    <i class="far fa-circlez nav-icon"></i>-->
<!--                                    <p>ประเภทลูกค้า</p>-->
<!--                                </a>-->
<!--                            </li>-->
                        <?php //endif; ?>
                        <?php //if (\Yii::$app->user->can('customers/index')): ?>
                            <li class="nav-item">
                                <a href="index.php?r=customer" class="nav-link customer">
                                    <i class="far fa-circlez nav-icon"></i>
                                    <p>
                                        ลูกค้า
                                        <!--                                <span class="right badge badge-danger">New</span>-->
                                    </p>
                                </a>
                            </li>
                        <?php //endif; ?>

                    </ul>
                </li>
                <li class="nav-item has-treeview has-sub">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            พนักงาน
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php //if (\Yii::$app->user->can('position/index')): ?>
                            <li class="nav-item">
                                <a href="index.php?r=position/index" class="nav-link position">
                                    <i class="far fa-circlez nav-icon"></i>
                                    <p>ตำแหน่ง</p>
                                </a>
                            </li>
                        <?php //endif; ?>
                        <?php //if (\Yii::$app->user->can('employee/index')): ?>
                            <li class="nav-item">
                                <a href="index.php?r=employee/index" class="nav-link employee">
                                    <i class="far fa-circlez nav-icon"></i>
                                    <p>พนักงาน</p>
                                </a>
                            </li>
                        <?php //endif; ?>
                    </ul>
                </li>
                <li class="nav-item has-treeview has-sub">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>
                            จัดการใบงาน
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php //if (\Yii::$app->user->can('position/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=dropoffplace/index" class="nav-link dropoffplace">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>จัดการจุดขึ้นสินค้า</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=item/index" class="nav-link item">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>ของนำกลับ</p>
                            </a>
                        </li>

                        <?php //endif; ?>
                        <?php //if (\Yii::$app->user->can('position/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=routeplan/index" class="nav-link routeplan">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>จัดการปลายทาง</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=workqueue/index" class="nav-link workqueue">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>จัดคิวงาน</p>
                            </a>
                        </li>
                        <?php //endif; ?>

                    </ul>
                </li>
                <li class="nav-item has-treeview has-sub">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            รายงาน
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if (\Yii::$app->user->can('salecomreport/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=salecomreport" class="nav-link salecomreport">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>รายงานค่าคอมฯ</p>
                            </a>
                        </li>
                        <?php endif;?>

                    </ul>
                </li>
                <?php // if (isset($_SESSION['user_group_id'])): ?>
                <?php //if ($_SESSION['user_group_id'] == 1): ?>
                <?php //if (\Yii::$app->user->identity->username == 'iceadmin'): ?>
                    <li class="nav-item has-treeview has-sub">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                ผู้ใช้งาน
                                <i class="fas fa-angle-left right"></i>
                                <!--                                <span class="badge badge-info right">6</span>-->
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php //if (\Yii::$app->user->can('usergroup/index')): ?>
                                <li class="nav-item">
                                    <a href="index.php?r=usergroup" class="nav-link usergroup">
                                        <i class="far fa-circlez nav-icon"></i>
                                        <p>กลุ่มผู้ใช้งาน</p>
                                    </a>
                                </li>
                            <?php //endif; ?>
                            <?php //if (\Yii::$app->user->can('user/index')): ?>
                                <li class="nav-item">
                                    <a href="index.php?r=user" class="nav-link user">
                                        <i class="far fa-circlez nav-icon"></i>
                                        <p>ผู้ใช้งาน</p>
                                    </a>
                                </li>
                            <?php //endif;?>

                            <?php //if (\Yii::$app->user->can('authitem/index')): ?>
                                <li class="nav-item">
                                    <a href="index.php?r=authitem" class="nav-link auth">
                                        <i class="far fa-circlez nav-icon"></i>
                                        <p>สิทธิ์การใช้งาน</p>
                                    </a>
                                </li>
                            <?php //endif;?>

                        </ul>
                    </li>
                <?php //if (\Yii::$app->user->can('dbbackup/backuplist')): ?>
                    <li class="nav-item has-treeview has-sub">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-database"></i>
                            <p>
                                สำรองข้อมูล
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="index.php?r=dbbackup/backuplist" class="nav-link dbbackup">
                                    <i class="far fa-file-archive nav-icon"></i>
                                    <p>สำรองข้อมูล</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?r=dbrestore/restorepage" class="nav-link dbrestore">
                                    <i class="fa fa-upload nav-icon"></i>
                                    <p>กู้คืนข้อมูล</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php //endif;?>
                <?php //endif; ?>
                <?php //endif; ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

