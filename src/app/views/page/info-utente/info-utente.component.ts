import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { ActivatedRoute, ParamMap } from '@angular/router';
import { finalize, timeout } from 'rxjs/operators';
import { Squadra } from 'src/app/classes/models/squadra';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { MyModalValidate } from 'src/app/components/my-modal-validate/my-modal-validate.component';
import { AlertService } from 'src/app/services/alert.service';
import { AuthService } from 'src/app/services/auth.service';
import { PlayerService } from 'src/app/services/player.service';
import { SpinnerService } from 'src/app/services/spinner.service';



@Component({
  selector: 'info-utente',
  templateUrl: './info-utente.component.html',
  styleUrls: ['./info-utente.component.scss']
})
export class InfoUtenteComponent extends GlobalComponent implements OnInit {

  constructor(
    private authService: AuthService,
    private playerService: PlayerService,
    private spinner: SpinnerService,
    public dialog: MatDialog,
    private route: ActivatedRoute,
    private alert: AlertService) {
    super();
  }

  avatars: any = [];
  loggato: Utente;
  avatarSel: any;

  squadra: any;

  id_selected: number = 0;
  squadre: Array<Squadra> = [];


  ngOnInit() {

    this.loggato = this.playerService.getLoggato();
    this.id_selected = this.loggato.selezionata.id_squadra

    this.getAvatars()

  }


  getAvatars() {

    this.startPage(this.spinner);

    this.playerService.getAvatars()
      .pipe(finalize(() => {
        this.loadPage(this.spinner);
      }))
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
    this.loggato.selezionata.avatar = item.nome
    this.loggato.selezionata.id_avatar = item.id_avatar
  }


  preselect() {
    this.avatarSel = this.avatars.find(x => x.id_avatar == this.loggato.selezionata.id_avatar)
  }

  onChangeTeam(id_element: number) {

    let selezionata: Squadra = this.loggato.squadre.find(x => x.id_squadra == id_element)
    this.changeTeam(selezionata)

  }

  changeTeam(element: Squadra) {

    this.loading_btn = true;
    let utente: Utente = this.playerService.getLoggato();
    utente.selezionata = element
    this.playerService.updateUtente(utente)
      .pipe(finalize(() => this.loading_btn = false))
      .subscribe({
        next: (result: any) => {

          this.authService.setTokenDecoded(result);
          this.alert.success(this.language.alert.success);
          this.refreshPage();
        },
        error: (error: any) => {
          this.alert.error(error);
        },

      })
  }



  updateAvatar() {

    this.loading_btn = true;
    let utente: Utente = this.playerService.getLoggato();
    utente.selezionata.id_avatar = this.loggato.selezionata.id_avatar
    this.playerService.updateUtente(utente)
      .pipe(finalize(() => this.loading_btn = false))
      .subscribe({
        next: (result: any) => {

          let token = this.playerService.getLocalStorage();
          token.selezionata = this.loggato.selezionata
          this.authService.setToken(token);
          this.alert.success(this.language.alert.success);
          this.refreshPage();
        },
        error: (error: any) => {
          this.alert.error(error);
        },

      })
  }

  onUpdate(element: any) {
    const dialogRef = this.dialog.open(MyModalValidate);
    dialogRef.afterClosed().subscribe(result => {
      if (result)
        this.updateUtente(element);
    });
  }

  updateUtente(element: Utente) {

    this.loading_btn = true;

    this.playerService.updateUtente(element)
      .pipe(finalize(() => this.loading_btn = false))
      .subscribe({
        next: (result: any) => {

          let token = this.playerService.getLocalStorage();
          token.username = element.username;
          token.email = element.email;
          token.cellulare = element.cellulare;
          token.selezionata = element.selezionata;
          this.authService.setToken(token);
          this.alert.success(this.language.alert.success);
          this.refreshPage();
        },
        error: (error: any) => {
          this.alert.error(error);
        },

      })
  }



  /* SEZIONE UPGRADE */


}
