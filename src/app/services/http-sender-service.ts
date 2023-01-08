import { HttpErrorResponse, HttpHeaders } from '@angular/common/http'
import { JwtHelperService } from '@auth0/angular-jwt';
import { throwError } from 'rxjs'
import { Utente } from '../classes/models/utente';
import { WS_BASE_URL } from '../classes/utils/costanti'
import { Formatting } from '../classes/utils/formatting';


export class HttpSenderService {

  typeServices: string;

  constructor(type = "/") {
    this.typeServices = type
  }

  formatting: Formatting = new Formatting();

  helper = new JwtHelperService();
  myheaders = { headers: new HttpHeaders().set('Authorization', `Bearer ${this.getLoggato().token}`) }

  buildURL(operation: string = ""): string {

    let URL: string = WS_BASE_URL

    URL = URL + this.typeServices + operation + ".php"

    return URL

  }

  getLocalStorage() {
    return JSON.parse(localStorage.getItem("tk-user"))
  }

  getLoggato() {
    let element = JSON.parse(localStorage.getItem("tk-user"))
    let utente: Utente = new Utente('', '', '', '')
    if (element) {
      utente.username = element.username
      utente.email = element.email
      utente.squadra = element.squadra

      utente.avatar = element.avatar
      utente.id_avatar = element.id_avatar
      utente.id = element.id_utente
      utente.ruolo = element.ruolo
      utente.token = element.token
      utente.num_msg = element.num_msg
      utente.lega = element.lega
      utente.account = element.account
      utente.pagato = element.pagato == 1
    }

    return utente
  }


  scadenza() {
    let primaDate = new Date();
    primaDate.setHours(primaDate.getHours() + 2);

    return primaDate;
  }


  handleError(response: HttpErrorResponse) {
    console.log("response", response)
    let message = response.error ? response.error.message : response
    return throwError(message);
  }

  tokenError(res: any) {
    let errorToken = res['errorToken'];
    if (errorToken) {
      throw new Error('Token Non Valido')
    }
  }

  versione() {
    return "4.3"
  }
}