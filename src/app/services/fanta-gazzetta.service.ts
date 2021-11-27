import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { catchError, map } from 'rxjs/operators';
import { SERVICE_TYPE } from '../classes/utils/costanti';
import { HttpSenderService } from './http-sender-service';

@Injectable({
  providedIn: 'root'
})
export class FantaGazzettaService extends HttpSenderService {

  constructor(private http: HttpClient, private route: Router) {
    super(SERVICE_TYPE.FANTA);
  }

  getProbabiliFormazione() {
    return this.http.get(`${this.buildURL("get_probabili_formazioni")}`).pipe(
      map((res) => {

        return res['data'];

      }),
      catchError(this.handleError));
  }

  getLiveFormazione() {
    return this.http.get(`${this.buildURL("get_voti_live")}`).pipe(
      map((res) => {

        return res['data'];

      }),
      catchError(this.handleError));
  }

  getLega(name: string) {

    const params = new HttpParams().set('lega', name);

    return this.http.get<any>(`${this.buildURL("get_lega")}`, { params: params })
      .pipe(map((res) => {

        return res['data'];

      }),
        catchError(this.handleError));
  }
}
