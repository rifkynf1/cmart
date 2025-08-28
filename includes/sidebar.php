<?php
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$my_product_pages = ['my_product', 'edit_product', 'product_detail'];
?>

<div class="col-lg-3">
    <nav class="navbar navbar-expand-lg rounded border mt-2">
        <div class="container-fluid">
            <button class="navbar-toggler order-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" style="width:200px">
                <div class="offcanvas-body p-2">
                    <ul class="navbar-nav nav-pills flex-column justify-content-end flex-grow-1">
                        <li class="nav-item">
                            <a class="nav-link text-dark rounded ps-2 <?= $current_page == 'index' ? 'active bg-purple text-white' : '' ?>" href="index.php">
                                <i class="bi bi-house-door"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark rounded ps-2 <?= $current_page == 'product' ? 'active bg-purple text-white' : '' ?>" href="product.php">
                                <i class="bi bi-box-seam"></i> Product
                            </a>
                        </li>
                        <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link text-dark rounded ps-2 <?= $current_page == 'add_product' ? 'active bg-purple text-white' : '' ?>" href="add_product.php">
                                <i class="bi bi-plus-circle"></i> Add Product
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark rounded ps-2 <?= in_array($current_page, $my_product_pages) ? 'active bg-purple text-white' : '' ?>" href="my_product.php">
                                <i class="bi bi-bag-check"></i> My Product
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>