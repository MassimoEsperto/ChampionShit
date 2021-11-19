import { Component, OnInit } from '@angular/core';
import { finalize } from 'rxjs/operators';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AdminService } from 'src/app/services/admin.service';
import { AlertService } from 'src/app/services/alert.service';
import { SpinnerService } from 'src/app/services/spinner.service';

@Component({
  selector: 'gestione-admin',
  templateUrl: './gestione-admin.component.html',
  styleUrls: ['./gestione-admin.component.scss']
})
export class GestioneAdminComponent extends GlobalComponent implements OnInit {


  utenti: Utente[];
  calciatori:any;

  constructor(
    private spinner: SpinnerService,
    private alert: AlertService,
    private adminService: AdminService) {
    super();
  }

  ngOnInit() {
    this.listaCalciatori();
  }

  ricalcola(event){
    this.getAllUtenti();
  }

  getAllUtenti() {

    this.adminService.getUtenti()
      .pipe(finalize(() => {
        this.spinner.clear(),
          this.loading_page = false;
      }))
      .subscribe({
        next: (result: Utente[]) => {
         
          this.utenti = result;
         
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }
 
  listaCalciatori() {

    this.loading_btn = true;
    this.spinner.view();

    this.adminService.getListaCalciatori()
      .subscribe({

        next: (result: any) => {
          this.calciatori=result;
          this.getAllUtenti();
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })

  }
}