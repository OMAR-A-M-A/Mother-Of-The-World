CREATE TABLE admins (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(255),
    password VARCHAR(255)
);

CREATE TABLE governorates (
    G_ID INT PRIMARY KEY AUTO_INCREMENT,
    G_name VARCHAR(255),
    image_url VARCHAR(255)
    G_description TEXT
);

CREATE TABLE categories (
    C_ID INT PRIMARY KEY AUTO_INCREMENT,
    C_name VARCHAR(255),
    C_image VARCHAR(255),
    C_description TEXT
);

CREATE TABLE places (
    P_ID INT PRIMARY KEY AUTO_INCREMENT,
    p_name VARCHAR(255),
    description TEXT,
    ticket_price DECIMAL(10,2),
    opening_hours VARCHAR(255),
    location_url VARCHAR(255),
    main_image VARCHAR(255),
    g_num INT,
    C_num INT,
    -- Foreign Key Definitions
    FOREIGN KEY (g_num) REFERENCES governorates(G_ID) ON DELETE SET NULL,
    FOREIGN KEY (C_num) REFERENCES categories(C_ID) ON DELETE SET NULL
);

CREATE TABLE place_images (
    I_ID INT PRIMARY KEY AUTO_INCREMENT,
    image_url VARCHAR(255), -- I added this column; otherwise the table holds no image data
    P_num INT,
    FOREIGN KEY (P_num) REFERENCES places(P_ID) ON DELETE CASCADE
);
