import { Component, OnInit } from '@angular/core';
import { finalize } from 'rxjs/operators';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AlertService } from 'src/app/services/alert.service';
import { PlayerService } from 'src/app/services/player.service';
import { SpinnerService } from 'src/app/services/spinner.service';

@Component({
  selector: 'classifiche',
  templateUrl: './classifiche.component.html',
  styleUrls: ['./classifiche.component.scss']
})
export class ClassificheComponent extends GlobalComponent implements OnInit {

  constructor(
    private alert: AlertService,
    private spinner: SpinnerService,
    private playerService: PlayerService) {
    super();
  }


  classifiche: any;

  headElementsGironi = [this.language.page['squadra'], 'GOL', 'PT'];
  headElementsFactory = [this.language.page['squadra'], 'CM'];

  ngOnInit() {
    this.startPage(this.spinner);
    this.getClassifiche();
  }


  getClassifiche() {

    this.playerService.getClassifiche()
      .pipe(finalize(() => {
        this.loadPage(this.spinner);
      }))
      .subscribe({
        next: (result: any) => {
          this.classifiche = result
        },
        error: (error: any) => {
          this.alert.error(error);
        }
      })
  }




}