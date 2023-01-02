(function($) {
  "use strict";

  /*-----------------
      Delete Confirm
    -----------------------*/
  $('.delete-confirm').on('click', function (event) {
    const id = $(this).data('id');
      Swal.fire({
        title: 'Are you sure?',
        text: "You want to delete this project!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
      if (result.value) {
        event.preventDefault();
        document.getElementById('delete_form_'+id).submit();
      } else if (
        result.dismiss === Swal.DismissReason.cancel
        ) {
        swalWithBootstrapButtons.fire(
          'Cancelled',
          'Your Data is Save :)',
          'error'
        )
      }
    })
  });

  /*----------------------
      Custom File Change
    -------------------------*/
  $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
  });

})(jQuery);

/*--------------
    Sweet Aleart
  --------------------*/
  function Sweet(icon,title,time=3000){
      
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: time,
      timerProgressBar: true,
      onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    })
    Toast.fire({
      icon: icon,
      title: title,
    })
}