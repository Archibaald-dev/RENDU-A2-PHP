<?php
require __DIR__ . "./composer/vendor/autoload.php";

## ETAPE 0

## CONNECTEZ VOUS A VOTRE BASE DE DONNEE

## ETAPE 1

## RECUPERER TOUT LES PERSONNAGES CONTENU DANS LA TABLE personnages

## ETAPE 2

## LES AFFICHERS DANS LE HTML
## AFFICHER SON NOM, SON ATK, SES PV, SES STARS

## ETAPE 3

## DANS CHAQUE PERSONNAGE JE VEUX POUVOIR APPUYER SUR UN BUTTON OU IL EST ECRIT "STARS"

## LORSQUE L'ON APPUIE SUR LE BOUTTON "STARS"

## ON SOUMET UN FORMULAIRE QUI METS A JOURS LE PERSONNAGE CORRESPONDANT (CELUI SUR LEQUEL ON A CLIQUER) EN INCREMENTANT LA COLUMN STARS DU PERSONNAGE DANS LA BASE DE DONNEE

#######################
## ETAPE 4
# AFFICHER LE MSG "PERSONNAGE ($name) A GAGNER UNE ETOILES"

?>
<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=rendu', "root", "");
} catch (PDOException $e) {
    echo "erreur de connection";
};

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rendu Php</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <nav class="nav mb-3">
        <a href="./rendu.php" class="nav-link">Acceuil</a>
        <a href="./personnage.php" class="nav-link">Mes Personnages</a>
        <a href="./combat.php" class="nav-link">Combats</a>
    </nav>
    <h1>Mes personnages</h1>
    <div class="w-100 mt-5">
        <?php
        $stat = $pdo->prepare('SELECT id,name,atk,pv,stars FROM personnages');
        $stat->execute();
        $stats = $stat->fetchAll(PDO::FETCH_OBJ);




        if (!empty($stats)) :
            foreach ($stats as $key => $value) : ?>
               <div> <?php echo "NOM: $value->name / ATK: $value->atk / PV: $value->pv / STARS: $value->stars" ?>
                <form method="post" name="starplus">
                    <input type="hidden" name="id" value="<?php echo $value->id; ?>">
                    <button  type="submit">Étoile +1 pour <?php echo $value->name; ?></button>
                </form>
                <br>
        </div>
        <?php endforeach;
        endif;
        
        if (isset($_POST) && !empty($_POST)) {
            $id = $_POST["id"];

            $perso = $pdo->prepare('SELECT id,name,stars FROM personnages WHERE id = :id');
            $perso->execute(["id" => $id]);
            $persoselect = $perso->fetchAll(PDO::FETCH_OBJ);
            $bonjour = $persoselect[0]->stars + 1;
            $idperso = $persoselect[0]->id;
            $add_form = $pdo->prepare('UPDATE personnages SET stars = :starsplus WHERE id = :id');
            //dd($value->stars);

            $add_form->execute(["id" => $idperso, 'starsplus' => (int) $bonjour]);
            $nameuh = $persoselect[0]->name;
            echo "$nameuh a gagné une étoile !";
            header('Location: personnage.php');
        }
        ?>
        
        
       
    </div>

</body>

</html>