<?php

/**
 * @author Danijel
 * @copyright 2012
 */

require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php'); 
if (isset($_SESSION['admin_user']))
{
    header('Location: /admin/interface/');
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

  <head>
    <meta charset="utf-8">
    <title>Xenium CMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/admin/theme/css/bootstrap.min.css" rel="stylesheet">
    
    <style type="text/css">
      

      .form-signin {
        position: relative;
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
      #login-container {
        margin-top: 100px;
      }
      #login-form-logo {
        position: absolute;
        top: -115px;
        left: 50px;
      }

    </style>
    <link href="/admin/theme/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="/admin/theme/css/style.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>

  <body id="body-login">

    <div id="login-container" class="container">

      <form id="admin_login_form" class="form-signin">
        <img id="login-form-logo" src="/admin/theme/img/xenium-logo-dark-transparent.png"/>
        <h2 class="form-signin-heading">Please sign in</h2>
        <input id="username" name="username" type="text" class="input-block-level" placeholder="Username">
        <input id="password" name="password" type="password" class="input-block-level" placeholder="Password">
        <!--
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>
        -->
        <div align="right">
            <button class="btn btn-large btn-primary" onclick="admin_login(); return false;">Sign in</button>
        </div>
      </form>
      <div id="login_result" style="display: none;"></div>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/admin/theme/js/jquery.min.js"></script>
    <script src="/admin/theme/js/bootstrap.min.js"></script>
    <script src="/admin/theme/js/bootbox.min.js"></script>
    <script src="/admin/theme/js/jAPI.js"></script>
    <script src="/admin/system/admin.login.actions/admin.login.actions.php"></script>
    
    <script type="text/javascript">
    function admin_login(){
        //var user_login_data = $("#admin_login_form").serialize();
        //AdminLoginAction.login_user(user_login_data);
        var username = $("#username").val();
        var password = $("#password").val();
        $("#login_result").html(AdminLoginAction.login_user(username, password));
    }
    </script>
    
  </body>
</html>