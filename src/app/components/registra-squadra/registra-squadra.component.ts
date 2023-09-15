import { Component, EventEmitter, OnInit, Output } from '@angular/core';
import { Router } from '@angular/router';
import { finalize } from 'rxjs/operators';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AdminService } from 'src/app/services/admin.service';
import { AlertService } from 'src/app/services/alert.service';
import { AuthService } from 'src/app/services/auth.service';
import { FantaGazzettaService } from 'src/app/services/fanta-gazzetta.service';
import { PlayerService } from 'src/app/services/player.service';

@Component({
  selector: 'registra-squadra',
  templateUrl: './registra-squadra.component.html',
  styleUrls: ['./registra-squadra.component.scss']
})
export class RegistraSquadraComponent extends GlobalComponent implements OnInit {

  constructor(
    private router: Router,
    private alert: AlertService,
    private playerService: PlayerService,
    private fanta: FantaGazzettaService,
    private service: AuthService) {
    super();
  }

  @Output() esci = new EventEmitter();

  fantalega: any;
  calciatori: any;

  vincolati = []
  stepform = 1
  squadra: any;
  loggato: Utente;

  ngOnInit() {
    this.loggato = this.service.getLoggato();
    this.getInfoUtente();
  }

  getInfoUtente() {

    this.loading_btn = true

    this.playerService.getInfoUtente()
      .pipe(finalize(() => {
        this.loading_btn = false
      }))
      .subscribe({
        next: (result: any) => {
          this.calciatori = result.lista_calciatori
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }


  getLega(lega: string) {

    this.loading_btn = true
    let re = /\ /gi;
    let nome_lega = lega.replace(re, "-")

    this.fanta.getLega(nome_lega)
      .pipe(finalize(() => this.loading_btn = false))
      .subscribe({
        next: (result: any) => {

          this.fantalega = result

          if (this.fantalega && this.fantalega.length > 0) {
            if (this.fantalega.length >= 8) {
              this.fantalega['lega'] = nome_lega
              this.stepform += 1
            } else {
              this.alert.error("La lega deve avere un minimo di 8 partecipanti");
            }
          } else {
            this.alert.error("Lega inesistente");
          }
        },
        error: (error: any) => {
          this.alert.error(error);
        },
      })
  }


  registraSquadra(payload: any) {

    this.service.registraSquadra(payload)
      .pipe(finalize(() => this.loading_btn = false))
      .subscribe({
        next: (result: any) => {
          this.alert.success(this.language.alert.success);
          this.esci.emit()
        },
        error: (error: any) => {
          this.alert.error(error);
        },
      })
  }



  onRegistraSquadra(element: any) {

    this.loading_btn = true;

    let payload = {

      "squadra": element.squadra,
      "id_utente": this.loggato.id,
      "lega": this.fantalega['lega'],
      "account": this.fantalega['account'],
      "players": this.squadra
    }

    this.registraSquadra(payload)
  }

  navigateToHome() {

    setTimeout(() => {
      this.router.navigate(['/sign-in'])
    }, 2000);
  }

  onLega(lega) {

    this.fantalega['account'] = lega.team
    this.loading_btn = true
    this.vincolati = []
    for (let ele of lega.lista) {
      let singolo = this.calciatori.find(i => i.nome_calciatore == ele);
      if (singolo) {
        singolo['selected'] = true;
        this.vincolati.push(singolo)
      }
    }

    setTimeout(() => {
      this.loading_btn = false
      this.stepform += 1
    }, 1000);

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

  conferma(selected) {

    this.squadra = selected.map(item => item.value);
    this.stepform += 1

  }


}

