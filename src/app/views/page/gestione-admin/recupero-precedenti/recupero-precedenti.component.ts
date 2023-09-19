import { AdminService } from 'src/app/services/admin.service';
import { Component, Input, OnInit } from '@angular/core';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AlertService } from 'src/app/services/alert.service';
import { finalize } from 'rxjs/operators';
import { MatDialog } from '@angular/material/dialog';
import { MyModalValidate } from 'src/app/components/my-modal-validate/my-modal-validate.component';


@Component({
  selector: 'recupero-precedenti',
  templateUrl: './recupero-precedenti.component.html',
  styleUrls: ['./recupero-precedenti.component.scss']
})
export class RecuperoPrecedentiComponent extends GlobalComponent implements OnInit {

  @Input() administrator: any;

  constructor(
    private alert: AlertService,
    public dialog: MatDialog,
    private adminService: AdminService) {
    super();
  }


  ngOnInit() {
    this.giornata_selezionata = this.administrator.calcolato['NO'][0]
  }

  giornata_selezionata: string;
  formazioni_inserite: any;

  onInsert(payload: any) {
    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.setFormazione(payload);
    });
  }

  recTeamTrasferta(item:any) {

    let payload = {
      id_utente: item.CASA.id_utente,
      id_risultato: item.TRASFERTA.id_risultato,
      id_avversario: item.TRASFERTA.id_utente
    }

    this.onInsert(payload);
  }

  recTeamCasa(item:any) {

    let payload = {
      id_utente: item.CASA.id_utente,
      id_risultato: item.CASA.id_risultato
    }
  
    this.onInsert(payload);
  }



  /* CHIAMATA AI SERVIZI */
  formazioniInserite() {
    this.loading_btn = true;

    this.adminService.getFormazioniByGionata(this.giornata_selezionata)
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


  setFormazione(payload) {

    this.loading_btn = true;

    this.adminService.recuperoFormazione(payload)
      .pipe(finalize(() =>
        this.loading_btn = false
      ))
      .subscribe({

        next: (result: any) => {
          this.formazioniInserite();
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })

  }



  /* FINE CHIAMATA AI SERVIZI */
}


