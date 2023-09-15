import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { ActivatedRoute, ParamMap } from '@angular/router';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AlertService } from 'src/app/services/alert.service';
import { AuthService } from 'src/app/services/auth.service';
import { PlayerService } from 'src/app/services/player.service';
import { SpinnerService } from 'src/app/services/spinner.service';

@Component({
  selector: 'aggiorna-rosa',
  templateUrl: './aggiorna-rosa.component.html',
  styleUrls: ['./aggiorna-rosa.component.scss']
})
export class AggiornaRosaComponent extends GlobalComponent implements OnInit {

  constructor(
    private playerService: PlayerService,
    public dialog: MatDialog,
    private route: ActivatedRoute,
    private alert: AlertService) {
    super();
  }

  upgrade: boolean = false;
  loggato: Utente;

  squadra: any;

  id_selected: number = 0;

  ngOnInit() {

    this.loggato = this.playerService.getLoggato();
    this.id_selected = this.loggato.selezionata.id_squadra

    this.onStart()

  }

  onStart() {

    this.route.queryParams.subscribe(params => {
      this.upgrade = Number(params['upgrade']) == 1;
    });
  }

}
