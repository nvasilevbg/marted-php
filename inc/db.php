<?php
// PDO connection + avto-instalaciq za sqlite (lokalen test). Za mysql se bijaga schema.sql.
function config() { static $c; if ($c === null) $c = require __DIR__ . '/config.php'; return $c; }

function db() {
    static $pdo = null;
    if ($pdo !== null) return $pdo;
    $cfg = config()['db'];
    if ($cfg['driver'] === 'sqlite') {
        $path = $cfg['sqlite_path'];
        $dir = dirname($path);
        if (!is_dir($dir)) @mkdir($dir, 0775, true);
        $pdo = new PDO('sqlite:' . $path);
        $pdo->exec('PRAGMA journal_mode=WAL; PRAGMA foreign_keys=ON;');
    } else {
        $m = $cfg['mysql'];
        $dsn = "mysql:host={$m['host']};dbname={$m['name']};charset={$m['charset']}";
        $pdo = new PDO($dsn, $m['user'], $m['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    db_install($pdo);
    return $pdo;
}

function db_install($pdo) {
    $exists = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='settings'")->fetch();
    if ($exists) return;
    $pdo->exec("CREATE TABLE settings (k TEXT PRIMARY KEY, v TEXT)");
    $pdo->exec("CREATE TABLE projects (id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT UNIQUE, title TEXT, category TEXT, pdate TEXT, location TEXT, description TEXT, cover TEXT, gallery TEXT, created_at TEXT DEFAULT (datetime('now')))");
    $pdo->exec("CREATE TABLE bookings (id INTEGER PRIMARY KEY AUTOINCREMENT, bdate TEXT, slot TEXT, name TEXT, phone TEXT, service TEXT, notes TEXT, status TEXT DEFAULT 'pending', created_at TEXT DEFAULT (datetime('now')))");
    $pdo->exec("CREATE TABLE services (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, stext TEXT, icon TEXT, image TEXT, sort_order INTEGER DEFAULT 0)");
    $pdo->exec("CREATE TABLE testimonials (id INTEGER PRIMARY KEY AUTOINCREMENT, tname TEXT, ttext TEXT, stars INTEGER DEFAULT 5, sort_order INTEGER DEFAULT 0)");
    $pdo->exec("CREATE TABLE stats (id INTEGER PRIMARY KEY AUTOINCREMENT, svalue TEXT, slabel TEXT, sort_order INTEGER DEFAULT 0)");
    db_seed($pdo);
}

function db_seed($pdo) {
    $s = [
        'name'=>'MarTed','subtitle'=>'монтаж на мебели','tagline'=>'Фирма за монтаж и демонтаж',
        'phone'=>'0898 535 885','phoneHref'=>'tel:+359898535885','email'=>'marted.montaj@gmail.com',
        'location'=>'гр. Добрич и околността','region'=>'Работим в Добрич, Балчик, Варна и околността',
        'hours'=>'Пон – Нед: 08:00 – 20:00','established'=>'2019',
    ];
    $st = $pdo->prepare("INSERT INTO settings (k,v) VALUES (?,?)");
    foreach ($s as $k=>$v) $st->execute([$k,$v]);
    // page content
    $content = [
        'home_hero_title'=>'Монтаж и демонтаж на мебели',
        'home_hero_lead'=>'Професионален монтаж, демонтаж, разнос и изнасяне на мебели в Добрич, Балчик, Варна и околността — с гаранция за качество, точност и чист завършек.',
        'about_heading'=>'Първо се уточнява задачата. След това се работи.',
        'about_text'=>'Всеки монтаж започва с проверка на обекта, размерите и особеностите на мебелите. Целта е да няма изненади, крив монтаж, липсващи елементи или недовършена работа.',
    ];
    $st2 = $pdo->prepare("INSERT INTO settings (k,v) VALUES (?,?)");
    foreach ($content as $k=>$v) $st2->execute([$k,$v]);
    // services
    $svcs = [
        ['Монтаж на мебели','Монтаж на кухни, спални, гардероби, секции и всякакви мебели.','drill','/assets/media/kitchen-1.jpg',1],
        ['Демонтаж на мебели','Професионален демонтаж и опаковане при пренасяне или ремонт.','demolition','',2],
        ['Разнос на мебели','Транспорт и разнос на мебели до адрес и етаж по ваш избор.','truck','',3],
        ['Изнасяне на стари мебели','Изнасяме и извозваме стари мебели и ненужни вещи.','box','',4],
        ['Замерване и консултация','Замерване на място и консултация за вашия проект.','measure','',5],
        ['Коректност и гаранция','Работим чисто и прецизно с гаранция за качество.','shield','',6],
    ];
    $st3 = $pdo->prepare("INSERT INTO services (title,stext,icon,image,sort_order) VALUES (?,?,?,?,?)");
    foreach ($svcs as $s) $st3->execute($s);
    // testimonials
    $tests = [
        ['Иван Петров','Много съм доволен от услугата. Бързи, точни и коректни. Препоръчвам.',5,1],
        ['Мария Георгиева','Стегнат екип. Монтираха ми кухнята чисто и прецизно. Благодаря.',5,2],
        ['Георги Йорданов','Професионалисти. Работят точно и подредено. Много съм доволен.',5,3],
    ];
    $st4 = $pdo->prepare("INSERT INTO testimonials (tname,ttext,stars,sort_order) VALUES (?,?,?,?)");
    foreach ($tests as $t) $st4->execute($t);
    // stats
    $stats = [
        ['500+','Доволни клиенти',1],['1200+','Монтирани мебели',2],['5+','Години опит',3],['Добрич','и околността',4],
    ];
    $st5 = $pdo->prepare("INSERT INTO stats (svalue,slabel,sort_order) VALUES (?,?,?)");
    foreach ($stats as $s) $st5->execute($s);
    $seed = [
        ['kuhnya-po-porachka','Кухня по поръчка','Кухни','Юли 2024','Добрич','Монтаж на кухня по поръчка с остров, вградени шкафове и завършващи панели.','/assets/media/kitchen-2.jpg',['/assets/media/kitchen-2.jpg','/assets/media/kitchen-1.jpg','/assets/media/kitchen-3.jpg','/assets/media/kitchen-4.jpg']],
        ['moderna-kuhnya','Модерна кухня','Кухни','Юни 2024','Балчик','Монтаж на модерна кухня с остров, вградени шкафове и завършващи панели.','/assets/media/kitchen-3.jpg',['/assets/media/kitchen-3.jpg','/assets/media/kitchen-4.jpg','/assets/media/kitchen-1.jpg']],
        ['spalnya-i-garderob','Спалня и гардероб','Спални','Април 2024','Добрич','Сглобяване на спалня, нощни шкафчета и гардероб с плъзгащи се врати.','/assets/media/bedroom-1.jpg',['/assets/media/bedroom-1.jpg','/assets/media/bedroom-2.jpg','/assets/media/living-3.jpg']],
        ['garderob-po-razmer','Гардероб по размер','Гардероби','Март 2024','Варна','Монтаж на висок гардероб по размер с плавно затваряне и вътрешно разпределение.','/assets/media/bedroom-2.jpg',['/assets/media/bedroom-2.jpg','/assets/media/bedroom-1.jpg','/assets/media/kitchen-4.jpg']],
        ['sektsiya-za-dnevna','Секция за дневна','Други','Февруари 2024','Добрич','Монтаж на секция за дневна с ТВ панел, шкафове и скрит монтаж.','/assets/media/living-1.jpg',['/assets/media/living-1.jpg','/assets/media/living-2.jpg','/assets/media/living-3.jpg']],
        ['ofis-obzavezhdane','Офис обзавеждане','Други','Януари 2024','Добрич','Монтаж на офис бюро, шкафове, етажерки и конферентна маса.','/assets/media/living-3.jpg',['/assets/media/living-3.jpg','/assets/media/living-2.jpg','/assets/media/kitchen-4.jpg']],
    ];
    $st = $pdo->prepare("INSERT INTO projects (slug,title,category,pdate,location,description,cover,gallery) VALUES (?,?,?,?,?,?,?,?)");
    foreach ($seed as $p) $st->execute([$p[0],$p[1],$p[2],$p[3],$p[4],$p[5],$p[6],json_encode($p[7])]);
}