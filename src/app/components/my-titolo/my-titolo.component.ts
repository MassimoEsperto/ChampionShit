import { Component, Input, OnInit } from '@angular/core';

@Component({
  selector: 'my-titolo',
  templateUrl: './my-titolo.component.html',
  styleUrls: ['./my-titolo.component.scss']
})
export class MyTitolo implements OnInit {

  @Input() title: string= "";
  constructor() { }

  ngOnInit(): void {
  }

}
