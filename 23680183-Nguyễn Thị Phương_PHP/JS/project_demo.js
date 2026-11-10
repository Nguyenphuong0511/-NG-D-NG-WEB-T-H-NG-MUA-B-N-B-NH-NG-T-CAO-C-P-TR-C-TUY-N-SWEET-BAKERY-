// ======================================================
// GIỎ HÀNG PHÍA TRÌNH DUYỆT (dựa trên bài thực hành TH4)
// - Giỏ hàng là một mảng: [{id, name, price, qty}, ...]
// - Được lưu vào localStorage để tải lại trang không bị mất
// - Khi đặt hàng, mảng được chuyển thành JSON và gửi lên server.
//   Server CHỈ tin "id" và "qty", tên và giá được lấy lại từ CSDL.
// ======================================================

var KHOA_LUU = 'gio_hang';
var gioHang = docGioHang();

function docGioHang() {
    try {
        var duLieu = JSON.parse(localStorage.getItem(KHOA_LUU) || '[]');
        return Array.isArray(duLieu) ? duLieu : [];
    } catch (e) {
        return [];
    }
}

function luuGioHang() {
    try {
        localStorage.setItem(KHOA_LUU, JSON.stringify(gioHang));
    } catch (e) {
        // Trình duyệt chặn localStorage thì giỏ hàng chỉ mất khi tải lại trang
    }
}

function dinhDangTien(soTien) {
    return soTien.toLocaleString('en-US');
}

// Nút + / - trên thẻ sản phẩm
function doiSoLuong(nut, buocNhay) {
    var oSoLuong = nut.parentElement.querySelector('.so-luong');
    var soLuong = parseInt(oSoLuong.textContent) + buocNhay;
    if (soLuong < 1) soLuong = 1;
    if (soLuong > 999) soLuong = 999;
    oSoLuong.textContent = soLuong;
}

// Nút "THÊM VÀO GIỎ HÀNG"
function themVaoGio(nut) {
    var the = nut.closest('.the-san-pham');
    var id = parseInt(the.dataset.id);
    var soLuong = parseInt(the.querySelector('.so-luong').textContent);

    var daCo = gioHang.find(function (muc) { return muc.id === id; });
    if (daCo) {
        daCo.qty = Math.min(999, daCo.qty + soLuong);
    } else {
        gioHang.push({
            id: id,
            name: the.dataset.name,
            price: parseInt(the.dataset.price),
            qty: soLuong
        });
    }

    the.querySelector('.so-luong').textContent = 1;   // đặt lại số lượng về 1
    luuGioHang();
    veGioHang();
}

function xoaKhoiGio(viTri) {
    gioHang.splice(viTri, 1);
    luuGioHang();
    veGioHang();
}

function tinhTongTien() {
    return gioHang.reduce(function (tong, muc) { return tong + muc.price * muc.qty; }, 0);
}

// Vẽ lại danh sách giỏ hàng bên phải.
// Dùng textContent (không dùng innerHTML) để tên sản phẩm không thể chèn mã HTML/JS.
function veGioHang() {
    var ds = document.getElementById('ds-gio-hang');
    if (!ds) return;   // trang không có giỏ hàng

    ds.innerHTML = '';
    gioHang.forEach(function (muc, viTri) {
        var li = document.createElement('li');

        var ten = document.createElement('span');
        ten.textContent = muc.name + ' × ' + muc.qty;

        var tien = document.createElement('span');
        tien.textContent = dinhDangTien(muc.price * muc.qty) + ' đ';

        var nutXoa = document.createElement('button');
        nutXoa.type = 'button';
        nutXoa.className = 'nut-xoa';
        nutXoa.textContent = '✕';
        nutXoa.title = 'Xoá khỏi giỏ';
        nutXoa.onclick = function () { xoaKhoiGio(viTri); };

        li.appendChild(ten);
        li.appendChild(tien);
        li.appendChild(nutXoa);
        ds.appendChild(li);
    });

    document.getElementById('tong-tien').textContent = dinhDangTien(tinhTongTien());
    document.getElementById('gio-hang-trong').style.display = gioHang.length ? 'none' : 'block';
}

// Nút "MUA HÀNG" -> mở hộp thoại xác nhận
function moXacNhan() {
    if (gioHang.length === 0) {
        alert('Giỏ hàng trống!');
        return;
    }

    var tomTat = document.getElementById('tom-tat-don-hang');
    tomTat.innerHTML = '';
    var ul = document.createElement('ul');
    gioHang.forEach(function (muc) {
        var li = document.createElement('li');
        li.textContent = muc.name + ' × ' + muc.qty + ' = ' + dinhDangTien(muc.price * muc.qty) + ' đ';
        ul.appendChild(li);
    });
    var tong = document.createElement('p');
    tong.innerHTML = '<b>Tổng tiền: </b>';
    tong.appendChild(document.createTextNode(dinhDangTien(tinhTongTien()) + ' đ'));
    tomTat.appendChild(ul);
    tomTat.appendChild(tong);

    document.getElementById('hop-thoai-dat-hang').classList.add('hien');
}

function dongXacNhan() {
    document.getElementById('hop-thoai-dat-hang').classList.remove('hien');
}

// Trước khi gửi form: chuyển giỏ hàng thành JSON, đặt vào ô ẩn cart_json
function guiGioHang() {
    if (typeof daDangNhap !== 'undefined' && !daDangNhap) {
        alert('Vui lòng đăng nhập tài khoản trước khi đặt hàng!');
        window.location.href = 'login.php?yeu_cau_dang_nhap=1';
        return false;
    }
    if (gioHang.length === 0) {
        alert('Giỏ hàng trống!');
        return false;
    }
    document.getElementById('cart_json').value = JSON.stringify(gioHang);
    return true;
}

// Bấm vào vùng tối bên ngoài hộp thoại thì đóng lại
window.addEventListener('click', function (suKien) {
    if (suKien.target.classList.contains('hop-thoai')) {
        suKien.target.classList.remove('hien');
    }
});

document.addEventListener('DOMContentLoaded', veGioHang);

// Trang thêm/sửa sản phẩm: xem trước ảnh vừa chọn, trước khi bấm lưu
function xemTruocAnh(oChonFile) {
    var file = oChonFile.files && oChonFile.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
        alert('Ảnh quá lớn, tối đa 2 MB.');
        oChonFile.value = '';
        return;
    }
    document.getElementById('xem-truoc-anh').src = URL.createObjectURL(file);
}
