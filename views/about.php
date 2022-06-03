<?php

use codewild\csubmboer\core\Application;

$this->title = 'About';

?>

<div class="container">

<?php
    $Parsedown = new Parsedown();
    echo $Parsedown->text(file_get_contents(Application::$ROOT_DIR.'README.md'));
?>

</div>
