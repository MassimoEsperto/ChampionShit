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



  async importVoti(event: any) {
    let file: File
    let filelist: any = [];

    file = event.target.files[0];
    filelist = await this.adminService.getVotiFromFile(file)
    console.log("filelist2", filelist)
    /*
        for (let item of this.formazioni_inserite.lista) {
          item.voto = filelist[item.nome_calciatore] || 4
        }*/

    for (let partite of this.formazioni_inserite) {
      partite.CASA.somma = Number(partite.CASA.bonus)
      partite.TRASFERTA.somma = Number(partite.TRASFERTA.bonus)

      for (let casa of partite.CASA.schieramento) {
        casa.voto = Number(filelist[casa.calciatore]) || 4
        partite.CASA.somma += casa.voto;
      }



      for (let trasferta of partite.TRASFERTA.schieramento) {
        trasferta.voto = Number(filelist[trasferta.calciatore]) || 4
        partite.TRASFERTA.somma += trasferta.voto;
      }

      partite.CASA.goals = this.gol(partite.CASA.somma)
      partite.TRASFERTA.goals = this.gol(partite.TRASFERTA.somma)

      partite.CASA.punti = this.classifica(partite.CASA.goals, partite.TRASFERTA.goals)
      partite.TRASFERTA.punti = this.classifica(partite.TRASFERTA.goals, partite.CASA.goals)
    }

    console.log("this.formazioni_inserite", this.formazioni_inserite)
  }


  classifica(a: number, b: number) {
    if (a == b) return 1
    if (a > b) return 3
    else return 0
  }



  gol(punti: number) {

    if (punti < 30) {
      return 0
    } else {
      let tmp: any = (punti - 27) / 3;
      return parseInt(tmp).toFixed(0);
    }
  }


  /* CHIAMATA AI SERVIZI */
  formazioniByGionata() {
    this.loading_btn = true;

    this.adminService.getFormazioniByGionata(this.giornata_selezionata)
      .pipe(finalize(() => {
        this.loading_btn = false;
      }
      ))
      .subscribe({

        next: (result: any) => {
          this.formazioni_inserite = result
          console.log("this.formazioni_inserite", this.formazioni_inserite)
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

  /* FINE CHIAMATA AI SERVIZI */
}


