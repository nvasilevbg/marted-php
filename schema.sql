-- MySQL schema za superhosting (izpulni v phpMyAdmin)
CREATE TABLE IF NOT EXISTS settings (k VARCHAR(64) PRIMARY KEY, v TEXT) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS projects (
  id INT AUTO_INCREMENT PRIMARY KEY, slug VARCHAR(160) UNIQUE, title VARCHAR(255), category VARCHAR(120),
  pdate VARCHAR(120), location VARCHAR(255), description TEXT, cover VARCHAR(500), gallery TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY, bdate VARCHAR(20), slot VARCHAR(10), name VARCHAR(160), phone VARCHAR(60),
  service VARCHAR(160), notes TEXT, status VARCHAR(20) DEFAULT 'pending', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE bookings ADD UNIQUE INDEX idx_bookings_slot (bdate, slot);
CREATE TABLE IF NOT EXISTS services (
  id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255), stext TEXT, icon VARCHAR(60), image VARCHAR(500), sort_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS testimonials (
  id INT AUTO_INCREMENT PRIMARY KEY, tname VARCHAR(160), ttext TEXT, stars INT DEFAULT 5, sort_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS stats (
  id INT AUTO_INCREMENT PRIMARY KEY, svalue VARCHAR(120), slabel VARCHAR(160), sort_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO settings (k,v) VALUES
('name','MarTed'),('subtitle','Ð¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸'),('tagline','Ð¤Ð¸Ñ€Ð¼Ð° Ð·Ð° Ð¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð¸ Ð´ÐµÐ¼Ð¾Ð½Ñ‚Ð°Ð¶'),
('phone','0898 535 885'),('phoneHref','tel:+359898535885'),('email','marted.montaj@gmail.com'),
('location','Ð³Ñ€. Ð”Ð¾Ð±Ñ€Ð¸Ñ‡ Ð¸ Ð¾ÐºÐ¾Ð»Ð½Ð¾ÑÑ‚Ñ‚Ð°'),('region','Ð Ð°Ð±Ð¾Ñ‚Ð¸Ð¼ Ð² Ð”Ð¾Ð±Ñ€Ð¸Ñ‡, Ð‘Ð°Ð»Ñ‡Ð¸Ðº, Ð’Ð°Ñ€Ð½Ð° Ð¸ Ð¾ÐºÐ¾Ð»Ð½Ð¾ÑÑ‚Ñ‚Ð°'),
('hours','ÐŸÐ¾Ð½ â€“ ÐÐµÐ´: 08:00 â€“ 20:00'),('established','2019'),
('home_hero_title','ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð¸ Ð´ÐµÐ¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸'),
('home_hero_lead','ÐŸÑ€Ð¾Ñ„ÐµÑÐ¸Ð¾Ð½Ð°Ð»ÐµÐ½ Ð¼Ð¾Ð½Ñ‚Ð°Ð¶, Ð´ÐµÐ¼Ð¾Ð½Ñ‚Ð°Ð¶, Ñ€Ð°Ð·Ð½Ð¾Ñ Ð¸ Ð¸Ð·Ð½Ð°ÑÑÐ½Ðµ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸ Ð² Ð”Ð¾Ð±Ñ€Ð¸Ñ‡, Ð‘Ð°Ð»Ñ‡Ð¸Ðº, Ð’Ð°Ñ€Ð½Ð° Ð¸ Ð¾ÐºÐ¾Ð»Ð½Ð¾ÑÑ‚Ñ‚Ð° â€” Ñ Ð³Ð°Ñ€Ð°Ð½Ñ†Ð¸Ñ Ð·Ð° ÐºÐ°Ñ‡ÐµÑÑ‚Ð²Ð¾, Ñ‚Ð¾Ñ‡Ð½Ð¾ÑÑ‚ Ð¸ Ñ‡Ð¸ÑÑ‚ Ð·Ð°Ð²ÑŠÑ€ÑˆÐµÐº.'),
('home_hero_image','/assets/media/hero-kitchen.jpg'),('about_heading','ÐŸÑŠÑ€Ð²Ð¾ ÑÐµ ÑƒÑ‚Ð¾Ñ‡Ð½ÑÐ²Ð° Ð·Ð°Ð´Ð°Ñ‡Ð°Ñ‚Ð°. Ð¡Ð»ÐµÐ´ Ñ‚Ð¾Ð²Ð° ÑÐµ Ñ€Ð°Ð±Ð¾Ñ‚Ð¸.'),
('about_text','Ð’ÑÐµÐºÐ¸ Ð¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð·Ð°Ð¿Ð¾Ñ‡Ð²Ð° Ñ Ð¿Ñ€Ð¾Ð²ÐµÑ€ÐºÐ° Ð½Ð° Ð¾Ð±ÐµÐºÑ‚Ð°, Ñ€Ð°Ð·Ð¼ÐµÑ€Ð¸Ñ‚Ðµ Ð¸ Ð¾ÑÐ¾Ð±ÐµÐ½Ð¾ÑÑ‚Ð¸Ñ‚Ðµ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸Ñ‚Ðµ. Ð¦ÐµÐ»Ñ‚Ð° Ðµ Ð´Ð° Ð½ÑÐ¼Ð° Ð¸Ð·Ð½ÐµÐ½Ð°Ð´Ð¸, ÐºÑ€Ð¸Ð² Ð¼Ð¾Ð½Ñ‚Ð°Ð¶, Ð»Ð¸Ð¿ÑÐ²Ð°Ñ‰Ð¸ ÐµÐ»ÐµÐ¼ÐµÐ½Ñ‚Ð¸ Ð¸Ð»Ð¸ Ð½ÐµÐ´Ð¾Ð²ÑŠÑ€ÑˆÐµÐ½Ð° Ñ€Ð°Ð±Ð¾Ñ‚Ð°.');

INSERT INTO projects (slug,title,category,pdate,location,description,cover,gallery) VALUES
('kuhnya-po-porachka','ÐšÑƒÑ…Ð½Ñ Ð¿Ð¾ Ð¿Ð¾Ñ€ÑŠÑ‡ÐºÐ°','ÐšÑƒÑ…Ð½Ð¸','Ð®Ð»Ð¸ 2024','Ð”Ð¾Ð±Ñ€Ð¸Ñ‡','ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° ÐºÑƒÑ…Ð½Ñ Ð¿Ð¾ Ð¿Ð¾Ñ€ÑŠÑ‡ÐºÐ° Ñ Ð¾ÑÑ‚Ñ€Ð¾Ð², Ð²Ð³Ñ€Ð°Ð´ÐµÐ½Ð¸ ÑˆÐºÐ°Ñ„Ð¾Ð²Ðµ Ð¸ Ð·Ð°Ð²ÑŠÑ€ÑˆÐ²Ð°Ñ‰Ð¸ Ð¿Ð°Ð½ÐµÐ»Ð¸.','/assets/media/kitchen-2.jpg','["/assets/media/kitchen-2.jpg","/assets/media/kitchen-1.jpg","/assets/media/kitchen-3.jpg","/assets/media/kitchen-4.jpg"]'),
('moderna-kuhnya','ÐœÐ¾Ð´ÐµÑ€Ð½Ð° ÐºÑƒÑ…Ð½Ñ','ÐšÑƒÑ…Ð½Ð¸','Ð®Ð½Ð¸ 2024','Ð‘Ð°Ð»Ñ‡Ð¸Ðº','ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¼Ð¾Ð´ÐµÑ€Ð½Ð° ÐºÑƒÑ…Ð½Ñ Ñ Ð¾ÑÑ‚Ñ€Ð¾Ð², Ð²Ð³Ñ€Ð°Ð´ÐµÐ½Ð¸ ÑˆÐºÐ°Ñ„Ð¾Ð²Ðµ Ð¸ Ð·Ð°Ð²ÑŠÑ€ÑˆÐ²Ð°Ñ‰Ð¸ Ð¿Ð°Ð½ÐµÐ»Ð¸.','/assets/media/kitchen-3.jpg','["/assets/media/kitchen-3.jpg","/assets/media/kitchen-4.jpg","/assets/media/kitchen-1.jpg"]'),
('spalnya-i-garderob','Ð¡Ð¿Ð°Ð»Ð½Ñ Ð¸ Ð³Ð°Ñ€Ð´ÐµÑ€Ð¾Ð±','Ð¡Ð¿Ð°Ð»Ð½Ð¸','ÐÐ¿Ñ€Ð¸Ð» 2024','Ð”Ð¾Ð±Ñ€Ð¸Ñ‡','Ð¡Ð³Ð»Ð¾Ð±ÑÐ²Ð°Ð½Ðµ Ð½Ð° ÑÐ¿Ð°Ð»Ð½Ñ, Ð½Ð¾Ñ‰Ð½Ð¸ ÑˆÐºÐ°Ñ„Ñ‡ÐµÑ‚Ð° Ð¸ Ð³Ð°Ñ€Ð´ÐµÑ€Ð¾Ð± Ñ Ð¿Ð»ÑŠÐ·Ð³Ð°Ñ‰Ð¸ ÑÐµ Ð²Ñ€Ð°Ñ‚Ð¸.','/assets/media/bedroom-1.jpg','["/assets/media/bedroom-1.jpg","/assets/media/bedroom-2.jpg","/assets/media/living-3.jpg"]'),
('garderob-po-razmer','Ð“Ð°Ñ€Ð´ÐµÑ€Ð¾Ð± Ð¿Ð¾ Ñ€Ð°Ð·Ð¼ÐµÑ€','Ð“Ð°Ñ€Ð´ÐµÑ€Ð¾Ð±Ð¸','ÐœÐ°Ñ€Ñ‚ 2024','Ð’Ð°Ñ€Ð½Ð°','ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð²Ð¸ÑÐ¾Ðº Ð³Ð°Ñ€Ð´ÐµÑ€Ð¾Ð± Ð¿Ð¾ Ñ€Ð°Ð·Ð¼ÐµÑ€ Ñ Ð¿Ð»Ð°Ð²Ð½Ð¾ Ð·Ð°Ñ‚Ð²Ð°Ñ€ÑÐ½Ðµ Ð¸ Ð²ÑŠÑ‚Ñ€ÐµÑˆÐ½Ð¾ Ñ€Ð°Ð·Ð¿Ñ€ÐµÐ´ÐµÐ»ÐµÐ½Ð¸Ðµ.','/assets/media/bedroom-2.jpg','["/assets/media/bedroom-2.jpg","/assets/media/bedroom-1.jpg","/assets/media/kitchen-4.jpg"]'),
('sektsiya-za-dnevna','Ð¡ÐµÐºÑ†Ð¸Ñ Ð·Ð° Ð´Ð½ÐµÐ²Ð½Ð°','Ð”Ñ€ÑƒÐ³Ð¸','Ð¤ÐµÐ²Ñ€ÑƒÐ°Ñ€Ð¸ 2024','Ð”Ð¾Ð±Ñ€Ð¸Ñ‡','ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° ÑÐµÐºÑ†Ð¸Ñ Ð·Ð° Ð´Ð½ÐµÐ²Ð½Ð° Ñ Ð¢Ð’ Ð¿Ð°Ð½ÐµÐ», ÑˆÐºÐ°Ñ„Ð¾Ð²Ðµ Ð¸ ÑÐºÑ€Ð¸Ñ‚ Ð¼Ð¾Ð½Ñ‚Ð°Ð¶.','/assets/media/living-1.jpg','["/assets/media/living-1.jpg","/assets/media/living-2.jpg","/assets/media/living-3.jpg"]'),
('ofis-obzavezhdane','ÐžÑ„Ð¸Ñ Ð¾Ð±Ð·Ð°Ð²ÐµÐ¶Ð´Ð°Ð½Ðµ','Ð”Ñ€ÑƒÐ³Ð¸','Ð¯Ð½ÑƒÐ°Ñ€Ð¸ 2024','Ð”Ð¾Ð±Ñ€Ð¸Ñ‡','ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¾Ñ„Ð¸Ñ Ð±ÑŽÑ€Ð¾, ÑˆÐºÐ°Ñ„Ð¾Ð²Ðµ, ÐµÑ‚Ð°Ð¶ÐµÑ€ÐºÐ¸ Ð¸ ÐºÐ¾Ð½Ñ„ÐµÑ€ÐµÐ½Ñ‚Ð½Ð° Ð¼Ð°ÑÐ°.','/assets/media/living-3.jpg','["/assets/media/living-3.jpg","/assets/media/living-2.jpg","/assets/media/kitchen-4.jpg"]');

INSERT INTO services (title,stext,icon,image,sort_order) VALUES
('ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸','ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° ÐºÑƒÑ…Ð½Ð¸, ÑÐ¿Ð°Ð»Ð½Ð¸, Ð³Ð°Ñ€Ð´ÐµÑ€Ð¾Ð±Ð¸, ÑÐµÐºÑ†Ð¸Ð¸ Ð¸ Ð²ÑÑÐºÐ°ÐºÐ²Ð¸ Ð¼ÐµÐ±ÐµÐ»Ð¸.','drill','/assets/media/kitchen-1.jpg',1),
('Ð”ÐµÐ¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸','ÐŸÑ€Ð¾Ñ„ÐµÑÐ¸Ð¾Ð½Ð°Ð»ÐµÐ½ Ð´ÐµÐ¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð¸ Ð¾Ð¿Ð°ÐºÐ¾Ð²Ð°Ð½Ðµ Ð¿Ñ€Ð¸ Ð¿Ñ€ÐµÐ½Ð°ÑÑÐ½Ðµ Ð¸Ð»Ð¸ Ñ€ÐµÐ¼Ð¾Ð½Ñ‚.','demolition','',2),
('Ð Ð°Ð·Ð½Ð¾Ñ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸','Ð¢Ñ€Ð°Ð½ÑÐ¿Ð¾Ñ€Ñ‚ Ð¸ Ñ€Ð°Ð·Ð½Ð¾Ñ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸ Ð´Ð¾ Ð°Ð´Ñ€ÐµÑ Ð¸ ÐµÑ‚Ð°Ð¶ Ð¿Ð¾ Ð²Ð°Ñˆ Ð¸Ð·Ð±Ð¾Ñ€.','truck','',3),
('Ð˜Ð·Ð½Ð°ÑÑÐ½Ðµ Ð½Ð° ÑÑ‚Ð°Ñ€Ð¸ Ð¼ÐµÐ±ÐµÐ»Ð¸','Ð˜Ð·Ð½Ð°ÑÑÐ¼Ðµ Ð¸ Ð¸Ð·Ð²Ð¾Ð·Ð²Ð°Ð¼Ðµ ÑÑ‚Ð°Ñ€Ð¸ Ð¼ÐµÐ±ÐµÐ»Ð¸ Ð¸ Ð½ÐµÐ½ÑƒÐ¶Ð½Ð¸ Ð²ÐµÑ‰Ð¸.','box','',4),
('Ð—Ð°Ð¼ÐµÑ€Ð²Ð°Ð½Ðµ Ð¸ ÐºÐ¾Ð½ÑÑƒÐ»Ñ‚Ð°Ñ†Ð¸Ñ','Ð—Ð°Ð¼ÐµÑ€Ð²Ð°Ð½Ðµ Ð½Ð° Ð¼ÑÑÑ‚Ð¾ Ð¸ ÐºÐ¾Ð½ÑÑƒÐ»Ñ‚Ð°Ñ†Ð¸Ñ Ð·Ð° Ð²Ð°ÑˆÐ¸Ñ Ð¿Ñ€Ð¾ÐµÐºÑ‚.','measure','',5),
('ÐšÐ¾Ñ€ÐµÐºÑ‚Ð½Ð¾ÑÑ‚ Ð¸ Ð³Ð°Ñ€Ð°Ð½Ñ†Ð¸Ñ','Ð Ð°Ð±Ð¾Ñ‚Ð¸Ð¼ Ñ‡Ð¸ÑÑ‚Ð¾ Ð¸ Ð¿Ñ€ÐµÑ†Ð¸Ð·Ð½Ð¾ Ñ Ð³Ð°Ñ€Ð°Ð½Ñ†Ð¸Ñ Ð·Ð° ÐºÐ°Ñ‡ÐµÑÑ‚Ð²Ð¾.','shield','',6);

INSERT INTO testimonials (tname,ttext,stars,sort_order) VALUES
('Ð˜Ð²Ð°Ð½ ÐŸÐµÑ‚Ñ€Ð¾Ð²','ÐœÐ½Ð¾Ð³Ð¾ ÑÑŠÐ¼ Ð´Ð¾Ð²Ð¾Ð»ÐµÐ½ Ð¾Ñ‚ ÑƒÑÐ»ÑƒÐ³Ð°Ñ‚Ð°. Ð‘ÑŠÑ€Ð·Ð¸, Ñ‚Ð¾Ñ‡Ð½Ð¸ Ð¸ ÐºÐ¾Ñ€ÐµÐºÑ‚Ð½Ð¸. ÐŸÑ€ÐµÐ¿Ð¾Ñ€ÑŠÑ‡Ð²Ð°Ð¼.',5,1),
('ÐœÐ°Ñ€Ð¸Ñ Ð“ÐµÐ¾Ñ€Ð³Ð¸ÐµÐ²Ð°','Ð¡Ñ‚ÐµÐ³Ð½Ð°Ñ‚ ÐµÐºÐ¸Ð¿. ÐœÐ¾Ð½Ñ‚Ð¸Ñ€Ð°Ñ…Ð° Ð¼Ð¸ ÐºÑƒÑ…Ð½ÑÑ‚Ð° Ñ‡Ð¸ÑÑ‚Ð¾ Ð¸ Ð¿Ñ€ÐµÑ†Ð¸Ð·Ð½Ð¾. Ð‘Ð»Ð°Ð³Ð¾Ð´Ð°Ñ€Ñ.',5,2),
('Ð“ÐµÐ¾Ñ€Ð³Ð¸ Ð™Ð¾Ñ€Ð´Ð°Ð½Ð¾Ð²','ÐŸÑ€Ð¾Ñ„ÐµÑÐ¸Ð¾Ð½Ð°Ð»Ð¸ÑÑ‚Ð¸. Ð Ð°Ð±Ð¾Ñ‚ÑÑ‚ Ñ‚Ð¾Ñ‡Ð½Ð¾ Ð¸ Ð¿Ð¾Ð´Ñ€ÐµÐ´ÐµÐ½Ð¾. ÐœÐ½Ð¾Ð³Ð¾ ÑÑŠÐ¼ Ð´Ð¾Ð²Ð¾Ð»ÐµÐ½.',5,3);

INSERT INTO stats (svalue,slabel,sort_order) VALUES
('500+','Ð”Ð¾Ð²Ð¾Ð»Ð½Ð¸ ÐºÐ»Ð¸ÐµÐ½Ñ‚Ð¸',1),('1200+','ÐœÐ¾Ð½Ñ‚Ð¸Ñ€Ð°Ð½Ð¸ Ð¼ÐµÐ±ÐµÐ»Ð¸',2),('5+','Ð“Ð¾Ð´Ð¸Ð½Ð¸ Ð¾Ð¿Ð¸Ñ‚',3),('Ð”Ð¾Ð±Ñ€Ð¸Ñ‡','Ð¸ Ð¾ÐºÐ¾Ð»Ð½Ð¾ÑÑ‚Ñ‚Ð°',4);
