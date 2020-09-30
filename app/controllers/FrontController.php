<?php

namespace App\controllers;

class FrontController
{
   public function __construct(array $args)
   {
      $this::render_template($args['page']);
   }

   public static function render_template($page, $adding_result = null)
   {
      require dirname(__DIR__) . '/views/template.php';
   }

   public static function render_page($page, $adding_result = null)
   {
      require dirname(__DIR__) . "/views/$page.php";
   }

   public static function check_user_authentification()
   {
      if (isset($_COOKIE["login"]) && !empty($_COOKIE["login"])) {
         return true;
      } else {
         return false;
      }
   }

   public static function user_logout($page)
   {
      setcookie("login", '', time() + 60 * 60 * 24 * 30, "/", null, null, true);
      self::render_template($page);
   }
}
