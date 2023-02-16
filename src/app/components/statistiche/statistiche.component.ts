import { Component, Input, OnInit } from '@angular/core';
import { StatiUtente } from 'src/app/classes/utils/enums';
import { GlobalComponent } from 'src/app/classes/utils/global-component';


@Component({
  selector: 'statistiche',
  templateUrl: './statistiche.component.html',
  styleUrls: ['./statistiche.component.scss']
})
export class StatisticheComponent extends GlobalComponent implements OnInit {

  @Input() statistiche: any;

  constructor() {
    super();
  }

  ngOnInit() { }


  getStato(num) {

    switch (Number(num)) {
      case StatiUtente.ELIMINATO:
        return this.language.page.stati.eliminato
      case StatiUtente.VINCITORE:
        return this.language.page.stati.vincitore
      default:
        return this.language.page.stati.in_corsa
    }


  }


}
