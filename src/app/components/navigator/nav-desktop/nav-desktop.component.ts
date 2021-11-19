import { Component, ElementRef, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Utente } from 'src/app/classes/models/utente';
import { Ruolo } from 'src/app/classes/utils/enums';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'nav-desktop',
  templateUrl: './nav-desktop.component.html',
  styleUrls: ['./nav-desktop.component.scss']
})
export class NavDesktopComponent extends GlobalComponent implements OnInit {

  loggato: Utente;
  isAdmin: boolean;
  isPlayer: boolean;
  isVisitors: boolean;

  constructor( private elementRef: ElementRef,
    private authService: AuthService,
    private router: Router) {
    super();
  }

  ngOnInit() {
    this.loggato = this.authService.getLoggato();
    let ruolo = this.loggato.ruolo;
    this.isAdmin = ruolo == Ruolo.ADMIN;
    this.isPlayer = ruolo == Ruolo.ADMIN || ruolo == Ruolo.GIOCATORE;
    this.isVisitors = ruolo == Ruolo.VISITATORE;
  }

  logOut() {
    this.authService.logout();
    this.router.navigate(['login']);
  }

  
  azzeraMessaggiDesk() {
    this.loggato.num_msg = 0;
  }

}
