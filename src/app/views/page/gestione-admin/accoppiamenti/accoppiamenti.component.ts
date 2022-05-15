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

  constructor(
    public dialog: MatDialog,
    private adminService: AdminService,
    private alert: AlertService) {
    super();
  }

  ngOnInit() { this.getAccoppiamenti() }


  onUpdate(ele: any) {
    console.log("upd", ele)
    this.onChangeItem(ele)
  }


  onChangeItem(ele: any) {

    const dialogRef = this.dialog.open(MyModalAccopiamenti, {
      panelClass: 'dialog-language',
      data: {
        titolo: 'ACCOPPIAMENTI',
        valori: ele,
        combo: this.accoppiamenti.utenti
      }
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.updAccoppiamento(result)
    });
  }

  onNewItem() {

    const dialogRef = this.dialog.open(MyModalAccopiamenti, {
      panelClass: 'dialog-language',
      data: {
        titolo: 'ACCOPPIAMENTI',
        valori: null,
        combo: this.accoppiamenti.utenti
      }
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.setAccoppiamento(result)
    });
  }


  onDelete(id: any) {
    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.delAccoppiamento(id);
    });
  }

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
          console.log(result)
          this.accoppiamenti = result
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }


  setAccoppiamento(item: any) {

    this.loading_btn = true;

    this.adminService.setAccoppiamento(item)
      .pipe(finalize(() => {
        this.loading_btn = false;
      }))
      .subscribe({
        next: (result: any) => {
          this.getAccoppiamenti()
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }

  updAccoppiamento(item: any) {

    this.loading_btn = true;

    this.adminService.updAccoppiamento(item)
      .pipe(finalize(() => {
        this.loading_btn = false;
      }))
      .subscribe({
        next: (result: any) => {
          this.getAccoppiamenti()
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }




  delAccoppiamento(id: any) {

    this.adminService.deleteAccoppiamento(id)
      .subscribe({
        next: (result: any) => {
          this.alert.success(this.language.alert.success);

          this.getAccoppiamenti()
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }
}
