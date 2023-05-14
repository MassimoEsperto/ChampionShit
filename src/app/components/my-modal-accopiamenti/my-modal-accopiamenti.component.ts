import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';


@Component({
  selector: 'my-modal-accopiamenti',
  templateUrl: './my-modal-accopiamenti.component.html',
  styleUrls: ['./my-modal-accopiamenti.component.scss']
})
export class MyModalAccopiamenti implements OnInit {

  form: FormGroup;

  constructor(
    private fb: FormBuilder,
    private dialogRef: MatDialogRef<MyModalAccopiamenti>,
    @Inject(MAT_DIALOG_DATA) public data: any) {
  }

  ngOnInit() {
    this.form = this.fb.group({
      casa: [this.data.valori ? this.data.valori.id_casa : null, Validators.required],
      trasferta: [this.data.valori ? this.data.valori.id_trasferta : null, Validators.required],
      giornata: [this.data.valori ? this.data.valori.giornata : null, Validators.required],
      id_calendario: [this.data.valori ? this.data.valori.id_calendario : null]
    });

    console.log("update accoppiamenti",this.data.valori)
  }

  save() {
    if (this.form.valid)
      this.dialogRef.close(this.form.value);
  }

  close() {
    this.dialogRef.close();
  }
}