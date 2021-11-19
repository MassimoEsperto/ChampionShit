import {Component, Inject} from '@angular/core';
import {MAT_DIALOG_DATA} from '@angular/material/dialog';

@Component({
  selector: 'my-modal-info',
  templateUrl: './my-modal-info.component.html',
  styleUrls: ['./my-modal-info.component.scss']
})
export class MyModalInfo {

  constructor(@Inject(MAT_DIALOG_DATA) public data: any) { }

}
