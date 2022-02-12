import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { ActivatedRoute, ParamMap } from '@angular/router';
import { finalize, timeout } from 'rxjs/operators';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { MyModalValidate } from 'src/app/components/my-modal-validate/my-modal-validate.component';
import { AdminService } from 'src/app/services/admin.service';
import { AlertService } from 'src/app/services/alert.service';
import { AuthService } from 'src/app/services/auth.service';
import { FantaGazzettaService } from 'src/app/services/fanta-gazzetta.service';
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
    private fanta: FantaGazzettaService,
    private adminService: AdminService,
    private spinner: SpinnerService,
    public dialog: MatDialog,
    private route: ActivatedRoute,
    private alert: AlertService) {
    super();
  }

  avatars: any = [];
  loggato: Utente;
  avatarSel: any;
  upgrade: boolean = false;

  svincolati: any;
  vincolati = []
  squadra: any;


  ngOnInit() {

    this.loggato = this.playerService.getLoggato();
    this.onStart()

  }

  onStart() {

    this.loading_page = true;
    this.spinner.view();

    this.route.queryParams.subscribe(params => {
      this.upgrade = params['upgrade'];
      if (this.upgrade) this.listaCalciatori()
      else this.getAvatars()
    });
  }

  getAvatars() {

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

  /* SEZIONE UPGRADE */

  listaCalciatori() {

    this.adminService.getListaCalciatori()
      .subscribe({

        next: (result: any) => {
          this.svincolati = result;
          this.getLega(this.loggato.lega)
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })
  }



  getLega(lega: string) {

    this.fanta.getLega(lega)
      .subscribe({
        next: (result: any) => {

          if (result && result.length > 0) {
            let fantalega = result.find(i => i.team == this.loggato.account).lista || [];
            this.onLega(fantalega)
          } else {
            this.alert.error("Lega inesistente");
          }
        },
        error: (error: any) => {
          this.alert.error(error);
        },
      })
  }

  onLega(lega) {

    this.vincolati = []
    for (let ele of lega) {
      let singolo = this.svincolati.find(i => i.nome_calciatore == ele);
      if (singolo) {
        singolo['selected'] = true;
        this.vincolati.push(singolo)
      }
    }
    this.loadPage(this.spinner);
  }


  disabledTeam(tot, item) {

    let selected = tot.selectedOptions.selected;

    let size = selected.length;

    if (size == 25) return true;

    try {

      let centro = selected.filter(e => e.value.ruolo === 'C').length;
      let difensori = selected.filter(e => e.value.ruolo === 'D').length;
      let attaccanti = selected.filter(e => e.value.ruolo === 'A').length;
      let portieri = selected.filter(e => e.value.ruolo === 'P').length;

      switch (item.ruolo) {
        case 'P': {
          return portieri == 3;
        }
        case 'D': {
          return difensori == 8;
        }
        case 'C': {
          return centro == 8;
        }
        case 'A': {
          return attaccanti == 6;
        }
        default: {
          return false;
        }
      }

    } catch (error) {
      return false;
    }

  }

  confermaUpgrade(selected) {

    this.squadra = selected.map(item => item.value);
    let payload = {
      lista: this.squadra,
      lega: this.loggato.lega
    }
    this.upgradeRosa(payload)

  }

  upgradeRosa(payload: any) {

    this.loading_btn = true;

    this.playerService.upgradeRosa(payload)
      .pipe(finalize(() =>
        this.loading_btn = false
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

}
