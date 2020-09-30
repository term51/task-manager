<?php

namespace App;


use FastRoute;
use App\controllers\TaskController;
use App\controllers\FrontController;

class Router
{
   public static function start()
   {
      $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
         $r->addRoute('GET', '/', ['page' => 'task-list']);
         $r->addRoute('POST', '/', ['action' => 'add-task', 'page' => 'task-list']);
         $r->addRoute('POST', '/edit-task', ['action' => 'edit-task', 'page' => 'task-list']);
         $r->addRoute('GET', '/admin', ['page' => 'admin']);
         $r->addRoute('POST', '/login', ['action' => 'login', 'page' => 'task-list']);
         $r->addRoute('GET', '/logout', ['action' => 'logout', 'page' => 'admin']);

      });

      $httpMethod = $_SERVER['REQUEST_METHOD'];
      $uri = $_SERVER['REQUEST_URI'];

      if (false !== $pos = strpos($uri, '?')) {
         $uri = substr($uri, 0, $pos);
      }
      $uri = rawurldecode($uri);
      $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

      switch ($routeInfo[0]) {
         case FastRoute\Dispatcher::NOT_FOUND:
            echo '404 Not Found';
            break;
         case FastRoute\Dispatcher::FOUND:
            if (isset($routeInfo[1]['action'])) {
               new TaskController($routeInfo[1]);
            } else {
               new FrontController($routeInfo[1]);
            }
            break;
      }
   }
}
