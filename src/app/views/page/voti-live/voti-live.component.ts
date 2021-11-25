import { Component, OnInit } from '@angular/core';
import { finalize } from 'rxjs/operators';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { AlertService } from 'src/app/services/alert.service';
import { FantaGazzettaService } from 'src/app/services/fanta-gazzetta.service';
import { SpinnerService } from 'src/app/services/spinner.service';

@Component({
  selector: 'voti-live',
  templateUrl: './voti-live.component.html',
  styleUrls: ['./voti-live.component.scss']
})
export class VotiLiveComponent extends GlobalComponent implements OnInit {

  constructor(
    private spinner: SpinnerService,
    private alert: AlertService,
    private fantaService: FantaGazzettaService
  ) {
    super();
  }

  formazioni: any
  votilive = []

  ngOnInit() {
    this.getLivefanta();
  }

  getLivefanta() {

    this.loading_page = true;
    this.spinner.view();

    this.fantaService.getLiveFormazione()
      .pipe(finalize(() => {
        this.loadPage(this.spinner);
      }
      ))
      .subscribe({

        next: (result: any) => {
          this.formazioni = result;
        },
        error: (error: any) => {
          this.alert.error(error);

        }
      })

  }


}
