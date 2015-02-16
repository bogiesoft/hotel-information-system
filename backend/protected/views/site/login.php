<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'contact',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
    'htmlOptions' => array("action" => Yii::app()->request->baseUrl . "/site/login"),
));
?>
    <div class="panel-body bg-light p30">
        <div class="row">
            <div class="col-sm-7 pr30">
                
                <?php
                if (isset($message)) {
                    ?>
                    <div class="section">
                        <?php echo $message; ?>
                    </div>
                    <?php
                }
                ?>

                <div class="section">
                    <label for="username" class="field-label text-muted fs18 mb10">Username</label>
                    <label for="username" class="field prepend-icon">
                        <?php echo $form->textField($model, 'username', array("class" => "gui-input", "placeholder" => "Username")); ?>
                        <?php echo $form->error($model, 'username'); ?>
                        <label for="username" class="field-icon"><i class="fa fa-user"></i>
                        </label>
                    </label>
                </div>
                <!-- end section -->

                <div class="section">
                    <label for="username" class="field-label text-muted fs18 mb10">Password</label>
                    <label for="password" class="field prepend-icon">
                        <?php echo $form->passwordField($model, 'password', array("class" => "gui-input", "placeholder" => "Password")); ?>
                        <?php echo $form->error($model, 'password'); ?>
                        <label for="password" class="field-icon"><i class="fa fa-lock"></i>
                        </label>
                    </label>
                </div>
                <!-- end section -->

            </div>
            <div class="col-sm-5 br-l br-grey pl30">
                <h3 class="mb25"> Cara Mengakses Sistem:</h3>
                <p class="mb15">
                    <span class="fa fa-check text-success pr5"></span> Masukkan Username Anda.</p>
                <p class="mb15">
                    <span class="fa fa-check text-success pr5"></span> Masukkan Password Anda.</p>
                <p class="mb15">
                    <span class="fa fa-check text-success pr5"></span> Tekan Tombol <b>Masuk</b></p>
            </div>
        </div>
    </div>
    <!-- end .form-body section -->
    <div class="panel-footer clearfix p10 ph15">
        <button type="submit" class="button btn-primary mr10 pull-right">Masuk</button>
        <label class="switch block switch-primary pull-left input-align mt10">
            <input type="checkbox" name="remember" id="remember" checked>
            <label for="remember" data-on="YES" data-off="NO"></label>
            <span>Remember me</span>
        </label>
    </div>
    <!-- end .form-footer section -->
<?php $this->endWidget(); ?>