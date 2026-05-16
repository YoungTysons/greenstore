<?php
/*
Plugin Name: Trợ Lý Chọn Cây Theo Phong Thủy
Plugin URI: https://gemini.ai
Description: Plugin gợi ý cây cảnh theo Mệnh phong thủy của khách hàng, tích hợp nút tìm nhanh sản phẩm.
Version: 1.0
Author: Gemini Partner
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Ngăn truy cập trực tiếp
}

// Tạo Shortcode [tro_ly_phong_thuy]
function gmn_shortcode_phong_thuy() {
    // Lấy URL trang chủ để làm chức năng tìm kiếm sản phẩm tự động
    $search_url = esc_url( home_url( '/' ) );

    ob_start(); ?>
    
    <div class="gmn-phong-thuy-box">
        <h3>🔮 Tìm Cây Hợp Phong Thủy</h3>
        <p>Chọn mệnh của bạn để xem gợi ý các loại cây mang lại tài lộc, may mắn:</p>
        
        <select id="gmn-select-menh" onchange="gmnXemGoiY()">
            <option value="">-- Chọn Mệnh của bạn --</option>
            <option value="kim">Mệnh Kim (Kim loại)</option>
            <option value="moc">Mệnh Mộc (Cây cối)</option>
            <option value="thuy">Mệnh Thủy (Nước)</option>
            <option value="hoa">Mệnh Hỏa (Lửa)</option>
            <option value="tho">Mệnh Thổ (Đất)</option>
        </select>

        <!-- Khối hiển thị kết quả gợi ý -->
        <div id="gmn-ket-qua" style="display: none;">
            <p id="gmn-loi-khuyen"></p>
            <a id="gmn-btn-tim-kiem" href="#" class="button alt">Xem các cây hợp mệnh ➔</a>
        </div>
    </div>

    <!-- CSS Giao diện (Tone màu xanh lá hợp với web cây cảnh) -->
    <style>
        .gmn-phong-thuy-box {
            background: #f4f9f4;
            border: 2px solid #2e7d32;
            border-radius: 8px;
            padding: 20px;
            max-width: 450px;
            margin: 20px auto;
            font-family: sans-serif;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .gmn-phong-thuy-box h3 {
            color: #2e7d32;
            margin-top: 0;
            font-size: 20px;
            text-align: center;
        }
        #gmn-select-menh {
            width: 100%;
            padding: 10px;
            border: 1px solid #a5d6a7;
            border-radius: 4px;
            font-size: 16px;
            outline: none;
            margin-bottom: 15px;
        }
        #gmn-select-menh:focus {
            border-color: #2e7d32;
        }
        #gmn-ket-qua {
            background: #ffffff;
            border-left: 4px solid #81c784;
            padding: 15px;
            border-radius: 4px;
            animation: fadeIn 0.5s ease;
        }
        #gmn-loi-khuyen {
            font-size: 14px;
            color: #333;
            line-height: 1.5;
            margin-bottom: 12px;
        }
        #gmn-btn-tim-kiem {
            display: inline-block;
            background-color: #2e7d32;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
            transition: background 0.3s;
        }
        #gmn-btn-tim-kiem:hover {
            background-color: #1b5e20;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <!-- JavaScript xử lý logic trả kết quả trực tiếp -->
    <script>
    function gmnXemGoiY() {
        var select = document.getElementById('gmn-select-menh');
        var value = select.value;
        var ketQuaDiv = document.getElementById('gmn-ket-qua');
        var loiKhuyen = document.getElementById('gmn-loi-khuyen');
        var btnTimKiem = document.getElementById('gmn-btn-tim-kiem');
        
        if (value === "") {
            ketQuaDiv.style.display = "none";
            return;
        }
        
        var text = "";
        var keyword = "";
        
        switch(value) {
            case "kim":
                text = "✨ <b>Mệnh Kim:</b> Bạn hợp với cây có lá/hoa màu trắng, vàng, nâu. Giúp tăng tài lộc, gặp hung hóa cát.<br>🍀 <b>Cây gợi ý:</b> Bạch Mã Hoàng Tử, Lan Ý, Ngọc Ngân, Kim Tiền.";
                keyword = "Mệnh Kim";
                break;
            case "moc":
                text = "🌱 <b>Mệnh Mộc:</b> Bạn hợp với cây toàn thân màu xanh lục hoặc cây thủy sinh (Thủy sinh mộc). Giúp gia đạo bình an.<br>🍀 <b>Cây gợi ý:</b> Phát Tài Núi, Trầu Bà Xanh, Cau Tiểu Trâm, Vạn Niên Thanh.";
                keyword = "Mệnh Mộc";
                break;
            case "thuy":
                text = "💧 <b>Mệnh Thủy:</b> Bạn hợp với cây thủy sinh, cây có màu đen, xanh nước biển hoặc trắng. Giúp đường công danh hanh thông.<br>🍀 <b>Cây gợi ý:</b> Lan Ý, Tùng Bồng Lai, Phát Lộc Thủy Sinh, Thường Xuân.";
                keyword = "Mệnh Thủy";
                break;
            case "hoa":
                text = "🔥 <b>Mệnh Hỏa:</b> Bạn hợp với cây có sắc đỏ, hồng, tím hoặc cam. Giúp kích hoạt năng lượng tích cực, thăng tiến.<br>🍀 <b>Cây gợi ý:</b> Hồng Môn, Vạn Lộc, Phú Quý, Trạng Nguyên, Cẩm Nhung Đỏ.";
                keyword = "Mệnh Hỏa";
                break;
            case "tho":
                text = "⛰️ <b>Mệnh Thổ:</b> Bạn hợp với cây trồng đất có màu vàng bọc, nâu hoặc màu của hành Hỏa (Đỏ, hồng). Giúp sự nghiệp vững chắc.<br>🍀 <b>Cây gợi ý:</b> Lưỡi Hổ, Ngũ Gia Bì, Thiết Mộc Lan, Sen Đá Nâu.";
                keyword = "Mệnh Thổ";
                break;
        }
        
        loiKhuyen.innerHTML = text;
        // Tận dụng cơ chế tìm kiếm mặc định của sản phẩm WordPress: ?s=Từ+Khóa&post_type=product
        btnTimKiem.href = "<?php echo $search_url; ?>?s=" + encodeURIComponent(keyword) + "&post_type=product";
        ketQuaDiv.style.display = "block";
    }
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('tro_ly_phong_thuy', 'gmn_shortcode_phong_thuy');

