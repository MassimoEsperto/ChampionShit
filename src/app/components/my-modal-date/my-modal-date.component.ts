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
      annoStart: [null, Validators.required],
      meseStart: [null, Validators.required],
      giornoStart: [null, Validators.required],
      oraStart: [null, Validators.required],
      annoEnd: [null, Validators.required],
      meseEnd: [null, Validators.required],
      giornoEnd: [null, Validators.required],
      oraEnd: [null, Validators.required],
      id_data: [null]
    });
  }

  save() {
    if (this.form.valid) {

      let control = this.form.controls
      let response = {
        giornata: control.giornata.value,
        serie_a: control.serieA.value,
        data_inizio: control.annoStart.value +'-'+ control.meseStart.value +'-'+control.giornoStart.value+'-'+ control.oraStart.value,
        data_fine: control.annoEnd.value +'-'+ control.meseEnd.value +'-'+control.giornoEnd.value+'-'+ control.oraEnd.value
      }

      this.dialogRef.close(response);
    }

  }

  close() {
    this.dialogRef.close();
  }
}