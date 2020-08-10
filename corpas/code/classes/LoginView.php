<?php


class LoginView
{

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
                    <a href="forgotPassword.php" title="Forgot my password">Forgot my password</a>
                </div>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="loginAction" value="login">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                <button type="submit" id="login" class="btn btn-primary">login</button>
              </div>
            </div>
          </div>
        </div>
        </form>
HTML;
  }

  private function _writeUserSelect() {
    $users = Users::getAllUsers();
    $dropdownHtml = '<option value="">-- select a user --</option>';
   /* foreach ($users as $user) {
      $dropdownHtml .= <<<HTML
        <option value="{$user->getEmail()}">{$user->getFirstname()} {$user->getLastname()}</option>
HTML;
    }*/
    echo <<<HTML
            <select name="email" id="email" class="form-control">
                {$dropdownHtml}
            </select>
HTML;
  }
}
