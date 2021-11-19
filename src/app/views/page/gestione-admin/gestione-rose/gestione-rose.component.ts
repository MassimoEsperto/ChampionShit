import { Component, Input, OnInit } from '@angular/core';
import { finalize } from 'rxjs/operators';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AlertService } from 'src/app/services/alert.service';
import * as XLSX from 'xlsx';
import { Rosa } from 'src/app/classes/models/rosa';
import { MatDialog } from '@angular/material/dialog';
import { MyModalValidate } from 'src/app/components/my-modal-validate/my-modal-validate.component';
import { AdminService } from 'src/app/services/admin.service';

@Component({
  selector: 'gestione-rose',
  templateUrl: './gestione-rose.component.html',
  styleUrls: ['./gestione-rose.component.scss']
})
export class GestioneRoseComponent extends GlobalComponent implements OnInit {

  constructor(
    private adminService: AdminService,
    public dialog: MatDialog,
    private alert: AlertService) {
    super();
  }

  ngOnInit() { }

  @Input() attuali: any;
  rose: Rosa[] = [];

  importListaCalciatori(event: any) {
    let file: File
    let arrayBuffer: any;

    file = event.target.files[0];
    let fileReader = new FileReader();
    fileReader.readAsArrayBuffer(file);
    fileReader.onload = (e) => {
      arrayBuffer = fileReader.result;
      var data = new Uint8Array(arrayBuffer);
      var arr = new Array();
      for (var i = 0; i != data.length; ++i) {
        arr[i] = String.fromCharCode(data[i]);
      }
      var bstr = arr.join("");

      var workbook = XLSX.read(bstr, { type: "binary" });
      var first_sheet_name = workbook.SheetNames[0];
      var worksheet = workbook.Sheets[first_sheet_name];

      var arraylist: any = XLSX.utils.sheet_to_json(worksheet, { raw: true, defval: null });

      for (let i = 2; i < arraylist.length; i++) {
        let element = arraylist[i]

        let ruolo: string = element['__EMPTY'].toLocaleUpperCase().replace("'", "").replace(".", "").trim()
        let nome: string = element['__EMPTY_1'].toLocaleUpperCase().replace("'", "").replace(".", "").trim()

        if (!nome.includes("*")) {
          let ele: Rosa = new Rosa(ruolo, nome);
          this.rose.push(ele);
        }

      }

    }
  }



  aggiungiCalciatori() {

    this.loading_btn = true;

    this.adminService.insertSvincolati(this.rose)
      .pipe(finalize(() => {
        this.loading_btn = false;
      }
      ))
      .subscribe({
        next: (result: any) => {
          this.alert.success(this.language.alert.success);
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })
  }

  onAggiungiCalciatori() {

    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.aggiungiCalciatori();
    });
  }
}
