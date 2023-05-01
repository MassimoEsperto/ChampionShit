import { Component, Input, OnInit } from '@angular/core';
import * as XLSX from 'xlsx';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AlertService } from 'src/app/services/alert.service';
import { finalize } from 'rxjs/operators';
import { AdminService } from 'src/app/services/admin.service';

@Component({
  selector: 'calcolo-voti',
  templateUrl: './calcolo-voti.component.html',
  styleUrls: ['./calcolo-voti.component.scss']
})
export class CalcoloVotiComponent extends GlobalComponent implements OnInit {

  @Input() calcolato: any;

  giornata_selezionata: string;
  formazioni_inserite: any;
  risultati = [];

  constructor(
    private alert: AlertService,
    private adminService: AdminService) {
    super();
  }


  ngOnInit() {
    this.giornata_selezionata = this.calcolato.SI[0];
  }



  importVoti(event: any) {
    let file: File
    let arrayBuffer: any;
    let filelist: any = [];

    file = event.target.files[0];
    let fileReader = new FileReader();
    fileReader.readAsArrayBuffer(file);
    fileReader.onload = (e) => {
      arrayBuffer = fileReader.result;
      var data = new Uint8Array(arrayBuffer);
      var arr = new Array();
      for (var i = 0; i != data.length; ++i) {
        arr[i] = String.fromCharCode(data[i]);
      }
      var bstr = arr.join("");

      var workbook = XLSX.read(bstr, { type: "binary" });
      var first_sheet_name = workbook.SheetNames[0];
      var worksheet = workbook.Sheets[first_sheet_name];

      let _EMPTY_0 = worksheet.A1['h']
      var arraylist: any = XLSX.utils.sheet_to_json(worksheet, { raw: true, defval: null });
      //  filelist = [];
      for (let element of arraylist) {
        if (element.__EMPTY) {
          let nome: string = element['__EMPTY'].toLocaleUpperCase().replace("'", "").replace(".", "").trim()
          let voto: number = element['__EMPTY_3'] != '-' ? Number(element['__EMPTY_3'].toString().trim()) : 4
          let ruolo: string = element[_EMPTY_0].toString().trim()
          if ("P" == ruolo) { voto = voto + 1; }

          let ris = {
            nome: nome,
            voto: voto
          }
          filelist.push(ris);
        }

        if (element.__EMPTY_6) {
          let nome: string = element['__EMPTY_6'].toLocaleUpperCase().replace("'", "").replace(".", "").trim()
          let voto: number = element['__EMPTY_9'] != '-' ? Number(element['__EMPTY_9'].toString().trim()) : 4
          let ruolo: string = element['__EMPTY_5'].toString().trim()
          if ("P" == ruolo) { voto = voto + 1; }

          let ris = {
            nome: nome,
            voto: voto
          }
          filelist.push(ris);
        }
      }
      this.risultati = this.adminService.getPalinsesto(filelist, this.formazioni_inserite)
    }
  }


  somma(punteggio: any) {
    let punti: number = 0;
    for (let item of punteggio.schieramento) {
      punti += item.voto;
    }
    punteggio.punti = punti;
  }

  gol(punteggio: any) {
    let punti: number = 0;
    for (let item of punteggio.schieramento) {
      punti += item.voto;
    }

    if (punti < 30) {
      punteggio.gol = 0
    } else {
      let tmp: any = (punti - 27) / 3;
      punteggio.gol = parseInt(tmp).toFixed(0);
    }
  }


  /* CHIAMATA AI SERVIZI */
  formazioniInserite() {
    this.loading_btn = true;

    this.adminService.getFormazioniInserite(this.giornata_selezionata)
      .pipe(finalize(() => {
        this.loading_btn = false;
      }
      ))
      .subscribe({

        next: (result: any) => {
          this.formazioni_inserite = result
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })

  }


  calcolaGiornata(payload: any) {

    this.adminService.calcolaGiornata(payload)
      .pipe(finalize(() => {
        this.loading_btn = false;
      }
      ))
      .subscribe({

        next: (result: any) => {
          this.alert.success(this.language.alert.success);
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })

  }

  updateRisultati() {

    this.loading_btn = true;

    this.adminService.insertVoti(this.risultati)
      .subscribe({

        next: (result: any) => {
          this.calcolaGiornata(result)
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })

  }
  /* FINE CHIAMATA AI SERVIZI */
}


