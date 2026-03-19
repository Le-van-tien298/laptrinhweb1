

// Hàm mở Modal và lấy dữ liệu từ Server
async function viewUser(id) {
    const modal = document.getElementById('userModal');
    const bodyData = document.getElementById('modal-body-data');

    // Hiện modal và trạng thái chờ
    modal.style.display = "block";
    bodyData.innerHTML = "<p style='text-align:center;'>Đang tải dữ liệu, Pro đợi xíu nha...</p>";

    try {
        // Gọi đến file logic trong folder module
        // Lưu ý: Đường dẫn phải khớp với cấu trúc thư mục của Pro
        const response = await fetch(`module/serviceView.php?id=${id}`);

        if (!response.ok) throw new Error('Hổng kết nối được server Pro ơi');

        const html = await response.text();

        // Đổ dữ liệu HTML từ PHP trả về vào trong Popup
        bodyData.innerHTML = html;
    } catch (error) {
        bodyData.innerHTML = "<p style='color:red;'>Lỗi: " + error.message + "</p>";
    }
}

// Hàm đóng Modal
function closeModal() {
    document.getElementById('userModal').style.display = "none";
}

// Đóng modal khi bấm ra ngoài vùng màu đen
window.onclick = function (event) {
    const modal = document.getElementById('userModal');
    if (event.target == modal) {
        closeModal();
    }
}

alert("Chào mừng Pro đến với trang quản lý người dùng!");