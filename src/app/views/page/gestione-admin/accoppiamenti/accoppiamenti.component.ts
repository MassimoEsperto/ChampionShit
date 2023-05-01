import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { finalize } from 'rxjs/operators';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { MyModalAccopiamenti } from 'src/app/components/my-modal-accopiamenti/my-modal-accopiamenti.component';
import { MyModalValidate } from 'src/app/components/my-modal-validate/my-modal-validate.component';
import { AdminService } from 'src/app/services/admin.service';
import { AlertService } from 'src/app/services/alert.service';

@Component({
  selector: 'accoppiamenti',
  templateUrl: './accoppiamenti.component.html',
  styleUrls: ['./accoppiamenti.component.scss']
})
export class AccoppiamentiComponent extends GlobalComponent implements OnInit {

  accoppiamenti: any
  
  view_attuale:number=0;

  view_possibili = [
    {id: 1, name: "Date"},
    {id: 2, name: "Eliminatorie"},
    {id: 3, name: "Sorteggi gironi"}];

  constructor(
    public dialog: MatDialog,
    private adminService: AdminService,
    private alert: AlertService) {
    super();
  }

  ngOnInit() { this.getAccoppiamenti() }


  /* CHIAMATE AI SERVIZI */

  getAccoppiamenti() {

    this.accoppiamenti = '';
    this.loading_btn = true;

    this.adminService.getAccoppiamenti()
      .pipe(finalize(() => {
        this.loading_btn = false;
      }))
      .subscribe({
        next: (result: any) => {

          this.accoppiamenti = result
          console.log("getAccoppiamenti",result)
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }

}
