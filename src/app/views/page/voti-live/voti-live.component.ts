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
          this.getVotiLive(result.team, result.seriea, 0)

        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })
  }


  getVotiLive(team: any, seriea: string, indice: number) {

    if (indice > 19) {
      this.loadPage(this.spinner);
      this.assegnaVoti()
      return;
    }
    this.playerService.getVotiLive(team[indice], seriea)
      .subscribe({

        next: (result: any) => {
          for (let ele of result) {
            this.votilive.push(ele)
          }
          this.getVotiLive(team, seriea, indice + 1)
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
            calcio.voto = fantavoto.voto = 55 ? fantavoto.voto : 4
          }
          calcio.calciatore=calcio.calciatore.replace(" ","").substring(0,10)
        }
      }
    }

  }


}
