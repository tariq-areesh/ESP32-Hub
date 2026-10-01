
CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
  category_id INT AUTO_INCREMENT PRIMARY KEY,
  category_name VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS projects (
  project_id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  title VARCHAR(150) NOT NULL,
  difficulty VARCHAR(50) NOT NULL,
  estimated_time VARCHAR(50) NOT NULL,
  short_description TEXT,
  CONSTRAINT fk_projects_category
    FOREIGN KEY (category_id)
    REFERENCES categories (category_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS feedback (
  feedback_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  project_title VARCHAR(150) NOT NULL,
  experience_level VARCHAR(100) NOT NULL,
  feedback_type VARCHAR(50) NOT NULL,
  improvements VARCHAR(255) NOT NULL,
  source VARCHAR(50) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contact_messages (
  message_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  subject VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS help_requests (
  request_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  description TEXT NOT NULL,
  file_path VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_help_requests_user
    FOREIGN KEY (user_id)
    REFERENCES users (user_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS help_solutions (
  solution_id INT AUTO_INCREMENT PRIMARY KEY,
  request_id INT NOT NULL,
  user_id INT NOT NULL,
  solution_text TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_help_solutions_request
    FOREIGN KEY (request_id)
    REFERENCES help_requests (request_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_help_solutions_user
    FOREIGN KEY (user_id)
    REFERENCES users (user_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO categories (category_name) VALUES
  ('Getting Started'),
  ('Sensors & Inputs'),
  ('Outputs & Actuators'),
  ('IoT & Connectivity')
ON DUPLICATE KEY UPDATE category_name = VALUES(category_name);

INSERT INTO projects (category_id, title, difficulty, estimated_time, short_description)
VALUES
  (1, 'Blink an LED', 'Beginner', '15 minutes', 'Your very first ESP32 sketch, blinking an LED.'),
  (1, 'Read a Button', 'Beginner', '20 minutes', 'Use a push button as a digital input.'),
  (2, 'Temperature & Humidity Monitor', 'Intermediate', '45 minutes', 'Read data from a DHT sensor and print it to the serial monitor.'),
  (2, 'Light Sensor with LDR', 'Intermediate', '40 minutes', 'Measure ambient light level and display it.'),
  (3, 'Servo Motor Control', 'Intermediate', '60 minutes', 'Move a servo to different angles.'),
  (3, 'RGB LED Strip Effects', 'Advanced', '90 minutes', 'Use the ESP32 to drive addressable RGB LEDs.'),
  (4, 'Wi-Fi Web Server', 'Advanced', '90 minutes', 'Expose a simple web page hosted by the ESP32.'),
  (4, 'IoT Data Logger', 'Advanced', '120 minutes', 'Send sensor data to an online service.');
