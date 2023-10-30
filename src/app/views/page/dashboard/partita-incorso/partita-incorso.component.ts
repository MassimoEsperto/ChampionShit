import { Component, Input, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { GlobalComponent } from 'src/app/classes/utils/global-component';
import { FantaGazzettaService } from 'src/app/services/fanta-gazzetta.service';

@Component({
  selector: 'partita-incorso',
  templateUrl: './partita-incorso.component.html',
  styleUrls: ['../dashboard.component.scss']
})
export class PartitaIncorsoComponent extends GlobalComponent implements OnInit {

  @Input() dash: any;
  votilive: any


  constructor(
    private router: Router,
    private fantaService: FantaGazzettaService) {
    super();
  }

  ngOnInit(){
    this.getLivefanta()
  }

  getLivefanta() {

    this.fantaService.getLiveFormazione()

      .subscribe({

        next: (result: any) => {
          this.votilive = result;
        }
      })

  }

  visualizzaLive() {
    this.router.navigate(['/home/voti-live']);
  }

}