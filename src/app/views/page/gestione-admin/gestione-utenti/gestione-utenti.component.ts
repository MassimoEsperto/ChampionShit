import { Component, Input, OnInit, Output, EventEmitter } from '@angular/core';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AdminService } from 'src/app/services/admin.service';
import { AlertService } from 'src/app/services/alert.service';
import { MatDialog } from '@angular/material/dialog';
import { MyModalValidate } from 'src/app/components/my-modal-validate/my-modal-validate.component';
import { MyModalUtente } from 'src/app/components/my-modal-utente/my-modal-utente.component';


@Component({
  selector: 'gestione-utenti',
  templateUrl: './gestione-utenti.component.html',
  styleUrls: ['./gestione-utenti.component.scss']
})
export class GestioneUtentiComponent extends GlobalComponent implements OnInit {


  editField: string;
  @Input() administrator: any;
  squadre: any;

  constructor(
    private alert: AlertService,
    public dialog: MatDialog,
    private adminService: AdminService) {
    super();
  }

  ngOnInit() {
    this.squadre = this.administrator.squadre;
  }



  /* CHIAMATE AI SERVIZI */
  remove(id: any, id_utente: string) {

    this.adminService.deleteUtente(id_utente)
      .subscribe({
        next: (result: any) => {
          this.alert.success(this.language.alert.success);
          this.ricalcola()
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }


  updDetailUtente(user: any) {

    this.adminService.updDetailUtente(user)
      .subscribe({
        next: (result: any) => {

          this.alert.success(this.language.alert.success);
          this.ricalcola()
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }




  ricalcola() {

    this.adminService.getAdministrator()
      .subscribe({
        next: (result: any) => {

          this.squadre = result.squadre;

        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }

  /* FINE CHIAMATE AI SERVIZI */

  onDelete(id: any, id_utente: string) {
    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.remove(id, id_utente);
    });
  }

  onUpdUtente(utente) {

    const dialogRef = this.dialog.open(MyModalUtente, {
      panelClass: 'dialog-language',
      data: {
        titolo: 'Modifica Utenti',
        valori: utente,
        stati: this.administrator.stati_squadre,
        ruoli: this.administrator.ruoli
      }
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        this.updDetailUtente(result);
      }
    });
  }

}