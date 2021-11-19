import { CanActivate, Router, ActivatedRouteSnapshot } from "@angular/router";
import { Injectable } from "@angular/core";
import { AuthService } from 'src/app/services/auth.service';


@Injectable()
export class AuthGuard implements CanActivate {

    /**
     * Costruttore
     * @param route Servizio di routing
     */
    constructor(private route: Router, private auth: AuthService) {
    }

    /**
     * se l'utente è loggato può accedere a determinate page 
     */
    canActivate(route: ActivatedRouteSnapshot): boolean {
   
        if (this.auth.isLogged()) return true

        //se non è loggato torna all login
        this.route.navigate(['/login'])

        return false
    }
}