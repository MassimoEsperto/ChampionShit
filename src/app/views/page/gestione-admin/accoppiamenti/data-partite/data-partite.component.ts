import { Component, Input, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { finalize } from 'rxjs/operators';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { MyModalDate } from 'src/app/components/my-modal-date/my-modal-date.component';
import { MyModalValidate } from 'src/app/components/my-modal-validate/my-modal-validate.component';
import { AdminService } from 'src/app/services/admin.service';
import { AlertService } from 'src/app/services/alert.service';

@Component({
  selector: 'data-partite',
  templateUrl: './data-partite.component.html',
  styleUrls: ['./data-partite.component.scss']
})
export class DataPartiteComponent extends GlobalComponent implements OnInit {

  @Input() accoppiamenti: any;
  editField: string;

  constructor(
    private alert: AlertService,
    public dialog: MatDialog,
    private adminService: AdminService) {
    super();
  }

  ngOnInit() { }


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
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }



  onChangeData(element) {

console.log("onChangeData",element)

    const dialogRef = this.dialog.open(MyModalDate, {
      panelClass: 'dialog-language',
      data: {
        titolo: 'GIORNATE',
        valori: element,
        combo: this.accoppiamenti.date_possibili,
        fasi: this.accoppiamenti.fasi
      }
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        this.changeData(result);
      }
    });
  }



  changeData(payload) {

    this.adminService.cambiaDate(payload)
      .subscribe({
        next: (result: any) => {
          this.getAccoppiamenti()
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }

  onNewDate() {

    const dialogRef = this.dialog.open(MyModalDate, {
      panelClass: 'dialog-language',
      data: {
        titolo: 'GIORNATE',
        valori: null,
        combo: this.accoppiamenti.date_possibili,
        fasi: this.accoppiamenti.fasi
      }
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        this.newData(result)
      }
    });
  }


  newData(payload) {

    this.adminService.newData(payload)
      .subscribe({
        next: (result: any) => {
          this.getAccoppiamenti()
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }

  onDeleteData(element) {

    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        let payload = {
          tabella: "giornate",
          id_nome: "id_giornata",
          id_valore: element.giornata
        }

        this.deleteData(payload);
      }
    });
  }

  deleteData(payload) {

    this.adminService.deleteObjectById(payload)
      .subscribe({
        next: (result: any) => {
          this.getAccoppiamenti()
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }
}
