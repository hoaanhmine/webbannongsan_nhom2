-- Tạo bảng lưu thông tin người dùng
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,  -- ID tự động tăng, là khóa chính
    FullName VARCHAR(100) NOT NULL,       -- Họ và tên
    Email VARCHAR(100) UNIQUE NOT NULL,   -- Email đăng nhập (duy nhất)
    PasswordHash VARCHAR(255) NOT NULL,   -- Mật khẩu đã mã hóa
    Role VARCHAR(20) NOT NULL CHECK (Role IN ('admin', 'customer')), -- Vai trò ('admin' hoặc 'customer')
    Status VARCHAR(20) DEFAULT 'active' CHECK (Status IN ('active', 'inactive')), -- Trạng thái ('active', 'inactive')
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP   -- Thời gian tạo tài khoản
);
-- Tạo bảng danh mục sản phẩm
CREATE TABLE Categories (
    CategoryID INT AUTO_INCREMENT PRIMARY KEY, -- ID tự động tăng
    CategoryName VARCHAR(100) NOT NULL,     -- Tên danh mục
    Description TEXT,               -- Mô tả danh mục
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP     -- Ngày giờ tạo
);

-- Tạo bảng sản phẩm
CREATE TABLE Products (
    ProductID INT AUTO_INCREMENT PRIMARY KEY, -- ID tự động tăng
    ProductName VARCHAR(100) NOT NULL,      -- Tên sản phẩm
    CategoryID INT,                          -- ID danh mục
    Price DECIMAL(10, 2) NOT NULL,           -- Giá sản phẩm
    Stock INT DEFAULT 0,                     -- Số lượng tồn kho
    Description TEXT,               -- Mô tả sản phẩm
    ImageURL VARCHAR(255),                  -- URL hình ảnh
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,    -- Ngày giờ tạo
    CONSTRAINT FK_Category FOREIGN KEY (CategoryID) REFERENCES Categories(CategoryID) ON DELETE SET NULL
);

-- Tạo bảng đơn hàng
CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,   -- ID đơn hàng
    UserID INT NOT NULL,                     -- ID người dùng
    OrderDate DATETIME DEFAULT CURRENT_TIMESTAMP,    -- Ngày đặt hàng
    TotalAmount DECIMAL(10, 2) NOT NULL,     -- Tổng giá trị đơn hàng
    Status VARCHAR(20) DEFAULT 'pending',   -- Trạng thái đơn hàng
    CONSTRAINT FK_User FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE
);

-- Tạo bảng chi tiết đơn hàng
CREATE TABLE OrderDetails (
    OrderDetailID INT AUTO_INCREMENT PRIMARY KEY, -- ID chi tiết đơn hàng
    OrderID INT NOT NULL,                       -- ID đơn hàng
    ProductID INT NOT NULL,                     -- ID sản phẩm
    Quantity INT NOT NULL,                      -- Số lượng
    UnitPrice DECIMAL(10, 2) NOT NULL,          -- Giá sản phẩm tại thời điểm đặt hàng
    CONSTRAINT FK_Order FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) ON DELETE CASCADE,
    CONSTRAINT FK_Product FOREIGN KEY (ProductID) REFERENCES Products(ProductID) ON DELETE CASCADE
);

-- admin
CREATE TABLE AdminActions (
    ActionID INT AUTO_INCREMENT PRIMARY KEY, -- ID tự động tăng
    AdminID INT NOT NULL,                   -- ID Admin (liên kết đến bảng Users)
    Action VARCHAR(255) NOT NULL,          -- Mô tả hành động (ví dụ: "Thêm sản phẩm", "Xóa người dùng")
    Timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,   -- Thời gian thực hiện hành động
    CONSTRAINT FK_Admin FOREIGN KEY (AdminID) REFERENCES Users(UserID) 
        ON DELETE CASCADE
);

-- lịch sử đăng nhập
CREATE TABLE LoginLogs (
    LogID INT AUTO_INCREMENT PRIMARY KEY,    -- ID tự động tăng
    UserID INT NOT NULL,                    -- ID người dùng (liên kết đến bảng Users)
    LoginTime DATETIME DEFAULT CURRENT_TIMESTAMP,   -- Thời gian đăng nhập
    IPAddress VARCHAR(50),                 -- Địa chỉ IP của người dùng
    Status VARCHAR(20) CHECK (Status IN ('success', 'failed')), -- Trạng thái đăng nhập
    CONSTRAINT FK_User FOREIGN KEY (UserID) REFERENCES Users(UserID) 
        ON DELETE CASCADE
);

-- quyền
CREATE TABLE Permissions (
    PermissionID INT AUTO_INCREMENT PRIMARY KEY, -- ID tự động tăng
    Role VARCHAR(20) NOT NULL,                -- Vai trò (ví dụ: 'admin')
    Permission VARCHAR(100) NOT NULL          -- Quyền (ví dụ: 'ManageUsers', 'ManageProducts')
);

CREATE TABLE RolePermissions (
    RolePermissionID INT AUTO_INCREMENT PRIMARY KEY,
    Role VARCHAR(20) NOT NULL,
    PermissionID INT NOT NULL,
    CONSTRAINT FK_Permission FOREIGN KEY (PermissionID) REFERENCES Permissions(PermissionID)
);