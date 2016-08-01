<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 16/07/2016
 * Time: 14:17
 */
?>

<div class="container">
    <div class="jumbotron">
        <h1>Question et réponse secrète</h1>

        <p>Renseignez une question et la réponse correspondante connue seulement de vous afin de sécuriser votre compte.</p>
    </div>
</div>
<div class="container">
    <form action="index.php?page=QR_req" method="post" class="form-horizontal">
        <div class="form-group">
            <label for="question">Votre question : </label>
            <input type="text" id="question" name="question" placeholder="Votre question" class="form-control"
                   required <?php if (isset($_POST['question'])) echo 'value="' . $_POST['question'] . '"'; ?>>
        </div>
        <div class="form-group">
            <label for="reponse">Votre réponse : </label>
            <input type="text" id="reponse" name="reponse" placeholder="Votre réponse" class="form-control"
                   required <?php if (isset($_POST['reponse'])) echo 'value="' . $_POST['reponse'] . '"'; ?>>
        </div>
        <button type="submit" class="btn btn-default">Envoyer</button>
    </form>
</div>