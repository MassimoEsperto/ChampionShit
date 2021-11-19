import {Component, Inject} from '@angular/core';
import {MAT_DIALOG_DATA} from '@angular/material/dialog';

@Component({
  selector: 'my-modal-match',
  templateUrl: './my-modal-match.component.html',
  styleUrls: ['./my-modal-match.component.scss']
})
export class MyModalMatch {

  constructor(@Inject(MAT_DIALOG_DATA) public data: any) { }

}
