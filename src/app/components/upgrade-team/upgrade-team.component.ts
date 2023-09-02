import { Component, OnInit } from '@angular/core';
import { finalize } from 'rxjs/operators';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AdminService } from 'src/app/services/admin.service';
import { AlertService } from 'src/app/services/alert.service';
import { FantaGazzettaService } from 'src/app/services/fanta-gazzetta.service';
import { PlayerService } from 'src/app/services/player.service';
import { SpinnerService } from 'src/app/services/spinner.service';

@Component({
  selector: 'upgrade-team',
  templateUrl: './upgrade-team.component.html',
  styleUrls: ['./upgrade-team.component.scss']
})
export class UpgradeTeamComponent extends GlobalComponent implements OnInit {

  loggato: Utente;
  svincolati: any;
  vincolati = []
  squadra: any;

  constructor(
    private alert: AlertService,
    private fanta: FantaGazzettaService,
    private adminService: AdminService,
    private spinner: SpinnerService,
    private playerService: PlayerService) {
    super();
  }

  ngOnInit() {
    this.loggato = this.playerService.getLoggato();
    this.onStart()
  }

  onStart() {
    this.startPage(this.spinner);
    this.getListaCalciatori()
  }

  getListaCalciatori() {

    this.adminService.getListaCalciatori()
      .subscribe({

        next: (result: any) => {
          this.svincolati = result;
          this.getLega(this.loggato.selezionata.lega)
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
            let fantalega = result.find(i => i.team == this.loggato.selezionata.account).lista || [];
            this.onLega(fantalega)
          } else {
            this.alert.error("Lega inesistente");
            this.loadPage(this.spinner);
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
      lega: this.loggato.selezionata.lega
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
