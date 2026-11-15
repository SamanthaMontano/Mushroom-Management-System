-- =====================================================================
-- CMD (Cultivation & Mushroom Data) Management System
-- Database Schema
-- =====================================================================

CREATE DATABASE IF NOT EXISTS mushroom_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mushroom_db;

-- ---------------------------------------------------------------------
-- Employees (also used for system login / authentication)
-- ---------------------------------------------------------------------
CREATE TABLE employees (
    employee_id     INT AUTO_INCREMENT PRIMARY KEY,
    first_name      VARCHAR(50)  NOT NULL,
    last_name       VARCHAR(50)  NOT NULL,
    username        VARCHAR(50)  NOT NULL UNIQUE,
    password        VARCHAR(255) NOT NULL,
    email           VARCHAR(100),
    phone           VARCHAR(20),
    position        VARCHAR(50)  NOT NULL DEFAULT 'Worker',
    role            ENUM('admin','manager','staff') NOT NULL DEFAULT 'staff',
    date_hired      DATE,
    salary          DECIMAL(10,2) DEFAULT 0,
    status          ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Inventory (raw materials / supplies: substrate, spawn, bags, etc.)
-- ---------------------------------------------------------------------
CREATE TABLE inventory (
    inventory_id    INT AUTO_INCREMENT PRIMARY KEY,
    item_name       VARCHAR(100) NOT NULL,
    category        ENUM('Substrate','Spawn','Packaging','Equipment','Supplies','Other') NOT NULL DEFAULT 'Other',
    quantity        DECIMAL(10,2) NOT NULL DEFAULT 0,
    unit            VARCHAR(20)  NOT NULL DEFAULT 'kg',
    reorder_level   DECIMAL(10,2) NOT NULL DEFAULT 0,
    unit_cost       DECIMAL(10,2) NOT NULL DEFAULT 0,
    supplier        VARCHAR(100),
    last_restocked  DATE,
    status          ENUM('In Stock','Low Stock','Out of Stock') NOT NULL DEFAULT 'In Stock',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Production (growing batches / cultivation cycles)
-- ---------------------------------------------------------------------
CREATE TABLE production (
    production_id       INT AUTO_INCREMENT PRIMARY KEY,
    batch_code           VARCHAR(30) NOT NULL UNIQUE,
    mushroom_variety      VARCHAR(50) NOT NULL DEFAULT 'Oyster Mushroom',
    substrate_type        VARCHAR(50),
    bag_quantity           INT NOT NULL DEFAULT 0,
    inoculation_date       DATE NOT NULL,
    expected_harvest_date  DATE,
    growing_location        VARCHAR(100),
    responsible_employee_id INT,
    status                 ENUM('Incubation','Fruiting','Harvesting','Completed','Failed') NOT NULL DEFAULT 'Incubation',
    notes                   TEXT,
    created_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (responsible_employee_id) REFERENCES employees(employee_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Harvest (yield records tied to a production batch)
-- ---------------------------------------------------------------------
CREATE TABLE harvest (
    harvest_id      INT AUTO_INCREMENT PRIMARY KEY,
    production_id   INT NOT NULL,
    harvest_date    DATE NOT NULL,
    weight_kg       DECIMAL(10,2) NOT NULL DEFAULT 0,
    grade           ENUM('A','B','C','Reject') NOT NULL DEFAULT 'A',
    harvested_by    INT,
    notes           TEXT,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (production_id) REFERENCES production(production_id) ON DELETE CASCADE,
    FOREIGN KEY (harvested_by) REFERENCES employees(employee_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Processing (post-harvest processing: drying, powdering, packing)
-- ---------------------------------------------------------------------
CREATE TABLE processing (
    processing_id     INT AUTO_INCREMENT PRIMARY KEY,
    harvest_id        INT NOT NULL,
    process_type       ENUM('Fresh Pack','Dried','Powdered','Marinated','Other') NOT NULL DEFAULT 'Fresh Pack',
    input_weight_kg    DECIMAL(10,2) NOT NULL DEFAULT 0,
    output_weight_kg   DECIMAL(10,2) NOT NULL DEFAULT 0,
    output_unit        VARCHAR(20) NOT NULL DEFAULT 'kg',
    process_date        DATE NOT NULL,
    processed_by        INT,
    status               ENUM('In Progress','Completed') NOT NULL DEFAULT 'Completed',
    notes                TEXT,
    created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (harvest_id) REFERENCES harvest(harvest_id) ON DELETE CASCADE,
    FOREIGN KEY (processed_by) REFERENCES employees(employee_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Sales
-- ---------------------------------------------------------------------
CREATE TABLE sales (
    sale_id         INT AUTO_INCREMENT PRIMARY KEY,
    invoice_no      VARCHAR(30) NOT NULL UNIQUE,
    customer_name   VARCHAR(100) NOT NULL,
    customer_contact VARCHAR(50),
    product_name    VARCHAR(100) NOT NULL,
    quantity        DECIMAL(10,2) NOT NULL DEFAULT 0,
    unit            VARCHAR(20) NOT NULL DEFAULT 'kg',
    unit_price      DECIMAL(10,2) NOT NULL DEFAULT 0,
    total_amount    DECIMAL(10,2) NOT NULL DEFAULT 0,
    sale_date       DATE NOT NULL,
    payment_status  ENUM('Paid','Pending','Partial') NOT NULL DEFAULT 'Paid',
    sold_by         INT,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sold_by) REFERENCES employees(employee_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Delivery
-- ---------------------------------------------------------------------
CREATE TABLE delivery (
    delivery_id       INT AUTO_INCREMENT PRIMARY KEY,
    sale_id           INT NOT NULL,
    delivery_address   TEXT NOT NULL,
    delivery_date       DATE,
    rider_name           VARCHAR(100),
    contact_number       VARCHAR(20),
    delivery_status      ENUM('Pending','In Transit','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
    delivery_fee          DECIMAL(10,2) NOT NULL DEFAULT 0,
    notes                 TEXT,
    created_at             TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at             TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sale_id) REFERENCES sales(sale_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Expenses
-- ---------------------------------------------------------------------
CREATE TABLE expenses (
    expense_id      INT AUTO_INCREMENT PRIMARY KEY,
    category        ENUM('Substrate','Utilities','Labor','Equipment','Transport','Packaging','Rent','Other') NOT NULL DEFAULT 'Other',
    description     VARCHAR(255) NOT NULL,
    amount          DECIMAL(10,2) NOT NULL DEFAULT 0,
    expense_date    DATE NOT NULL,
    recorded_by     INT,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recorded_by) REFERENCES employees(employee_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================================
-- Seed data
-- =====================================================================

INSERT INTO employees (first_name, last_name, username, password, email, phone, position, role, date_hired, salary, status) VALUES
('Juan', 'Dela Cruz', 'admin', '$2y$10$IRDvIev88sv4ueM2qkmMvesy490UnUfYetFzwt/cr7RuTlU38i0Tq', 'admin@cmd.local', '09171234567', 'System Administrator', 'admin', '2024-01-15', 25000.00, 'active'),
('Maria', 'Santos', 'mstaff', '$2y$10$IRDvIev88sv4ueM2qkmMvesy490UnUfYetFzwt/cr7RuTlU38i0Tq', 'maria@cmd.local', '09181234567', 'Farm Supervisor', 'manager', '2024-02-01', 18000.00, 'active'),
('Pedro', 'Reyes', 'preyes', '$2y$10$IRDvIev88sv4ueM2qkmMvesy490UnUfYetFzwt/cr7RuTlU38i0Tq', 'pedro@cmd.local', '09191234567', 'Harvester', 'staff', '2024-03-10', 14000.00, 'active');
-- default password for all seeded accounts is: password123

INSERT INTO inventory (item_name, category, quantity, unit, reorder_level, unit_cost, supplier, last_restocked, status) VALUES
('Sawdust Substrate', 'Substrate', 500.00, 'kg', 100.00, 8.50, 'Negros Sawdust Supply', '2026-08-20', 'In Stock'),
('Oyster Mushroom Spawn', 'Spawn', 25.00, 'kg', 10.00, 120.00, 'Bacolod Mushroom Spawn Center', '2026-08-15', 'In Stock'),
('Polypropylene Bags', 'Packaging', 300.00, 'pcs', 100.00, 3.00, 'Negros Packaging Co.', '2026-08-10', 'In Stock'),
('Rice Bran', 'Substrate', 40.00, 'kg', 50.00, 22.00, 'Bacolod Feeds & Grains', '2026-08-05', 'Low Stock'),
('Lime (Agricultural)', 'Supplies', 15.00, 'kg', 20.00, 15.00, 'Negros Sawdust Supply', '2026-07-28', 'Low Stock');

INSERT INTO production (batch_code, mushroom_variety, substrate_type, bag_quantity, inoculation_date, expected_harvest_date, growing_location, responsible_employee_id, status, notes) VALUES
('BATCH-2026-001', 'Oyster Mushroom', 'Sawdust + Rice Bran', 150, '2026-08-01', '2026-09-01', 'Grow House A', 2, 'Fruiting', 'Healthy mycelium growth observed'),
('BATCH-2026-002', 'Oyster Mushroom', 'Sawdust + Rice Bran', 200, '2026-08-15', '2026-09-15', 'Grow House B', 2, 'Incubation', NULL),
('BATCH-2026-003', 'Shiitake', 'Sawdust Blocks', 100, '2026-07-10', '2026-09-10', 'Grow House A', 3, 'Harvesting', 'First flush completed');

INSERT INTO harvest (production_id, harvest_date, weight_kg, grade, harvested_by, notes) VALUES
(1, '2026-09-02', 12.50, 'A', 3, 'First flush'),
(3, '2026-09-05', 8.20, 'A', 3, NULL),
(3, '2026-09-10', 5.10, 'B', 3, 'Second flush, slightly smaller caps');

INSERT INTO processing (harvest_id, process_type, input_weight_kg, output_weight_kg, output_unit, process_date, processed_by, status, notes) VALUES
(1, 'Fresh Pack', 12.50, 12.00, 'kg', '2026-09-02', 2, 'Completed', 'Packed into 500g retail packs'),
(2, 'Dried', 8.20, 0.90, 'kg', '2026-09-06', 2, 'Completed', 'Sun-dried for 3 days');

INSERT INTO sales (invoice_no, customer_name, customer_contact, product_name, quantity, unit, unit_price, total_amount, sale_date, payment_status, sold_by) VALUES
('INV-2026-0001', 'Bacolod Public Market Vendor', '09201234567', 'Fresh Oyster Mushroom', 10.00, 'kg', 180.00, 1800.00, '2026-09-03', 'Paid', 2),
('INV-2026-0002', 'Negros Grocery Mart', '09221234567', 'Dried Shiitake Mushroom', 0.90, 'kg', 1200.00, 1080.00, '2026-09-07', 'Pending', 2);

INSERT INTO delivery (sale_id, delivery_address, delivery_date, rider_name, contact_number, delivery_status, delivery_fee, notes) VALUES
(1, 'Bacolod Public Market, Bacolod City', '2026-09-03', 'Ramon Cruz', '09301234567', 'Delivered', 50.00, NULL),
(2, 'Negros Grocery Mart, Lacson St., Bacolod City', '2026-09-08', 'Ramon Cruz', '09301234567', 'In Transit', 80.00, NULL);

INSERT INTO expenses (category, description, amount, expense_date, recorded_by) VALUES
('Substrate', 'Purchase of sawdust and rice bran', 4500.00, '2026-08-01', 1),
('Utilities', 'Electricity bill - Grow House A & B', 3200.00, '2026-08-31', 1),
('Labor', 'Weekly wages - harvesting team', 6000.00, '2026-09-05', 1),
('Packaging', 'Polypropylene bags and labels', 900.00, '2026-08-10', 2),
('Transport', 'Delivery fuel and rider allowance', 650.00, '2026-09-03', 2);
