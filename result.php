<?php

    session_set_cookie_params('86400');
    session_start();
    include("includes/db.php");
    include("functions/functions.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kết Quả Tìm Kiếm</title>
    <!--fonts-->
    <link rel="stylesheet" href="fonts/rougescript.css">
    <!--script swiper-->
    <link rel="stylesheet" href="css/swiper.min.css">
    <!--css-->
    <link rel="stylesheet" href="css/result.css">

    <!-- icon and title -->
    <link rel="shortcut icon" href="images/favicon.ico" />

</head>
<body>
    <!--Navigation-->
    
    <div class="menu-mobile">

        <div class="menu-items">
            <a href="index.php" class="menu-link">Trang chủ</a>
            <a href="shop.php" class="menu-link">Cửa hàng</a>
            <a href="register.php" class="menu-link">Tài khoản</a>
            <a href="cart.php" class="menu-link">Giỏ hàng</a>
            <a href="intro.php" class="menu-link">Giới thiệu</a>
            <a href="news.php" class="menu-link">Tin tức</a>
        </div>
        <div class="menu-icon close">
            <span></span>
        </div>

    </div>

    </div>
        
    <nav>

        <a href="index.php" class="mainLogo"> <img src="assets/logo.png" alt=""></a>
        <div class="menu">
            <div class="menulinks">
                <a href="index.php" class="menuLink">Trang chủ</a>
                <a href="shop.php" class="menuLink">Cửa hàng</a>
                <a href="customer/my_account.php?my_orders" class="menuLink">Tài khoản</a>
                <a href="cart.php" class="menuLink">Giỏ hàng</a>
                <a href="intro.php" class="menuLink">Giới thiệu</a>
                <a href="news.php" class="menuLink">Tin tức</a>
            </div>
        </div>
        <div class="iconWrapper">

            <!-- Form search -->
            <form method="get" action="result.php">
                <div class="mainNav__input">
                    <input type="search" name="user_query" placeholder="Tìm kiếm ...">
                    <button class="mainNav__btnSearch" type="submit"> <img src="assets/icon-search.svg" alt=""></button>
                </div>
            </form>
            <!--end Form search-->
                
            <a href="cart.php">
                <div class="shoppingCart">
                    <img src="assets/shopping-cart.svg" alt="">
                    <span class="itemNumber"><?php items(); ?></span>
                </div>
            </a>

            <?php
            
                if (!isset($_SESSION['customer_email'])) {

                    echo "
                        <a href='customer/login.php'>
                            <div class='profile'>
                                <img src='customer/customer_images/customer_default.png' title='Đăng Nhập' alt=''>
                            </div>
                        </a>
                    ";

                } else {

                    $session_email = $_SESSION['customer_email'];

                    $get_customer = "select * from customers where customer_email='$session_email'";

                    $run_customer = mysqli_query($conn, $get_customer);

                    $row_customer = mysqli_fetch_array($run_customer);

                        $customer_name = $row_customer['customer_name'] ?? '';

                        $customer_image = $row_customer['customer_image'] ?? '';
                        
                    if ($customer_image=='') {

                        echo "
                            <a href='customer/my_account.php?my_orders'>
                                <div class='profile'>
                                    <img src='customer/customer_images/customer_default_2.png' title='Xem Hồ Sơ' alt=''>
                                </div>
                            </a>
                        ";

                    } else {

                        echo "
                            <a href='customer/my_account.php?my_orders'>
                                <div class='profile'>
                                    <img src='customer/customer_images/$customer_image' title='Xem Hồ Sơ' alt=''>
                                </div>
                            </a>
                        ";
                    }
                }
            
            ?>

            <div class="menu-icon open">
                <span></span>
            </div>
        </div>
    </nav>
    <!--end Navigation-->

    <!--Content-->

    <!--Card-->
    <section class="wrapper">
        <h3 class="section__title"><b>Kết quả</b><span>Tìm kiếm</span></h3>
            <h4 class="count__resultProduct">Kết quả tìm kiếm:
                 <?php 

                    $find = "%{$_GET['user_query']}%";

                    $get_products = "select * from products where product_keywords like '$find' or product_title like'$find' or product_price like'$find' or product_sale like'$find'";

                    $run_products = mysqli_query($conn, $get_products);

                    $count_products = mysqli_num_rows($run_products);

                    echo "$count_products";

                ?>
            </h4>
        <div class="cards">
            <!--card-->
            <!-- php get product -->
            <?php 

                $find = "%{$_GET['user_query']}%";

                $get_products = "select * from products where product_keywords like '$find' or product_title like'$find' or product_price like'$find' or product_sale like'$find'";

                $run_products = mysqli_query($conn, $get_products);

                $count_products = mysqli_num_rows($run_products);

                if ($count_products>0) {

                    while ($row_products = mysqli_fetch_array($run_products)) {
    
                        $product_id = $row_products['product_id'];
    
                        $product_title = $row_products['product_title'];
    
                        $product_price = number_format((float)$row_products['product_price']);
    
                        $product_image_1 = $row_products['product_image_1'];
    
                        $product_label = $row_products['product_label'];
    
                        $product_sale = $row_products['product_sale'];
    
                
                ?>

            <a href="details.php?product_id=<?php echo $product_id; ?>" rel="noopenner" class="card">
            
                <?php
                    
                    if ($product_label == "new") {

                        echo "<div class='new'>mới!</div>";

                    } else {

                        echo "<div class='sale'>giảm giá!</div>";

                    }

                ?>
                <div class="card__image">
                    <img src="admin/<?php echo $product_image_1; ?>" alt="">
                </div>

                <div class="card__content">
                    <article class="card__text">
                        <h2 class="card__title"><?php echo $product_title; ?></h2>
                        <div class="card__price">
                            <?php
                            
                                if ($product_label == "sale") {

                                    echo "
                                        <p class='card__priceFinal'>$product_sale ₫</p>
                                        <p class='card__priceOriginal'>$product_price  ₫</p>
                                    ";
                                } else {

                                    echo "<p class='card__priceFinal'>$product_price  ₫</p>";

                                }

                            ?>
                        </div>
                    </article>

                    <div class="card__icon">
                        <p class="card__detail">Chi tiết<span>+</span></p>
                        <button class="btn"><span>Xem chi tiết</span></button>
                    </div>
                </div>
            </a>
            <?php } } ?>
            <!-- end php get product -->
            <!--end Card-->
        </div>

        <a href="shop.html">
            <button class="button">Xem Thêm</button>
        </a>
        
    </section>


    <!--Footer-->
    <footer class="footer">
        <div class="footer__top">
            <div class="footer__links">
                <h4 class="footer__title">Phòng trưng bày</h4>
                <a class="footer__link" href="">Công đồng</a>
                <a class="footer__link" href="">Xu hướng</a>
                <a class="footer__link" href="">Chọn</a>
            </div>
            <div class="footer__links">
                <h4 class="footer__title">Thương trường</h4>
                <a class="footer__link" href="">Xu hướng</a>
                <a class="footer__link active" href="">Bán chạy nhất</a>
                <a class="footer__link" href="">Mới nhất</a>
            </div>
            <div class="footer__links">
                <h4 class="footer__title">Tạp chí</h4>
                <a class="footer__link" href="">Kỹ năng nghệ thuật</a>
                <a class="footer__link" href="">Sự nghiệp</a>
                <a class="footer__link" href="">Cảm hứng</a>
                <a class="footer__link" href="">Tin tức</a>
            </div>
            <div class="footer__links">
                <h4 class="footer__title">Bản tin</h4>
                <p class="footer__description">Đăng ký nhận bản tin của chúng tôi để nhận tin tức, cập nhật, mẹo và ưu đãi đặc biệt hàng tuần của bạn.</p>
                <div class="footer__input">
                    <img src="assets/email.svg" alt="">
                    <input type="text" placeholder="Nhập địa chỉ email của bạn">
                </div>
                <button class="btn">Đăng ký</button>
            </div>
        </div>

        <hr class="line">
        <div class="footer__bottom">
            <div class="footer__hiperLinks">
                <a class="footer__hiperLink" href="">Chính sách bảo mật</a>
                <span>・</span>
                <a class="footer__hiperLink" href="">Các điều khoản và điều kiện</a>
            </div>
            <div class="footer__hiperLinks">
                <a class="footer__hiperLink" href="">Dribbble</a>
                <span>・</span>
                <a class="footer__hiperLink" href="">Behance</a>
                <span>・</span>
                <a class="footer__hiperLink" href="">Instagram</a>
            </div>
        </div>
    </footer>
    <!--end Footer-->

    <!--script-->
    <script src="js/main.js"></script>
    <script src="js/back_to_top.js"></script>
    <script  src="js/mobile_menu.js"></script>
</body>
</html>