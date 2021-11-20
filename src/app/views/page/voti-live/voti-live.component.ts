import { Component, OnInit } from '@angular/core';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AlertService } from 'src/app/services/alert.service';
import { PlayerService } from 'src/app/services/player.service';
import { SpinnerService } from 'src/app/services/spinner.service';

@Component({
  selector: 'voti-live',
  templateUrl: './voti-live.component.html',
  styleUrls: ['./voti-live.component.scss']
})
export class VotiLiveComponent extends GlobalComponent implements OnInit {

  constructor(
    private spinner: SpinnerService,
    private alert: AlertService,
    private playerService: PlayerService
  ) {
    super();
  }

  formazioni: any
  votilive = []

  ngOnInit() {
    this.getTeamLive();
  }

  getTeamLive() {

    this.spinner.view();
    this.playerService.getFormazioni()
      .subscribe({

        next: (result: any) => {
          this.formazioni = result.formazioni
          this.getVotiLive(result.team, result.seriea, result.bonusmalus, 0)

        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })
  }


  getVotiLive(team: any, seriea: string, bonus: any, indice: number) {

    if (indice > 19) {
      this.loadPage(this.spinner);
      this.assegnaVoti()
      return;
    }
    this.playerService.getVotiLive(team[indice], seriea)
      .subscribe({

        next: (result: any) => {

          for (let ele of result) {
            ele.voto = this.calcoloVoto(ele, bonus)
            ele.nome = ele.nome.replace(".", "").trim()
            this.votilive.push(ele)
          }
          this.getVotiLive(team, seriea, bonus, indice + 1)
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })

  }

  assegnaVoti() {

    for (let formazione of this.formazioni) {
      for (let match of formazione.match) {
        for (let calcio of match.schieramento) {
          let fantavoto = this.votilive.find(i => i.nome == calcio.calciatore);
          if (fantavoto) {
            calcio.voto = fantavoto.voto
          }
          calcio.calciatore = calcio.calciatore.replace(" ", "").substring(0, 10)
        }
      }
    }

  }

  calcoloVoto(fanta, griglia) {

    let voto = Number(fanta.voto)

    if (voto == 55) return 4;

    let evento = fanta.evento.split(",")
    for (let bonus of evento) {
      voto = griglia[bonus] ? Number(griglia[bonus]) + voto : voto
    }
    return voto
  }



}
