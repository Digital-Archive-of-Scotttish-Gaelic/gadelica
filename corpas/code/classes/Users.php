<?php

class Users
{
  public static function getUser($email) {
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sth = $dbh->prepare("SELECT `username`, `password`, `salt`, `firstname`, `lastname`, `slip_admin`, 
        `passwordAuth`, UNIX_TIMESTAMP(`last_logged_in`) AS last_logged_in, 
				UNIX_TIMESTAMP(`updated`) AS updated FROM user WHERE email = :email;");
      $sth->execute(array(":email"=>$email));
      while ($row = $sth->fetch()) {
        $user = new User($email);
        $user->setUsername($row['username']);
        $user->setPassword($row['password']);
        $user->setSalt($row['salt']);
        $user->setFirstName($row['firstname']);
        $user->setLastName($row['lastname']);
        $user->setIsSlipAdmin($row['slip_admin']);
        $user->setPasswordAuth($row['passwordAuth']);
        $user->setUpdated($row["updated"]);
      }
      return $user;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public static function saveUser($user) {
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sth = $dbh->prepare("REPLACE INTO user(email, username, password, salt, firstname, lastname, slip_admin, passwordAuth, last_logged_in) VALUES 
				(:email, :username, :password, :salt, :firstname, :lastname, :slip_admin, :passwordAuth, now())");
      $sth->execute(array(":email"=>$user->getEmail(), ":username"=>$user->getUsername(),
        ":password"=>$user->getPassword(), ":salt"=>$user->getSalt(), ":firstname"=>$user->getFirstName(),
        ":lastname"=>$user->getLastName(),
        "blog_admin"=>$user->getIsSlipAdmin(), "passwordAuth"=>$user->getPasswordAuth()));
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public static function getAllUsers() {
    $users = array();
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sth = $dbh->prepare("SELECT email FROM user;");
      $sth->execute();
      while ($row = $sth->fetch()) {
        $users[] = self::getUser($row["email"]);
      }
      return $users;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public static function checkUsernameExists($username) {
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sth = $dbh->prepare("SELECT `email` FROM user WHERE username = :username;");
      $sth->execute(array(":username"=>$username));
      if ($row = $sth->fetch()) {
        return true;
      } else {
        return false;
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}