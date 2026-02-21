<!-- ======= Sidebar Admin ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_dash.php') ? '' : 'collapsed'; ?>" href="admin_dash.php">
                <i class="bi bi-speedometer2"></i>
                <span>Admin Dashboard</span>
            </a>
        </li><!-- End Admin Dashboard Nav -->

        <li class="nav-heading">การจัดการผู้ใช้งาน</li>

        <li class="nav-item">
            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_users.php') ? '' : 'collapsed'; ?>" href="admin_users.php">
                <i class="bi bi-people"></i>
                <span>จัดการผู้ใช้งาน</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_change_password.php') ? '' : 'collapsed'; ?>" href="admin_change_password.php">
                <i class="bi bi-key"></i>
                <span>เปลี่ยนรหัสผ่านผู้ใช้</span>
            </a>
        </li>

        <li class="nav-heading">รายงานและสรุป</li>

        <li class="nav-item">
            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_reports.php') ? '' : 'collapsed'; ?>" href="admin_reports.php">
                <i class="bi bi-file-earmark-text"></i>
                <span>สรุปใบกันเงิน</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_projects.php') ? '' : 'collapsed'; ?>" href="admin_projects.php">
                <i class="bi bi-folder"></i>
                <span>สรุปโครงการ</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_budget.php') ? '' : 'collapsed'; ?>" href="admin_budget.php">
                <i class="bi bi-cash-stack"></i>
                <span>สรุปงบประมาณ</span>
            </a>
        </li>

        <li class="nav-heading">ข้อมูลอ้างอิง</li>

        <li class="nav-item">
            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'middle_price.php') ? '' : 'collapsed'; ?>" href="middle_price.php">
                <i class="bi bi-calculator"></i>
                <span>ราคากลาง</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_all_requests.php') ? '' : 'collapsed'; ?>" href="admin_all_requests.php">
                <i class="bi bi-list-ul"></i>
                <span>รายการกันเงินทั้งหมด</span>
            </a>
        </li>

        <li class="nav-heading">ระบบ</li>

        <li class="nav-item">
            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_settings.php') ? '' : 'collapsed'; ?>" href="admin_settings.php">
                <i class="bi bi-gear"></i>
                <span>ตั้งค่าระบบ</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="sign_out.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>ออกจากระบบ</span>
            </a>
        </li><!-- End Logout Nav -->

    </ul>

</aside><!-- End Sidebar-->
