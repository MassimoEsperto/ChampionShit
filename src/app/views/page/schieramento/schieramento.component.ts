import { ChangeDetectorRef, Component, OnInit } from '@angular/core';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { SpinnerService } from 'src/app/services/spinner.service';
import { finalize } from 'rxjs/operators';
import { AlertService } from 'src/app/services/alert.service';
import { PlayerService } from 'src/app/services/player.service';


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
    private ref: ChangeDetectorRef) {
    super();
  }

  ngOnInit() {
    this.getFormazione()
  }

  ngAfterViewInit() { }

  ngAfterViewChecked() {
    this.ref.detectChanges();
  }

  //dichiara le variabili
  formazione: any;
  squadra = [];
  schieramento: boolean = true;


  getProbabiliFormazione() {

    this.playerService.getProbabiliFormazione()

      .subscribe({

        next: (result: string) => {

          let splittato = result.split("player-name")
          let nome
          let percentuale

          for (let ris of splittato) {

            let str = ris.split("player-percentage-value")

            if (str && str.length > 1) {

              nome = str[0].substring(
                str[0].indexOf("<"),
                str[0].indexOf(">") + 1

              );
              percentuale = str[1].substring(
                str[1].indexOf("<"),
                str[1].indexOf(">") + 1

              );
              let giocatore = nome.replace("&#39;", "").toUpperCase();

              let element = this.formazione.rosa.find(x => x.nome == giocatore)
              if (element) {
                element.percentuale = percentuale
              }
            }
          }
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })
  }



  changeView() {
    this.schieramento = !this.schieramento;
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

    this.spinner.view();
    this.playerService.getRosaDisponibile()
      .pipe(finalize(() => {
        this.loadPage(this.spinner);
      }))
      .subscribe({

        next: (result: any) => {
          this.formazione = result
          this.squadra = result.schierata
          this.getProbabiliFormazione()
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
      id_partita: this.formazione.id_partita
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
          break;
        }
        case 'D': {
          return difensori < 2 && (size < 4 || (centro > 0 && attaccanti > 0));
          break;
        }
        case 'C': {
          return centro < 2 && (size < 4 || (difensori > 0 && attaccanti > 0));
          break;
        }
        case 'A': {
          return attaccanti < 2 && (size < 4 || (centro > 0 && difensori > 0));
          break;
        }
        default: {
          return true;
          break;
        }
      }

    } catch (error) {
      return true;
    }

  }

}
