import { Component, Input, OnInit } from '@angular/core';
import { GlobalComponent } from 'src/app/classes/utils/global-component';


@Component({
  selector: 'statistiche',
  templateUrl: './statistiche.component.html',
  styleUrls: ['./statistiche.component.scss']
})
export class StatisticheComponent extends GlobalComponent implements OnInit {

  @Input() statistiche:any;
  
  constructor() {
    super();
  }

  

  ngOnInit(){
   
  }



}
