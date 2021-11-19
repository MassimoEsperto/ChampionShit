import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AdminService } from 'src/app/services/admin.service';
import { AlertService } from 'src/app/services/alert.service';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'recupera-password',
  templateUrl: './recupera-password.component.html',
  styleUrls: ['./recupera-password.component.scss']
})
export class RecuperaPasswordComponent extends GlobalComponent implements OnInit {

  
  utenti: Utente[];

  constructor(
    private router: Router,
    private alert: AlertService,
    private admin: AdminService,
    private service: AuthService) {
    super();
  }

  ngOnInit() {
    this.getAllUtenti();
  }



  /* CHIAMATE AI SERVIZI */
  getAllUtenti() {

    this.admin.getUtenti()
      .subscribe({
        next: (result: Utente[]) => {
          this.utenti = result;
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }

  /**
   * 
   * @param recupera password
   */
  recupera(element: any) {

    let usr = element.value;
    this.loading_btn = true;

    this.alert.success(this.language.alert.email);

    this.service.recuperaPassword(usr.id_utente)
      .subscribe({
        next: (result: any) => { },
        error: (error: any) => {
          console.log("Errore:", error);
        },

      })

    this.navigateToHome();

  }

  navigateToHome() {
    setTimeout(() => {
      this.router.navigate(['/sign-in'])
    }, 3000);
  }
}
