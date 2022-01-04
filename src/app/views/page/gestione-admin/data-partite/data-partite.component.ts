import { Component, Input, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { MyModalValidate } from 'src/app/components/my-modal-validate/my-modal-validate.component';
import { AdminService } from 'src/app/services/admin.service';
import { AlertService } from 'src/app/services/alert.service';

@Component({
  selector: 'data-partite',
  templateUrl: './data-partite.component.html',
  styleUrls: ['./data-partite.component.scss']
})
export class DataPartiteComponent extends GlobalComponent implements OnInit {

  @Input() dataPartite: any;
  editField: string;

  constructor(
    private alert: AlertService,
    public dialog: MatDialog,
    private adminService: AdminService) {
    super();
  }

  ngOnInit(){}

  changeValue(id: number, property: string, event: any) {
    this.editField = event.target.textContent;
  }

  updateList(id: number, property: string, event: any) {
    const editField = event.target.textContent;
    this.dataPartite[id][property] = editField;
  }

  onChangeData(element){

    let payload = {
      giornata: element.giornata,
      data_inizio: element.data_inizio,
      data_fine: element.data_fine
    }

    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.changeData(payload);
    });
  }

  changeData(payload) {

    this.adminService.cambiaDate(payload)
      .subscribe({
        next: (result: any) => {
          this.alert.success(this.language.alert.success);
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }
}
