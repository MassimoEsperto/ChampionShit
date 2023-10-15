import { Component, Input, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { GlobalComponent } from 'src/app/classes/utils/global-component';

@Component({
  selector: 'pre-partita',
  templateUrl: './pre-partita.component.html',
  styleUrls: ['../dashboard.component.scss']
})
export class PrePartitaComponent extends GlobalComponent implements OnInit {

  @Input() dash: any;

  
  constructor(
    private router: Router
  ) {
    super();
  }

  ngOnInit(): void {
  }

  inserisciFormazione() {
    this.router.navigate(['/home/schieramento']);
  }

}