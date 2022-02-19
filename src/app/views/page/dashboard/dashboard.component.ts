import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { Router } from '@angular/router';
import { finalize } from 'rxjs/operators';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AlertService } from 'src/app/services/alert.service';
import { PlayerService } from 'src/app/services/player.service';
import { SpinnerService } from 'src/app/services/spinner.service';

@Component({
  selector: 'dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent extends GlobalComponent implements OnInit {

  constructor(
    private spinner: SpinnerService,
    private alert: AlertService,
    private router: Router,
    private playerService: PlayerService,
    public dialog: MatDialog) {
    super();
  }

  dash: any;
  loggato: Utente;

  ngOnInit() {
    this.loading_page = true;
    this.spinner.view();
    this.loggato = this.playerService.getLoggato();
    this.getDashboard();
  }


  getDashboard() {
    this.playerService.getDashboard()
      .pipe(finalize(() => {
        this.loadPage(this.spinner);
      }))
      .subscribe({
        next: (result: any) => {
          this.dash = result
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }


  inserisciFormazione() {
    this.router.navigate(['/home/schieramento']);
  }

  visualizzaLive() {
    this.router.navigate(['/home/voti-live']);
  }

}