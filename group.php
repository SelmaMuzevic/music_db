<?php
$get = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    if(empty($get['id'])){

        // on dit a navigateur comment afficher 
        // ça envoie du text et non du html
        header('content-type: text/plain'); 

        echo "id must be set\n";
        exit(1);
    }
    try {
    // 1. connexion a la base de donnees.
	    $pdo = new PDO('mysql:host=localhost;dbname=music_db', 'selma', 'ppp');

	    // Transforme toutes les erreurs en exception.
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// 2. preparation la requête par PHP avant de l'executer
	    $stmt = $pdo->prepare('SELECT album.*, `group`.name AS `group_name` FROM `album` INNER JOIN 
        `group` ON album.`group_id` = `group`.id');

    // 3. on remplace les valeurs dans la requête 
        $stmt->bindValue(':id', $get['id']);

    // 4. on envoie la requête a MariaDB     
        $stmt->execute();

	// 5. On récupére les donnees.
	    $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
	    header('Content-Type: text/plain');
	    echo 'fail to contact DB: ' . $e->getMessage();
	    exit(1);
	}
    header('content-type: text/plain');
    var_dump($albums);

    ?><!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Discography</title>
    </head>
    <body>
        <h1>Discography</h1>
        <?php foreach($albums as $a) { ?>
            <article>
                <h2><?php echo $a['name']; ?></h2>
            </article>
       <?php } ?>
    </body>
    </html>