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
('name','MarTed'),('subtitle','монтаж на мебели'),('tagline','Фирма за монтаж и демонтаж'),
('phone','0898 535 885'),('phoneHref','tel:+359898535885'),('email','marted.montaj@gmail.com'),
('location','гр. Добрич и околността'),('region','Работим в Добрич, Балчик, Варна и околността'),
('hours','Пон – Нед: 08:00 – 20:00'),('established','2019'),
('home_hero_title','Монтаж и демонтаж на мебели'),
('home_hero_lead','Професионален монтаж, демонтаж, разнос и изнасяне на мебели в Добрич, Балчик, Варна и околността — с гаранция за качество, точност и чист завършек.'),
('home_hero_image','/assets/media/hero-kitchen.jpg'),('about_heading','Първо се уточнява задачата. След това се работи.'),
('about_text','Всеки монтаж започва с проверка на обекта, размерите и особеностите на мебелите. Целта е да няма изненади, крив монтаж, липсващи елементи или недовършена работа.');

INSERT INTO projects (slug,title,category,pdate,location,description,cover,gallery) VALUES
('kuhnya-po-porachka','Кухня по поръчка','Кухни','Юли 2024','Добрич','Монтаж на кухня по поръчка с остров, вградени шкафове и завършващи панели.','/assets/media/kitchen-2.jpg','["/assets/media/kitchen-2.jpg","/assets/media/kitchen-1.jpg","/assets/media/kitchen-3.jpg","/assets/media/kitchen-4.jpg"]'),
('moderna-kuhnya','Модерна кухня','Кухни','Юни 2024','Балчик','Монтаж на модерна кухня с остров, вградени шкафове и завършващи панели.','/assets/media/kitchen-3.jpg','["/assets/media/kitchen-3.jpg","/assets/media/kitchen-4.jpg","/assets/media/kitchen-1.jpg"]'),
('spalnya-i-garderob','Спалня и гардероб','Спални','Април 2024','Добрич','Сглобяване на спалня, нощни шкафчета и гардероб с плъзгащи се врати.','/assets/media/bedroom-1.jpg','["/assets/media/bedroom-1.jpg","/assets/media/bedroom-2.jpg","/assets/media/living-3.jpg"]'),
('garderob-po-razmer','Гардероб по размер','Гардероби','Март 2024','Варна','Монтаж на висок гардероб по размер с плавно затваряне и вътрешно разпределение.','/assets/media/bedroom-2.jpg','["/assets/media/bedroom-2.jpg","/assets/media/bedroom-1.jpg","/assets/media/kitchen-4.jpg"]'),
('sektsiya-za-dnevna','Секция за дневна','Други','Февруари 2024','Добрич','Монтаж на секция за дневна с ТВ панел, шкафове и скрит монтаж.','/assets/media/living-1.jpg','["/assets/media/living-1.jpg","/assets/media/living-2.jpg","/assets/media/living-3.jpg"]'),
('ofis-obzavezhdane','Офис обзавеждане','Други','Януари 2024','Добрич','Монтаж на офис бюро, шкафове, етажерки и конферентна маса.','/assets/media/living-3.jpg','["/assets/media/living-3.jpg","/assets/media/living-2.jpg","/assets/media/kitchen-4.jpg"]');

INSERT INTO services (title,stext,icon,image,sort_order) VALUES
('Монтаж на мебели','Монтаж на кухни, спални, гардероби, секции и всякакви мебели.','drill','/assets/media/kitchen-1.jpg',1),
('Демонтаж на мебели','Професионален демонтаж и опаковане при пренасяне или ремонт.','demolition','',2),
('Разнос на мебели','Транспорт и разнос на мебели до адрес и етаж по ваш избор.','truck','',3),
('Изнасяне на стари мебели','Изнасяме и извозваме стари мебели и ненужни вещи.','box','',4),
('Замерване и консултация','Замерване на място и консултация за вашия проект.','measure','',5),
('Коректност и гаранция','Работим чисто и прецизно с гаранция за качество.','shield','',6);

INSERT INTO testimonials (tname,ttext,stars,sort_order) VALUES
('Иван Петров','Много съм доволен от услугата. Бързи, точни и коректни. Препоръчвам.',5,1),
('Мария Георгиева','Стегнат екип. Монтираха ми кухнята чисто и прецизно. Благодаря.',5,2),
('Георги Йорданов','Професионалисти. Работят точно и подредено. Много съм доволен.',5,3);

INSERT INTO stats (svalue,slabel,sort_order) VALUES
('500+','Доволни клиенти',1),('1200+','Монтирани мебели',2),('5+','Години опит',3),('Добрич','и околността',4);