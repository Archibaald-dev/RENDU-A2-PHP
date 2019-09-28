<?php
require __DIR__ . "./composer/vendor/autoload.php";

## ETAPE 0

## CONNECTEZ VOUS A VOTRE BASE DE DONNEE


## ETAPE 1

## POUVOIR SELECTIONER UN PERSONNE DANS LE PREMIER SELECTEUR

## ETAPE 2

## POUVOIR SELECTIONER UN PERSONNE DANS LE DEUXIEME SELECTEUR

## ETAPE 3

## LORSQUE LON APPPUIE SUR LE BOUTON FIGHT, RETIRER LES PV DE CHAQUE PERSONNAGE PAR RAPPORT A LATK DU PERSONNAGE QUIL COMBAT

## ETAPE 4

## UNE FOIS LE COMBAT LANCER (QUAND ON APPPUIE SUR LE BTN FIGHT) AFFICHER en dessous du formulaire
# pour le premier perso PERSONNAGE X (name) A PERDU X PV (l'atk du personnage d'en face)
# pour le second persoPERSONNAGE X (name) A PERDU X PV (l'atk du personnage d'en face)

## ETAPE 5

## N'AFFICHER DANS LES SELECTEUR QUE LES PERSONNAGES QUI ONT PLUS DE 10 PV


?>

<?php


try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=rendu', "root", "");
} catch (Exception $e) {
    echo "erreur de connection";
};

$personnage = $pdo->prepare("SELECT * FROM personnages WHERE stars != 0");
$personnage->execute();
$statistiques = $personnage->fetchAll(PDO::FETCH_OBJ);
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
    <h1>Combats</h1>
    <div class="w-100 mt-5">

        <form action="" method="POST">
            <div class="form-group">
                <select name="selectp1" id="selectp1">
                    <option value="" selected disabled>Choissisez un premier combatant</option>
                    <?php foreach ($statistiques as $item) { ?>
                        <option value="<?= $item->id ?>">
                            <?= $item->name ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <select name="selectp2" id="selectp2">
                    <option value="" selected disabled>Choissisez un deuxième combatant</option>
                    <?php foreach ($statistiques as $item) { ?>
                        <option value="<?= $item->id ?>">
                            <?= $item->name ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <button class="btn" type="submit">Fight</button>
        </form>

    </div>
    <?php
    if (!empty($_POST)) {

        if ($_POST["selectp1"] == $_POST["selectp2"]) {
            echo "Le même personnage ne peut pas être sélectionné !!";
            die;
        }

        $idPerso1 = $_POST['selectp1'];
        $idPerso2 = $_POST['selectp2'];
        $dbPerso1 = getPerso($idPerso1, $pdo);
        $dbPerso2 = getPerso($idPerso2, $pdo);

        $pvPerso1 = $dbPerso1->pv - $dbPerso2->atk;
        $pvPerso2 = $dbPerso2->pv - $dbPerso1->atk;

        $newPerso1 = updatePv($idPerso1, $pvPerso1, $pdo);
        $newPerso2 = updatePv($idPerso2, $pvPerso2, $pdo);



        echo " $newPerso1->name a perdu " . $newPerso2->atk . " HP sur ces " . $dbPerso1->pv . " de départ ! Il lui reste $newPerso1->pv HP !<br>";
        echo " $newPerso2->name a perdu " . $newPerso1->atk . " HP sur ces " . $dbPerso2->pv . " de départ ! Il lui reste $newPerso2->pv HP !<br>";

        switch ($newPerso1->pv) {
            case ($newPerso1->pv <= 10):
                echo " $newPerso1->name n'as plus assez de points de vie pour combattre ! ! :/";

                $query = $pdo->prepare("UPDATE personnages SET stars = 0 WHERE id = :id");
                $query->execute(["id" =>$idPerso1]);
                break;
        }
        switch ($newPerso2->pv) {
            case ($newPerso2->pv <= 10):
                echo " $newPerso2->name n'as plus assez de points de vie pour combattre ! ! :/";

                $query = $pdo->prepare("UPDATE personnages SET stars = 0 WHERE id = :id");
                $query->execute(["id" => $idPerso2]);
                break;
        }
    }
    function updatePv($id, $pv, $pdo)
    {
        $query = $pdo->prepare("UPDATE personnages SET pv = :newPv WHERE id = :id");
        $query->execute(["newPv" => $pv, "id" => $id]);
        $state = $query->fetch(PDO::FETCH_OBJ);

        return getPerso($id, $pdo);
    }
    
    
    function getPerso($id, $pdo)
    {
        $query = $pdo->prepare("SELECT * FROM personnages WHERE id = :id");
        $query->execute(['id' => $id]);

        return $query->fetch(PDO::FETCH_OBJ);
    }

    ?>

</body>

</html>