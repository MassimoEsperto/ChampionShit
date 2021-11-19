import { MyModalLanguage } from './../components/my-modal-language/my-modal-language.component';
import { MyModalValidate } from './../components/my-modal-validate/my-modal-validate.component';

import { CommonModule } from '@angular/common';
import { NgModule, NO_ERRORS_SCHEMA, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { BrowserModule } from '@angular/platform-browser';
import { MyAlert } from '../components/my-alert/my-alert.component';
import { MyButton } from '../components/my-button/my-button.component';
import { MySpinner } from '../components/my-spinner/my-spinner.component';
import { AppCustomFrameworkModule } from './framework.module';
import { MyModalInfo } from '../components/my-modal-info/my-modal-info.component';
import { MyModalMatch } from '../components/my-modal-match/my-modal-match.component';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    BrowserModule,
    AppCustomFrameworkModule
  ],
  declarations: [
    MyButton,
    MyAlert,
    MySpinner,
    MyModalValidate,
    MyModalInfo,
    MyModalMatch,
    MyModalLanguage
  ],
  exports: [
    MyButton,
    MyAlert,
    MySpinner,
    MyModalValidate,
    MyModalInfo,
    MyModalMatch
  ],
  providers: [
  ],
  schemas: [NO_ERRORS_SCHEMA, CUSTOM_ELEMENTS_SCHEMA]
})
export class SharedModule { }
