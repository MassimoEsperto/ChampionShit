import { AdminService } from 'src/app/services/admin.service';
import { Component, OnInit } from '@angular/core';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AlertService } from 'src/app/services/alert.service';
import { finalize } from 'rxjs/operators';
import { INIBITO } from 'src/app/classes/utils/costanti';
import { MatDialog } from '@angular/material/dialog';
import { MyModalValidate } from 'src/app/components/my-modal-validate/my-modal-validate.component';
import { PlayerService } from 'src/app/services/player.service';

@Component({
  selector: 'recupero-precedenti',
  templateUrl: './recupero-precedenti.component.html',
  styleUrls: ['./recupero-precedenti.component.scss']
})
export class RecuperoPrecedentiComponent extends GlobalComponent implements OnInit {

  constructor(
    private alert: AlertService,
    public dialog: MatDialog,
    private adminService: AdminService,
    private playerService: PlayerService) {
    super();
  }


  ngOnInit() {
    this.listaRoseUtenti()
    this.schieramentiPrecedenti();
    this.giornataDaCalcolare();
  }

  attuali = [];
  ultimeInserite: any;
  giornata: number;
  listaRose: any;
  giornateDaCalcolare: any;

  onInsert(payload: any) {
    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.setFormazione(payload);
    });
  }

  recTeamTrasferta(squadra, id_avversario) {
  
    let ripescata = this.ultimeInserite.filter(x => x.id_utente == squadra.id_utente)
    let avversario = this.listaRose.find(x => x.id_utente == id_avversario)

    let payload = {
      lista: [],
      id_partita: squadra.formazione[0].id_partita,
      id_utente: squadra.id_utente,
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

  recTeamCasa(squadra) {

    let ripescata = this.ultimeInserite.filter(x => x.id_utente == squadra.id_utente)

    let payload = {
      lista: [],
      id_partita: squadra.formazione[0].id_partita,
      id_utente: squadra.id_utente,
    }

    for (let membro of ripescata) {
      payload.lista.push(membro.id_calciatore)
    }
    this.onInsert(payload);

  }

  giornateComplete() {
    this.schieramentiAttuali();
  }



  /* CHIAMATA AI SERVIZI */
  schieramentiAttuali() {

    this.loading_btn = true;

    this.adminService.getSchiermenti(this.giornata.toString())
      .pipe(finalize(() => {
        this.loading_btn = false;
      }
      ))
      .subscribe({

        next: (result: any) => {
          this.attuali = result
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })

  }

  schieramentiPrecedenti() {

    this.adminService.lastFormazioniInserite()
      .subscribe({

        next: (result: any) => {
          this.ultimeInserite = result
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
          this.schieramentiAttuali();
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })

  }

  listaRoseUtenti() {

    this.playerService.getListaRose()
      .subscribe({

        next: (result: any) => {
          this.listaRose = result;
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })

  }

  giornataDaCalcolare() {
    this.loading_page = true;

    this.adminService.getGiornateCalcolate()
      .subscribe({

        next: (result: any) => {
          this.giornateDaCalcolare = result;
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })

  }
  /* FINE CHIAMATA AI SERVIZI */
}


