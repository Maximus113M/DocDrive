export class UserModel {
    id;
    name;
    email;
    document;
    phone;
    password;

    constructor({
        id,
        name,
        email,
        document,
        phone,
        password,
    }) {
        this.id = id ? id : ''
        this.name = name ? name : '';
        this.email = email ? email : '';
        this.document = document ? document : '';
        this.phone = phone ? phone : '';
        this.password = password ? password : '';
    }
}