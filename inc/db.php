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
        db_migrate_mysql($pdo);
        return $pdo;
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    db_install($pdo);
    return $pdo;
}


function db_migrate_mysql($pdo) {
    $exists = $pdo->query("SHOW TABLES LIKE 'settings'")->fetch();
    if ($exists) return;
    $sql = @file_get_contents(__DIR__ . '/../schema.sql');
    if ($sql === false) return;
    foreach (preg_split('/;[\r\n]+/', $sql) as $stmt) {
        $stmt = trim($stmt);
        if ($stmt !== '') $pdo->exec($stmt);
    }
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
        'name'=>'MarTed','subtitle'=>'монÑâ€šаж на мебели','tagline'=>'ФиÑâ‚¬ма за монÑâ€šаж и демонÑâ€šаж',
        'phone'=>'0898 535 885','phoneHref'=>'tel:+359898535885','email'=>'marted.montaj@gmail.com',
        'location'=>'гÑâ‚¬. Ðâ€обÑâ‚¬иÑâ€¡ и околносÑâ€šÑâ€šа','region'=>'РабоÑâ€šим в Ðâ€обÑâ‚¬иÑâ€¡, Ðâ€˜алÑâ€¡ик, Ðâ€™аÑâ‚¬на и околносÑâ€šÑâ€šа',
        'hours'=>'Пон ââ‚¬â€œ Нед: 08:00 ââ‚¬â€œ 20:00','established'=>'2019',
    ];
    $st = $pdo->prepare("INSERT INTO settings (k,v) VALUES (?,?)");
    foreach ($s as $k=>$v) $st->execute([$k,$v]);
    // page content
    $content = [
        'home_hero_title'=>'МонÑâ€šаж и демонÑâ€šаж на мебели',
        'home_hero_lead'=>'ПÑâ‚¬оÑâ€žесионален монÑâ€šаж, демонÑâ€šаж, Ñâ‚¬азнос и изнасяне на мебели в Ðâ€обÑâ‚¬иÑâ€¡, Ðâ€˜алÑâ€¡ик, Ðâ€™аÑâ‚¬на и околносÑâ€šÑâ€šа ââ‚¬â€ с гаÑâ‚¬анÑâ€ ия за каÑâ€¡есÑâ€šво, Ñâ€šоÑâ€¡носÑâ€š и Ñâ€¡исÑâ€š завъÑâ‚¬шек.',
        'about_heading'=>'ПъÑâ‚¬во се уÑâ€šоÑâ€¡нява задаÑâ€¡аÑâ€šа. След Ñâ€šова се Ñâ‚¬абоÑâ€šи.',
        'home_hero_image'=>'/assets/media/hero-kitchen.jpg','about_text'=>'Ðâ€™секи монÑâ€šаж запоÑâ€¡ва с пÑâ‚¬овеÑâ‚¬ка на обекÑâ€šа, Ñâ‚¬азмеÑâ‚¬иÑâ€šе и особеносÑâ€šиÑâ€šе на мебелиÑâ€šе. ЦелÑâ€šа е да няма изненади, кÑâ‚¬ив монÑâ€šаж, липсваÑâ€°и елеменÑâ€šи или недовъÑâ‚¬шена Ñâ‚¬абоÑâ€šа.',
    ];
    $st2 = $pdo->prepare("INSERT INTO settings (k,v) VALUES (?,?)");
    foreach ($content as $k=>$v) $st2->execute([$k,$v]);
    // services
    $svcs = [
        ['МонÑâ€šаж на мебели','МонÑâ€šаж на куÑâ€¦ни, спални, гаÑâ‚¬деÑâ‚¬оби, секÑâ€ ии и всякакви мебели.','drill','/assets/media/kitchen-1.jpg',1],
        ['Ðâ€емонÑâ€šаж на мебели','ПÑâ‚¬оÑâ€žесионален демонÑâ€šаж и опаковане пÑâ‚¬и пÑâ‚¬енасяне или Ñâ‚¬емонÑâ€š.','demolition','',2],
        ['Разнос на мебели','ТÑâ‚¬анспоÑâ‚¬Ñâ€š и Ñâ‚¬азнос на мебели до адÑâ‚¬ес и еÑâ€šаж по ваш избоÑâ‚¬.','truck','',3],
        ['Изнасяне на сÑâ€šаÑâ‚¬и мебели','Изнасяме и извозваме сÑâ€šаÑâ‚¬и мебели и ненужни веÑâ€°и.','box','',4],
        ['Ðâ€”амеÑâ‚¬ване и консулÑâ€šаÑâ€ ия','Ðâ€”амеÑâ‚¬ване на мясÑâ€šо и консулÑâ€šаÑâ€ ия за вашия пÑâ‚¬оекÑâ€š.','measure','',5],
        ['КоÑâ‚¬екÑâ€šносÑâ€š и гаÑâ‚¬анÑâ€ ия','РабоÑâ€šим Ñâ€¡исÑâ€šо и пÑâ‚¬еÑâ€ изно с гаÑâ‚¬анÑâ€ ия за каÑâ€¡есÑâ€šво.','shield','',6],
    ];
    $st3 = $pdo->prepare("INSERT INTO services (title,stext,icon,image,sort_order) VALUES (?,?,?,?,?)");
    foreach ($svcs as $s) $st3->execute($s);
    // testimonials
    $tests = [
        ['Иван ПеÑâ€šÑâ‚¬ов','Много съм доволен оÑâ€š услугаÑâ€šа. Ðâ€˜ъÑâ‚¬зи, Ñâ€šоÑâ€¡ни и коÑâ‚¬екÑâ€šни. ПÑâ‚¬епоÑâ‚¬ъÑâ€¡вам.',5,1],
        ['МаÑâ‚¬ия Ðâ€œеоÑâ‚¬гиева','СÑâ€šегнаÑâ€š екип. МонÑâ€šиÑâ‚¬аÑâ€¦а ми куÑâ€¦няÑâ€šа Ñâ€¡исÑâ€šо и пÑâ‚¬еÑâ€ изно. Ðâ€˜лагодаÑâ‚¬я.',5,2],
        ['Ðâ€œеоÑâ‚¬ги Ðâ„¢оÑâ‚¬данов','ПÑâ‚¬оÑâ€žесионалисÑâ€šи. РабоÑâ€šяÑâ€š Ñâ€šоÑâ€¡но и подÑâ‚¬едено. Много съм доволен.',5,3],
    ];
    $st4 = $pdo->prepare("INSERT INTO testimonials (tname,ttext,stars,sort_order) VALUES (?,?,?,?)");
    foreach ($tests as $t) $st4->execute($t);
    // stats
    $stats = [
        ['500+','Ðâ€оволни клиенÑâ€šи',1],['1200+','МонÑâ€šиÑâ‚¬ани мебели',2],['5+','Ðâ€œодини опиÑâ€š',3],['Ðâ€обÑâ‚¬иÑâ€¡','и околносÑâ€šÑâ€šа',4],
    ];
    $st5 = $pdo->prepare("INSERT INTO stats (svalue,slabel,sort_order) VALUES (?,?,?)");
    foreach ($stats as $s) $st5->execute($s);
    $seed = [
        ['kuhnya-po-porachka','КуÑâ€¦ня по поÑâ‚¬ъÑâ€¡ка','КуÑâ€¦ни','Юли 2024','Ðâ€обÑâ‚¬иÑâ€¡','МонÑâ€šаж на куÑâ€¦ня по поÑâ‚¬ъÑâ€¡ка с осÑâ€šÑâ‚¬ов, вгÑâ‚¬адени шкаÑâ€žове и завъÑâ‚¬шваÑâ€°и панели.','/assets/media/kitchen-2.jpg',['/assets/media/kitchen-2.jpg','/assets/media/kitchen-1.jpg','/assets/media/kitchen-3.jpg','/assets/media/kitchen-4.jpg']],
        ['moderna-kuhnya','МодеÑâ‚¬на куÑâ€¦ня','КуÑâ€¦ни','Юни 2024','Ðâ€˜алÑâ€¡ик','МонÑâ€šаж на модеÑâ‚¬на куÑâ€¦ня с осÑâ€šÑâ‚¬ов, вгÑâ‚¬адени шкаÑâ€žове и завъÑâ‚¬шваÑâ€°и панели.','/assets/media/kitchen-3.jpg',['/assets/media/kitchen-3.jpg','/assets/media/kitchen-4.jpg','/assets/media/kitchen-1.jpg']],
        ['spalnya-i-garderob','Спалня и гаÑâ‚¬деÑâ‚¬об','Спални','АпÑâ‚¬ил 2024','Ðâ€обÑâ‚¬иÑâ€¡','Сглобяване на спалня, ноÑâ€°ни шкаÑâ€žÑâ€¡еÑâ€šа и гаÑâ‚¬деÑâ‚¬об с плъзгаÑâ€°и се вÑâ‚¬аÑâ€šи.','/assets/media/bedroom-1.jpg',['/assets/media/bedroom-1.jpg','/assets/media/bedroom-2.jpg','/assets/media/living-3.jpg']],
        ['garderob-po-razmer','Ðâ€œаÑâ‚¬деÑâ‚¬об по Ñâ‚¬азмеÑâ‚¬','Ðâ€œаÑâ‚¬деÑâ‚¬оби','МаÑâ‚¬Ñâ€š 2024','Ðâ€™аÑâ‚¬на','МонÑâ€šаж на висок гаÑâ‚¬деÑâ‚¬об по Ñâ‚¬азмеÑâ‚¬ с плавно заÑâ€šваÑâ‚¬яне и въÑâ€šÑâ‚¬ешно Ñâ‚¬азпÑâ‚¬еделение.','/assets/media/bedroom-2.jpg',['/assets/media/bedroom-2.jpg','/assets/media/bedroom-1.jpg','/assets/media/kitchen-4.jpg']],
        ['sektsiya-za-dnevna','СекÑâ€ ия за дневна','Ðâ€Ñâ‚¬уги','ФевÑâ‚¬уаÑâ‚¬и 2024','Ðâ€обÑâ‚¬иÑâ€¡','МонÑâ€šаж на секÑâ€ ия за дневна с ТÐâ€™ панел, шкаÑâ€žове и скÑâ‚¬иÑâ€š монÑâ€šаж.','/assets/media/living-1.jpg',['/assets/media/living-1.jpg','/assets/media/living-2.jpg','/assets/media/living-3.jpg']],
        ['ofis-obzavezhdane','ОÑâ€žис обзавеждане','Ðâ€Ñâ‚¬уги','ЯнуаÑâ‚¬и 2024','Ðâ€обÑâ‚¬иÑâ€¡','МонÑâ€šаж на оÑâ€žис бюÑâ‚¬о, шкаÑâ€žове, еÑâ€šажеÑâ‚¬ки и конÑâ€žеÑâ‚¬енÑâ€šна маса.','/assets/media/living-3.jpg',['/assets/media/living-3.jpg','/assets/media/living-2.jpg','/assets/media/kitchen-4.jpg']],
    ];
    $st = $pdo->prepare("INSERT INTO projects (slug,title,category,pdate,location,description,cover,gallery) VALUES (?,?,?,?,?,?,?,?)");
    foreach ($seed as $p) $st->execute([$p[0],$p[1],$p[2],$p[3],$p[4],$p[5],$p[6],json_encode($p[7])]);
}
