<aside class="main-sidebar sidebar-dark-blue elevation-4">
    <!-- Brand Logo -->
    <a href="index.php?r=site/index" class="brand-link">
<!--        <img src="--><?php //echo Yii::$app->request->baseUrl; ?><!--/uploads/logo/narono_logo.png" alt="Narono" class="brand-image">-->
<!--        <span class="brand-text font-weight-light">VORAPAT</span>-->
        <span class="brand-text font-weight-light">NARONO</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <?php if (!isset($_SESSION['driver_login'])): ?>
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
                        <?php //if (\Yii::$app->user->can('company/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=location/index" class="nav-link location">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>สำนักงาน</p>
                            </a>
                        </li>
                        <?php //endif; ?>
                    </ul>
                </li>
                <?php if (1>0): ?>
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
                                <a href="index.php?r=workoptiontype" class="nav-link workoptiontype">
                                    <i class="far fa-file-import nav-icon"></i>
                                    <p>ประเภทงาน</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?r=costtitle" class="nav-link costtitle">
                                    <i class="far fa-file-import nav-icon"></i>
                                    <p>รายการค่าใช้จ่าย</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?r=paymentmethod" class="nav-link paymentmethod">
                                    <i class="far fa-file-import nav-icon"></i>
                                    <p>วิธีชำระเงิน</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?r=paymentterm" class="nav-link paymentterm">
                                    <i class="far fa-file-import nav-icon"></i>
                                    <p>เงื่อนไขชำระเงิน</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?r=doccontrol" class="nav-link doccontrol">
                                    <i class="far fa-file-import nav-icon"></i>
                                    <p>จัดการเอกสาร</p>
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
                            <a href="index.php?r=cityzone/index" class="nav-link cityzone">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>โซนพื้นที่</p>
                            </a>
                        </li>
                        <?php //endif; ?>
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
                        <?php //if (\Yii::$app->user->can('location/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=quotationtitle" class="nav-link quotationtitle">
                                <i class="far fa-oil-can nav-icon"></i>
                                <p>
                                    เสนอราคา
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
                            <a href="index.php?r=carbrand" class="nav-link carbrand">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    ยี่ห้อรถ
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
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
                        <li class="nav-item">
                            <a href="index.php?r=carloantrans" class="nav-link carloantrans">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    บันทึกชำระค่างวด
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
                            <a href="index.php?r=department/index" class="nav-link department">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>แผนก</p>
                            </a>
                        </li>
                        <?php //endif; ?>
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
                            <a href="index.php?r=cashrecord/index" class="nav-link cashrecord">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>บันทึกเงินสดย่อย</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=recieptrecord/index" class="nav-link recieptrecord">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>บันทึกรับเงิน</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=customer-po/index" class="nav-link customer-po">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>PO ลูกค้า</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=workqueue/index" class="nav-link workqueue">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>จัดคิวงาน</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=preinvoice/index" class="nav-link preinvoice">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>รวมบิล</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=customerinvoice/index" class="nav-link customerinvoice">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>วางบิล</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=costtitle/index" class="nav-link costtitle">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>รายการค่าใช้จ่าย</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="index.php?r=workqueueback/index" class="nav-link workqueueback">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>จัดคิวงาน (ของกลับ)</p>
                            </a>
                        </li>


                        <?php //endif; ?>

                    </ul>
                </li>
                <li class="nav-item has-treeview has-sub">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>
                            จัดการสต๊อกสินค้า
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php //if (\Yii::$app->user->can('customergroup/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=productgroup/index" class="nav-link productgroup">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>กลุ่มสินค้า/อะไหล่</p>
                            </a>
                        </li>
                        <?php //endif; ?>
                        <?php //if (\Yii::$app->user->can('customergroup/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=product" class="nav-link product">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>สินค้า/อะไหล่</p>
                            </a>
                        </li>
                        <?php //endif; ?>
                        <?php //if (\Yii::$app->user->can('customergroup/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=warehouse" class="nav-link warehouse">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>คลังสินค้า</p>
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
                            <a href="index.php?r=stocksum" class="nav-link stocksum">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    สินค้าคงเหลือ
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=stocktrans" class="nav-link stocktrans">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    ประวัติทำรายการ
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=vendorgroup" class="nav-link vendorgroup">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    กลุ่มผู้ขาย
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=vendor" class="nav-link vendor">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    ผู้ขาย
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=purchorder" class="nav-link purchorder">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    ใบสั่งซื้อ
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=journalissue" class="nav-link journalissue">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    เบิกสินค้า/อะไหล่
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=workorder" class="nav-link workorder">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    บันทึกแจ้งซ่อม
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=customerscore" class="nav-link customerscore">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    ประเมินผู้ขาย
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
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
                        <?php //if (\Yii::$app->user->can('salecomreport/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=report/workqueuedaily" class="nav-link workqueuedaily">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>รายงานประจำวัน</p>
                            </a>
                        </li>
                        <?php //endif;?>
                        <?php //if (\Yii::$app->user->can('salecomreport/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=cashrecordreport" class="nav-link cashrecordreport">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>รายงานสรุปรับเงิน</p>
                            </a>
                        </li>
                        <?php //endif;?>
                        <?php //if (\Yii::$app->user->can('salecomreport/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=report/report1" class="nav-link report">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>จำนวนเที่ยววิ่ง</p>
                            </a>
                        </li>
                        <?php //endif;?>

                        <?php //if (\Yii::$app->user->can('salecomreport/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=report/report2" class="nav-link report">
                                <i class="far fa-circlez nav-icon"></i>
                                <p> สรุปน้ำมันแยกคัน </p>
                            </a>
                        </li>
                        <?php //endif;?>
                        <?php //if (\Yii::$app->user->can('salecomreport/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=carsummaryreport/index" class="nav-link carsummaryreport">
                                <i class="far fa-circlez nav-icon"></i>
                                <p> รายงานค่าเที่ยว </p>
                            </a>
                        </li>
                        <?php //endif;?>
                        <?php //if (\Yii::$app->user->can('salecomreport/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=cashrecordreportdaily/index" class="nav-link cashrecordreportdaily">
                                <i class="far fa-circlez nav-icon"></i>
                                <p> รายละเอียดเงินสดย่อย </p>
                            </a>
                        </li>
                        <?php //endif;?>
                        <?php //if (\Yii::$app->user->can('salecomreport/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=cashreportdaily/index" class="nav-link cashreportdaily">
                                <i class="far fa-circlez nav-icon"></i>
                                <p> รายงานเงินสดย่อย </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=cashrecordsummary/index" class="nav-link cashrecordsummary">
                                <i class="far fa-circlez nav-icon"></i>
                                <p> กราฟสรุปเงินสดย่อย </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=custsummaryreport/index" class="nav-link custsummaryreport">
                                <i class="far fa-circlez nav-icon"></i>
                                <p> เที่ยววิ่งแยกลูกค้า </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=custdetailreport/index" class="nav-link custdetailreport">
                                <i class="far fa-circlez nav-icon"></i>
                                <p> เที่ยววิ่งระบุลูกค้า </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=custweightsummaryreport/index" class="nav-link custweightsummaryreport">
                                <i class="far fa-circlez nav-icon"></i>
                                <p> น้ำหนักแยกลูกค้า </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=carsummaryexport/index" class="nav-link carsummaryexport">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>Export ค่าเที่ยว</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=workqueuereport/report" class="nav-link workqueuereport">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>สรุปรอบขนส่ง</p>
                            </a>
                        </li>
                        <?php //endif;?>

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
                <li class="nav-item has-treeview has-sub">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-check-circle"></i>
                        <p>
                            ข้อมูลความปลอดภัย
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php //if (\Yii::$app->user->can('producttype/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=accidenttitle/index" class="nav-link accidenttitle">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>หัวข้ออุบัติเหตุ</p>
                            </a>
                        </li>
                        <?php //endif; ?>
                        <?php // if (\Yii::$app->user->can('productgroup/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=complaintitle/index" class="nav-link complaintitle">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    หัวข้อคอมเพลน
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
                            </a>
                        </li>
                        <?php //endif; ?>
                        <?php // if (\Yii::$app->user->can('productgroup/index')): ?>
                        <li class="nav-item">
                            <a href="index.php?r=accident/index" class="nav-link accident">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    บันทึกอุบัติเหตุ
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=complain/index" class="nav-link complain">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    บันทึกคอมเพลน
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=employeefine/index" class="nav-link employeefine">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    บันทึกค่าปรับ
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?r=alert/index" class="nav-link alert">
                                <i class="far fa-circlez nav-icon"></i>
                                <p>
                                    บันทึก Alert
                                    <!--                                <span class="right badge badge-danger">New</span>-->
                                </p>
                            </a>
                        </li>
                        <?php //endif; ?>

                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->

        <?php endif; ?>

    </div>
    <!-- /.sidebar -->
</aside>

