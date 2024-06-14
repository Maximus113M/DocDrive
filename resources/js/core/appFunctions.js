export class AppFunctions{
    static Errors = Object.freeze({
        Field: 'field',
        Password: 'password',
      });

    static getErrorTranslate= (error)=>{
        switch (error) {
            case 'field':
                return 'Debes completar este campo';
            case 'password':
                return 'Debe ser mayor a 8 caracteres';
        }    
    }
}

export class Constants {
    static INVESTIGATOR_ID = 2
    static COLLABORATOR_ID = 3
}