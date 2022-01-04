import { Component, OnInit } from '@angular/core';
import { finalize } from 'rxjs/operators';
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

  administrator: any;

  constructor(
    private spinner: SpinnerService,
    private alert: AlertService,
    private adminService: AdminService) {
    super();
  }

  ngOnInit() {
    this.spinner.view();
    this.getAdministrator()
  }


  getAdministrator() {

    this.adminService.getAdministrator()
      .pipe(finalize(() => {
        this.spinner.clear()
      }))
      .subscribe({
        next: (result: any) => {
        
          this.administrator = result

        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }




}