<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? '' : 'collapsed'; ?>" href="dashboard.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'forms.php') ? '' : 'collapsed'; ?>" href="forms.php">
                <i class="bi bi-1-circle"></i>
                <span>ฟอร์มกันเงิน กัน</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'kan_lists.php' || basename($_SERVER['PHP_SELF']) == 'kan_details.php') ? '' : 'collapsed'; ?>" href="kan_lists.php">
                <i class="bi bi-2-circle"></i>
                <span>รายการกันเงิน กัน</span>
            </a>
        </li><!-- End Register Page Nav -->

        <li class="nav-item">
            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'middle_price.php') ? '' : 'collapsed'; ?>" href="middle_price.php">
                <i class="bi bi-3-circle"></i>
                <span>ราคากลาง</span>
            </a>
        </li><!-- End Middle Price Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="sign_out.php">
                <i class="bi bi-4-circle"></i>
                <span>ออกจากระบบ</span>
            </a>
        </li><!-- End Login Page Nav -->

    </ul>

</aside><!-- End Sidebar-->
