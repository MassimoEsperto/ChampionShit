import { AdminService } from 'src/app/services/admin.service';
import { Component, Input, OnInit } from '@angular/core';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AlertService } from 'src/app/services/alert.service';
import { finalize } from 'rxjs/operators';
import { INIBITO } from 'src/app/classes/utils/costanti';
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
    this.giornata_selezionata = this.administrator.calcolato['NO'][0]}

  giornata_selezionata: string;
  formazioni_inserite: any;

  onInsert(payload: any) {
    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.setFormazione(payload);
    });
  }

  recTeamTrasferta(item) {

    let ultimeInserite =this.administrator.recuperate;
    let listaRose = this.administrator.rose;

    let ripescata = ultimeInserite.filter(x => x.id_utente == item.match[1].id_utente)
    let avversario = listaRose.find(x => x.id_utente == item.match[0].id_utente)

    let payload = {
      lista: [],
      id_partita: item.id_partita,
      id_utente: item.match[1].id_utente,
    }

    for (let membro of ripescata) {
      let esiste = avversario.lista.some(x => x.id_calciatore == membro.id_calciatore)
      if (esiste)
        payload.lista.push(INIBITO)
      else
        payload.lista.push(membro.id_calciatore)
    }
    this.onInsert(payload);

  }

  recTeamCasa(item) {

    let ultimeInserite =this.administrator.ultime_formazioni_inserite;

    let ripescata = ultimeInserite.filter(x => x.id_utente == item.match[0].id_utente)

    let payload = {
      lista: [],
      id_partita: item.id_partita,
      id_utente: item.match[0].id_utente,
    }

    for (let membro of ripescata) {
      payload.lista.push(membro.id_calciatore)
    }
    this.onInsert(payload);

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


