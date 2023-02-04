switch (document.location.pathname) {
case '/':
  $('header .nav-item:nth-child(1)').addClass('active');
  break;
  
case '/products':
  $('header .nav-item:nth-child(2)').addClass('active');
  break;

case '/categories':
  $('header .nav-item:nth-child(3)').addClass('active');
  break;

case '/login':
  $('header .nav-item:nth-child(4)').addClass('active');
  break;

case '/orders':
  $('header .nav-item:nth-child(4)').addClass('active');
  break;

case '/profile':
  $('header .nav-item:nth-child(4)').addClass('active');
  break;

default:
  $('header .nav-item:nth-child(1)').addClass('active');
}

$('.sub-btn').click(function () {
  let email = $('.sub-input')[0].value;
  $.ajax({
    url: '/subscribe',
    method: 'post',
    data: {email: email},
    success: function(response){
      let div = $('#sub-alert');
      switch(response) {

      case 'ValidateError':
        div.html('Правильно заполните поле!');
        div.removeClass();
        div.addClass('alert alert-danger');
        div.fadeIn(200);
        break;

      case 'UniqueError':
        div.html('Такой email уже зарегистрирован на сайте!');
        div.removeClass();
        div.addClass('alert alert-danger');
        div.fadeIn(200);
        break;

      default:
        div.html('Данные успешно отправлены!');
        div.removeClass();
        div.addClass('alert alert-success');
        div.fadeIn(200);
        break;
      }
    },
    error: function(){
      alert('На сервере возникла ошибка, попробуйте позже!');
    }
  });
});

$('.add-to-cart').click(function (event) {
  event.preventDefault();
  let parent = $(this).parent();
  let id = parent.find('input[name="id"]').val();
  let count = parent.find('input[name="count"]').val() ?? 1;

  $.ajax({
    url: '/add-to-cart',
    method: 'post',
    data: {
      id: id,
      count: count
    },
    success: function(response){
      showAlert();
      updateCount();
    },
    error: function(){
      alert('На сервере возникла ошибка, попробуйте позже!');
    }
  });

});

function showAlert() {
  let alertMessage = $('.alert-message');
  alertMessage.find('.alert').html('Товар добавлен в корзину!');

  alertMessage.fadeIn();
  setTimeout(function () {
    alertMessage.fadeOut();
  }, 1500);
}

function updateCount() {

  $.ajax({
    url: '/get-products-count',
    method: 'post',
    success: function(response){
      $('.nav-shop__circle').html(response);
    }
  });  
}

$('.update-cart').click(function () {
  let ids = document.querySelectorAll('input[name=id]');
  let counts = document.querySelectorAll('input[name=count]');

  let data = [];

  ids.forEach((element, number) => {
    
    data.push({
      'id': element.value,
      'count': counts[number].value
    });

  });

  $.ajax({
    url: '/update-cart',
    method: 'post',
    data: {
      data: data
    },
    success: function(response){
      window.location.replace("/cart");
    },
    error: function(){
      alert('На сервере возникла ошибка, попробуйте позже!');
    }
  });

});

$('.search-products').click(function () {
  let text = $('.filter-bar-search input').val();
  let titles = $('.lattest-product-area .card-product__title a');

  let check = false;

  if (text) {
    titles.each(function (index, element) {

      let string = $(this).html().toLowerCase();
      let substring = text.toLowerCase();

      if ( string.includes(substring) ) {
        // $('.card__outer').fadeOut(0);
        check = true;
        $(this).closest('.card__outer').fadeIn(0);
      } else {
        $(this).closest('.card__outer').fadeOut(0);
      }
    });

    if (check === false) {
      $('.not-found').fadeIn(0);
    } else {
      $('.not-found').fadeOut(0);
    }
  } else {
    $('.card__outer').fadeIn(0);
    $('.not-found').fadeOut(0);
  }

});

$('.review-info button').click(function () {
  let id = $('.review-info input[name=product_id]').val();
  let rating = $('.review-info input[name=rating]:checked').val();
  let message = $('.review-info textarea[name=message]').val();
  
  let args = {
    product_id: id,
    rating: rating,
    message: message,
  };

  $.ajax({
    url: '/leave-review',
    method: 'post',
    data: {
      data: args
    },
    success: function(response){

      let div = $('#review-alert');
      switch(response) {

      case 'ValidateError':
        div.html('Для добавления отзыва поставьте оценку и напишите комментарий!');
        div.removeClass();
        div.addClass('alert alert-danger');
        div.fadeIn(200);
        break;

      case 'AuthError':
        div.html('Для добавления отзыва необходимо авторизироваться!');
        div.removeClass();
        div.addClass('alert alert-danger');
        div.fadeIn(200);
        break;

      case 'RepeatError':
        div.html('Вы уже оставили отзыв к этому товару!');
        div.removeClass();
        div.addClass('alert alert-danger');
        div.fadeIn(200);
        break;

      case 'Success':
        div.html('Комментарий сохранен и будет опубликован после одобрения администратором!');
        div.removeClass();
        div.addClass('alert alert-success');
        div.fadeIn(200);
        break;

      default:
        console.log(response);
      }
      
    },
    error: function(){
      alert('На сервере возникла ошибка, попробуйте позже!');
    }
  });

});