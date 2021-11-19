import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { finalize } from 'rxjs/operators';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AlertService } from 'src/app/services/alert.service';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent extends GlobalComponent implements OnInit {

  constructor(
    private router: Router,
    private alert: AlertService,
    private service: AuthService) {
    super();
  }

  ngOnInit() { }


  registrati(element: any) {

    let ut: any = element.value;
    this.loading_btn = true;

    let newUtente: Utente = new Utente(ut.username, ut.password, ut.email, ut.squadra.toUpperCase());

    this.service.registerUtente(newUtente)
      .pipe(finalize(() => this.resetLoading()))
      .subscribe({
        next: (result: any) => {
          this.alert.success(this.language.alert.success);
          this.segnala(newUtente);
          this.navigateToHome()
        },
        error: (error: any) => {
          this.alert.error(error);
        },
      })
  }

  segnala(newUtente: Utente) {

    this.service.segnalaUtente(newUtente)
      .subscribe({
        next: (result: any) => {
        },
        error: (error: any) => {
          console.log("Errore:",error);
        },
      })
  }

  navigateToHome() {
    setTimeout(() => {
      this.router.navigate(['/sign-in'])
    }, 2000);
  }

}
