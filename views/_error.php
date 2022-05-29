<?php

/** @var $model \Exception
 *  @var $contact \codewild\csubmboer\models\ContactForm
 *
 */

use codewild\csubmboer\core\Application;
use codewild\csubmboer\core\form\Form;

    $code = $model->getCode();
    $message = $model->getMessage();

    $this->title = 'Error '.$code;


    $url = Application::$app->request->getUrl();
    $method = Application::$app->request->getMethod();

    $contact->subject = $this->title;
    $contact->body = "
        $message
        URL: $url
        Method: $method
    ";
?>
<div class="container">

    <p class="alert alert-danger text-center">
        <?php echo $message ?>
    </p>

    <?php if($code !== 403 && $code !== 404): ?>
    <div class="row justify-content-center mb-4">
        <?php
            $form = new Form();
            echo $form->begin('', '/error');
            echo $form->field($contact, 'subject')->readonly();
            echo $form->textarea($contact, 'body')->readonly();
            echo $form->end();
        ?>
    </div>
    <?php endif; ?>
</div>
