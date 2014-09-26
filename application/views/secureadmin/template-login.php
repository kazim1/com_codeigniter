<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
        <meta name="description" content="">
        <link href="<?php echo base_url('public/'.ADMIN_PUBLIC_FOLDER.'css/login.css') ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo base_url('public/'.ADMIN_PUBLIC_FOLDER.'js/jquery.min.js'); ?>"></script>
    </head>

    <body>
        
        <div class="login-container">
        
            <div class="login-box">
            
                <div id="login-form">
                    <div class="lock">
                        <img src="<?php echo base_url('public/'.ADMIN_PUBLIC_FOLDER.'images/lock.png'); ?>" />
                    </div>
                    
                    <?php if(isset($error_message)): ?>
                        
                        <p class="error-message"><?php echo $error_message ?></p>
                    
                    <?php endif; ?>
                    
                    <form name="loginform" method="post">
                        
                        <div class="input-container">
                        
                            <label title="Username" class="username-label">Username</label>
                            <input type="text" name="auth[username]" title="Username" value="" />
                            
                        </div>
                        
                        <div class="input-container">
                        
                            <label title="Password" class="password-label">Password</label>
                            <input type="password" name="auth[password]" title="Username" value="" />
                            
                        </div>
                        
                        <div class="input-container submit-container">

                            <input class="button-submit" type="submit" title="Login" value="Sign in" />
                            
                        </div>
                        
                    </form>
                
                </div>
            
            </div>
        
        </div>
        
    </body>
</html>
