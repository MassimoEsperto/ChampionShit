import { Component, ElementRef, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { Router } from '@angular/router';
import { Utente } from 'src/app/classes/models/utente';
import { Ruolo } from 'src/app/classes/utils/enums';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AdminService } from 'src/app/services/admin.service';
import { AuthService } from 'src/app/services/auth.service';
import { MyModalInfo } from '../../my-modal-info/my-modal-info.component';
import { MyModalLanguage } from '../../my-modal-language/my-modal-language.component';

@Component({
  selector: 'nav-smartphone',
  templateUrl: './nav-smartphone.component.html',
  styleUrls: ['./nav-smartphone.component.scss']
})
export class NavSmartphoneComponent extends GlobalComponent implements OnInit {

  loggato: Utente;
  isAdmin: boolean;
  isPlayer: boolean;
  isVisitors: boolean;
  versione: any;

  constructor(private elementRef: ElementRef,
    private authService: AuthService,
    private services: AdminService,
    public dialog: MatDialog,
    private router: Router) {
    super();
  }

  ngOnInit() {
    this.loggato = this.authService.getLoggato();
    let ruolo = this.loggato.ruolo;
    this.isAdmin = ruolo == Ruolo.ADMIN;
    this.isPlayer = ruolo == Ruolo.ADMIN || ruolo == Ruolo.GIOCATORE;
    this.isVisitors = ruolo == Ruolo.VISITATORE;
    this.versione = this.services.versione()
  }

  logOut() {
    this.authService.logout();
    this.router.navigate(['login']);
  }


  azzeraMessaggiDesk() {
    this.loggato.num_msg = 0;
  }




  onChangeLanguage() {

    const dialogRef = this.dialog.open(MyModalLanguage, {
      panelClass: 'dialog-language',
      data: {
        titolo: this.language.modali.cambia_lingua,
        lista: this.language.modali.lingue
      }
    });

    dialogRef.afterClosed().subscribe(lan => {
      if (lan)
        this.setLinguaggio(lan);
    });
  }

  onInfo() {
    this.dialog.open(MyModalInfo, {
      panelClass: 'dialog-info',
      data: this.language.albo
    });
  }


}
