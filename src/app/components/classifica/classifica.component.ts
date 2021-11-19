import { Component, OnInit } from '@angular/core';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AlertService } from 'src/app/services/alert.service';
import { PlayerService } from 'src/app/services/player.service';

@Component({
  selector: 'classifica',
  templateUrl: './classifica.component.html',
  styleUrls: ['./classifica.component.scss']
})
export class ClassificaComponent extends GlobalComponent implements OnInit {

  constructor(
    private alert: AlertService,
    private playerService: PlayerService) {
    super();
  }


  classifiche: any;
  headElements = [this.language.page['squadra'], 'GOL', 'PT'];

  ngOnInit() {
    this.classifica();
  }


  classifica() {
    this.playerService.getClassifica()

      .subscribe({
        next: (result: any) => {
          this.classifiche = result
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }


}