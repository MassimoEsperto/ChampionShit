import { Squadra } from "./squadra";

export class Utente {
  id:string;
  username: string;
  password: string;
  email: string;
  cellulare: string;
  scadenza:string;
  num_msg: number;
  ruolo: number;
  qta: number;
  token?: string;
  squadre?:Array<Squadra> = [];
  selezionata?:Squadra;
  language?:string;
 
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
