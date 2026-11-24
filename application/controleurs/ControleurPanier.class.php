<?php

require_once Chemins::MODELES . 'Panier.class.php';

class ControleurPanier {

	public function __construct() {
		Panier::initialiser();
	}

	public function ajouter() {
		$id = isset($_POST['idProduit']) ? intval($_POST['idProduit']) : 0;
		if ($id > 0) {
			Panier::ajouterProduit($id, 1);
			$_SESSION['panier_message'] = 'Produit ajouté au panier.';
		}
		$retour = 'index.php';
		if (isset($_POST['retour']) && is_string($_POST['retour'])
			&& preg_match('#^index\.php(\?[A-Za-z0-9=&%_.+~\-]*)?$#', $_POST['retour'])) {
			$retour = $_POST['retour'];
		}
		header('Location: ' . $retour);
		exit;
	}

	public function afficher() {
		require Chemins::VUES . 'v_panier.inc.php';
	}

	public function supprimer() {
		$id = isset($_GET['idProduit']) ? intval($_GET['idProduit']) : 0;
		if ($id > 0) {
			Panier::retirerProduit($id);
		}
		header('Location: index.php?controleur=Panier&action=afficher');
		exit;
	}

	public function vider() {
		Panier::vider();
		header('Location: index.php?controleur=Panier&action=afficher');
		exit;
	}

	public function valider() {
		if (!isset($_SESSION['login_user'])) {
			$_SESSION['connexion_retour'] = 'index.php?controleur=Panier&action=afficher';
			$_SESSION['login_error'] = 'Vous devez vous connecter pour valider votre panier.';
			header('Location: index.php?controleur=Utilisateur&action=afficherConnexion');
			exit;
		}

		if (Panier::isVide()) {
			$_SESSION['panier_message'] = 'Votre panier est vide.';
			header('Location: index.php?controleur=Panier&action=afficher');
			exit;
		}

		$_SESSION['panier_message'] = 'Commande validée. Merci pour votre achat !';
		Panier::vider();
		header('Location: index.php?controleur=Panier&action=afficher');
		exit;
	}

	public function augmenter() {
		$id = isset($_GET['idProduit']) ? intval($_GET['idProduit']) : 0;
		if ($id > 0 && Panier::contains($id)) {
			Panier::ajouterProduit($id, 1);
		}
		header('Location: index.php?controleur=Panier&action=afficher');
		exit;
	}

	public function diminuer() {
		$id = isset($_GET['idProduit']) ? intval($_GET['idProduit']) : 0;
		if ($id > 0 && Panier::contains($id)) {
			$qte = Panier::getQteByProduit($id) - 1;
			if ($qte <= 0) {
				Panier::retirerProduit($id);
			} else {
				Panier::modifierQteProduit($id, $qte);
			}
		}
		header('Location: index.php?controleur=Panier&action=afficher');
		exit;
	}

	public function modifierQuantite() {
		$id = isset($_POST['idProduit']) ? intval($_POST['idProduit']) : 0;
		$qte = isset($_POST['qte']) ? intval($_POST['qte']) : 0;

		if ($id > 0 && Panier::contains($id)) {
			if ($qte <= 0) {
				Panier::retirerProduit($id);
			} else {
				Panier::modifierQteProduit($id, min($qte, 99));
			}
		}

		header('Location: index.php?controleur=Panier&action=afficher');
		exit;
	}
}

?>
