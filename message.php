<?php
// connecting to database
$conn = mysqli_connect("localhost", "root", "", "bantrangsuc-chatbot") or die("Database Error");

// getting user message through ajax
$getMesg = mysqli_real_escape_string($conn, $_POST['text']);

//checking user query to database query , chỗ này sẽ truy vấn cầu trả lời từ ký tự người dung nhập, ( code xử lý gọi ở bot.php với file này message.php)
$slug = slugify_vi($getMesg);
$question = explode('-', $slug);
$name_product = count($question) > 2 ? implode('-', array_slice($question, count($question) - 2, 2)) : '';
if (count($question) > 2) {
    $question = array_slice($question, 0, 3);
}
$question = implode('-', $question);
$check_data = "SELECT * FROM chatbot WHERE queries LIKE '%$getMesg%' or queries LIKE '%$question%'";
$run_query = mysqli_query($conn, $check_data) or die("Error");

// if user query matched to database query we'll show the reply otherwise it go to else statement
if (mysqli_num_rows($run_query) > 0) {
    //fetching reply from the database according to the user query
    $fetch_data = mysqli_fetch_assoc($run_query);
    //storing reply to a varible which we'll send to ajax
    $reply = $fetch_data['replies'];
    switch ($fetch_data['type']) {
        case 0:
            echo $reply;
            break;
        case 1:
            // retrive data products from database
            $get_product = "SELECT p.*, c.product_category_title FROM products p, product_categories c where p.product_category_id = c.product_category_id";
            $fetch_products = mysqli_query($conn, $get_product);
            $result_products = [];
            while ($row = mysqli_fetch_assoc($fetch_products)) {
                if (str_contains(slugify_vi($row['product_title']), $name_product)) {
                    $result_products[] = $row;
                }
                $result_products[] = $row;
            }
            if (count($result_products) === 0) {
                echo 'Hiện tại sản phẩm này chúng tôi chưa có! Bạn có thể tìm kiếm các sản phẩm khác';
            } else {
                $type = explode(' ', $getMesg);
                $type = count($type) > 2 ? implode(' ', array_slice($type, count($type) - 2, 2)) : '';
                $reply = str_replace('%sp', $type, $reply);
                echo $reply;
                foreach ($result_products as $result_product) {
                    echo '
            <div class="">
                <a href="./details.php?product_id=' . $result_product['product_id'] . '" target="_blank">
                    <img src="admin/' . $result_product['product_image_1'] . '" width="150px" height="50px" alt="">
                    ' . $result_product['product_title'] . '
                </a>
            </div><br>';
                }
            }
            break;
        case 2:
            echo $reply;
            echo '
            <div class="">
                <a href="./shop.php" target="_blank">
                    <img src="assets/logo.png" width="150px" height="50px" alt="">
                    Cửa hàng
                </a>
            </div><br>';
            break;
        case 3:
            echo $reply;
            echo '
            <div class="">
                <a href="./intro.php" target="_blank">
                    <img src="assets/images/simple-heading.jpg" width="150px" height="50px" alt="">
                    Giới thiệu
                </a>
            </div><br>';
            break;
        case 4:
            break;
        case 5:
            break;
        default:
            break;
    }
} else {
    echo "Xin lỗi không thể hiểu câu hỏi của bạn!";
}

function slugify_vi($text, string $divider = '-', bool $transliterate = false): string
{
    // Make the string lowercase
    $text = mb_strtolower($text, 'UTF-8');

    // Replace non-letter or digits with the divider
    $text = preg_replace('~[^\p{L}\d]+~u', $divider, $text);

    // Convert accented characters to their non-accented equivalents
    $text = remove_accents($text);

    // Optionally transliterate non-latin characters
    if ($transliterate) {
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    }

    // Remove any remaining unwanted characters
    $text = trim(preg_replace('~[^-\w]+~', '', $text), $divider);

    // Remove duplicate dividers
    $text = preg_replace('~-+~', $divider, $text);

    return $text;
}

// Function to remove accents from Vietnamese characters
function remove_accents($text)
{
    $vietnameseMap = array(
        'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
        'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
        'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
        'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ặ' => 'e',
        'ì' => 'i', 'í' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
        'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
        'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
        'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
        'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
        'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
        'đ' => 'd',
        'Đ' => 'D'
    );

    return strtr($text, $vietnameseMap);
}
