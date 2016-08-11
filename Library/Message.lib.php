<?php

function afficherAlertSucces($message)
{
?>
    <div class="alert alert-success">
        <strong>Succes</strong> <?php echo($message); ?>
    </div>

<?php
}

function afficherAlertErreur($message)
{
?>
    <div class="alert alert-danger">
        <strong>Erreur!</strong> <?php echo($message); ?>
    </div>
<?php
}

?>
