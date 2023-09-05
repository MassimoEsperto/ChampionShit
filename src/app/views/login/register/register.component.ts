import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { finalize } from 'rxjs/operators';
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


  ngOnInit() {}


  registrati(payload: any) {

    this.service.registraUtente(payload)
      .pipe(finalize(() => this.loading_btn = false))
      .subscribe({
        next: (result: any) => {
          this.alert.success(this.language.alert.success);
          this.segnala(payload);
          this.navigateToHome()
        },
        error: (error: any) => {
          this.alert.error(error);
        },
      })
  }

  segnala(ele: any) {

    this.service.segnalaUtente(ele)
      .subscribe({
        next: (result: any) => {
        },
        error: (error: any) => {
          console.log("Errore:", error);
        },
      })
  }


  onRegistrati(element: any) {

    this.loading_btn = true;
  
    let payload = {
      "email": element.email,
      "password": element.password,
      "username": element.username
    }

    this.registrati(payload)
  }

  navigateToHome() {

    setTimeout(() => {
      this.router.navigate(['/sign-in'])
    }, 2000);
  }


}
