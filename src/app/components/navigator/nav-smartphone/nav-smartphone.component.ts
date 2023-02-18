import { Component, ElementRef, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { Router } from '@angular/router';
import { Utente } from 'src/app/classes/models/utente';
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
    this.versione = this.services.versione()
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

  logOut() {
    this.authService.logout();
    this.refreshPage();
  }

  goToLink(url: string) {
    window.open(url, "_blank");
  }

  azzeraMessaggi() {
    this.loggato.num_msg = 0;
  }

}
