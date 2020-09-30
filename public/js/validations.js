
function validateLoginForm() {
  let succes = true
  $(".text-error").remove()
  if ($('#inputLogin').val() === '') {
    $('#inputLogin').after('<span class="text-error">Введите логин</span>')
    succes = false
  }

  if ($('#inputPassword').val() === '') {
    $('#inputPassword').after('<span class="text-error">Введите пароль</span>')
    succes = false
  }
  return succes
}

function validateTaskForm() {
  let succes = true
  $(".text-error").remove()

  if ($('#name').val() === '') {
    $('#name').after('<span class="text-error">Имя пусто</span>')
    succes = false
  }

  if ($('#inputEmail').val() === '') {
    $('#inputEmail').after('<span class="text-error">Email пуст</span>')
    succes = false
  } else {
    pattern = /^([a-z0-9_\.-])+[@][a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i

    if (!pattern.test($('#inputEmail').val())) {
      $('#inputEmail').after('<span class="text-error">Email введен не верно</span>')
      succes = false
    }
  }

  if ($('#formControlTextarea').val() === '') {
    $('#formControlTextarea').after('<span class="text-error">Текст пуст</span>')
    succes = false
  }
  return succes
}