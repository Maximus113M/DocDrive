export class AppFunctions{
    static Errors = Object.freeze({
        Field: 'field',
        Email: 'email',
        Password: 'password',
        startDate: 'startDate',
        endDate: 'endDate',
        Investigator: 'investigators'
      });

    static getErrorTranslate= (error)=>{
        switch (error) {
            case 'field':
                return 'Debes completar este campo';
            case 'email':
                return 'Correo invalido o ya existente';
            case 'password':
                return 'Debe ser mayor a 8 caracteres';
            case 'startDate':
                return 'Debe coincidir con el año de vigencia';
            case 'endDate':
                return 'Debe coincidir con el año de vigencia y ser mayor a la inicial';
            case 'investigators':
                return 'Debes seleccionar al menos un investigador';
        }    
    }
}

export class Constants {
    static INVESTIGATOR_ID = 2
    static COLLABORATOR_ID = 3
    static COLLABORATOR = "collaborator"
    static INVESTIGATOR = "investigator"
}