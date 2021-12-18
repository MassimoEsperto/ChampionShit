import { Component, OnInit } from '@angular/core';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AlertService } from 'src/app/services/alert.service';
import { PlayerService } from 'src/app/services/player.service';

@Component({
  selector: 'statistiche',
  templateUrl: './statistiche.component.html',
  styleUrls: ['./statistiche.component.scss']
})
export class StatisticheComponent extends GlobalComponent implements OnInit {

  constructor(
    private alert: AlertService,
    private playerService: PlayerService) {
    super();
  }

  statistiche:any

  ngOnInit(){
    this.getStatistiche()
  }

  getStatistiche() {
    this.playerService.getStatistiche()
      .subscribe({
        next: (result: any) => {
          this.statistiche = result
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }


}
