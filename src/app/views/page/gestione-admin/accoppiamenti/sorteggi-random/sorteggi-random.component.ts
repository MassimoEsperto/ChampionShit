import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { finalize } from 'rxjs/operators';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { MyModalValidate } from 'src/app/components/my-modal-validate/my-modal-validate.component';
import { AdminService } from 'src/app/services/admin.service';
import { AlertService } from 'src/app/services/alert.service';

@Component({
  selector: 'sorteggi-random',
  templateUrl: './sorteggi-random.component.html',
  styleUrls: ['./sorteggi-random.component.scss']
})
export class SorteggiRandomComponent extends GlobalComponent implements OnInit {

  constructor(
    private adminService: AdminService,
    public dialog: MatDialog,
    private alert: AlertService) {
    super();
  }

  start: boolean = false
  randomizzabili: any
  girone: string
  selezionati: any = []


  ngOnInit(): void { }

  sorteggio(list) {

    let lista = list.map(item => item.value);
  
    let ran = this.randomInteger(0, lista.length - 1)
    this.selezionati.push(lista[ran])
    this.removeElement(lista[ran].id)
  
  }


  randomInteger(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }

  removeElement(key: number) {
    this.randomizzabili.utenti.forEach((value, index) => {
      if (value.id == key) this.randomizzabili.utenti.splice(index, 1);
    });
  }


  salvaGirone() {

    let lista = this.selezionati.map(item => item.id);
    
    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        let payload = { squadre: lista, girone: this.girone }
        this.setGeneraCompetizioneGironi(payload)
      }

    });
  }

  getStartNewGrirone() {

    this.adminService.getGeneraCompetizioneGironi()

      .subscribe({
        next: (result: any) => {

          this.randomizzabili = result;
          this.start = true
          this.girone=''

        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }


  setGeneraCompetizioneGironi(payload: any) {

    this.loading_btn = true;

    this.adminService.setGeneraCompetizioneGironi(payload)
      .pipe(finalize(() => {
        this.loading_btn = false;
      }
      )).subscribe({

        next: (result: any) => {
          this.alert.success(this.language.alert.success);
          this.start = false
          this.selezionati=[]
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })

  }

}
