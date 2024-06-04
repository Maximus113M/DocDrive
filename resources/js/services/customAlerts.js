import Swal from 'sweetalert2';

export class CustomAlertsService {

  constructor() {}

  static generalAlert({ title, text, icon, timer, isToast, showTimer, position }) {
    return Swal.fire({
      position: position? position : "top-end",
      icon: icon? icon : "success",
      title: title,
      text: text,
      showConfirmButton: false,
      timer: timer ? timer : 3000,
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

  // warningAlert(title, text, showConfirmButton, timer) {
  //   return Swal.fire({
  //     position: "top",
  //     icon: "warning",
  //     title: title,
  //     text: text,
  //     showConfirmButton: showConfirmButton ? showConfirmButton : false,
  //     timer: timer ? timer : 2000
  //   });
  // }

  // infoAlert(title, text, showConfirmButton, timer) {
  //   return Swal.fire({
  //     position: "top",
  //     icon: "info",
  //     title: title,
  //     text: text,
  //     showConfirmButton: showConfirmButton ? showConfirmButton : false,
  //     timer: timer ? timer : 2000
  //   });
  // }

  // questionAlert(title, text, showConfirmButton, timer) {
  //   return Swal.fire({
  //     position: "top",
  //     icon: "question",
  //     title: title,
  //     text: text,
  //     showConfirmButton: showConfirmButton ? showConfirmButton : false,
  //     timer: timer ? timer : 2000
  //   });
  // }

  //En uso, verificar los demas
  static successConfirmAlert({ title, text, color }) {
    return Swal.fire({
      position: "top",
      icon: "success",
      title: title,
      text: text,
      showConfirmButton: true,
      confirmButtonColor: color ?? '#39A900'
    });
  }

  static deleteConfirmAlert({ title, text }) {
    return Swal.fire({
      title: title,
      text: text,
      icon: "warning",
      iconColor:"#f87171",
      showCancelButton: true,
      confirmButtonText: "Continuar",
      confirmButtonColor: '#72BB53',
      cancelButtonText: "Cancelar",
      cancelButtonColor:"#ef4444",
      reverseButtons: true
    });
  }
}
