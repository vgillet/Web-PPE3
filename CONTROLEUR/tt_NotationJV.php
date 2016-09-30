<?php
require_once ('../MODELE/CommentaireModele.class.php');
require_once ('../MODELE/UserModele.class.php');
?>
<!DOCTYPE html>
<html lang='fr'>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>

<?php

$msgERREUR = "";

if (isset ( $_POST ['email'] ) && isset ( $_POST ['pseudo'] )) {
	$modeleUser = new UserModele ();
	// recuperation de l'identifiant de l'utlisateur avec son email et son pseudo
	$resultU = $modeleUser->getUser ( $_POST ['email'], $_POST ['pseudo'] );
	if ($resultU->rowCount() == 0) {
		// cas ou l'email ou le pseudo ne sont pas valides (retour du count)
		$msgERREUR = "ERREUR : votre email et/ou pseudo ne sont pas valides ! :" ;
	} else {
		//un seul tuple donc inutile de faire un foreach, on prend juste le tuple courant
		$idU = $resultU->fetch()->IdU ;
		
		if (isset ( $_POST ['listJV'] ) && isset ( $_POST ['comments'] ) && isset ( $_POST ['agree'] )) {
			// ajout du commentaire en récupérant l'Id du jeu (ici dans radioJV)
			$modeleCom = new CommentaireModele ();
			try {
				//pour traiter les eventuelles apostrophes dans la chaine des commentaires
				$chaineCommentaire = addslashes($_POST ['comments']);
				
				$nb = $modeleCom->add ( $idU, $_POST ['listJV'], $chaineCommentaire );
				$msgERREUR = "SUCCESS : AJOUT du commentaire reussi";
			} catch ( PDOException $pdoe ) {
				// cas ou 2 pseudo ont deja mis un commentaire sur un jeu
				$msgERREUR = "ERREUR : vous avez déjà effectué un commentaire pour ce jeu ! : <br/>" . $pdoe->getMessage ();
			}
		}
	}
}

// redirection vers la page appelante en lui passant le message
header ( 'Location: ../VUE/ajoutCommentaireJeux.php?error='.$msgERREUR );
exit ();
?>
</html>
