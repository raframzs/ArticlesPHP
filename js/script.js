$(".delete").on("click", function (e) {
  console.log("hello");
  //Si se llama a este método, la acción predeterminada del evento no se activará.
  e.preventDefault();

  if (confirm("Are you sure?")) {
    //  Creamos un elemento
    var frm = $("<form>");
    // Setteamos los atributos
    frm.attr("method", "post");
    frm.attr("action", $(this).attr("href"));
    frm.appendTo("body");
    frm.submit();
  }
});

/**
 * Add a method to validate a date time string
 */
$.validator.addMethod(
  "dateTime",
  function (value, element) {
    return value == "" || !isNaN(Date.parse(value));
  },
  "Must be a valid date and time"
);

/**
 * Validate the article form
 */
$("#formArticle").validate({
  
  errorClass: "my-error-class",
  validClass: "my-valid-class",
  
  rules: {
    title: {
      required: true,
    },
    content: {
      required: true,
    },
    published: {
      dateTime: true,
    },
  },
});

/**
 * Handle the publish button for publishing articles
 */
$("button.publish").on("click", function(e) {

    var id = $(this).data('id');
    var button = $(this);

    $.ajax({
        url: '/articles/admin/publish-article.php',
        type: 'POST',
        data: {id: id}
    })
    .done(function(data) {

        button.parent().html(data);

    })
    .fail(function data(){

      alert("An error occured");

    });
});

$("#published").datetimepicker({
    format: 'Y-m-d H:i:s'
});

$("#formContact").validate({
  
  errorClass: "my-error-class",
  validClass: "my-valid-class",
  
  rules: {
    email: {
      required: true,
      email : true
    },
    subject: {
      required: true,
    },
    message: {
      required: true,
    },
  },
});