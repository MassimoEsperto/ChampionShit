import { ChangeDetectorRef, Component, OnInit } from '@angular/core';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { SpinnerService } from 'src/app/services/spinner.service';
import { finalize } from 'rxjs/operators';
import { AlertService } from 'src/app/services/alert.service';
import { PlayerService } from 'src/app/services/player.service';
import { FantaGazzettaService } from 'src/app/services/fanta-gazzetta.service';


@Component({
  selector: 'schieramento',
  templateUrl: './schieramento.component.html',
  styleUrls: ['./schieramento.component.scss']
})
export class SchieramentoComponent extends GlobalComponent implements OnInit {

  constructor(
    private spinner: SpinnerService,
    private alert: AlertService,
    private playerService: PlayerService,
    private fantaService: FantaGazzettaService,
    private ref: ChangeDetectorRef) {
    super();
  }

  ngOnInit() {
    this.spinner.view();
    this.getProbabiliFormazione()
  }

  ngAfterViewInit() { }

  ngAfterViewChecked() {
    this.ref.detectChanges();
  }

  //dichiara le variabili
  formazione: any;
  percentuale: any;
  squadra = [];
  schieramento: boolean = true;
  moduli = [];
  modulo: any;


  getProbabiliFormazione() {

    this.fantaService.getProbabiliFormazione()
      .pipe(finalize(() => {
        this.getFormazione();
      }))
      .subscribe({

        next: (result: any) => {

          this.percentuale = result

        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })
  }



  changeView() {
    this.schieramento = !this.schieramento;
    this.aggiornaModulo()
  }

  aggiornaModulo() {

    let indice = this.squadra.length > 0 ? "" : "NNNNN";
    for (let ele of this.squadra) {
      indice += ele.tipo
    }

    this.modulo = this.moduli[indice]
  }

  sortedByRuoli(squadra) {

    let sortedsquadra = squadra.sort((n1, n2) => {
      if (n1.tipo < n2.tipo) {
        return 1;
      }

      if (n1.tipo > n2.tipo) {
        return -1;
      }

      return 0;
    });

    return sortedsquadra;
  }


  getFormazione() {

    this.playerService.getRosaDisponibile()
      .pipe(finalize(() => {
        this.loadPage(this.spinner);
      }))
      .subscribe({

        next: (result: any) => {

          this.formazione = result
          this.squadra = result.schierata
          this.moduli = result.moduli
          this.aggiornaModulo()

        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })

  }

  //inserisce materialmente nel db la formazione
  setFormazione() {

    this.loading_btn = true;
    let payload = {
      lista: [],
      id_partita: this.formazione.id_partita,
      modulo: this.modulo.id
    }

    for (let membro of this.squadra) {
      payload.lista.push(membro.id)
    }

    this.playerService.insertFormazione(payload)
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




  schiera(list) {

    let lista = list.map(item => item.value);

    this.squadra = this.sortedByRuoli(lista);
    this.changeView();
  }


  disabledTeam(tot, item) {

    let selected = tot.selectedOptions.selected;

    let size = selected.length;

    if (size > 4)
      return false;

    if (size == 0)
      return true;

    try {

      let centro = selected.filter(e => e.value.tipo === 'C').length;
      let difensori = selected.filter(e => e.value.tipo === 'D').length;
      let attaccanti = selected.filter(e => e.value.tipo === 'A').length;
      let portieri = selected.filter(e => e.value.tipo === 'P').length;

      switch (item.tipo) {
        case 'P': {
          return portieri < 1 && (size < 4 || (centro > 0 && attaccanti > 0 && difensori > 0));
        }
        case 'D': {
          return difensori < 2 && (size < 4 || (centro > 0 && attaccanti > 0));
        }
        case 'C': {
          return centro < 2 && (size < 4 || (difensori > 0 && attaccanti > 0));
        }
        case 'A': {
          return attaccanti < 2 && (size < 4 || (centro > 0 && difensori > 0));
        }
        default: {
          return true;
        }
      }

    } catch (error) {
      return true;
    }

  }

}
