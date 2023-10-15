import { Component, Input, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { GlobalComponent } from 'src/app/classes/utils/global-component';

@Component({
  selector: 'partita-incorso',
  templateUrl: './partita-incorso.component.html',
  styleUrls: ['../dashboard.component.scss']
})
export class PartitaIncorsoComponent extends GlobalComponent implements OnInit {

  @Input() dash: any;

  
  constructor( private router: Router) {
    super();
  }

  ngOnInit(): void {
  }

  visualizzaLive() {
    this.router.navigate(['/home/voti-live']);
  }

}