-- MySQL schema za superhosting (izpulni v phpMyAdmin ili cPanel > MySQL)
CREATE TABLE IF NOT EXISTS settings (k VARCHAR(64) PRIMARY KEY, v TEXT) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(160) UNIQUE, title VARCHAR(255), category VARCHAR(120),
  pdate VARCHAR(120), location VARCHAR(255), description TEXT, cover VARCHAR(500), gallery TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  bdate VARCHAR(20), slot VARCHAR(10), name VARCHAR(160), phone VARCHAR(60),
  service VARCHAR(160), notes TEXT, status VARCHAR(20) DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO settings (k,v) VALUES
('name','MarTed'),('subtitle','монтаж на мебели'),('tagline','Фирма за монтаж и демонтаж'),
('phone','0898 535 885'),('phoneHref','tel:+359898535885'),('email','marted.montaj@gmail.com'),
('location','гр. Добрич и околността'),('region','Работим в Добрич, Балчик, Варна и околността'),
('hours','Пон – Нед: 08:00 – 20:00'),('established','2019');

INSERT INTO projects (slug,title,category,pdate,location,description,cover,gallery) VALUES
('kuhnya-po-porachka','Кухня по поръчка','Кухни','Юли 2024','Добрич','Монтаж на кухня по поръчка с остров, вградени шкафове и завършващи панели.','/assets/media/kitchen-2.jpg','["/assets/media/kitchen-2.jpg","/assets/media/kitchen-1.jpg","/assets/media/kitchen-3.jpg","/assets/media/kitchen-4.jpg"]'),
('moderna-kuhnya','Модерна кухня','Кухни','Юни 2024','Балчик','Монтаж на модерна кухня с остров, вградени шкафове и завършващи панели.','/assets/media/kitchen-3.jpg','["/assets/media/kitchen-3.jpg","/assets/media/kitchen-4.jpg","/assets/media/kitchen-1.jpg"]'),
('spalnya-i-garderob','Спалня и гардероб','Спални','Април 2024','Добрич','Сглобяване на спалня, нощни шкафчета и гардероб с плъзгащи се врати.','/assets/media/bedroom-1.jpg','["/assets/media/bedroom-1.jpg","/assets/media/bedroom-2.jpg","/assets/media/living-3.jpg"]'),
('garderob-po-razmer','Гардероб по размер','Гардероби','Март 2024','Варна','Монтаж на висок гардероб по размер с плавно затваряне и вътрешно разпределение.','/assets/media/bedroom-2.jpg','["/assets/media/bedroom-2.jpg","/assets/media/bedroom-1.jpg","/assets/media/kitchen-4.jpg"]'),
('sektsiya-za-dnevna','Секция за дневна','Други','Февруари 2024','Добрич','Монтаж на секция за дневна с ТВ панел, шкафове и скрит монтаж.','/assets/media/living-1.jpg','["/assets/media/living-1.jpg","/assets/media/living-2.jpg","/assets/media/living-3.jpg"]'),
('ofis-obzavezhdane','Офис обзавеждане','Други','Януари 2024','Добрич','Монтаж на офис бюро, шкафове, етажерки и конферентна маса.','/assets/media/living-3.jpg','["/assets/media/living-3.jpg","/assets/media/living-2.jpg","/assets/media/kitchen-4.jpg"]');