import { Component, ElementRef, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'nav-desktop',
  templateUrl: './nav-desktop.component.html',
  styleUrls: ['./nav-desktop.component.scss']
})
export class NavDesktopComponent extends GlobalComponent implements OnInit {

  loggato: Utente;
 

  constructor( private elementRef: ElementRef,
    private authService: AuthService,
    private router: Router) {
    super();
  }

  ngOnInit() {
    this.loggato = this.authService.getLoggato();
  }

  logOut() {
    this.authService.logout();
    this.refreshPage();
  }

  
  azzeraMessaggiDesk() {
    this.loggato.num_msg = 0;
  }

}
