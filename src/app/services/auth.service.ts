import { Router } from '@angular/router';
import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { map, catchError } from 'rxjs/operators';
import { HttpSenderService } from './http-sender-service';
import { Observable } from 'rxjs';
import { SERVICE_TYPE } from '../classes/utils/costanti';



@Injectable({
  providedIn: 'root'
})
export class AuthService extends HttpSenderService {

  /**
   * Costruttore
   * @param http Servizio richieste HTTP
   */
  constructor(private http: HttpClient, private route: Router) {
    super(SERVICE_TYPE.AUT);
  }

  /**
   * Login
   * @param username Username
   * @param password Password
   */
  login(username: string, pass: string) {
    const params = new HttpParams().set('user', username).set('pass', pass);

    return this.http.get<any>(`${this.buildURL("sign-in")}`, { params: params })
      .pipe(map((res) => {

        let token = res['token'];

        const decoded = this.helper.decodeToken(token);
        decoded.token = token;

        this.setToken(decoded);

        return decoded;

      }),
        catchError(this.handleError));
  }




  /**
   * Effettua il logout
   */
  logout(): void {
    localStorage.removeItem("tk-user");
  }

  /**
   * Verifica se l'utente è loggato
   */
  isLogged(): boolean {

    let token = localStorage.getItem("tk-user")

    if (!token) return false; //nel caso nn ci sia token

    let now = new Date();
    let scadenza: Date = new Date(JSON.parse(localStorage.getItem("tk-user")).scadenza);

    // Ritorna true se il token è presente nella sessione false nel caso sia scaduto
    return !!token && now < scadenza

  }


  /**
  * salva il token in sessione
  * @param tkuser 
  */
  setToken(tkuser: any) {
    tkuser.scadenza = this.scadenza().toString();
    let input = JSON.stringify(tkuser)

    localStorage.setItem('tk-user', input);
  }

  setTokenDecoded(tkuser: any) {
    const decoded = this.helper.decodeToken(tkuser);
        decoded.token = tkuser;

        this.setToken(decoded);
  }

  /**
   * recupera la password
   * @param username 
   * @param email 
   */
  recuperaPassword(id: string) {

    const params = new HttpParams().set('id_utente', id);

    return this.http.get<any>(`${this.buildURL("recupera_password")}`, { params: params })
      .pipe(map((res) => {

        return res['data'];

      }),
        catchError(this.handleError));
  }

  segnalaUtente(utente: any) {

    return this.http.post(`${this.buildURL("register_mail")}`, { data: utente })
      .pipe(map((res) => {
        return 'ok';
      }),
        catchError(this.handleError));
  }

  verificaVersioneWeb() {
    return this.http.get(`${this.buildURL("info")}`)
      .pipe(map((res) => {
        let verifica = {
          applicazione: res['data'].versione,
          locale: this.versione(),
          error: res['data'].versione != this.versione()
        }
        return verifica;
      }),
        catchError(this.handleError));
  }

  registraUtente(payload: any): Observable<any[]> {

    return this.http.post(`${this.buildURL("registra_utente")}`, { data: payload })
      .pipe(map((res) => {
        return res['data'];
      }),
        catchError(this.handleError));
  }

  registraSquadra(payload: any): Observable<any[]> {

    return this.http.post(`${this.buildURL("registra_squadra")}`, { data: payload })
      .pipe(map((res) => {
        return res['data'];
      }),
        catchError(this.handleError));
  }

  

  getRegister() {
    return this.http.get(`${this.buildURL("get_register")}`).pipe(
      map((res) => {

        return res['data'];

      }),
      catchError(this.handleError));
  }


}