import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { finalize } from 'rxjs/operators';
import { FasiCompetizione } from 'src/app/classes/utils/enums';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AlertService } from 'src/app/services/alert.service';
import { PlayerService } from 'src/app/services/player.service';
import { SpinnerService } from 'src/app/services/spinner.service';

@Component({
  selector: 'calendario',
  templateUrl: './calendario.component.html',
  styleUrls: ['./calendario.component.scss'],
  encapsulation: ViewEncapsulation.None
})
export class CalendarioComponent extends GlobalComponent implements OnInit {

  palinsesto:any;

  giornate = [];
 

  constructor(
    private spinner:SpinnerService,
    private alert: AlertService,
    private playerService: PlayerService) {
    super();
  }

  ngOnInit(){
    this.calendario();
  }


calendario() {
  this.loading_page = true;
  this.spinner.view();

  this.playerService.getCalendario()
    .pipe(finalize(() => {
      this.loadPage(this.spinner);
    }))
    .subscribe({
      next: (result: any) => {
        this.palinsesto = result;
        let giornate = this.palinsesto.map(({ giornata }) => giornata);
        this.giornate= giornate.filter((n, i) => giornate.indexOf(n) === i);
      },
      error: (error: any) => {
        this.alert.error(error);
      }
    })
}

getSelected(num) {
  return this.palinsesto.filter(e => e.giornata == num);
}

getFasi(num) {

  let selected=this.palinsesto.filter(e => e.giornata == num);
    
  switch (Number(selected[0].fase)) {
    case FasiCompetizione.GIRONI:
      return this.language.page.fasi.gironi
    case FasiCompetizione.SPAREGGI:
      return this.language.page.fasi.spareggi
    case FasiCompetizione.QUARTI:
      return this.language.page.fasi.quarti
    case FasiCompetizione.SEMI_FINALE:
      return this.language.page.fasi.semi_finale
    case FasiCompetizione.FINALE:
      return this.language.page.fasi.finale
    default: return ""
  }


}

}