<?php

use  App\models\Task;
use  App\controllers\FrontController;

$args = array();
$task = new Task();

if (count($_GET) > 0) {
   $params = [
      'page' => $_GET['page'],
      'sort' => $_GET['sort'],
      'order' => $_GET['order']
   ];
   $tasks = $task->get_task_with_params($params);
} else {
   $tasks = $task->get_all_task();
}

$total_tasks = $task->get_total_tasks();
$total_tasks = (int)$total_tasks["count_tasks"];
?>
   <div class="row m-3">
      <div class="col-6">
         <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addTaskModal">Добавить задачу
         </button>
      </div>
      <div class="col-6 text-right ">
         <?php if (FrontController::check_user_authentification()) : ?>
            <a href="/logout" class="btn btn-info">Выход</a>
         <?php else : ?>
            <a href="/admin" class="btn btn-info">Вход для администратора</a>
         <?php endif; ?>
      </div>
   </div>
   <div class="row">
      <div class="col-12">

         <table class="table">
            <thead class="thead-dark">
            <tr>
               <th scope="col">
                  <a href="/?sort=name&page=<?= is_null($_GET['page']) ? '1' : $_GET['page'] ?>&order=<?= $_GET['order'] === 'asc'
                     ? 'desc' : 'asc' ?>">Имя пользователя </a>
               </th>
               <th scope="col">
                  <a href="/?sort=email&page=<?= is_null($_GET['page']) ? '1' : $_GET['page'] ?>&order=<?= $_GET['order'] === 'asc'
                     ? 'desc' : 'asc' ?>">Email</a>
               </th>
               <th scope="col">Текст задачи</th>
               <th scope="col">
                  <a href="/?sort=status&page=<?= is_null($_GET['page']) ? '1' : $_GET['page'] ?>&order=<?= $_GET['order'] === 'asc'
                     ? 'desc' : 'asc' ?>">Статус</a>
               </th>
               <?php if (FrontController::check_user_authentification()) : ?>
                  <th scope="col"></th>
               <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($tasks as $task):
               ?>
               <tr>
                  <td><?= $task['name'] ?></td>
                  <td><?= $task['email'] ?></td>
                  <td class="task-text"><?= $task['text'] ?></td>
                  <td data-status="<?= $task['status'] ?>" class="task-status"><?php
                     echo $task['status'] === '0' ? 'не выполнено' : 'выполнено';
                     echo $task['edited_by_admin'] === '0' ? '' : ', отредактировано администратором'
                     ?></td>

                  <?php if (FrontController::check_user_authentification()) : ?>
                     <td>
                        <button type="button" data-task_id="<?= $task['id'] ?>"
                                class="btn btn-warning taskEditBtn" data-toggle="modal"
                                data-target="#editModal">
                           Редактировать
                        </button>
                     </td>
                  <?php endif; ?>
               </tr>
            <?php
            endforeach;
            ?>
            </tbody>
         </table>

         <!-- table pagination-->
         <nav aria-label="Page navigation example">
            <ul class="pagination">
               <?php for ($i = 1; $i <= ceil($total_tasks / TASK_LIMIT_PER_PAGE); $i++):
                  ?>
                  <li class="page-item <?= (!is_null($_GET['page']) || $i !== 1)
                     ? ((int)$_GET['page'] === $i ? 'active' : '')
                     : 'active' ?>">
                     <a class="page-link"
                        href="/?sort=<?= $_GET['sort']
                           ? $_GET['sort'] : 'id' ?>&order=<?= $_GET['order']
                           ? $_GET['order'] : 'desc' ?>&page=<?= $i ?>"><?= $i ?>
                     </a>
                  </li>
               <?php endfor; ?>
            </ul>
         </nav>
         <!-- /table pagination-->

         <!-- Modal add task-->
         <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel">Добавить задачу</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">

                     <form class="add-task" action="/?sort=<?= $_GET['sort']
                        ? $_GET['sort'] : 'id' ?>&order=<?= $_GET['order']
                        ? $_GET['order'] : 'desc' ?>&page=<?= $_GET['page']
                        ? $_GET['page'] : '1' ?>" method="POST">
                        <div class="form-row">
                           <div class="form-group col-md-6">
                              <label for="name">Имя</label>
                              <input type="text" name="name" class="form-control" id="name">
                           </div>
                           <div class="form-group col-md-6">
                              <label for="inputEmail">Email</label>
                              <input name="email" type="text" class="form-control" id="inputEmail">
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="formControlTextarea">Задача</label>
                           <textarea
                              name="text"
                              class="form-control"
                              id="formControlTextarea"
                              rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Сохранить задачу</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         <!-- /Modal -->

         <!-- Modal edit task-->
         <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="addTaskModalLabel"
              aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="addTaskModalLabel">Редактировать задачу</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <form class="edit-task" action="/?sort=<?= $_GET['sort']
                        ? $_GET['sort'] : 'id' ?>&order=<?= $_GET['order']
                        ? $_GET['order'] : 'desc' ?>&page=<?= $_GET['page']
                        ? $_GET['page'] : '1' ?>" method="POST">
                        <div class="form-group">
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="taskDone">
                              <label class="form-check-label" for="taskDone">
                                 Выполнено
                              </label>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="editTaskTextField">Задача</label>
                           <textarea
                              name="text"
                              class="form-control"
                              id="editTaskTextField"
                              rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         <!-- /Modal -->

         <?php if ($adding_result === 'success') : ?>
            <!-- Modal success-->
            <div class="modal" id="modal-success" tabindex="-1">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title">Успех</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body">
                        <p>Новая задача успешно добавлена</p>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Ок</button>
                     </div>
                  </div>
               </div>
            </div>
            <!-- /Modal -->
         <?php endif ?>

      </div>
   </div>
<?php
