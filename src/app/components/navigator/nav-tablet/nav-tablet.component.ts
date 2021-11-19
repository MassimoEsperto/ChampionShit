import { Component, ElementRef, Input, OnInit, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { Utente } from 'src/app/classes/models/utente';
import { Ruolo } from 'src/app/classes/utils/enums';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'nav-tablet',
  templateUrl: './nav-tablet.component.html',
  styleUrls: ['./nav-tablet.component.scss']
})
export class NavTabletComponent extends GlobalComponent implements OnInit {

  @ViewChild('pannello') myDiv: ElementRef;
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

  info() {
    let presente: boolean = this.myDiv.nativeElement.classList.length == 2
    if (presente) {
      this.myDiv.nativeElement.classList.toggle("tooglePanel");
    }

    this.router.navigate(['/home/info-utente']);
  }

  azzeraMessaggiSmart() {
    this.navElement();
    this.loggato.num_msg = 0;
  }

  navElement() {
    this.myDiv.nativeElement.classList.toggle("tooglePanel");
  }
}

