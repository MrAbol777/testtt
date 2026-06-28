-- 1) Table: products
CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `stock` INT NOT NULL DEFAULT 0,
    `image_url` VARCHAR(255) NULL
) ENGINE=InnoDB;

-- 2) Table: customers
CREATE TABLE `customers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `phone` VARCHAR(50) NULL
) ENGINE=InnoDB;

-- 3) Table: orders
CREATE TABLE `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NOT NULL,
    `order_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `status` VARCHAR(20) NOT NULL DEFAULT 'Pending',
    INDEX `idx_customer_id` (`customer_id`),
    CONSTRAINT `fk_orders_customer` 
        FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) 
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 4) Table: order_items
CREATE TABLE `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `price_at_order` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    INDEX `idx_order_id` (`order_id`),
    INDEX `idx_product_id` (`product_id`),
    CONSTRAINT `fk_items_order` 
        FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) 
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_items_product` 
        FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) 
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Sample Data Insertion
-- Add 2 products
INSERT INTO `products` (`name`, `description`, `price`, `stock`, `image_url`) VALUES
('Classic White T-Shirt', '100% Cotton basic tee', 19.99, 100, 'https://via.placeholder.com/200?text=T-Shirt'),
('Slim Fit Jeans', 'Dark blue denim jeans', 49.90, 50, 'https://via.placeholder.com/200?text=Jeans');

-- Add 1 customer
INSERT INTO `customers` (`name`, `email`, `phone`) VALUES 
('John Doe', 'john.doe@example.com', '+123456789');

-- Add 1 order
INSERT INTO `orders` (`customer_id`, `order_date`, `total_amount`, `status`) VALUES
(1, NOW(), 89.88, 'Pending');

-- Add 2 order items (linked to order id 1)
INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price_at_order`) VALUES 
(1, 1, 2, 19.99),
(1, 2, 1, 49.90);
