import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { Observable } from 'rxjs';
import { HttpSenderService } from './http-sender-service';
import { map, catchError } from 'rxjs/operators';
import { PROBABILI_FORMAZIONI_URL, SERVICE_TYPE, VOTI_LIVE_URL } from '../classes/utils/costanti';
import { Utente } from '../classes/models/utente';

@Injectable({
  providedIn: 'root'
})
export class PlayerService extends HttpSenderService {

  constructor(private http: HttpClient, private route: Router) {
    super(SERVICE_TYPE.PLAYER);
  }

  /* START ROSE */
  getListaRose() {

    return this.http.get(`${this.buildURL("get_all_rose_utenti")}`, this.myheaders)
      .pipe(map((res) => {

        this.tokenError(res);//controllo token

        return res['data'];

      }),
        catchError(this.handleError));
  }
  /* FINE ROSE */

  /* START UTENTE */
  updateUtente(utente: Utente) {
    return this.http.put(`${this.buildURL("upd_utente")}`,
      { data: utente }, this.myheaders)
      .pipe(map((res) => {

        this.tokenError(res);//controllo token

        return 'ok';
      }),
        catchError(this.handleError));
  }

  getAvatars() {
    return this.http.get(`${this.buildURL("get_avatars")}`, this.myheaders).pipe(
      map((res) => {

        this.tokenError(res);//controllo token

        return res['data'];;

      }),
      catchError(this.handleError));
  }
  /* FINE UTENTE */

  /* START RISULTATI */
  getDashboard() {
    return this.http.get(`${this.buildURL("get_dashboard")}`, this.myheaders).pipe(
      map((res) => {

        this.tokenError(res);//controllo token

        return res['data'];

      }),
      catchError(this.handleError));
  }

  getCalendario() {
    return this.http.get(`${this.buildURL("get_risultati")}`, this.myheaders).pipe(
      map((res) => {

        this.tokenError(res);//controllo token 

        return res['data'];

      }),
      catchError(this.handleError));
  }

  getClassifica() {
    return this.http.get(`${this.buildURL("get_classifiche")}`, this.myheaders).pipe(
      map((res) => {

        this.tokenError(res);//controllo token

        return res['data'];

      }),
      catchError(this.handleError));
  }


  /* FINE RISULTATI */

  /* START SCHIERAMENTI */
  getRosaDisponibile() {

    return this.http.get(`${this.buildURL("get_rosa_disponibile")}`, this.myheaders)
      .pipe(map((res) => {

        this.tokenError(res);//controllo token

        return res['data'];

      }),
        catchError(this.handleError));
  }



  viewMatch(match: any) { //match_live

    const params = new HttpParams().set('match', match.partita);

    return this.http.get<any>(`${this.buildURL("get_match_live")}`,
      { params: params, headers: this.myheaders.headers })
      .pipe(map((res) => {

        this.tokenError(res);//controllo token

        res['data']['calcolato'] = match.calcolato == '1'

        return res['data']

      }),
        catchError(this.handleError));
  }

  insertFormazione(payload: any): Observable<any[]> {

    return this.http.post(`${this.buildURL("set_schieramento")}`,
      { data: payload }, this.myheaders)
      .pipe(map((res) => {

        this.tokenError(res);//controllo token

        return res['data'];
      }),
        catchError(this.handleError));
  }

  getProbabiliFormazione(): Observable<any> {
    return this.http.get(PROBABILI_FORMAZIONI_URL, { responseType: 'text' });
  }


  getFormazioni() {

    return this.http.get(`${this.buildURL("live_fantacalcio")}`, this.myheaders)
      .pipe(map((res) => {

        this.tokenError(res);//controllo token

        return res['data'];

      }),
        catchError(this.handleError));
  }

  getVotiLive(team: any, gio: string) {

    return this.http.get(VOTI_LIVE_URL + team + "?g=" + gio + "&i=16")
      .pipe(map((res) => {

        return res;

      }),
        catchError(this.handleError));
  }


  /* FINE SCHIERAMENTI */

  /* COMUNICAZIONI */
  getCommunicazioni() {
    return this.http.get(`${this.buildURL("get_comunicazioni")}`, this.myheaders).pipe(
      map((res) => {

        this.tokenError(res);//controllo token

        return res['data'];

      }),
      catchError(this.handleError));
  }

  readCommunicazioni() {
    return this.http.get(`${this.buildURL("read_comunicazioni")}`, this.myheaders).pipe(
      map((res) => {

        this.tokenError(res);//controllo token

        return 'ok';

      }),
      catchError(this.handleError));
  }
  /* FINE COMUNICAZIONI */
}

