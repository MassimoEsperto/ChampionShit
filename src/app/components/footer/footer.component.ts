import { MyModalInfo } from './../my-modal-info/my-modal-info.component';
import { MyModalLanguage } from './../my-modal-language/my-modal-language.component';
import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AdminService } from 'src/app/services/admin.service';
import { AuthService } from 'src/app/services/auth.service';
import { Utente } from 'src/app/classes/models/utente';

@Component({
  selector: 'my-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.scss']
})
export class FooterComponent extends GlobalComponent implements OnInit {

  versione: any;
  loggato: Utente;

  constructor(
    private services: AdminService,
    private authService: AuthService,
    public dialog: MatDialog) {
    super();
  }

  ngOnInit() { 
    this.versione = this.services.versione() 
    this.loggato = this.authService.getLoggato();
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

  azzeraMessaggi() {
    this.loggato.num_msg = 0;
  }

}
