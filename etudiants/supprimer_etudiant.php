<?php
require_once __DIR__ . "/../config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  $id = (int) $_POST['id'];
  $db = getConnection();
  $error = null;

  try {
    $db->beginTransaction();

    $queryNotes = $db->prepare("DELETE FROM notes WHERE student_id = :id");
    $queryNotes->execute(['id' => $id]);

    $queryStudent = $db->prepare("DELETE FROM students WHERE id = :id");
    $queryStudent->execute(['id' => $id]);

    $db->commit();

    header("Location: ../index.php?success=deleted");
    exit;

  } catch (Exception $e) {
    if ($db->inTransaction()) {
      $db->rollBack();
    }
    $error = "Erreur lors de la suppression : " . $e->getMessage();
  }
}

$id_to_delete = $_GET['id'] ?? null;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>TP 1.5 — Supprimer un étudiant</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container mt-5">
    <a href="../index.php" class="btn btn-secondary mb-3">← Retour</a>

    <div class="card border-danger">
      <div class="card-header bg-danger text-white">Confirmation de suppression</div>
      <div class="card-body">
        <?php if (isset($error)): ?>
          <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <p>Etes-vous sur vouloir supprimer <strong><?= htmlspecialchars($id_to_delete) ?></strong> ainsi
          que toutes ses notes ?</p>

        <form method="POST">
          <input type="hidden" name="id" value="<?= htmlspecialchars($id_to_delete) ?>">
          <button type="submit" class="btn btn-danger">Confirmer la suppression définitive</button>
          <a href="../index.php" class="btn btn-light">Annuler</a>
        </form>
      </div>
    </div>
  </div>
</body>

</html>