import Swal from 'sweetalert2';

export class CustomAlertsService {

  constructor() {}

  static generalAlert({ title, text, icon, timer, isToast, showTimer }) {
    return Swal.fire({
      position: "top",
      icon: icon? icon : "success",
      title: title,
      text: text,
      showConfirmButton: false,
      timer: timer ? timer : 1500,
      toast: isToast? isToast : false,
      timerProgressBar: showTimer ? showTimer : false
    });
  }

  static errorAlert({ title, text, timer }) {
    return Swal.fire({
      position: "top",
      icon: "error",
      title: title,
      text: text,
      showConfirmButton: false,
      timer: timer ? timer : 2000
    });
  }

  warningAlert(title, text, showConfirmButton, timer) {
    return Swal.fire({
      position: "top",
      icon: "warning",
      title: title,
      text: text,
      showConfirmButton: showConfirmButton ? showConfirmButton : false,
      timer: timer ? timer : 2000
    });
  }

  infoAlert(title, text, showConfirmButton, timer) {
    return Swal.fire({
      position: "top",
      icon: "info",
      title: title,
      text: text,
      showConfirmButton: showConfirmButton ? showConfirmButton : false,
      timer: timer ? timer : 2000
    });
  }

  questionAlert(title, text, showConfirmButton, timer) {
    return Swal.fire({
      position: "top",
      icon: "question",
      title: title,
      text: text,
      showConfirmButton: showConfirmButton ? showConfirmButton : false,
      timer: timer ? timer : 2000
    });
  }

  //En uso, verificar los demas
  static successConfirmAlert({ title, text }) {
    return Swal.fire({
      position: "top",
      icon: "success",
      title: title,
      text: text,
      showConfirmButton: true,
      confirmButtonColor: '#39A900'
    });
  }
  //TODO TERMINAR
  static deleteConfirmAlert({ title, text }) {
    let swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: "btn bg-[#39A900]",
        cancelButton: "btn btn-danger"
      },
      buttonsStyling: false
    });
    return swalWithBootstrapButtons.fire({
      title: title,
      text: text,
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Continuar",
      cancelButtonText: "Cancelar",
      reverseButtons: true
    });
  }
}
