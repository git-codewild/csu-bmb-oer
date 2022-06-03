<?php

$this->title = 'About';

?>

<div class="container">

<?php
    $Parsedown = new Parsedown();
    echo $Parsedown->text(file_get_contents('../README.md'));
?>

</div>
