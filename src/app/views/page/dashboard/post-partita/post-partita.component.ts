import { Component, Input, OnInit } from '@angular/core';
import { GlobalComponent } from 'src/app/classes/utils/global-component';

@Component({
  selector: 'post-partita',
  templateUrl: './post-partita.component.html',
  styleUrls: ['../dashboard.component.scss']
})
export class PostPartitaComponent extends GlobalComponent implements OnInit {

  @Input() dash: any;

  
  constructor() {
    super();
  }

  ngOnInit(): void {
  }

}
