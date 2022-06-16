<?php

use codewild\phpmvc\Application;

$this->title = 'About';

?>

<div class="container">

<?php
    $Parsedown = new Parsedown();
    echo $Parsedown->text(file_get_contents(Application::$ROOT_DIR.'README.md'));
?>

</div>
