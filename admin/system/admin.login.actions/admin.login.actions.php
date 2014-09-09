<?php

/**
 * @author Danijel
 * @copyright 2013
 */

include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/admin/theme/js/jAPI-CORE.php');

class AdminLoginAction
{
    public function login_user($username, $password)
    {
        global $salt;
        if ($username && $password)
        {
            if (preg_match('/[^a-zA-Z1-9]/', $username)) die('<script type="text/javascript"> bootbox.alert("The username can consist alphanumeric characters only!"); $("html").find("#admin_login_form")[0].reset(); </script>');
            if (preg_match('/[^a-zA-Z1-9]/', $password)) die('<script type="text/javascript"> bootbox.alert("The password can consist alphanumeric characters only!"); $("html").find("#admin_login_form")[0].reset(); </script>');
            $login_xdb = new Xdb;
            $login_xdb_rows = $login_xdb->set_table('admin_users')
                                        ->where(array('username' => $username, 'password' => sha1($salt . $password)))
                                        ->db_select(false);
            if (count($login_xdb_rows) == 1)
            {
                $_SESSION['admin_user'] = $login_xdb_rows;
                echo '<script type="text/javascript"> window.location = "/admin/interface/"; </script>';
            }
            else
            {
                die('<script type="text/javascript"> bootbox.alert("The username or password was incorrect!<br />Please try again."); $("html").find("#admin_login_form")[0].reset(); </script>');
            }
        }
        elseif ($username && !$password)
        {
            die('<script type="text/javascript"> bootbox.alert("Please enter your password!"); </script>');
        }
        elseif (!$username && $password)
        {
            die('<script type="text/javascript"> bootbox.alert("Please fill out both fields!"); $("html").find("#admin_login_form")[0].reset(); </script>');
        }
        else
        {
            die('<script type="text/javascript"> bootbox.alert("Please enter your login data!"); </script>');
        }
    }
}

new jAPIBaseClass('AdminLoginAction');
?>