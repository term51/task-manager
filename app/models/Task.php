<?php


namespace App\models;

use PDO;
use PDOException;

class Task
{
   private $host;
   private $user;
   private $password;
   private $database;
   private $conn;

   public function __construct()
   {
      $this->host = DB_HOST;
      $this->user = DB_USER;
      $this->password = DB_PASSWORD;
      $this->database = DB_DATABASE;
      $this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password);

      $this->limit = TASK_LIMIT_PER_PAGE;
   }

   public function get_all_task()
   {
      try {
         $limit = TASK_LIMIT_PER_PAGE;
         $sth = $this->conn->prepare("SELECT id, name, email, text, status, edited_by_admin FROM tasks ORDER BY id desc limit $this->limit");
         $sth->execute();
         return $sth->fetchAll(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
         print "Error!: " . $e->getMessage() . "<br/>";
         die();
      }
   }

   public function get_task_with_params($params)
   {
      if (isset($params['page']) && !empty($params['page'])) {
         $start = ((int)$params['page'] - 1) * $this->limit;
      } else {
         $start = 0;
      }

      $sort = '';
      if (isset($params['sort']) && !empty($params['sort'])) {
         $sort .= 'ORDER BY ' . $params['sort'];
      }

      if (isset($params['order']) && !empty($params['order'])) {
         $sort .= ' ' . $params['order'];
      }

      try {
         $sth = $this->conn->prepare("SELECT id, name, email, text, status, edited_by_admin FROM tasks $sort limit $start, $this->limit");
         $sth->execute();
         return $sth->fetchAll(PDO::FETCH_ASSOC);

      } catch (PDOException $e) {
         print "Error!: " . $e->getMessage() . "<br/>";
         die();
      }
   }

   public function get_total_tasks()
   {
      try {
         $sth = $this->conn->prepare("SELECT count(*) as count_tasks FROM `tasks`");
         $sth->execute();
         return $sth->fetch(PDO::FETCH_ASSOC);

      } catch (PDOException $e) {
         print "Error!: " . $e->getMessage() . "<br/>";
         die();
      }
   }

   public function add_task($form_data)
   {
      try {
         $sth = $this->conn->prepare("INSERT INTO tasks (`name`, `email`, `text`) VALUES (:name, :email, :text)");
         $sth->execute(array(
            'name' => $form_data['name'],
            'email' => $form_data['email'],
            'text' => $form_data['text']
         ));

         return 'success';

      } catch (PDOException $e) {
         print "Error!: " . $e->getMessage() . "<br/>";
         die();
      }
   }

   public function edit_task($form_data)
   {
      try {
         $sth = $this->conn->prepare("UPDATE tasks SET text = :text, status = :status where id = :id");
         $sth->execute([
            'text' => $form_data['text'],
            'status' => (int)$form_data['checkbox'],
            'id' => $form_data['id']
         ]);
         echo json_encode(array(
            'status' => 'success',
            'params' => $form_data['params']
         ));
      } catch (PDOException $e) {
         print "Error!: " . $e->getMessage() . "<br/>";
         die();
      }
   }

   public function user_login($form_data)
   {
      try {
         $sth = $this->conn->prepare("SELECT login, password FROM users WHERE login = :login AND password = :password");
         $sth->execute(array(
            'login' => $form_data['login'],
            'password' => md5($form_data['password'])
         ));
         $result = $sth->fetch(PDO::FETCH_ASSOC);
         if ($result) {
            setcookie("login", 'admin', time() + 60 * 60 * 24 * 30, "/", null, null, true);
            echo json_encode(array('access' => 'pass'));
         } else {
            echo json_encode(array('access' => 'denied'));
         }

      } catch (PDOException $e) {
         print "Error!: " . $e->getMessage() . "<br/>";
         die();
      }
   }

   public function get_task_text_by_id($id)
   {
      try {
         $sth = $this->conn->prepare("SELECT text FROM tasks WHERE id = :id");
         $sth->execute(['id' => $id]);
         return $sth->fetch(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
         print "Error!: " . $e->getMessage() . "<br/>";
         die();
      }
   }

   public function set_edited_by_admin($id)
   {
      try {
         $sth = $this->conn->prepare("UPDATE tasks SET edited_by_admin = 1 where id = :id");
         $sth->execute(['id' => $id]);
      } catch (PDOException $e) {
         print "Error!: " . $e->getMessage() . "<br/>";
         die();
      }
   }
}
