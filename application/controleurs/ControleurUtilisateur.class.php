<?php

class ControleurUtilisateur {

    public function afficherConnexion() {
        if (isset($_SESSION['login_user'])) {
            header('Location: index.php?controleur=Panier&action=afficher');
            exit;
        }
        require Chemins::VUES . 'v_connexion.inc.php';
    }

    public function verifierConnexion() {
        $pseudo = isset($_POST['pseudo']) ? trim($_POST['pseudo']) : '';
        $passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';

        if (strlen($pseudo) < 2 || strlen($passe) < 4) {
            $_SESSION['login_error'] = 'Veuillez saisir un pseudo et un mot de passe valides.';
            header('Location: index.php?controleur=Utilisateur&action=afficherConnexion');
            exit;
        }

        $utilisateur = GestionBoutique::isUserOK($pseudo, $passe);
        if ($utilisateur) {
            session_regenerate_id(true);
            $_SESSION['login_user'] = $utilisateur->login;
            $_SESSION['login_user_id'] = (int) $utilisateur->id;
            $_SESSION['login_user_nom'] = $utilisateur->nom ?: $utilisateur->login;

            $destination = isset($_SESSION['connexion_retour']) ? $_SESSION['connexion_retour'] : 'index.php?controleur=Panier&action=afficher';
            unset($_SESSION['connexion_retour']);
            header('Location: ' . $destination);
            exit;
        }

        $_SESSION['login_error'] = 'Pseudo ou mot de passe incorrect.';
        header('Location: index.php?controleur=Utilisateur&action=afficherConnexion');
        exit;
    }

    public function rechercherPseudo() {
        header('Content-Type: application/json; charset=utf-8');
        $terme = isset($_GET['terme']) ? trim($_GET['terme']) : '';

        if (strlen($terme) < 1) {
            echo json_encode(array('existence' => false, 'suggestions' => array()));
            exit;
        }

        $suggestions = GestionBoutique::rechercherPseudos($terme);
        echo json_encode(array(
            'existence' => in_array($terme, $suggestions, true),
            'suggestions' => $suggestions
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function seDeconnecter() {
        unset($_SESSION['login_user'], $_SESSION['login_user_id'], $_SESSION['login_user_nom']);
        header('Location: index.php');
        exit;
    }
}
