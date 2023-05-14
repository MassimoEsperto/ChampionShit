import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { Observable } from 'rxjs';
import { Utente } from '../classes/models/utente';
import { HttpSenderService } from './http-sender-service';
import { map, catchError } from 'rxjs/operators';
import { SERVICE_TYPE } from '../classes/utils/costanti';
import { Rosa } from '../classes/models/rosa';
import * as XLSX from 'xlsx';

@Injectable({
  providedIn: 'root'
})
export class AdminService extends HttpSenderService {

  utenti: Utente[];

  constructor(private http: HttpClient, private route: Router) {
    super(SERVICE_TYPE.ADMIN);
  }

  getAdministrator(): Observable<any> {
    return this.http.get(`${this.buildURL("get_administrator")}`).pipe(
      map((res) => {

        return res['data'];
      }),
      catchError(this.handleError));
  }


  updDetailUtente(utente: Utente) {
    return this.http.put(`${this.buildURL("upd_detail_utente")}`, { data: utente })
      .pipe(map((res) => {
        return 'ok';
      }),
        catchError(this.handleError));
  }

  deleteUtente(id_utente: string) {
    const params = new HttpParams().set('id_utente', id_utente);

    return this.http.get(`${this.buildURL("del_utente")}`, { params: params })
      .pipe(map(res => {
        return 'ok';
      }),
        catchError(this.handleError));
  }

  deleteRosa(id_utente: string) {
    const params = new HttpParams().set('id_utente', id_utente);

    return this.http.get(`${this.buildURL("del_rosa_utente")}`, { params: params })
      .pipe(map(res => {
        return 'ok';
      }),
        catchError(this.handleError));
  }

  getListaCalciatori() {
    return this.http.get(`${this.buildURL("get_lista_calciatori")}`).pipe(
      map((res) => {

        return res['data'];

      }),
      catchError(this.handleError));
  }



  calcolaGiornata(payload: any): Observable<any[]> {

    return this.http.post(`${this.buildURL("set_calcolo_giornata")}`, { data: payload })
      .pipe(map((res) => {
        return res['data'];
      }),
        catchError(this.handleError));
  }

  insertSvincolati(rose: Rosa[]) {

    return this.http.post(`${this.buildURL("set_svincolati")}`, { data: rose })
      .pipe(map((res) => {
        return 'ok';
      }),
        catchError(this.handleError));
  }

  insertRosaUtente(lista: any) { //associa la rosa all'utente

    return this.http.post(`${this.buildURL("set_rosa_utente")}`, { data: lista })
      .pipe(map((res) => {
        return 'ok';
      }),
        catchError(this.handleError));
  }

  insertVoti(risultati: any): Observable<any[]> {

    let payloads = this.formatting.payloadCalcolo(risultati);

    return this.http.post(`${this.buildURL("set_voti")}`, { data: payloads.voti })
      .pipe(map((res) => {
        return payloads.ris;
      }),
        catchError(this.handleError));
  }

  getFormazioniByGionata(giornata: string) {

    const params = new HttpParams().set('giornata', giornata);

    return this.http.get<any>(`${this.buildURL("get_formazioni_by_giornata")}`, { params: params })
      .pipe(map((res) => {

        return res['data'];

      }),
        catchError(this.handleError));
  }


  getPalinsesto(filelist: any, formazioni_inserite: any) {

    return this.formatting.palinsesto(filelist, formazioni_inserite)
  }


  recuperoFormazione(payload: any): Observable<any[]> {

    return this.http.post(`${this.buildURL("upd_schieramento")}`, { data: payload })
      .pipe(map((res) => {

        return res['data'];
      }),
        catchError(this.handleError));
  }

  setComunicazione(payload: any): Observable<any[]> {

    return this.http.post(`${this.buildURL("set_comunicazione")}`, { data: payload })
      .pipe(map((res) => {
        return res['data'];
      }),
        catchError(this.handleError));
  }

  getComunicazioni(): Observable<any[]> {
    return this.http.get(`${this.buildURL("get_all_comunicazioni")}`).pipe(
      map((res) => {
        return res['data'];
      }),
      catchError(this.handleError));
  }

  addComunicazione(payload: any): Observable<any[]> {

    return this.http.post(`${this.buildURL("add_comunicazione")}`, { data: payload })
      .pipe(map((res) => {
        return res['data'];
      }),
        catchError(this.handleError));
  }


  getCreaCompetizioneRandom() {
    return this.http.get(`${this.buildURL("get_crea_competizione_random")}`).pipe(
      map((res) => {

        return res['data'];

      }),
      catchError(this.handleError));
  }

  setCreaCompetizioneRandom(payload: any): Observable<any[]> {

    return this.http.post(`${this.buildURL("set_crea_competizione_random")}`, { data: payload })
      .pipe(map((res) => {
        return res['data'];
      }),
        catchError(this.handleError));
  }

  sostituisciCalciatore(payload: any) {

    return this.http.post(`${this.buildURL("upd_player_utente")}`, { data: payload })
      .pipe(map((res) => {
        return 'ok';
      }),
        catchError(this.handleError));
  }

  cambiaDate(payload: any) {

    return this.http.post(`${this.buildURL("upd_giornata")}`, { data: payload })
      .pipe(map((res) => {

        return 'ok';
      }),
        catchError(this.handleError));
  }

  newData(payload: any) {

    return this.http.post(`${this.buildURL("set_giornata")}`, { data: payload })
      .pipe(map((res) => {

        return 'ok';
      }),
        catchError(this.handleError));
  }



  getAccoppiamenti(): Observable<any[]> {
    return this.http.get(`${this.buildURL("get_accoppiamenti")}`).pipe(
      map((res) => {
        return res['data'];
      }),
      catchError(this.handleError));
  }

  setAccoppiamento(payload: any) {

    return this.http.post(`${this.buildURL("set_accoppiamento")}`, { data: payload })
      .pipe(map((res) => {

        return 'ok';
      }),
        catchError(this.handleError));
  }


  updAccoppiamento(payload: any) {

    return this.http.post(`${this.buildURL("upd_accoppiamento")}`, { data: payload })
      .pipe(map((res) => {

        return 'ok';
      }),
        catchError(this.handleError));
  }

  deleteAccoppiamento(id: string) {

    const params = new HttpParams().set('id', id);

    return this.http.get(`${this.buildURL("del_accoppiamento")}`, { params: params })
      .pipe(map(res => {
        return 'ok';
      }),
        catchError(this.handleError));
  }


  deleteObjectById(payload: any) {

    return this.http.post(`${this.buildURL("del_object_by_id")}`, { data: payload })
      .pipe(map((res) => {

        return 'ok';
      }),
        catchError(this.handleError));
  }


  get_all_object(tabelle: string) {

    const params = new HttpParams().set('tabelle', tabelle);

    return this.http.get(`${this.buildURL("get_all_objects")}`, { params: params })
      .pipe(map(res => {

        return res['data'];

      }),
        catchError(this.handleError));
  }


  //recupero voti da xls
  async getWorkbookFromFile(excelFile: File) {
    return new Promise<any>((resolve, reject) => {
      var reader = new FileReader();

      reader.onload = (event: any) => {
        var data = event.target.result;

        var workbook = XLSX.read(data, { type: 'binary' });

        console.log(workbook.SheetNames);
        resolve(workbook);
      };
      reader.readAsBinaryString(excelFile);
    });
  }

  async getVotiFromFile(file: File) {

    let filelist: any = [];

    var workbook = await this.getWorkbookFromFile(file);

    var first_sheet_name = workbook.SheetNames[0];
    var worksheet = workbook.Sheets[first_sheet_name];

    let _EMPTY_0 = worksheet.A1['h']
    var arraylist: any = XLSX.utils.sheet_to_json(worksheet, { raw: true, defval: null });

    for (let element of arraylist) {
      if (element.__EMPTY) {
        let nome: string = element['__EMPTY'].toLocaleUpperCase().replace("'", "").replace(".", "").trim()
        let voto: number = element['__EMPTY_3'] != '-' ? Number(element['__EMPTY_3'].toString().trim()) : 4
        let ruolo: string = element[_EMPTY_0].toString().trim()

        let ris = {
          nome: nome,
          voto: voto
        }
        //filelist.push(ris);
        filelist[nome] = voto
      }

      if (element.__EMPTY_6) {
        let nome: string = element['__EMPTY_6'].toLocaleUpperCase().replace("'", "").replace(".", "").trim()
        let voto: number = element['__EMPTY_9'] != '-' ? Number(element['__EMPTY_9'].toString().trim()) : 4
        let ruolo: string = element['__EMPTY_5'].toString().trim()

        let ris = {
          nome: nome,
          voto: voto
        }
        //filelist.push(ris);
        filelist[nome] = voto
      }
    }

    return filelist;
  }


}

