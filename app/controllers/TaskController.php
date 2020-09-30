<?php

namespace App\controllers;

use App\models\Task;

class TaskController
{
   public function __construct(array $args)
   {
      switch ($args['action']) {
         case 'add-task':
            $this::add_task($args['page'], [
               'name' => strip_tags($_POST['name']),
               'email' => strip_tags($_POST['email']),
               'text' => strip_tags($_POST['text'])
            ]);
            break;

         case 'edit-task':
            if (FrontController::check_user_authentification()) {
               $this::edit_task([
                  'id' => $_POST['id'],
                  'text' => strip_tags($_POST['text']),
                  'checkbox' => $_POST['checkbox'],
                  'params' => $_POST['params']
               ]);
            } else {
               echo json_encode(array('status' => 'denied'));
            }
            break;

         case 'login':
            $task = new Task();
            $task->user_login([
               'login' => $_POST['login'],
               'password' => $_POST['password']
            ]);
            break;
         case 'logout':
            FrontController::user_logout($args['page']);
            break;
      }
   }

   public static function add_task($page, $form_data)
   {
      $task = new Task();
      $adding_result = $task->add_task($form_data);
      FrontController::render_template($page, $adding_result);
   }

   public static function edit_task($form_data)
   {
      $task = new Task();
      $task_text_in_db = $task->get_task_text_by_id($form_data['id']);

      if ($task_text_in_db['text'] !== $form_data['text']) {
         $task->set_edited_by_admin($form_data['id']);
      }
      $task->edit_task($form_data);
   }
}
