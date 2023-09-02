import { Squadra } from "./squadra";

export class Utente {
  id:string;
  username: string;
  password: string;
  email: string;
  scadenza:string;
  num_msg: number;
  qta: number;
  token?: string;
  squadre?:[]
  selezionata?:Squadra
 
  constructor(
    username: string,
    password: string,
    email: string
  ) {
    this.username = username;
    this.password = password;
    this.email = email;
  }
}
