<?php


class LoginView
{
  public function writeModal($type, $error = "") {
    $title = $body = $footer = $js = $formId = "";
    switch ($type) {
      case "login":
        $dropdownHtml = $this->_getUserSelectHtml();
        $title = "Login";
        if ($error) {
          $body = <<<HTML
            <h4>{$error}</h4>
HTML;
        }
        $body .= <<<HTML
            <div>
                <label for="email">user</label>
                {$dropdownHtml}
            </div>
            <div>
              <label data-error="wrong" data-success="right" for="password">password</label>
              <input type="password" id="password" name="password" class="form-control validate">
            </div>
            <div>
                <a href="?loginAction=forgotPassword" title="Forgot my password">Forgot my password</a>
            </div>
HTML;
        $footer = <<<HTML
            <input type="hidden" name="loginAction" value="login">
            <button type="submit" id="login" class="btn btn-primary">login</button>
HTML;
      break;
      case "forgotPassword":
        $title = "Forgot Password";
        $body = <<<HTML
            <div>
                <label for="email">email address</label>
                <input type="email" name="email" id="email">
            </div>
HTML;
        $footer = <<<HTML
            <input type="hidden" name="loginAction" value="sendEmail">
            <button type="submit" class="btn btn-primary">submit</button>
HTML;
        break;
      case "emailSent":
        $title = "Email Sent";
        $body = <<<HTML
            <div>
                <p>An email has been sent to your email address.</p>
                <p>Please click the link in the email to reset your password.</p>
            </div>
HTML;
        break;
      case "emailAddressError":
        $title = "Email Address Error";
        $body = <<<HTML
            <div>
                <h4>Email address not recognised.</h4>
                <p><a href="?loginAction=forgotPassword" title="Forgot my password">
                    Enter a different email address.</a>
                </p>
            </div>
HTML;
        break;
      case "resetPassword":
        $formId = ' id="savePassword"';
        $title = "Reset Password";
        $body = <<<HTML
            <div>
                <label for="pass1">password</label>
                <input type="password" name="pass1" id="pass1">
            </div>
            <div>
                <label for="pass2">retype password</label>
                <input type="password" name="pass2" id="pass2">
            </div>
HTML;
        $footer = <<<HTML
            <input type="hidden" name="loginAction" value="savePassword">
            <button type="submit" class="btn btn-primary">submit</button>
HTML;
        $js = <<<HTML
            $(function () {
              $('#savePassword').validate({
                rules: {
                  pass1: "required",
                  pass2: {
                    equalTo: "#pass1"
                  }
                }
              });
            });   
HTML;
        break;
    }
    echo <<<HTML
        <form method="post" {$formId}>
        <div class="modal" id="loginModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{$title}</h5>
              </div>
              <div class="modal-body">
                {$body}
              </div>
              <div class="modal-footer">
                {$footer}
              </div>
            </div>
          </div>
        </div>
        </form>
        <script>
          $('#loginModal').modal({backdrop: 'static', keyboard: false});   
          {$js}      
        </script>
HTML;
  }

/*
  public function writeLoginModal() {
    $users = Users::getAllUsers();
    $dropdownHtml = '<option value="">-- select a user --</option>';
    foreach ($users as $user) {
       $dropdownHtml .= <<<HTML
         <option value="{$user->getEmail()}">{$user->getFirstname()} {$user->getLastname()}</option>
HTML;
    }
    echo <<<HTML
        <form method="post">
        <div class="modal" id="loginModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Login</h5>
              </div>
              <div class="modal-body">
                <div>
                    <label for="email">user</label>
                    <select name="email" id="email" class="form-control">
                        {$dropdownHtml}
                    </select>
                </div>
                <div>
                  <label data-error="wrong" data-success="right" for="password">password</label>
                  <input type="password" id="password" name="password" class="form-control validate">
                </div>
                <div>
                    <a href="?loginAction=forgotPassword" title="Forgot my password">Forgot my password</a>
                </div>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="loginAction" value="login">
                <button type="submit" id="login" class="btn btn-primary">login</button>
              </div>
            </div>
          </div>
        </div>
        </form>

        <script>
            $('#loginModal').modal({backdrop: 'static', keyboard: false});
        </script>
HTML;
  }

  public function writeForgotPasswordModal() {
    echo <<<HTML
        <form method="post">
        <div class="modal" id="forgotPasswordModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
              </div>
              <div class="modal-body">
                <div>
                    <label for="email">email address</label>
                    <input type="email" name="email" id="email">
                </div>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="loginAction" value="sendEmail">
                <button type="submit" class="btn btn-primary">submit</button>
              </div>
            </div>
          </div>
        </div>
        </form>

        <script>
            $('#forgotPasswordModal').modal({backdrop: 'static', keyboard: false});
        </script>
HTML;
  }

  public function writeEmailSentModal() {
    echo <<<HTML
        <div class="modal" id="emailSentModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Email Sent</h5>
              </div>
              <div class="modal-body">
                <div>
                    <p>An email has been sent to your email address.</p>
                    <p>Please click the link in the email to reset your password.</p>
                </div>
              </div>
              <div class="modal-footer">
              </div>
            </div>
          </div>
        </div>

        <script>
            $('#emailSentModal').modal({backdrop: 'static', keyboard: false});
        </script>
HTML;
  }

  public function writeEmailAddressErrorModal() {
    echo <<<HTML
        <div class="modal" id="emailAddressErrorModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Email Address Error</h5>
              </div>
              <div class="modal-body">
                <div>
                    <h4>Email address not recognised.</h4>
                    <p><a href="?loginAction=forgotPassword" title="Forgot my password">
                        Enter a different email address.</a>
                    </p>
                </div>
              </div>
              <div class="modal-footer">
              </div>
            </div>
          </div>
        </div>

        <script>
            $('#emailAddressErrorModal').modal({backdrop: 'static', keyboard: false});
        </script>
HTML;
  }

  public function writeResetPasswordModal() {
    echo <<<HTML
        <form method="post" id="savePassword">
        <div class="modal" id="resetPasswordModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
              </div>
              <div class="modal-body">
                <div>
                    <label for="pass1">password</label>
                    <input type="password" name="pass1" id="pass1">
                </div>
                <div>
                    <label for="pass2">retype password</label>
                    <input type="password" name="pass2" id="pass2">
                </div>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="loginAction" value="resetPassword">
                <button type="submit" class="btn btn-primary">submit</button>
              </div>
            </div>
          </div>
        </div>
        </form>

        <script>
          $('#resetPasswordModal').modal({backdrop: 'static', keyboard: false});
        
          $(function () {
            $('#savePassword').validate({
              rules: {
                pass1: "required",
                pass2: {
                  equalTo: "#pass1"
                }
              }
            });
          });       
        </script>
HTML;
  }
*/

  private function _getUserSelectHtml() {
    $users = Users::getAllUsers();
    $dropdownHtml = '<option value="">-- select a user --</option>';
    foreach ($users as $user) {
      $dropdownHtml .= <<<HTML
         <option value="{$user->getEmail()}">{$user->getFirstname()} {$user->getLastname()}</option>
HTML;
    }
    $html = <<<HTML
        <select name="email" id="email" class="form-control">
            {$dropdownHtml}
        </select>
HTML;
    return $html;
  }
}
