<?php
require_once __DIR__ . "/../config/database.php";

$conn = getConnection();

if (isset($_POST["submit"])) {
    $nom = trim($_POST["nom"] ?? "");
    $prenom = trim($_POST["prenom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telephone = trim($_POST["telephone"] ?? "");
    $classe = $_POST["classe"] ?? "";
    $date_naissance = $_POST["date_naissance"] ?? "";

    if (empty($nom))
        $errors[] = "Le nom est obligatoire.";
    if (empty($prenom))
        $errors[] = "Le prénom est obligatoire.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "L'adresse email n'est pas valide.";
    if (empty($classe))
        $errors[] = "La classe est obligatoire.";
    if (empty($date_naissance))
        $errors[] = "La date de naissance est obligatoire.";

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO etudiants (nom, prenom, email, classe, date_naissance) 
                    VALUES (:nom, :prenom, :email, :classe, :date_naissance)";

            $stmt = $conn->prepare($sql);
            $success = $stmt->execute([
                ":nom" => $nom,
                ":prenom" => $prenom,
                ":email" => $email,
                ":classe" => $classe,
                ":date_naissance" => $date_naissance
            ]);

            if ($success) {
                header("Location: ../index.php?success=add");
                exit();
            } else {
                $errors[] = "Erreur lors de l'insertion en base de donnees.";
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur SQL : " . $e->getMessage();
        }
    }
}



?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .container {
            max-width: 800px;
        }

        .card {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="index.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Retour</a>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">
                    <i class="bi bi-person-plus-fill text-primary"></i> Ajouter un Nouvel Étudiant
                </h2>
            </div>
        </div>

        <!-- Affichage des erreurs -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <strong>Erreurs :</strong>
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom *</label>
                            <input type="text" class="form-control" id="nom" name="nom"
                                value="<?php echo htmlspecialchars($nom ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">Prénom *</label>
                            <input type="text" class="form-control" id="prenom" name="prenom"
                                value="<?php echo htmlspecialchars($prenom ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone"
                                value="<?php echo htmlspecialchars($telephone ?? ''); ?>" placeholder="06xxxxxxxx">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="classe" class="form-label">Classe *</label>
                            <select class="form-select" id="classe" name="classe" required>
                                <option value="">-- Sélectionner --</option>
                                <?php
                                $classes = ["Sixieme", "Cinquieme", "Quatrieme", "Troisième", "Seconde", "Première", "Terminale"];
                                foreach ($classes as $c) {
                                    $selected = (isset($classe) && $classe == $c) ? "selected" : "";
                                    echo "<option value=\"$c\" $selected>$c</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_naissance" class="form-label">Date de Naissance *</label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance"
                                value="<?php echo htmlspecialchars($date_naissance ?? ''); ?>" required>
                        </div>
                    </div>

                    <hr>
                    <button type="submit" name="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle"></i> Enregistrer l'étudiant
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>