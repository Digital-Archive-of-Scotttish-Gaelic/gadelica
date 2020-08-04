<?php


class LoginView
{

  public function writeLoginModal() {
    echo <<<HTML
        <form method="post">
        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Login</h5>
              </div>
              <div class="modal-body">
                <div>
                  <input type="email" id="email" name="email" class="form-control validate">
                  <label data-error="wrong" data-success="right" for="email">email</label>
                </div> 
                <div>
                  <input type="password" id="password" name="password" class="form-control validate">
                  <label data-error="wrong" data-success="right" for="password">password</label>
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
}