import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { GlobalComponent } from 'src/app/classes/utils/global-component';

@Component({
  selector: 'my-modal-date',
  templateUrl: './my-modal-date.component.html',
  styleUrls: ['./my-modal-date.component.scss']
})
export class MyModalDate extends GlobalComponent implements OnInit {

  form: FormGroup;

  constructor(
    private fb: FormBuilder,
    private dialogRef: MatDialogRef<MyModalDate>,
    @Inject(MAT_DIALOG_DATA) public data: any) {
    super();
  }

 

  ngOnInit() {
    this.form = this.fb.group({
      giornata: [this.data.valori ? this.data.valori.giornata : null, Validators.required],
      serieA: [this.data.valori ? this.data.valori.serie_a : null, Validators.required],
      fase: [this.data.valori ? this.data.valori.fase : null, Validators.required],
      inizio_giornata: [this.data.valori ? this.data.valori.inizio_giornata : null, Validators.required],
      prima_partita: [this.data.valori ? this.data.valori.prima_partita : null, Validators.required],
      ultima_partita: [this.data.valori ? this.data.valori.ultima_partita : null, Validators.required],
      fine_giornata: [this.data.valori ? this.data.valori.fine_giornata : null, Validators.required],
      upgrade: [this.data.valori ? this.data.valori.is_upgrade : null, Validators.required]
    });
  }





  save() {
    if (this.form.valid) {

      let control = this.form.controls
      let response = {
        giornata: control.giornata.value,
        serie_a: control.serieA.value,
        inizio_giornata: control.inizio_giornata.value.replace("T", " "),
        prima_partita: control.prima_partita.value.replace("T", " "),
        ultima_partita: control.ultima_partita.value.replace("T", " "),
        fine_giornata: control.fine_giornata.value.replace("T", " "),
        is_upgrade: control.upgrade.value,
        fase: control.fase.value
      }

      this.dialogRef.close(response);
    }

  }

  close() {
    this.dialogRef.close();
  }
}