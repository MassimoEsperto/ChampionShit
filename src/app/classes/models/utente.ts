export class Utente {
  id:string;
  username: string;
  password: string;
  email: string;
  squadra: string;
  avatar: string;
  id_avatar: string;
  scadenza:string;
  num_msg: number;
  token?: string;
  lega?: string;
  account?: string;
  stato?: number;

  constructor(
    username: string,
    password: string,
    email: string,
    squadra: string
  ) {
    this.username = username;
    this.password = password;
    this.email = email;
    this.squadra = squadra;
  }
}
