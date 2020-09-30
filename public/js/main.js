$('#modal-success').modal('show')

let task_id
$('.taskEditBtn').on('click', function (e) {
   task_id = $(this).data('task_id')
   $('#editTaskTextField').val($(this).closest('tr').find('.task-text').html())

   if ($(this).closest('tr').find('.task-status').data('status') === 1) {
      $('#taskDone').attr("checked", true)
   } else {
      $('#taskDone').attr("checked", false)
   }
})

function ajaxSend(args) {
   $.ajax({
      url: args.url,
      method: 'post',
      dataType: 'json',
      data: args.data,
      beforeSend: function () {
         $(this).find('button[type=submit]').attr('disabled', true)
      },
      success: function (data) {
         if (args.action === 'login') {
            if (data.access === 'pass') {
               $(location).attr('href', '/')
            } else {
               $('#inputPassword').after('<span class="text-error">Доступ запрещен</span>')
            }
         }

         if (args.action === 'edit-tsak') {
            if (data.status !== 'denied') {
               $(location).attr('href', '/' + data.params)
            } else {
               $('#editTaskTextField').after('<span class="text-error">Доступ запрещен</span><br><a href="/admin">Войти в систему</a>')
            }
         }
      },
      error: function (jqXHR, exception) {
         $('#exampleInputPassword').after('<span class="text-error">Произошла ошибка' + exception + '</span>')
      }
   }).always(function () {
      $(this).find('button[type=submit]').attr('disabled', false)
   })
}


$('.edit-task').on('submit', function (e) {
   e.preventDefault()

   let args = {
      action: 'edit-tsak',
      url: '/edit-task',
      data: {
         checkbox: $('#taskDone').is(':checked') ? 1 : 0,
         text: $('#editTaskTextField').val(),
         id: task_id,
         params: location.search
      }
   }
   ajaxSend(args)
})

$('.add-task').on('submit', function (e) {
   if (!validateTaskForm()) {
      e.preventDefault()
   }
})

$('.admin-login').on('submit', function (e) {
   e.preventDefault()
   if (!validateLoginForm()) {
      return
   }

  let args = {
    action: 'login',
    url: '/login',
    data: {
      login: $('#inputLogin').val(),
      password: $('#inputPassword').val()
    }
  }
  ajaxSend(args)
})





