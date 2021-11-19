import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { finalize } from 'rxjs/operators';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { MyModalValidate } from 'src/app/components/my-modal-validate/my-modal-validate.component';
import { AlertService } from 'src/app/services/alert.service';
import { AuthService } from 'src/app/services/auth.service';
import { PlayerService } from 'src/app/services/player.service';



@Component({
  selector: 'info-utente',
  templateUrl: './info-utente.component.html',
  styleUrls: ['./info-utente.component.scss']
})
export class InfoUtenteComponent extends GlobalComponent implements OnInit {

  constructor(
    private authService: AuthService,
    private playerService: PlayerService,
    public dialog: MatDialog,
    private alert: AlertService) {
    super();
  }

  avatars: any = [];
  loggato: Utente;
  avatarSel: any;

  ngOnInit() {

    this.loggato = this.playerService.getLoggato();
    this.combo();
  }



  onUpdate(element: any) {
    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.updateUtente(element);
    });
  }

  updateUtente(element: any) {

    this.loading_btn = true;
    let utente: Utente = new Utente(element.username, '', element.email, element.squadra.toUpperCase())
    utente.id_avatar = this.loggato.id_avatar
    this.playerService.updateUtente(utente)
      .pipe(finalize(() => this.loading_btn = false))
      .subscribe({
        next: (result: any) => {
          let token = this.playerService.getLocalStorage();
          token.username = element.username;
          token.email = element.email;
          token.squadra = element.squadra.toUpperCase();
          this.authService.setToken(token);
          this.alert.success(this.language.alert.success);
          this.refreshPage();
        },
        error: (error: any) => {
          this.alert.error(error);
        },

      })
  }

  combo() {

    this.playerService.getAvatars()

      .subscribe({
        next: (result: any) => {
          this.avatars = result;
          this.preselect();
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }

  selectedCombo(item) {
    this.avatarSel = item;
    this.loggato.avatar = item.nome
    this.loggato.id_avatar = item.id_avatar
  }


  preselect() {
    this.avatarSel = this.avatars.find(x => x.id_avatar == this.loggato.id_avatar)
  }

  updateAvatar() {

    this.loading_btn = true;
    let utente: Utente = this.playerService.getLoggato();
    utente.id_avatar = this.loggato.id_avatar
    this.playerService.updateUtente(utente)
      .pipe(finalize(() => this.loading_btn = false))
      .subscribe({
        next: (result: any) => {
          let token = this.playerService.getLocalStorage();
          token.avatar = this.loggato.avatar
          token.id_avatar = this.loggato.id_avatar
          this.authService.setToken(token);
          this.alert.success(this.language.alert.success);
          this.refreshPage();
        },
        error: (error: any) => {
          this.alert.error(error);
        },

      })
  }
}
