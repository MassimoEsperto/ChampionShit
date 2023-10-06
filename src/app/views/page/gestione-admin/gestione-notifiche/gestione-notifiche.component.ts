import { Component, Input, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { finalize } from 'rxjs/operators';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AdminService } from 'src/app/services/admin.service';
import { AlertService } from 'src/app/services/alert.service';

@Component({
  selector: 'gestione-notifiche',
  templateUrl: './gestione-notifiche.component.html',
  styleUrls: ['./gestione-notifiche.component.scss']
})
export class GestioneNotificheComponent extends GlobalComponent implements OnInit {

  @Input() utenti: Utente[];
  toggle: boolean = true;
  notifiche:any;

  constructor(
    public dialog: MatDialog,
    private adminService: AdminService,
    private alert: AlertService) {
    super();
  }

  ngOnInit() {}


  toggla() {
    this.toggle = !this.toggle
    if(!this.notifiche) this.getComunicazioni();
    console.log("utenti",this.utenti)
  }

  /* CHIAMATE AI SERVIZI */
  setComunicazione(payload: any) {

    this.loading_btn = true;

    this.adminService.setComunicazione(payload)
      .pipe(finalize(() => {
        this.loading_btn = false;
      }))
      .subscribe({
        next: (result: any) => {
          this.alert.success(this.language.alert.success);
          this.getComunicazioni();
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }

  getComunicazioni() {

    this.adminService.getAdministrator()
      .subscribe({
        next: (result: any) => {      
          this.notifiche=result.comunicazioni;
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }

  addComunicazione(payload: any) {

    this.loading_btn = true;

    this.adminService.addComunicazione(payload)
      .pipe(finalize(() => {
        this.loading_btn = false;
      }))
      .subscribe({
        next: (result: any) => {
          this.alert.success(this.language.alert.success);
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }
  /* FINE CHIAMATE AI SERVIZI */
}
