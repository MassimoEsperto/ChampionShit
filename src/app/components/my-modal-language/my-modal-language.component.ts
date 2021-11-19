import {Component, Inject} from '@angular/core';
import {MAT_DIALOG_DATA} from '@angular/material/dialog';

@Component({
  selector: 'my-modal-language',
  templateUrl: './my-modal-language.component.html',
  styleUrls: ['./my-modal-language.component.scss']
})
export class MyModalLanguage{

  constructor(@Inject(MAT_DIALOG_DATA) public data: any) { }

  openModal() {}
}