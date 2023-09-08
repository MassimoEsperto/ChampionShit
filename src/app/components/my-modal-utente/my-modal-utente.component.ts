import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';

@Component({
  selector: 'my-modal-utente',
  templateUrl: './my-modal-utente.component.html',
  styleUrls: ['./my-modal-utente.component.scss']
})
export class MyModalUtente implements OnInit {

  form: FormGroup;

  constructor(
    private fb: FormBuilder,
    private dialogRef: MatDialogRef<MyModalUtente>,
    @Inject(MAT_DIALOG_DATA) public data: any) {
  }

  ngOnInit() {

    this.form = this.fb.group({
      username: [this.data.valori.username, Validators.required],
      squadra: [this.data.valori.squadra, Validators.required],
      email: [this.data.valori.email, Validators.required],
      account: [this.data.valori.account, Validators.required],
      stato: [this.data.valori.id_stato, Validators.required],
      ruolo: [this.data.valori.id_ruolo, Validators.required]
    });
  }

  save() {
    if (this.form.valid) {

      let control = this.form.controls
      let response = {
        username: control.username.value,
        squadra: control.squadra.value,
        email: control.email.value,
        account: control.account.value,
        stato: control.stato.value,
        ruolo: control.ruolo.value,
        id_utente: this.data.valori.id_utente,
        id_squadra: this.data.valori.id_squadra
      }

      this.dialogRef.close(response);
    }

  }

  close() {
    this.dialogRef.close();
  }
}
