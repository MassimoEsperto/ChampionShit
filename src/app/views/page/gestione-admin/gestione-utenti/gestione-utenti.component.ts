import { Component, Input, OnInit, Output, EventEmitter } from '@angular/core';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AdminService } from 'src/app/services/admin.service';
import { AlertService } from 'src/app/services/alert.service';
import { MatDialog } from '@angular/material/dialog';
import { MyModalValidate } from 'src/app/components/my-modal-validate/my-modal-validate.component';
import { ComboRuolo, Ruolo } from 'src/app/classes/utils/enums';


@Component({
  selector: 'gestione-utenti',
  templateUrl: './gestione-utenti.component.html',
  styleUrls: ['./gestione-utenti.component.scss']
})
export class GestioneUtentiComponent extends GlobalComponent implements OnInit {


  editField: string;
  @Input() utenti: Utente[];

  constructor(
    private alert: AlertService,
    public dialog: MatDialog,
    private adminService: AdminService) {
    super();
  }

  ngOnInit() { }



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


  pagato(user: Utente) {

    this.adminService.pagato(user)
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

  validate(user: Utente) {

    this.adminService.validateUtente(user)
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

    this.adminService.getUtenti()
      .subscribe({
        next: (result: Utente[]) => {

          this.utenti = result;

        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }

  /* FINE CHIAMATE AI SERVIZI */


  updateList(id: number, property: string, event: any) {
    const editField = event.target.textContent;
    this.utenti[id][property] = editField;
  }

  onDelete(id: any, id_utente: string) {
    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.remove(id, id_utente);
    });
  }

  onValidate(element: Utente) {
    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.validate(element);
    });
  }

  onPagato(element: Utente) {
    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.pagato(element);
    });
  }

  changeValue(id: number, property: string, event: any) {
    this.editField = event.target.textContent;
  }

  getRuolo(input: any) {
    switch (Number(input)) {
      case Ruolo.ADMIN:
        return ComboRuolo.ADMIN_DESC
        break;
      case Ruolo.GIOCATORE:
        return ComboRuolo.GIOCATORE_DESC
        break;
      default:
        return ComboRuolo.VISITATORE_DESC
        break;
    }
  }

}