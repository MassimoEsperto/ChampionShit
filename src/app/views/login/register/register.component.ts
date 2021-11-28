import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { finalize } from 'rxjs/operators';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AdminService } from 'src/app/services/admin.service';
import { AlertService } from 'src/app/services/alert.service';
import { AuthService } from 'src/app/services/auth.service';
import { FantaGazzettaService } from 'src/app/services/fanta-gazzetta.service';

@Component({
  selector: 'register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent extends GlobalComponent implements OnInit {

  constructor(
    private router: Router,
    private alert: AlertService,
    private fanta: FantaGazzettaService,
    private adminService: AdminService,
    private service: AuthService) {
    super();
  }

  fantalega: any;
  svincolati: any;
  utenti: any;
  vincolati = []
  stepform = 1
  squadra: any;

  ngOnInit() {
    this.listaUtenti()
    this.listaCalciatori()
  }


  listaCalciatori() {

    this.adminService.getListaCalciatori()
      .subscribe({

        next: (result: any) => {
          this.svincolati = result;
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })
  }

  listaUtenti() {

    this.adminService.getUtenti()
      .subscribe({

        next: (result: any) => {
          this.utenti = result;
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })
  }

  getLega(lega: string) {
    this.loading_btn = true
    this.fanta.getLega(lega)
      .pipe(finalize(() => this.loading_btn = false))
      .subscribe({
        next: (result: any) => {

          this.fantalega = result
          this.fantalega['lega'] = lega
          if (this.fantalega && this.fantalega.length > 0) {
            this.stepform += 1
          } else {
            this.alert.error("Lega inesistente");
          }
        },
        error: (error: any) => {
          this.alert.error(error);
        },
      })
  }


  registrati(payload: any) {

    this.service.registerNewUtente(payload)
      .pipe(finalize(() => this.loading_btn = false))
      .subscribe({
        next: (result: any) => {
          this.alert.success(this.language.alert.success);
          this.segnala(payload);
          this.navigateToHome()
        },
        error: (error: any) => {
          this.alert.error(error);
        },
      })
  }

  segnala(ele: any) {

    this.service.segnalaUtente(ele)
      .subscribe({
        next: (result: any) => {
        },
        error: (error: any) => {
          console.log("Errore:", error);
        },
      })
  }


  onRegistrati(element: any) {

    this.loading_btn = true;
    let esiste = this.utenti.some(i => i.username == element.username);

    if (esiste) {
      this.alert.error("Username già è in uso");
      return
    }
    let payload = {
      "email": element.email,
      "password": element.password,
      "squadra": element.squadra,
      "username": element.username,
      "lega": this.fantalega['lega'],
      "players": this.squadra
    }

    this.registrati(payload)
  }

  navigateToHome() {

    setTimeout(() => {
      this.router.navigate(['/sign-in'])
    }, 2000);
  }

  onLega(lega) {

    this.loading_btn = true
    this.vincolati = []
    for (let ele of lega.lista) {
      let singolo = this.svincolati.find(i => i.nome_calciatore == ele);
      if (singolo) {
        singolo['selected'] = true;
        this.vincolati.push(singolo)
      }
    }
    this.stepform += 1
    this.loading_btn = false

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
