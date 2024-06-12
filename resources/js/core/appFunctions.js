export class AppFunctions{
    static Errors = Object.freeze({
        Field: 'field',
        Password: 'password',
        Date: 'date',
        Investigator: 'investigators'
      });

    static getErrorTranslate= (error)=>{
        switch (error) {
            case 'field':
                return 'Debes completar este campo';
            case 'password':
                return 'Debe ser mayor a 8 caracteres';
            case 'date':
                return 'Debes escoger una fecha valida';
            case 'investigators':
                return 'Debes seleccionar al menos un investigador';
        }    
    }
}