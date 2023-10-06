import { Component, OnInit } from '@angular/core';
import { AlertService } from 'src/app/services/alert.service';
import { PlayerService } from 'src/app/services/player.service';
import { finalize } from 'rxjs/operators';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { SpinnerService } from 'src/app/services/spinner.service';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'comunicazioni',
  templateUrl: './comunicazioni.component.html',
  styleUrls: ['./comunicazioni.component.scss']
})
export class ComunicazioniComponent extends GlobalComponent implements OnInit {

  comunicazioni: any;

  constructor(
    private playerService: PlayerService,
    private spinner: SpinnerService,
    private authService: AuthService,
    private alert: AlertService) {
    super();
  }

  ngOnInit() {
    this.loading_page = true;
    this.spinner.view();
    this.getComunicazioni();
  }

  getComunicazioni() {
    this.playerService.getCommunicazioni()
      .pipe(finalize(() => {
        this.loadPage(this.spinner);
      }))
      .subscribe({
        next: (result: any) => {
          this.comunicazioni = result
          this.readComunicazioni();
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }

  readComunicazioni() {
    let token = this.playerService.getLocalStorage();
    token.num_msg = 0
    this.authService.setToken(token);
  }



}
