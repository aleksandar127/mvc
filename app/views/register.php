<?php

$this->start('body'); ?>

<div id="register">
        <h3 class="text-center text-white pt-5">Login form</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="<?=ROOT ?>register/registerAction" method="post">
                        <div class="bg-danger"><?= $this->displayErrors; ?></div>
                            <h3 class="text-center text-info">Register</h3>
                            <div class="form-group">
                                <label for="fname" class="text-info">First Name:</label><br>
                                <input type="text" name="fname" id="fname" class="form-control" 
                                value=""
                            </div>
                            <div class="form-group">
                                <label for="lname" class="text-info">Last Name:</label><br>
                                <input type="text" name="lname" id="lname" class="form-control" 
                                value="">
                            </div>
                            <div class="form-group">
                                <label for="username" class="text-info">Username:</label><br>
                                <input type="text" name="username" id="username" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="email" class="text-info">Email:</label><br>
                                <input type="text" name="email" id="email" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="password" name="password" id="password" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="re_password" class="text-info">Confirm Password:</label><br>
                                <input type="password" name="re_password" id="re_password" class="form-control" value="">
                            </div>
                            <div class="form-group">
                               
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$this->end('body'); ?>