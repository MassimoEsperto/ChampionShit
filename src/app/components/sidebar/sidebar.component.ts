
import { AfterViewInit, Component, ElementRef } from '@angular/core';
import { Utente } from 'src/app/classes/models/utente';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { Utils } from 'src/app/classes/utils/utils';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent extends GlobalComponent implements AfterViewInit {

  constructor(
    private elementRef: ElementRef,
    private authService: AuthService) {
    super();
  }

 
  loggato: Utente;

  ngAfterViewInit() {
    Utils.backgroundAzzurro(this.elementRef)
  }

  ngOnInit() {
    this.loggato = this.authService.getLoggato();
  }


}
