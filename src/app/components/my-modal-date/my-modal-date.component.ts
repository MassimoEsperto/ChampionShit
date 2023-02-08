import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';

@Component({
  selector: 'my-modal-date',
  templateUrl: './my-modal-date.component.html',
  styleUrls: ['./my-modal-date.component.scss']
})
export class MyModalDate implements OnInit {

  form: FormGroup;

  constructor(
    private fb: FormBuilder,
    private dialogRef: MatDialogRef<MyModalDate>,
    @Inject(MAT_DIALOG_DATA) public data: any) {
  }

  ngOnInit() {
    this.form = this.fb.group({
      giornata: [null, Validators.required],
      serieA: [null, Validators.required],
      fase: [null, Validators.required],
      inizio_giornata: [null, Validators.required],
      prima_partita: [null, Validators.required],
      ultima_partita: [null, Validators.required],
      fine_giornata: [null, Validators.required],
      upgrade: [null, Validators.required]
    });
  }





  save() {
    if (this.form.valid) {

      let control = this.form.controls
      let response = {
        giornata: control.giornata.value,
        serie_a: control.serieA.value,
        inizio_giornata: control.inizio_giornata.value.replace("T", " ") + ":00",
        prima_partita: control.prima_partita.value.replace("T", " ") + ":00",
        ultima_partita: control.ultima_partita.value.replace("T", " ") + ":00",
        fine_giornata: control.fine_giornata.value.replace("T", " ") + ":00",
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