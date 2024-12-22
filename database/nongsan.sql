

-- Tạo bảng lưu thông tin người dùng
CREATE TABLE Users (
    UserID INT IDENTITY(1,1) PRIMARY KEY,  -- ID tự động tăng, là khóa chính
    FullName NVARCHAR(100) NOT NULL,       -- Họ và tên
    Email NVARCHAR(100) UNIQUE NOT NULL,   -- Email đăng nhập (duy nhất)
    PasswordHash NVARCHAR(255) NOT NULL,   -- Mật khẩu đã mã hóa
    Role NVARCHAR(20) NOT NULL CHECK (Role IN ('admin', 'customer')), -- Vai trò ('admin' hoặc 'customer')
    Status NVARCHAR(20) DEFAULT 'active' CHECK (Status IN ('active', 'inactive')), -- Trạng thái ('active', 'inactive')
    CreatedAt DATETIME DEFAULT GETDATE()   -- Thời gian tạo tài khoản
);


-- Tạo bảng danh mục sản phẩm
CREATE TABLE Categories (
    CategoryID INT IDENTITY(1,1) PRIMARY KEY, -- ID tự động tăng
    CategoryName NVARCHAR(100) NOT NULL,     -- Tên danh mục
    Description NVARCHAR(MAX),               -- Mô tả danh mục
    CreatedAt DATETIME DEFAULT GETDATE()     -- Ngày giờ tạo
);


-- Tạo bảng sản phẩm
CREATE TABLE Products (
    ProductID INT IDENTITY(1,1) PRIMARY KEY, -- ID tự động tăng
    ProductName NVARCHAR(100) NOT NULL,      -- Tên sản phẩm
    CategoryID INT,                          -- ID danh mục
    Price DECIMAL(10, 2) NOT NULL,           -- Giá sản phẩm
    Stock INT DEFAULT 0,                     -- Số lượng tồn kho
    Description NVARCHAR(MAX),               -- Mô tả sản phẩm
    ImageURL NVARCHAR(255),                  -- URL hình ảnh
    CreatedAt DATETIME DEFAULT GETDATE(),    -- Ngày giờ tạo
    CONSTRAINT FK_Category FOREIGN KEY (CategoryID) REFERENCES Categories(CategoryID) ON DELETE SET NULL
);


-- Tạo bảng đơn hàng
CREATE TABLE Orders (
    OrderID INT IDENTITY(1,1) PRIMARY KEY,   -- ID đơn hàng
    UserID INT NOT NULL,                     -- ID người dùng
    OrderDate DATETIME DEFAULT GETDATE(),    -- Ngày đặt hàng
    TotalAmount DECIMAL(10, 2) NOT NULL,     -- Tổng giá trị đơn hàng
    Status NVARCHAR(20) DEFAULT 'pending',   -- Trạng thái đơn hàng
    CONSTRAINT FK_User FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE
);


-- Tạo bảng chi tiết đơn hàng
CREATE TABLE OrderDetails (
    OrderDetailID INT IDENTITY(1,1) PRIMARY KEY, -- ID chi tiết đơn hàng
    OrderID INT NOT NULL,                       -- ID đơn hàng
    ProductID INT NOT NULL,                     -- ID sản phẩm
    Quantity INT NOT NULL,                      -- Số lượng
    UnitPrice DECIMAL(10, 2) NOT NULL,          -- Giá sản phẩm tại thời điểm đặt hàng
    CONSTRAINT FK_Order FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) ON DELETE CASCADE,
    CONSTRAINT FK_Product FOREIGN KEY (ProductID) REFERENCES Products(ProductID) ON DELETE CASCADE
);

-- admin
CREATE TABLE AdminActions (
    ActionID INT IDENTITY(1,1) PRIMARY KEY, -- ID tự động tăng
    AdminID INT NOT NULL,                   -- ID Admin (liên kết đến bảng Users)
    Action NVARCHAR(255) NOT NULL,          -- Mô tả hành động (ví dụ: "Thêm sản phẩm", "Xóa người dùng")
    Timestamp DATETIME DEFAULT GETDATE(),   -- Thời gian thực hiện hành động
    CONSTRAINT FK_Admin FOREIGN KEY (AdminID) REFERENCES Users(UserID) 
        ON DELETE CASCADE
);

-- lịch sử đăng nhập
CREATE TABLE LoginLogs (
    LogID INT IDENTITY(1,1) PRIMARY KEY,    -- ID tự động tăng
    UserID INT NOT NULL,                    -- ID người dùng (liên kết đến bảng Users)
    LoginTime DATETIME DEFAULT GETDATE(),   -- Thời gian đăng nhập
    IPAddress NVARCHAR(50),                 -- Địa chỉ IP của người dùng
    Status NVARCHAR(20) CHECK (Status IN ('success', 'failed')), -- Trạng thái đăng nhập
    CONSTRAINT FK_User FOREIGN KEY (UserID) REFERENCES Users(UserID) 
        ON DELETE CASCADE
);

-- quyền
CREATE TABLE Permissions (
    PermissionID INT IDENTITY(1,1) PRIMARY KEY, -- ID tự động tăng
    Role NVARCHAR(20) NOT NULL,                -- Vai trò (ví dụ: 'admin')
    Permission NVARCHAR(100) NOT NULL          -- Quyền (ví dụ: 'ManageUsers', 'ManageProducts')
);

CREATE TABLE RolePermissions (
    RolePermissionID INT IDENTITY(1,1) PRIMARY KEY,
    Role NVARCHAR(20) NOT NULL,
    PermissionID INT NOT NULL,
    CONSTRAINT FK_Permission FOREIGN KEY (PermissionID) REFERENCES Permissions(PermissionID) 
        ON DELETE CASCADE
);
